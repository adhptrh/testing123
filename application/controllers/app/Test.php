<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Test extends MY_Controller
{

    /**
     * Peringatan ! selain fungsi index, create, save, edit, update, delete, dan restore
     * semua function HARUS protected-function
     *
     */

    public function __construct()
    {
        parent::__construct();
        $this->controller_id = 17;
        $this->load->model('Exam_m', 'exam');
        $this->load->model('Exam_temp_m', 'exam_temp');
        $this->load->model('Exam_schedule_m', 'exam_schedule');
        $this->load->model('Exam_question_detail_m', 'exam_question_detail');
        $this->load->model('Student_grade_m', 'student_grade');
        $this->load->model('Student_grade_exam_m', 'student_exam');
        $this->load->model('Token_m', 'token');
        $this->load->model('School_profile_m', 'school_profile');
    }

    public function execute($exam_schedule = 0)
    {
        $this->filter(1);

        $this->header = [
            'school_name' => $this->school_profile->find()[0]['name'],
            'title' => 'Ujian',
            'js_file' => 'app/execute',
            'sub_title' => 'Pelaksanaan Ujian',
            'nav_active' => 'app/test/execute',
            'breadcrumb' => [
                [
                    'label' => 'XPanel',
                    'icon' => 'fa-home',
                    'href' => '#',
                ],
                [
                    'label' => 'Aplikasi',
                    'icon' => 'fa-gear',
                    'href' => '#',
                ],
                [
                    'label' => 'Ujian',
                    'icon' => '',
                    'href' => '#',
                ],
            ],
        ];

        if ($exam_schedule === 0) {
            $this->temp_test('app/test/info', [
                'info' => 'Maaf, Anda belum menentukan Mata Uji, silahkan cek menu jadwal ujian',
            ]);
        }

        // get student_grade_id
        $this->set_student_grade_id();
        $sgi = enc($this->student_grade_id, 1); // Student_grade_id
        $esi = enc($exam_schedule, 1); // exam_schedule_id

        // Cek apakah student_grade_id memiliki hak atas ujian ini bedasarkan sesi, kelas, token dan waktu
        $student_grade = $this->student_grade->find($sgi);

        # Data jadwal ujian
        $data = $this->exam_schedule->find($esi);

        $cek_access = false;

        // DEPRECATED
        // $token_server = $this->token->get(); // Token server
        // $token_exam = $this->session->userdata('token_exam');

        // # Cek kelas, waktu dan token
        // if (count($data) && ($token_server == $token_exam)) {
        //     $grade_period_id = enc($student_grade['grade_period_id'], 1);
        //     $grade_period_ids = explode("-", $data['grade_period_id']);

        //     if ((in_array($grade_period_id, $grade_period_ids)) && $data['intime'] == 1) {
        //         $cek_access = true;
        //     }
        // }

        # Cek kelas dan waktu
        if (count($data)) {
            $grade_period_id = enc($student_grade['grade_period_id'], 1);
            $grade_period_ids = explode("-", $data['grade_period_id']);

            if (
                // Cek apakah kelas user ini terdaftar
                in_array($grade_period_id, $grade_period_ids) 
                
                // Cek apakah ujian sudah pada waktunya
                && 
                $data['intime'] == 1 

                // Cek apakah sesi user ini terdaftar
                && 
                enc($data['order_id'], 1) == $student_grade['order_id']
                ) {
                $cek_access = true;
            }
        }

        // token dari session
        $token_exam = $this->session->userdata('token_exam');

        if ($cek_access) { // (0) Jika Ya
            // is_register ?
            $is_register = $this->student_exam->find(false, [
                'a.student_grade_id' => $sgi,
                'a.exam_schedule_id' => $esi,
            ]);

            if (count($is_register)) { // (1) Jika sudah
                // Cek apakah student_grade_id sudah menyelesaikan ujian ini
                if ($is_register[0]['finish_time'] == null) { // (2) Jika belum
                    // Cek apakah token masih sama

                    //Token dari tabel student_grade_extend_exam
                    // $token_student_exam = $is_register[0]['token'];

                    // if (($token_exam) && $token_student_exam == $token_exam) {
                    if ($token_exam) {
                        // Dapatkan daftar soal dan jawaban
                        // arahkan ke laman ujian
                        $this->temp_test('app/test/content', [
                            'student_grade_exam_id' => $is_register[0]['id'],
                            'exam_schedule_id' => $exam_schedule,
                            'exam_question_id' => $data['exam_question_id'],
                            'number_of_exam' => $data['number_of_exam'],
                            'study' => $data['study'],
                            'order' => $data['order'],
                        ]);
                    } else {
                        // Go info atau logout
                        $this->temp_test('app/test/info', [
                            'info' => 'Maaf, Anda tidak memiliki akses untuk mengikut ujian ini',
                        ]);
                    }

                } else { // (2) Jika sudah
                    // Go info atau logout
                    $this->temp_test('app/test/info', [
                        'info' => 'Maaf, Anda telah menyelesaikan ujian ini pada ' . $is_register[0]['finish_time'],
                    ]);
                }
            } else { // (1) Jika belum
                // Cek token
                $token_server = $this->token->get(); // Token server
                if ($token_exam == $token_server) {
                    // Daftarkan
                    $this->db->trans_begin();
                    $regis = $this->student_exam->save([
                        'student_grade_id' => $sgi,
                        'exam_schedule_id' => $esi,
                        'numbers_before_answer' => $data['number_of_exam'],
                    ]);

                    if ($regis['status'] == '200') {
                        /**
                         * Update token_student
                         * Dapatkan :
                         * 1. $sgi (student_grade_id)
                         *
                         * Gunakan variable :
                         * 1. $esi (exam_schedule_id)
                         *
                         * Lakukan update token_student dengan by_pass validation
                         */
                        // $update_token_student = $this->student_exam->save([
                        //     'id' => enc($regis['id'], 1),
                        //     'token' => $token_exam,
                        // ], true);

                        // if ($update_token_student['status'] == '200') {
                        //     // Commit db
                        //     $this->db->trans_commit();

                        //     // arahkan ke laman ujian
                        //     $this->temp_test('app/test/content', [
                        //         'exam_schedule_id' => $exam_schedule,
                        //         'student_grade_exam_id' => $regis['id'],
                        //         'exam_question_id' => $data['exam_question_id'],
                        //         'number_of_exam' => $data['number_of_exam'],
                        //         'study' => $data['study'],
                        //         'order' => $data['order'],
                        //     ]);
                        // } else {
                        //     $this->db->trans_rollback();
                        //     $this->temp_test('app/test/info', [
                        //         'info' => 'Maaf, Gagal mengeksekusi perintah, silahkan hubungi penyelenggara ujian',
                        //     ]);
                        // }

                        // Commit db
                        $this->db->trans_commit();

                        // arahkan ke laman ujian
                        $this->temp_test('app/test/content', [
                            'exam_schedule_id' => $exam_schedule,
                            'student_grade_exam_id' => $regis['id'],
                            'exam_question_id' => $data['exam_question_id'],
                            'number_of_exam' => $data['number_of_exam'],
                            'study' => $data['study'],
                            'order' => $data['order'],
                        ]);
                    } else {
                        $this->db->trans_rollback();
                        $this->temp_test('app/test/info', [
                            'info' => 'Maaf, Gagal mengeksekusi perintah, silahkan hubungi penyelenggara ujian',
                        ]);
                    }
                } else {
                    // Go info atau logout
                    $this->temp_test('app/test/info', [
                        'info' => 'Maaf, Anda tidak memiliki akses untuk mengikut ujian ini',
                    ]);
                }
            }
        } else { // (0) Jika Tidak
            // Go info atau logout
            $this->temp_test('app/test/info', [
                'info' => 'Maaf, Anda tidak memiliki akses untuk mengikut ujian ini',
            ]);
        }
    }

    /**
     * Kondisi menyebabkan close :
     *
     * function closing()
     * Permintaan peserta ujian
     * Karena timeout ketika sedang ujian
     *
     * function closing_by_operator()
     * Permintaan panitia ujian
     *
     * * function closing_by_sistem()
     * Karena timeout direct link
     */

    public function closing($student_grade_exam_id, $is_time_out = false)
    {
        /**
         * variable yang dibutuhkan :
         * $student_grade_exam_id yang masih diecnrypt
         */

        $this->filter(3);
        $closing = $this->set_it_close($student_grade_exam_id);

        if ($is_time_out) {
            $message = "Waktu ujian telah habis, data ujian Anda sudah kami submit (diselesaikan) secara otomatis.";
        } else {
            $message = "Terimakasih, Anda telah berhasil menyelesaikan ujian ini.";

        }

        $this->load->view('app/test/close_dialog', [
            'status' => $closing['status'],
            'message_sistem' => $closing['message'],
            'message' => 'Terimakasih, Anda telah berhasil menyelesaikan ujian ini.',
        ]);

    }

    public function closing_by_operator($exam_schedule_id, $student_grade_exam_id)
    {
        /**
         * variable yang dibutuhkan :
         * $student_grade_exam_id yang masih diecnrypt
         */

        $this->filter(3);

        $closing = $this->set_it_close($student_grade_exam_id);

        $this->session->set_flashdata('message', 'Ujian siswa berhasil disubmit (diselesaikan).');

        redirect(base_url('app/exam_schedule/detail/' . $exam_schedule_id));
    }

    public function closing_by_sistem($student_grade_exam_id)
    {
        /**
         * variable yang dibutuhkan :
         * $student_grade_exam_id yang masih diecnrypt
         */

        $this->filter(3);

        $closing = $this->set_it_close($student_grade_exam_id);

        $this->temp_test('app/test/close_dialog', [
            'status' => $closing['status'],
            'message' => 'Kami telah men-submit (menyelesaikan lalu menyimpan) data ujian Anda, karena waktu ujian ini telah kadaluarsa.',
        ]);
    }

    public function reset_by_operator($exam_schedule_id, $student_grade_exam_id)
    {
        /**
         * Menghapus jawaban siswa di student_grade_extend_exams, exams dan exam_temps
         *
         * Variable yang dibutuhkan
         * $exam_schedule_id yang diencrypt
         * $student_grade_exam_id yang diencrypt
         *
         * return = redirect ke app/exam_schedule/detail/xxx
         */

        $this->filter(3);

        $sgei = enc($student_grade_exam_id, 1);

        $this->db->trans_begin();

        // Softdel student_grade_exam
        $update = $this->student_exam->delete($student_grade_exam_id);

        if ($update['status'] == '200') {
            // Softdel exam_temps
            $update = $this->exam_temp->delete_where([
                'student_grade_exam_id' => $sgei,
            ], false); // Hard Delete
            if ($update['status'] == '200') {
                // Softdel exams
                $update = $this->exam->delete_where([
                    'student_grade_exam_id' => $sgei,
                ]);
                if ($update['status'] == '200') {
                    $this->db->trans_commit();
                    $this->session->set_flashdata('message', $update['message']);
                } else {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('message', $update['message']);
                }
            } else {
                $this->db->trans_rollback();
                $this->session->set_flashdata('message', $update['message']);
            }
        } else {
            $this->db->trans_rollback();
            $this->session->set_flashdata('message', $update['message']);
        }

        redirect(base_url('app/exam_schedule/detail/' . $exam_schedule_id));
    }

    private function set_it_close($student_grade_exam_id)
    {
        /**
         * Fungsi ini digunakan untuk mereset ujian, yaitu
         * memberikan nilai finish_time, dan
         * menghapus ujian di table exam_temps
         *
         * variable yang dibutuhkan :
         * $student_grade_exam_id yang masih diecnrypt
         */

        $student_grade_exam_id = enc($student_grade_exam_id, 1);

        $this->db->trans_begin();
        // Finishing ujian
        $finishing = $this->student_exam->save([
            'id' => $student_grade_exam_id,
            'finish_time' => $this->token->info()['datetime'],
        ], true);

        if ($finishing['status'] == '200') {
            // Clearing ujian
            $clearing = $this->exam_temp->delete_where([
                'student_grade_exam_id' => $student_grade_exam_id,
            ], false); // Hard Delete

            if ($clearing['status'] == '200') {
                $this->db->trans_commit();
                return [
                    'status' => $clearing['status'],
                    'message' => $clearing['message'],
                ];
            } else {
                $this->db->trans_rollback();
                return [
                    'status' => $clearing['status'],
                    'message' => $clearing['message'],
                ];
            }

        } else {
            $this->db->trans_rollback();
            return [
                'status' => $finishing['status'],
                'message' => $finishing['message'],
            ];
        }
    }

    public function confirm($exam_schedule_id)
    {
        $this->filter(2);

        $this->header = [
            'school_name' => $this->school_profile->find()[0]['name'],
            'title' => 'Ujian',
            'js_file' => 'app/test_confirm',
            'sub_title' => 'Konfirmasi Biodata dan Ujian',
            'nav_active' => 'app/test/execute',
            'breadcrumb' => [
                [
                    'label' => 'XPanel',
                    'icon' => 'fa-home',
                    'href' => '#',
                ],
                [
                    'label' => 'Aplikasi',
                    'icon' => 'fa-gear',
                    'href' => '#',
                ],
                [
                    'label' => 'Ujian',
                    'icon' => '',
                    'href' => '#',
                ],
                [
                    'label' => 'Konfirmasi Data',
                    'icon' => '',
                    'href' => '#',
                ],
            ],
        ];

        //student_grade_id_from_session
        $this->set_student_grade_id();

        $data = $this->exam_schedule->find(enc($exam_schedule_id, 1));
        $student_grade = $this->student_grade->find(enc($this->student_grade_id, 1));

        if(enc($data['order_id'], 1) == $student_grade['order_id']){
            $this->temp_test('app/test/confirm', [
                'exam_schedule_id' => $exam_schedule_id,
                'data' => $data,
                'student' => $student_grade,
            ]);
        }else{
            // Go info atau logout
            $this->temp_test('app/test/info', [
                'info' => 'Maaf, Anda tidak memiliki akses untuk mengikut ujian ini',
            ]);
        }
    }

    public function get_exam_detail()
    {
        $this->filter(2);
        $is_allow = 0;
        $exam_item = enc($this->input->post('exam_item'), 1);
        $student_grade_exam = enc($this->input->post('student_grade_exam'), 1);

        // is_still_register ?
        $is_register = $this->student_exam->find($student_grade_exam);

        if ($is_register) {
            if ($is_register['finish_time'] == null) {
                $is_allow = 1;
            }
        }

        $exam_question = $this->exam_question_detail->find_for_student_details($exam_item);

        $data = [
            'is_allow' => $is_allow,
            'question' => $exam_question['question'],
            'opsi_a' => $exam_question['opsi_a'],
            'opsi_b' => $exam_question['opsi_b'],
            'opsi_c' => $exam_question['opsi_c'],
            'opsi_d' => $exam_question['opsi_d'],
            'opsi_e' => $exam_question['opsi_e'],
        ];

        $data['token'] = $this->security->get_csrf_hash();
        echo json_encode($data);
    }

    private function UniqueRandomNumbersWithinRange($min, $max, $quantity) {
        $numbers = range($min, $max);
        shuffle($numbers);
        return array_slice($numbers, 0, $quantity);
    }

    public function get_landing_data()
    {
        $this->filter(2);

        $student_grade_exam_id = enc($this->input->post('student_grade_exam_id'), 1);
        // $exam_question_id = enc($this->input->post('exam_question_id'), 1);

        $info = $this->exam_schedule->find(enc($this->input->post('exam_schedule_id'), 1));

        // cek apakah student_grade_exam_id sudah ada di exam
        $exams = $this->exam_temp->find(false, [
            'a.student_grade_exam_id' => $student_grade_exam_id,
        ]);

        if (count($exams)) { // jika ada (sudah ujian)
            $data = [
                'token' => $this->security->get_csrf_hash(),
                'number_of_exam' => $info['number_of_exam'],
                'time_left' => $info['time_left'],
                'time_server_now' => $info['time_server_now'],
                'exam_questions' => $exams,
                'number_of_options' => $info['number_of_options'],
            ];

        } else { // jika tidak ada (belum ujian)

            $exam_questions_to_be_save = [];

            if ($info['is_random'] == 1) {
                // Soal random
                $exam_questions_raw = $this->exam_question_detail->find_for_student_id_only(false, [
                    'a.exam_question_id' => enc($this->input->post('exam_question_id'), 1),
                ]);

                // Jika jumlah soal == jumlah soal yang akan ditampilkan
                if(count($exam_questions_raw) == $info['number_of_exam']){
                    // $exam_question_items = array_rand($exam_questions_raw, $info['number_of_exam']);
                    $max = ($info['number_of_exam'] - 1);
                    $index = $this->UniqueRandomNumbersWithinRange(0, $max, $info['number_of_exam']);
                    foreach ($index as $k => $v) {
                        $exam_question_items[] = $exam_questions_raw[$v];
                    }

                    foreach ($exam_question_items as $k => $v) {
                        // for save to db
                        array_push($exam_questions_to_be_save, [
                            'student_grade_exam_id' => $student_grade_exam_id,
                            'exam_question_detail_id' => enc($v['id'], 1),
                        ]);
                    }

                }else{ // Jika tidak
                    $exam_question_items = array_rand($exam_questions_raw, $info['number_of_exam']);

                    foreach ($exam_question_items as $k => $v) {
                        // for save to db
                        array_push($exam_questions_to_be_save, [
                            'student_grade_exam_id' => $student_grade_exam_id,
                            'exam_question_detail_id' => enc($exam_questions_raw[$v]['id'], 1),
                        ]);
                    }
                }

            } else {
                // Soal tidak radom
                $exam_questions_raw = $this->exam_question_detail->find_for_student_id_only(false, [
                    'a.exam_question_id' => enc($this->input->post('exam_question_id'), 1),
                ], false, 0, $info['number_of_exam']);
                
                foreach ($exam_questions_raw as $k => $v) {
                    // for save to db
                    array_push($exam_questions_to_be_save, [
                        'student_grade_exam_id' => $student_grade_exam_id,
                        'exam_question_detail_id' => enc($v['id'], 1),
                    ]);
                }
            }

            $this->db->trans_begin();

            $save_to_exam = $this->exam->save_batch($exam_questions_to_be_save);
            $save_to_exam_temp = $this->exam_temp->save_batch($exam_questions_to_be_save);

            $exams = $this->exam_temp->find(false, [
                'a.student_grade_exam_id' => $student_grade_exam_id,
            ]);

            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
            } else {
                $this->db->trans_commit();
            }

            $data = [
                'token' => $this->security->get_csrf_hash(),
                'number_of_exam' => $info['number_of_exam'],
                'time_left' => $info['time_left'],
                'time_server_now' => $info['time_server_now'],
                'exam_questions' => $exams,
            ];

        }

        echo json_encode($data);
    }

    public function cek_token()
    {
        $this->filter(2);
        $exam_schedule_id = $this->input->post('examSchedule');
        $esi = enc($exam_schedule_id, 1);
        $token_exam = $this->input->post('token_exam');

        $cek = $this->exam_schedule->find(false, [
            'a.id' => $esi,
        ]);

        $token_server = $this->token->get();

        if (count($cek) && $token_exam == $token_server) {
            $this->session->set_userdata('token_exam', $token_exam);
            $data['token_exam'] = 1;
            $data['time_start'] = $cek[0]['time_start'];
            $data['time_server_now'] = $cek[0]['time_server_now'];

        } else {
            $data['token_exam'] = 0;
        }

        $data['token'] = $this->security->get_csrf_hash();
        echo json_encode($data);
    }

    // private function token_sure_check()
    // {
    //     /**
    //      * Fungsi ini untuk memastikan sekali lagi apakah token sudah valid
    //      */
    //     $token_server = $this->token->get();
    //     $token_session = $this->session->userdata('token_exam');

    //     if($token_sever == $token_session){
    //         return true;
    //     }else{
    //         return false;
    //     }
    // }

    public function save()
    {
        $this->filter(3);

        $answer = $this->input->post('answer');
        $exam = enc($this->input->post('exam'), 1);
        $exam_question_detail_id = enc($this->input->post('exam_question_detail_id'), 1);
        $student_grade_exam_id = enc($this->input->post('student_grade_exam_id'), 1);

        // cek apakah jawaban correct
        $is_correct = $this->exam_question_detail->find(false, [
            'a.id' => $exam_question_detail_id,
            'a.keyword' => $answer,
        ]);

        if (count($is_correct)) {
            $correct = 1;
        } else {
            $correct = 0;
        }

        $this->db->trans_begin();

        // Update score ======================================================================
        // table : student_grade_extend_exams
        $sgXe = $this->student_exam->find($student_grade_exam_id);

        // table : exams
        $e = $this->exam_temp->find($exam, false, false, 0, true);

        $update_correct = $sgXe['correct']; // Jumlah Benar
        $update_incorrect = $sgXe['incorrect']; // Jumlah Salah
        $last_answer = $e['answer']; // apakah soal sudah dijawab sebelumnya? (respon true/false)
        $last_is_correct = $e['is_correct']; // jawaban sebelumnya benar / salah ? (respon true/false)
        $numbers_before_answer = $sgXe['numbers_before_answer']; // Jumlah soal yang sudah dijawab

        if ($correct) { // Jika jawaban sekarang benar
            if ($last_answer == null) {
                $numbers_before_answer--;
                $update_correct++;
            } else {
                if ($last_is_correct == 0) {
                    $update_incorrect--;
                    $update_correct++;
                }
            }
        } else { // Jika jawaban sekarang salah
            if ($last_answer == null) {
                $numbers_before_answer--;
                $update_incorrect++;
            } else {
                if ($last_is_correct == 1) {
                    $update_incorrect++;
                    $update_correct--;
                }
            }
        }

        $update = $this->student_exam->save([
            'id' => $student_grade_exam_id,
            'correct' => $update_correct,
            'incorrect' => $update_incorrect,
            'numbers_before_answer' => $numbers_before_answer,
            'score' => ($update_correct / ($update_correct + $update_incorrect + $numbers_before_answer)) * 10,
        ], true);

        if ($update['status'] == '200') {

            // table : exams
            $update = $this->exam->save([
                'id' => $exam,
                'answer' => $answer,
                'is_correct' => $correct,
            ], true);

            if ($update['status'] == '200') {

                // table : exams_temp
                $update = $this->exam_temp->save([
                    'id' => $exam,
                    'answer' => $answer,
                    'is_correct' => $correct,
                ], true);

                if ($update['status'] == '200') {
                    $this->db->trans_commit();
                } else {
                    $this->db->trans_rollback();
                }
            } else {
                $this->db->trans_rollback();
            }
        } else {
            $this->db->trans_rollback();
        }

        $data['message'] = $update['message'];
        $data['status'] = $update['status'];
        $data['token'] = $this->security->get_csrf_hash();
        echo json_encode($data);
    }
}
