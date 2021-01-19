<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Exam_question extends MY_Controller
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
        $this->load->model('Exam_question_m', 'data');
        $this->load->model('Study_m', 'study');
        $this->load->model('Period_m', 'period');
    }

    /**
     * Mendapatkan profile dari session
     *
     */

    public function index()
    {
        $this->filter(2);

        $this->header = [
            'title' => 'Soal',
            'sub_title' => 'Pengaturan Soal',
            'nav_active' => 'app/exam_question',
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
                    'label' => 'Soal',
                    'icon' => '',
                    'href' => '#',
                ],
            ],
        ];

        $this->temp('app/exam_question/content', [
            'data' => $this->data->find(),
        ]);
    }

    public function create($old = [])
    {
        $this->filter(1);

        $this->header = [
            'title' => 'Soal',
            'sub_title' => 'Tambah Soal',
            'nav_active' => 'app/exam_question',
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
                    'label' => 'Soal',
                    'icon' => 'fa-list',
                    'href' => base_url('app/exam_question'),
                ],
                [
                    'label' => 'Tambah',
                    'icon' => '',
                    'href' => '#',
                ],
            ],
        ];

        $this->temp('app/exam_question/create', [
            'old' => $old,
            'period' => $this->period->find(),
            'study' => $this->study->find(),
        ]);
    }

    public function save()
    {
        $this->filter(1);

        // Cek Soal apakah sudah ada
        $data = $this->data->find(0, [
            'a.period_id' => enc($this->input->post('period'), 1),
            'a.study_id' => enc($this->input->post('study'), 1),
        ], true);

        if ($data) {
            if ($data[0]['is_del'] == '1') {
                $link = '<a href="' . base_url('app/exam_question/restore/' . $data[0]['id']) . '" class="btn btn-sm btn-primary">Ya, kembalikan data ini</a>';
                $this->session->set_flashdata('create_info_message', 'Soal ini sebelumnya sudah digunakan, namun sudah dihapus pada ' . $data[0]['updated_at'] . ' oleh ' . $data[0]['updated_by'] . ', apakah Anda ingin memulihkan data ini?' . $link);
            } else {
                $this->session->set_flashdata('create_info_message', 'Mohon gunakan Soal lain, karena Soal ini sudah terdaftar');
            }
            $this->create($this->input->post());
        } else {
            $save = [
                'period_id' => enc($this->input->post('period'), 1),
                'study_id' => enc($this->input->post('study'), 1),
            ];

            $save = $this->data->save($save);
            if ($save['status'] == '200') {
                $this->session->set_flashdata('message', $save['message']);
                redirect(base_url('app/exam_question'));
            } else {
                $this->create($this->input->post());
            }
        }
    }

    public function edit($id, $old = [])
    {
        $this->filter(3);

        $this->header = [
            'title' => 'Soal',
            'sub_title' => 'Ubah Soal',
            'nav_active' => 'app/exam_question',
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
                    'label' => 'Soal',
                    'icon' => 'fa-list',
                    'href' => base_url('app/exam_question'),
                ],
                [
                    'label' => 'Edit',
                    'icon' => '',
                    'href' => '#',
                ],
            ],
        ];

        $this->temp('app/exam_question/edit', [
            'data' => $data = $this->data->find(enc($id, 1)),
            'study' => $this->study->find(false, false, false, enc($data['study_id'], 1)),
            'period' => $this->period->find(false, false, false, enc($data['period_id'], 1)),
            'old' => $old,
        ]);
    }

    public function update()
    {
        $this->filter(3);

        // Cek Soal apakah sudah ada
        $cek = $this->data->find(0, [
			'a.period_id' => enc($this->input->post('period'), 1),
			'a.study_id' => enc($this->input->post('study'), 1)
		], true);

        if ($cek && enc($cek[0]['id'], 1) != enc($this->input->post('id'), 1)) {
            if ($cek[0]['is_del'] == '1') {

                $link = '<a href="' . base_url('app/exam_question/restore/' . $cek[0]['id']) . '" class="btn btn-sm btn-primary">Ya, kembalikan data ini</a>';
                $this->session->set_flashdata('update_info_message', 'Soal ini sebelumnya sudah digunakan, namun sudah dihapus pada ' . $cek[0]['updated_at'] . ' oleh ' . $cek[0]['updated_by'] . ', apakah Anda ingin memulihkan data ini? ' . $link);
            } else {
                $this->session->set_flashdata('update_info_message', 'Soal sudah terdaftar');
            }

            $this->edit($this->input->post('id'), $this->input->post());
        } else {

            $save = [
                'id' => enc($this->input->post('id'), 1),
                'period_id' => enc($this->input->post('period'), 1),
                'study_id' => enc($this->input->post('study'), 1),
            ];

            $update = $this->data->save($save);
            if ($update['status'] == '200') {
                $this->session->set_flashdata('message', $update['message']);
                redirect(base_url('app/exam_question'));
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
        redirect(base_url('app/exam_question'));
    }

    public function restore($id)
    {
        $this->filter(4);
        $delete = $this->data->restore($id);

        $this->session->set_flashdata('message', $delete['message']);
        redirect(base_url('app/exam_question'));
    }
}
