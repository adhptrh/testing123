<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Exam_schedule extends MY_Controller
{

    /**
     * Peringatan ! selain fungsi index, create, save, edit, update, delete, dan restore
     * semua function HARUS protected-function
     *
     */

    public function __construct()
    {
        parent::__construct();
        $this->controller_id = 15;
        $this->load->model('Exam_schedule_m', 'data');
        $this->load->model('Period_m', 'period');
        $this->load->model('Exam_question_m', 'exam');
        $this->load->model('Order_m', 'order');
    }

    /**
     * Mendapatkan profile dari session
     *
     */

    public function index()
    {
        $this->filter(2);

        $this->header = [
            'title' => 'Jadwal Ujian',
            'sub_title' => 'Pengaturan Jadwal Ujian',
            'nav_active' => 'app/exam_schedule',
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
                    'label' => 'Jadwal Ujian',
                    'icon' => '',
                    'href' => '#',
                ],
            ],
        ];

        //student_grade_id_from_session
        $this->set_student_grade_id();

        if (enc($this->student_grade_id, 1)) { // Jika siswa

            //detail student_grade
            $sg = $this->student_grade->find(enc($this->student_grade_id, 1));

            // filter

            // grade_period
            $gp = enc($sg['period_id'], 1);

            // order_id
            $oi = $sg['order_id'];

            $data = $this->data->find(false, [
                'e.id' => $oi,
                'i.grade_period_id' => $gp,
			]);
			
			$data = [
				'exam_schedule' => $data,
				'student' => true,
			];

        } else {
            $data = [
				'exam_schedule' => $this->data->find(),
				'student' => false,
			];
        }

        $this->temp('app/exam_schedule/content', [
            'data' => $data,
        ]);
    }

    public function get_json()
    {
        $this->filter(2);
        $post = $this->input->post('filter');

        if (isset($post['period'])) {
            $filter = [
                'a.period_id' => enc($post['period'], 1),
            ];
        }

        $data = [
            'token' => $this->security->get_csrf_hash(),
            'exam' => $this->exam->find(false, $filter),
        ];
        echo json_encode($data);
    }

    public function create($old = [])
    {
        $this->filter(1);

        $this->header = [
            'title' => 'Jadwal Ujian',
            'js_file' => 'app/es',
            'sub_title' => 'Tambah Jadwal Ujian',
            'nav_active' => 'app/exam_schedule',
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
                    'label' => 'Jadwal Ujian',
                    'icon' => 'fa-list',
                    'href' => base_url('app/exam_schedule'),
                ],
                [
                    'label' => 'Tambah',
                    'icon' => '',
                    'href' => '#',
                ],
            ],
        ];

        $this->temp('app/exam_schedule/create', [
            'old' => $old,
            'period' => $this->period->find(),
            'order' => $this->order->find(),
        ]);
    }

    public function save()
    {
        $this->filter(1);

        // Cek Jadwal Ujian apakah sudah ada
        $data = $this->data->find(0, [
            'a.exam_question_id' => enc($this->input->post('exam'), 1),
            'a.order_id' => enc($this->input->post('order'), 1),
            'a.date' => $this->ptime($this->input->post('date')),
        ], true);

        if ($data) {
            if ($data[0]['is_del'] == '1') {
                $link = '<a href="' . base_url('app/exam_schedule/restore/' . $data[0]['id']) . '" class="btn btn-sm btn-primary">Ya, kembalikan data ini</a>';
                $this->session->set_flashdata('create_info_message', 'Jadwal Ujian ini sebelumnya sudah digunakan, namun sudah dihapus pada ' . $data[0]['updated_at'] . ' oleh ' . $data[0]['updated_by'] . ', apakah Anda ingin memulihkan data ini?' . $link);
            } else {
                $this->session->set_flashdata('create_info_message', 'Mohon gunakan Jadwal Ujian lain, karena Jadwal Ujian ini sudah terdaftar');
            }
            $this->create($this->input->post());
        } else {
            $date = $this->ptime($this->input->post('date'));
            $save = [
                'exam_question_id' => enc($this->input->post('exam'), 1),
                'order_id' => enc($this->input->post('order'), 1),
                'date' => $date,
                'start' => $date . " " .$this->input->post('start'),
                'finish' => $date . " " .$this->input->post('finish'),
                'number_of_exam' => $this->input->post('number_of_exam'),
            ];

            $save = $this->data->save($save);

            if ($save['status'] == '200') {
                $this->session->set_flashdata('message', $save['message']);
                redirect(base_url('app/exam_schedule'));
            } else {
                $this->create($this->input->post());
            }
        }
    }

    public function edit($id, $old = [])
    {
        $this->filter(3);

        $this->header = [
            'title' => 'Jadwal Ujian',
            'js_file' => 'app/es',
            'sub_title' => 'Ubah Jadwal Ujian',
            'nav_active' => 'app/exam_schedule',
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
                    'label' => 'Jadwal Ujian',
                    'icon' => 'fa-list',
                    'href' => base_url('app/exam_schedule'),
                ],
                [
                    'label' => 'Edit',
                    'icon' => '',
                    'href' => '#',
                ],
            ],
        ];

        $data = $this->data->find(enc($id, 1));
        $period_id = enc($data['period_id'], 1);

        $this->temp('app/exam_schedule/edit', [
            'data' => $data,
            'period' => $this->period->find(false, false, false, $period_id),
            'exam' => $this->exam->find(false, ['a.period_id' => $period_id], false, enc($data['exam_question_id'], 1)),
            'order' => $this->order->find(false, false, false, enc($data['order_id'], 1)),
            'old' => $old,
        ]);
    }

    public function update()
    {
        $this->filter(3);

        // Cek Jadwal Ujian apakah sudah ada
        $cek = $this->data->find(0, [
            'a.exam_question_id' => enc($this->input->post('exam'), 1),
            'a.order_id' => enc($this->input->post('order'), 1),
            'a.date' => $this->ptime($this->input->post('date')),
        ], true);

        if ($cek && enc($cek[0]['id'], 1) != enc($this->input->post('id'), 1)) {
            if ($cek[0]['is_del'] == '1') {

                $link = '<a href="' . base_url('app/exam_schedule/restore/' . $cek[0]['id']) . '" class="btn btn-sm btn-primary">Ya, kembalikan data ini</a>';
                $this->session->set_flashdata('update_info_message', 'Jadwal Ujian ini sebelumnya sudah digunakan, namun sudah dihapus pada ' . $cek[0]['updated_at'] . ' oleh ' . $cek[0]['updated_by'] . ', apakah Anda ingin memulihkan data ini? ' . $link);
            } else {
                $this->session->set_flashdata('update_info_message', 'Jadwal Ujian sudah terdaftar');
            }

            $this->edit($this->input->post('id'), $this->input->post());
        } else {
            $date = $this->ptime($this->input->post('date'));
            $save = [
                'id' => enc($this->input->post('id'), 1),
                'exam_question_id' => enc($this->input->post('exam'), 1),
                'order_id' => enc($this->input->post('order'), 1),
                'date' => $date,
                'start' => $date . " " .$this->input->post('start'),
                'finish' => $date . " " .$this->input->post('finish'),
                'number_of_exam' => $this->input->post('number_of_exam'),
            ];

            $update = $this->data->save($save);
            if ($update['status'] == '200') {
                $this->session->set_flashdata('message', $update['message']);
                redirect(base_url('app/exam_schedule'));
            } else {
                $this->edit($this->input->post('id'), $this->input->post());
            }
        }
    }

    public function delete($id)
    {
        $this->filter(4);
        $delete = $this->data->delete($id);

        $this->session->set_flashdata('message', $delete['message']);
        redirect(base_url('app/exam_schedule'));
    }

    public function restore($id)
    {
        $this->filter(4);
        $delete = $this->data->restore($id);

        $this->session->set_flashdata('message', $delete['message']);
        redirect(base_url('app/exam_schedule'));
    }
}
