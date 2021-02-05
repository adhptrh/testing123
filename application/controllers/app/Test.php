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
        $this->load->model('Exam_schedule_m', 'exam');
        $this->load->model('Student_grade_m', 'student_grade');
        $this->load->model('Student_grade_exam_m', 'student_exam');
    }

    public function create()
    {
        $this->filter(1);
        $this->load->view('app/test/create');
    }

    public function execute($exam_schedule)
    {
        $this->filter(1);

        $this->header = [
            'title' => 'Ujian',
            'js_file' => 'app/execute',
            'sub_title' => 'Pelaksanaan Ujian',
            'nav_active' => 'app/test',
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

        // get student_grade_id
        $this->set_student_grade_id();
        $sgi = enc($this->student_grade_id, 1); // Student_grade_id
        $esi = enc($exam_schedule, 1); // exam_schedule_id

        // Cek apakah student_grade_id memiliki hak atas ujian ini bedasarkan sesi, kelas, token dan waktu
        $student_grade = $this->student_grade->find($sgi);

        $cek_access = false;
        $data = $this->exam->find(false, [
            'a.order_id' => $student_grade['order_id'], # Cek sesi
            'a.token' => $this->session->userdata('token_exam'), # Cek Token
        ]);

        # Cek kelas dan waktu
        if (count($data)) {
            $grade_period_id = enc($student_grade['grade_period_id'], 1);
            $grade_period_ids = explode("-", $data[0]['grade_period_id']);
            if (
                (in_array($grade_period_id, $grade_period_ids)) # cek Kelas
                 &&
                $data[0]['intime'] == 1) /* cek Waktu */ {$cek_access = true;}
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
                    echo 'Halaman lanjutkan ujian';
                } else { // (2) Jika sudah
                    // Go info atau logout
                    $this->temp('app/test/info', [
                        'info' => 'Maaf, Anda telah menyelesaikan ujian ini pada ' . $is_register[0]['finish_time'],
                    ]);
                }
            } else { // (1) Jika belum
                // Daftarkan
                $this->db->trans_begin();
                $regis = $this->student_exam->save([
                    'student_grade_id' => $sgi,
                    'exam_schedule_id' => $esi,
                ]);

                if ($regis['status'] == '200') {
                    // Dapatkan daftar soal
                    $number_of_exam = $cek_access[0]['number_of_exam'];
                    // Commit db
                    $this->db->trans_commit();

                    // arahkan ke laman ujian
                    $this->temp('app/test/content', [
                        'number_of_exam' => $number_of_exam,
                    ]);
                } else {
                    $this->db->trans_rollback();
                    $this->temp('app/test/info', [
                        'info' => 'Maaf, Gagal mengeksekusi perintah, silahkan hubungi penyelenggara ujian',
                    ]);
                }
            }
        } else { // (0) Jika Tidak
            // Go info atau logout
            $this->temp('app/test/info', [
                'info' => 'Maaf, Anda tidak memiliki akses untuk mengikut ujian ini',
            ]);
        }
    }

    public function close()
    {
        $this->filter(1);

        // Cek apakah sudah menyelsaikan semua ujian
        // Jika sudah
        // insert finish time
        // set info
        // Jika belum
        // set info
    }

    public function confirm($exam_schedule_id)
    {
        $this->filter(2);

        $this->header = [
            'title' => 'Ujian',
            'js_file' => 'app/test_confirm',
            'sub_title' => 'Konfirmasi Biodata dan Ujian',
            'nav_active' => 'app/test',
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

        $this->temp('app/test/confirm', [
            'exam_schedule_id' => $exam_schedule_id,
            'data' => $this->exam->find(enc($exam_schedule_id, 1)),
            'student' => $this->student_grade->find(enc($this->student_grade_id, 1)),
        ]);
    }

    public function is_register($exam_schedule_id, $student_grade_id)
    {
        $this->filter(2);

    }

    public function get_header_data($exam_schedule_id)
    {
        $this->filter(2);

        $data = $this->exam_info->find(enc($exam_schedule_id, 1));
        $data['token'] = $this->security->get_csrf_hash();
        echo json_encode($data);
    }

    public function cek_token()
    {
        $this->filter(2);
        $exam_schedule_id = $this->input->post('examSchedule');
        $token_exam = $this->input->post('token_exam');

        $cek = $this->exam->find(false, [
            'a.id' => enc($exam_schedule_id, 1),
            'a.token' => $token_exam,
        ]);

        if (count($cek)) {
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
}
