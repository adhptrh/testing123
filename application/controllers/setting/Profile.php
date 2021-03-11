<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Profile extends MY_Controller
{

    /**
     * Peringatan ! selain fungsi index, create, save, edit, update, delete, dan restore
     * semua function HARUS protected-function
     *
     */

    public function __construct()
    {
        parent::__construct();
        $this->controller_id = 25;
        $this->load->model('school_profile_m', 'data');
        $this->load->model('hr_m', 'hrd');
    }

    /**
     * Mendapatkan profile dari session
     *
     */

    public function index()
    {
        $this->filter(2);

        $this->header = [
            'title' => 'Profil',
            'sub_title' => 'Pengaturan Profil',
            'nav_active' => 'setting/profile',
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
                    'label' => 'Profil',
                    'icon' => '',
                    'href' => '#',
                ],
            ],
        ];

        $data = $this->data->find();
        if ($data) {
            $profile = $data[0];
        } else {
            $profile = $this->data->empty_data();
        }

        $this->temp('setting/profile/content', [
            'data' => $profile,
        ]);
    }

    public function edit($old = [])
    {
        $this->filter(3);

        $this->header = [
            'title' => 'Profil',
            'sub_title' => 'Ubah Profil',
            'nav_active' => 'setting/profile',
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
                    'label' => 'Profil',
                    'icon' => 'fa-list',
                    'href' => base_url('setting/profile'),
                ],
                [
                    'label' => 'Edit',
                    'icon' => '',
                    'href' => '#',
                ],
            ],
        ];

        $data = $this->data->find();
        if ($data) {
            $profile = $data;
			$hrd = $this->hrd->find(false, false, false, enc($profile[0]['headmaster_id'], 1));
        } else {
			$profile = $this->data->empty_data();
			$hrd = $this->hrd->find();
        }

        $this->temp('setting/profile/edit', [
            'data' => $profile[0],
            'hrd' => $hrd,
            'old' => $old,
        ]);
    }

    public function update()
    {
        $this->filter(3);

		$save = [
            'name' => $this->input->post('name'),
            'headmaster' => enc($this->input->post('headmaster'), 1),
            'address' => $this->input->post('address'),
            'long_name' => $this->input->post('long_name'),
        ];

		$data_ready = $this->data->find();

		if($data_ready){
			$save['id'] = 1;
		}

        $update = $this->data->save($save);
        if ($update['status'] == '200') {
            $this->session->set_flashdata('message', $update['message']);
            redirect(base_url('setting/profile'));
        } else {
            $this->edit($this->input->post());
        }
    }
}
