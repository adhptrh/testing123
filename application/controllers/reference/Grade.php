<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Grade extends MY_Controller
{

    /**
     * Peringatan ! selain fungsi index, create, save, edit, update, delete, dan restore
     * semua function HARUS protected-function
     *
     */

    public function __construct()
    {
        parent::__construct();
        $this->controller_id = 21;
        $this->load->model('Grade_m', 'data');
        $this->load->model('Major_m', 'major');
    }

    /**
     * Mendapatkan profile dari session
     *
     */

    public function index()
    {
        $this->filter(2);

        $this->header = [
            'title' => 'Kelas',
            'sub_title' => 'Pengaturan Kelas',
            'nav_active' => 'reference/grade',
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
                    'label' => 'Kelas',
                    'icon' => '',
                    'href' => '#',
                ],
            ],
        ];

        $this->temp('reference/grade/content', [
            'data' => $this->data->find(),
        ]);
    }

    public function get_json($major_id)
    {
        $this->filter(2);

        $grade = $this->data->find(false, [
            'a.major_id' => enc($major_id, 1),
		]);
		
		$data = [
			'token' => $this->security->get_csrf_hash(),
			'grade' => $grade,
		];
        echo json_encode($data);
    }

    public function create($old = [])
    {
        $this->filter(1);

        $this->header = [
            'title' => 'Kelas',
            'sub_title' => 'Tambah Kelas',
            'nav_active' => 'reference/grade',
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
                    'label' => 'Kelas',
                    'icon' => 'fa-list',
                    'href' => base_url('reference/grade'),
                ],
                [
                    'label' => 'Tambah',
                    'icon' => '',
                    'href' => '#',
                ],
            ],
        ];

        $this->temp('reference/grade/create', [
            'major' => $this->major->find(),
            'old' => $old,
        ]);
    }

    public function save()
    {
        $this->filter(1);

        // Cek Kelas apakah sudah ada
        $data = $this->data->find(0, ['a.name' => $this->input->post('name')], true);

        if ($data) {
            if ($data[0]['is_del'] == '1') {
                $link = '<a href="' . base_url('reference/grade/restore/' . $data[0]['id']) . '" class="btn btn-sm btn-primary">Ya, kembalikan data ini</a>';
                $this->session->set_flashdata('create_info_message', 'Kelas ini sebelumnya sudah digunakan, namun sudah dihapus pada ' . $data[0]['updated_at'] . ' oleh ' . $data[0]['updated_by'] . ', apakah Anda ingin memulihkan data ini?' . $link);
            } else {
                $this->session->set_flashdata('create_info_message', 'Mohon gunakan Kelas lain, karena Kelas ini sudah terdaftar');
            }
            $this->create($this->input->post());
        } else {
            $save = [
                'name' => $this->input->post('name'),
                'major_id' => enc($this->input->post('major'), 1),
            ];

            $save = $this->data->save($save);
            if ($save['status'] == '200') {
                $this->session->set_flashdata('message', $save['message']);
                redirect(base_url('reference/grade'));
            } else {
                $this->create($this->input->post());
            }
        }
    }

    public function edit($id, $old = [])
    {
        $this->filter(3);

        $this->header = [
            'title' => 'Kelas',
            'sub_title' => 'Ubah Kelas',
            'nav_active' => 'reference/grade',
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
                    'label' => 'Kelas',
                    'icon' => 'fa-list',
                    'href' => base_url('reference/grade'),
                ],
                [
                    'label' => 'Edit',
                    'icon' => '',
                    'href' => '#',
                ],
            ],
        ];

        $this->temp('reference/grade/edit', [
            'data' => $data = $this->data->find(enc($id, 1)),
            'major' => $this->major->find(false, false, false, enc($data['major_id'], 1)),
            'old' => $old,
        ]);
    }

    public function update()
    {
        $this->filter(3);

        // Cek Kelas apakah sudah ada
        $cek = $this->data->find(0, ['a.name' => $this->input->post('name')], true);

        if ($cek && enc($cek[0]['id'], 1) != enc($this->input->post('id'), 1)) {
            if ($cek[0]['is_del'] == '1') {

                $link = '<a href="' . base_url('reference/grade/restore/' . $cek[0]['id']) . '" class="btn btn-sm btn-primary">Ya, kembalikan data ini</a>';
                $this->session->set_flashdata('update_info_message', 'Kelas ini sebelumnya sudah digunakan, namun sudah dihapus pada ' . $cek[0]['updated_at'] . ' oleh ' . $cek[0]['updated_by'] . ', apakah Anda ingin memulihkan data ini? ' . $link);
            } else {
                $this->session->set_flashdata('update_info_message', 'Kelas sudah terdaftar');
            }

            $this->edit($this->input->post('id'), $this->input->post());
        } else {

            $save = [
                'id' => enc($this->input->post('id'), 1),
                'name' => $this->input->post('name'),
                'major_id' => enc($this->input->post('major'), 1),
            ];

            $update = $this->data->save($save);
            if ($update['status'] == '200') {
                $this->session->set_flashdata('message', $update['message']);
                redirect(base_url('reference/grade'));
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
        redirect(base_url('reference/grade'));
    }

    public function restore($id)
    {
        $this->filter(4);
        $delete = $this->data->restore($id);

        $this->session->set_flashdata('message', $delete['message']);
        redirect(base_url('reference/grade'));
    }
}
