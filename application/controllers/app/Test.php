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
        $this->load->model('Student_grade_m', 'student');
    }

    public function create()
    {
        $this->filter(1);
        $this->load->view('app/test/create');
    }

    public function main($student_grade, $exam_schedule)
    {
        $this->filter(2);

        $this->header = [
            'title' => 'Ujian',
            'js_file' => 'app/main',
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

        $this->temp('app/test/content', [
            'data' => [],
        ]);
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
            'student' => $this->student->find(enc($this->student_grade_id, 1)),
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
