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
        $this->load->model('Order_m', 'order');
    }

    public function index()
    {
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

    public function get_student_grade_json()
    {
        $this->filter(2);
        $post = $this->input->post('filter');
        $data['data'] = $this->data->find(false, ['a.grade_period_id' => enc($post['gradePeriod'], 1)]);
        $data['token'] = $this->security->get_csrf_hash();

        echo json_encode($data);
    }

    public function get_orders()
    {
        $this->filter(2);
        $data['data'] = $this->order->find();
        $data['token'] = $this->security->get_csrf_hash();

        echo json_encode($data);
    }

    public function get_student_nongrade_json()
    {
        $this->filter(2);
        $post = $this->input->post('filter');
        $grade_period_id = enc($post['gradePeriod'], 1);

        // Siswa yang sudah terdaftar di periode ini.
        $data = $this->data->find(false, [
            // 'd.period_id' => $grade_period_id,
            'e.status' => 1,
        ]);

        $siswa_terdaftar = [];

        foreach ($data as $k => $v) {
            array_push($siswa_terdaftar, enc($v['student_id'], 1));
        }

        // Filter siswa yang belum terdaftar
        $data = $this->student->find();
        foreach ($data as $k => $v) {
            if (in_array(enc($v['id'], 1), $siswa_terdaftar)) {
                unset($data[$k]);
            }
        }

        $student_ready = array_values($data);
        $data['data'] = $student_ready;
        $data['token'] = $this->security->get_csrf_hash();
        $data['siswa_terdaftar'] = $siswa_terdaftar;

        echo json_encode($data);
    }

    public function set_order(){
        $student_grade_id = $this->input->post('student_grade_id');
        $order_id = $this->input->post('order_id');

        $save = $this->data->save([
            'id' => enc($student_grade_id, 1),
            'order_id' => enc($order_id, 1),
        ], true);

        $data = [
            'token' => $this->security->get_csrf_hash(),
            'query' => $this->db->last_query(),
            'post' => $this->input->post(),
        ];

        echo json_encode($data);
    }

    public function save()
    {
        $this->filter(1);

        // Get Student_id
        $student_id = enc($this->input->post('student'), 1);

        // Get Grade_period_id
        $grade_period_id = enc($this->input->post('grade_period'), 1);

        // Get Period_id
        $period_id = enc($this->input->post('period'), 1);

        // Cek apakah student_id sudah pernah didelete?
        $cek = $this->data->find(false, [
            'a.student_id' => $student_id,
            'e.id' => $period_id,
        ], true);

        if ($cek) { // Ya
            // Update Grade_period_id, set is_del = 0
            $save = $this->data->save([
                'id' => enc($cek[0]['id'], 1),
                'student_id' => $student_id,
                'grade_period_id' => $grade_period_id,
                'is_del' => '0',
            ]);
        } else { // Tidak
            // Insert student_exted_grades
            $save = $this->data->save([
                'student_id' => $student_id,
                'grade_period_id' => $grade_period_id,
            ]);
        }

        $data = [
            'status' => $save['status'],
            'message' => $save['message'],
            'token' => $this->security->get_csrf_hash(),
        ];

        echo json_encode($data);
    }

    public function card_print($grade_period_id){
        $gpi = enc($grade_period_id, 1);
        $data = $this->data->find(false, ['a.grade_period_id' => $gpi]);
        $this->load->view('data/student_grade/card_print', ['data' => $data]);
    }

    public function delete()
    {
        $this->filter(4);
        $delete = $this->data->delete($this->input->post('ID'));

        $this->session->set_flashdata('message', $delete['message']);

        $data = [
            'message' => '',
            'token' => $this->security->get_csrf_hash(),
        ];

        echo json_encode($data);
    }
}