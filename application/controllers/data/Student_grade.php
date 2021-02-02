<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Student_grade extends MY_Controller
{

    /**
     * Peringatan ! selain fungsi index, create, save, edit, update, delete, dan restore
     * semua function HARUS protected-function
     *
     */

    public function __construct()
    {
        parent::__construct();
        $this->controller_id = 23;
        $this->load->model('Student_grade_m', 'data');
        $this->load->model('Student_m', 'student');
        $this->load->model('Grade_period_m', 'grade_period');
        $this->load->model('Period_m', 'period');
    }

    public function index(){
        $this->filter(2);

        $this->header = [
            'title' => 'Siswa Kelas',
            'js_file' => 'data/student_grade',
            'sub_title' => 'Pengaturan Siswa Kelas',
            'nav_active' => 'data/student_grade',
            'breadcrumb' => [
                [
                    'label' => 'XPanel',
                    'icon' => 'fa-home',
                    'href' => '#',
                ],
                [
                    'label' => 'Data',
                    'icon' => 'fa-gear',
                    'href' => '#',
                ],
                [
                    'label' => 'Siswa Kelas',
                    'icon' => '',
                    'href' => '#',
                ],
            ],
        ];

        $this->temp('data/student_grade/content', [
            'period' => $this->period->find(),
        ]);
    }

    public function create($grade_period_id, $old = [])
    {
        $this->filter(1);

        $this->header = [
            'title' => 'Siswa Kelas',
            'sub_title' => 'Tambah Siswa Kelas',
            'nav_active' => 'data/grade_period',
            'breadcrumb' => [
                [
                    'label' => 'XPanel',
                    'icon' => 'fa-home',
                    'href' => '#',
                ],
                [
                    'label' => 'Referensi',
                    'icon' => 'fa-gear',
                    'href' => '#',
                ],
                [
                    'label' => 'Siswa Kelas',
                    'icon' => 'fa-list',
                    'href' => base_url('data/student_grade'),
                ],
                [
                    'label' => 'Tambah',
                    'icon' => '',
                    'href' => '#',
                ],
            ],
        ];

        $id = enc($grade_period_id, 1);
        $grade_period = $this->grade_period->find($id);
        
        // Daftar siswa yang sudah terdaftar di kelas ini.
        $student_current = $this->data->find(false, [
            'a.grade_period_id' => $id
        ]);

        // Siswa yang sudah terdaftar di periode ini.   
        $data = $this->data->find(false, [
            'd.period_id' => enc($grade_period['period_id'], 1),
        ]);

        $siswa_terdaftar = [];

        foreach ($data as $k => $v) {
            array_push($siswa_terdaftar, enc($v['student_id'], 1));
        }

        // Filter siswa yang belum terdaftar
        $data = $this->student->find();
        foreach ($data as $k => $v) {
            if(in_array(enc($v['id'], 1), $siswa_terdaftar)){
                unset($data[$k]);
            }
        }

        $student_ready = $data;

        $this->temp('data/student_grade/create', [
            'grade_period_id' => $grade_period_id,
            'student_current' => $student_current,
            'student_ready' => $student_ready,
            'grade_period' => $grade_period,
            'old' => $old,
        ]);
    }

    public function get_student_grade_json(){
        $this->filter(2);
        $data['data'] = $this->data->find();
        $data['token'] = $this->security->get_csrf_hash();
        
        echo json_encode($data);
    }

    public function save()
    {
        $this->filter(1);

        $data = $this->input->post();

        // DEBUG
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        die();

        $grade_period_id = enc($this->input->post('id'), 1);
        $dsave = [];

        $i = 0;
        foreach ($data as $k => $v) {
            if ($k != 'id') {
                $dsave[$i]['grade_period_id'] = $grade_period_id;
                $dsave[$i]['student_id'] = enc($k, 1);
                $i++;
            }
        }

        $save = $this->data->save_batch($dsave);

        if ($save['status'] == '200') {
            $this->session->set_flashdata('message', $save['message']);
            redirect(base_url('data/grade_period'));
        } else {
            $this->create($this->input->post());
        }
    }

    public function edit($id, $old = [])
    {
        $this->filter(3);
    }

    public function update()
    {
        $this->filter(3);
    }

    public function delete($id)
    {
        $this->filter(4);
    }

    public function restore($id)
    {
        $this->filter(4);
    }
}
