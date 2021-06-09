<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends MY_Controller
{

    /**
     * Peringatan ! selain fungsi index, create, save, edit, update, dan delete
     * semua function HARUS protected-function
     *
     */

    public function __construct()
    {
        parent::__construct();
        $this->controller_id = 8;
        $this->load->model('token_m', 'token');
        $this->load->model('exam_schedule_m', 'schedule');
        $this->load->model('student_grade_m', 'student');
    }

    /**
     * Mendapatkan profile dari session
     *
     */

    public function index()
    {
        $this->filter(2);

        $this->header = [
            'title' => 'Dashboard',
            'sub_title' => 'Simpulan Informasi',
            'nav_active' => 'dashboard',
            'breadcrumb' => [
                [
                    'label' => 'XPanel',
                    'icon' => 'fa-home',
                    'href' => '#',
                ],
                [
                    'label' => 'Beranda',
                    'icon' => '',
                    'href' => '#',
                ],
            ],
        ];

        //student_grade_id_from_session
        $this->set_student_grade_id();

        if (enc($this->student_grade_id, 1)) { // Jika siswa
            $this->temp('dashboard/content_student');
        } else {
            $this->temp('dashboard/content', [
                'token' => $this->token->get(),
                'student' => count($this->student->find()),
                'schedule' => count($this->schedule->find()),
            ]);
        }
    }

    public function create()
    {
        $this->filter(1);
    }

    public function save()
    {
        $this->filter(1);
    }

    public function edit()
    {
        $this->filter(3);
    }

    public function update()
    {
        $this->filter(3);
    }

    public function delete()
    {
        $this->filter(4);
    }
}
