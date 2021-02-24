<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Grade_period extends MY_Controller
{

    /**
     * Peringatan ! selain fungsi index, create, save, edit, update, delete, dan restore
     * semua function HARUS protected-function
     *
     */

    public function __construct()
    {
        parent::__construct();
        $this->controller_id = 22;
        $this->load->model('grade_period_m', 'data');
        $this->load->model('Grade_m', 'grade');
        $this->load->model('Major_m', 'major');
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
            'title' => 'Kelas',
            'sub_title' => 'Pengaturan Kelas',
            'nav_active' => 'data/grade_period',
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
                    'label' => 'Kelas',
                    'icon' => '',
                    'href' => '#',
                ],
            ],
        ];

        $this->temp('data/grade_period/content', [
            'data' => $this->data->find(),
        ]);
    }

    public function get_json(){
        $this->filter(2);
        $post = $this->input->post('filter');

        if(isset($post['period'])){
            $filter = [
                'a.period_id' => enc($post['period'], 1)
            ];
        }

        $data = [
            'token' => $this->security->get_csrf_hash(),
			'grade' => $this->data->find(false, $filter),
		];
        echo json_encode($data);
    }

    public function create($old = [])
    {
        $this->filter(1);

        $this->header = [
            'title' => 'Kelas',
            'sub_title' => 'Tambah Kelas',
            'nav_active' => 'data/grade_period',
            'js_file' => 'data/grade',
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
                    'label' => 'Kelas',
                    'icon' => 'fa-list',
                    'href' => base_url('data/grade_period'),
                ],
                [
                    'label' => 'Tambah',
                    'icon' => '',
                    'href' => '#',
                ],
            ],
        ];

        $this->temp('data/grade_period/create', [
            'period' => $this->period->find(),
            'major' => $this->major->find(),
            'old' => $old,
        ]);
    }

    public function save()
    {
        $this->filter(1);

        // Cek Kelas apakah sudah ada
        $data = $this->data->find(0, [
            'a.grade_id' => enc($this->input->post('grade'), 1),
            'a.period_id' => enc($this->input->post('period'), 1),
        ], true);

        if ($data) {
            if ($data[0]['is_del'] == '1') {
                $link = '<a href="' . base_url('data/grade_period/restore/' . $data[0]['id']) . '" class="btn btn-sm btn-primary">Ya, kembalikan data ini</a>';
                $this->session->set_flashdata('create_info_message', 'Kelas ini sebelumnya sudah digunakan, namun sudah dihapus pada ' . $data[0]['updated_at'] . ' oleh ' . $data[0]['updated_by'] . ', apakah Anda ingin memulihkan data ini?' . $link);
            } else {
                $this->session->set_flashdata('create_info_message', 'Mohon gunakan Kelas lain, karena Kelas ini sudah terdaftar');
            }
            $this->create($this->input->post());
        } else {
            $save = [
                'grade_id' => enc($this->input->post('grade'), 1),
                'period_id' => enc($this->input->post('period'), 1),
			];

            $save = $this->data->save($save);
            if ($save['status'] == '200') {
                $this->session->set_flashdata('message', $save['message']);
                redirect(base_url('data/grade_period'));
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
			'js_file' => 'data/grade_period',
            'sub_title' => 'Ubah Kelas',
            'nav_active' => 'data/grade_period',
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
                    'label' => 'Kelas',
                    'icon' => 'fa-list',
                    'href' => base_url('data/grade_period'),
                ],
                [
                    'label' => 'Edit',
                    'icon' => '',
                    'href' => '#',
                ],
            ],
        ];

		$data = $this->data->find(enc($id, 1));
		$grade = $this->grade->find(enc($data['grade_id'], 1));

        $this->temp('data/grade_period/edit', [
            'data' => $data,
            'major' => $this->major->find(false, false, false, enc($grade['major_id'], 1)),
            'grade' => $this->grade->find(false, false, false, enc($data['grade_id'], 1)),
            'period' => $this->period->find(false, false, false, enc($data['period_id'], 1)),
            'old' => $old,
        ]);
    }

    public function update()
    {
        $this->filter(3);

        // Cek Kelas apakah sudah ada
        $cek = $this->data->find(0, [
            'a.grade_id' => enc($this->input->post('grade'), 1),
            'a.period_id' => enc($this->input->post('period'), 1),
        ], true);

        if ($cek && enc($cek[0]['id'], 1) != enc($this->input->post('id'), 1)) {
            if ($cek[0]['is_del'] == '1') {

                $link = '<a href="' . base_url('data/grade_period/restore/' . $cek[0]['id']) . '" class="btn btn-sm btn-primary">Ya, kembalikan data ini</a>';
                $this->session->set_flashdata('update_info_message', 'Kelas ini sebelumnya sudah digunakan, namun sudah dihapus pada ' . $cek[0]['updated_at'] . ' oleh ' . $cek[0]['updated_by'] . ', apakah Anda ingin memulihkan data ini? ' . $link);
            } else {
                $this->session->set_flashdata('update_info_message', 'Kelas sudah terdaftar');
            }

            $this->edit($this->input->post('id'), $this->input->post());
        } else {

            $save = [
                'id' => enc($this->input->post('id'), 1),
                'grade_id' => enc($this->input->post('grade'), 1),
                'period_id' => enc($this->input->post('period'), 1),
            ];

            $update = $this->data->save($save);
            if ($update['status'] == '200') {
                $this->session->set_flashdata('message', $update['message']);
                redirect(base_url('data/grade_period'));
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
        redirect(base_url('data/grade_period'));
    }

    public function restore($id)
    {
        $this->filter(4);
        $delete = $this->data->restore($id);

        $this->session->set_flashdata('message', $delete['message']);
        redirect(base_url('data/grade_period'));
    }
}
