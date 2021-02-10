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
    }

    public function execute($exam_schedule = 0)
    {
        $this->filter(1);

        $this->header = [
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

        $cek_access = false;

        # Data jadwal ujian
        $data = $this->exam_schedule->find($esi);
        
        # Cek Token server
        $token_server =  $token_server = $this->token->get();
        $token_exam =  $this->session->userdata('token_exam');

        # Cek kelas, waktu dan token
        if (count($data) && ($token_server == $token_exam)) {
            $grade_period_id = enc($student_grade['grade_period_id'], 1);
            $grade_period_ids = explode("-", $data['grade_period_id']);
            if ((in_array($grade_period_id, $grade_period_ids)) && $data['intime'] == 1) {
                $cek_access = true;
            }
        }

        if ($cek_access) { // (0) Jika Ya
            // is_register ?
            $is_register = $this->student_exam->find(false, [
                'a.student_grade_id' => $sgi,
                'a.exam_schedule_id' => $esi,
            ]);
            if (count($is_register)) { // (1) Jika sudah
                // Cek apakah student_grade_id sudah menyelesaikan ujian ini
                if ($is_register[0]['finish_time'] == null) { // (2) Jika belum
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
                } else { // (2) Jika sudah
                    // Go info atau logout
                    $this->temp_test('app/test/info', [
                        'info' => 'Maaf, Anda telah menyelesaikan ujian ini pada ' . $is_register[0]['finish_time'],
                    ]);
                }
            } else { // (1) Jika belum
                // Daftarkan
                $this->db->trans_begin();
                $regis = $this->student_exam->save([
                    'student_grade_id' => $sgi,
                    'exam_schedule_id' => $esi,
                    'numbers_before_answer' => $data['number_of_exam']
                ]);

                if ($regis['status'] == '200') {
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

    public function closing_by_operator()
    {
        /**
         * variable yang dibutuhkan :
         * $student_grade_exam_id yang masih diecnrypt
         */

        $this->filter(3);

        $student_grade_exam_id = enc($student_grade_exam_id, 1);
        $closing = $this->set_it_close($student_grade_exam_id);

        $this->session->set_flashdata('create_info_message', 'Ujian siswa berhasil disubmit (diselesaikan).');

        redirect(base_url('app/exam_schedule'));
    }

    public function closing_by_sistem($student_grade_exam_id)
    {
        /**
         * variable yang dibutuhkan :
         * $student_grade_exam_id yang masih diecnrypt
         */

        $this->filter(3);

        $student_grade_exam_id = enc($student_grade_exam_id, 1);
        $closing = $this->set_it_close($student_grade_exam_id);

        $this->temp_test('app/test/close_dialog', [
            'status' => $closing['status'],
            'message' => 'Kami telah men-submit (menyelesaikan lalu menyimpan) data ujian Anda, karena waktu ujian ini telah kadaluarsa.',
        ]);
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
            ]);

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

        $this->temp_test('app/test/confirm', [
            'exam_schedule_id' => $exam_schedule_id,
            'data' => $this->exam_schedule->find(enc($exam_schedule_id, 1)),
            'student' => $this->student_grade->find(enc($this->student_grade_id, 1)),
        ]);
    }

    public function get_exam_detail()
    {
        $this->filter(2);
        $exam_item = enc($this->input->post('exam_item'), 1);

        $exam_question = $this->exam_temp->find($exam_item);

        $data = [
            'question' => $exam_question['question'],
            'opsi_a' => $exam_question['opsi_a'],
            'opsi_b' => $exam_question['opsi_b'],
            'opsi_c' => $exam_question['opsi_c'],
            'opsi_d' => $exam_question['opsi_d'],
            'opsi_e' => $exam_question['opsi_e'],
            'answer' => $exam_question['answer'],
        ];

        $data['token'] = $this->security->get_csrf_hash();
        echo json_encode($data);
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
            ];
        } else { // jika tidak ada (belum ujian)

            $exam_questions_raw = $this->exam_question_detail->find_for_student_id_only(false, [
                'a.exam_question_id' => enc($this->input->post('exam_question_id'), 1),
            ]);

            $exam_question_items = array_rand($exam_questions_raw, $info['number_of_exam']);
            $exam_questions_to_be_save = [];

            foreach ($exam_question_items as $k => $v) {

                // for save to db
                array_push($exam_questions_to_be_save, [
                    'student_grade_exam_id' => $student_grade_exam_id,
                    'exam_question_detail_id' => enc($exam_questions_raw[$v]['id'], 1),
                ]);
            }

            $this->db->trans_begin();

            $save_to_exam = $this->exam->save_batch($exam_questions_to_be_save);
            $save_to_exam_temp = $this->exam_temp->save_batch($exam_questions_to_be_save);

            $exams = $this->exam->find(false, [
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
        $token_exam = $this->input->post('token_exam');

        $cek = $this->exam_schedule->find(false, [
            'a.id' => enc($exam_schedule_id, 1)
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
        $e = $this->exam_temp->find($exam);

        $update_correct = $sgXe['correct']; // Jumlah Benar
        $update_incorrect = $sgXe['incorrect']; // Jumlah Salah
        $last_answer = $e['answer']; // apakah soal sudah dijawab sebelumnya? (respon true/false)
        $last_is_correct = $e['is_correct']; // jawaban sebelumnya benar / salah ? (respon true/false)
        $numbers_before_answer = $sgXe['numbers_before_answer']; // Jumlah soal yang sudah dijawab

        if ($correct) { // Jika jawaban sekarang benar
            if($last_answer == null){
                $numbers_before_answer--;
                $update_correct++;
            }else{
                if($last_is_correct == 0){
                    $update_incorrect--;
                    $update_correct++;
                }
            }
        } else { // Jika jawaban sekarang salah
            if($last_answer == null){
                $numbers_before_answer--;
                $update_incorrect++;
            }else{
                if($last_is_correct == 1){
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
            'score' => ($update_correct/($update_correct + $update_incorrect + $numbers_before_answer)) * 10,
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