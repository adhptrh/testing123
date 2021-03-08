<?php
defined('BASEPATH') or exit('No direct script access allowed');

class My_profile extends MY_Controller
{

    /**
     * Peringatan ! selain fungsi index, create, save, edit, update, delete, dan restore
     * semua function HARUS protected-function
     *
     */

    public function __construct()
    {
        parent::__construct();
        $this->controller_id = 26;
        $this->load->model('profile_m', 'profile');
        $this->load->model('user_m', 'user');
    }

    /**
     * Mendapatkan profile dari session
     *
     */

    public function index()
    {
        $this->filter(2);

        $this->header = [
            'title' => 'Profilku',
            'sub_title' => 'Pengaturan Profilku',
            'nav_active' => 'app/my_profile',
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
                    'label' => 'Profilku',
                    'icon' => '',
                    'href' => '#',
                ],
            ],
        ];

        $this->temp('app/my_profile/content', [
            'name' => $this->profile->my_profile()['name'],
        ]);
    }

    public function create($old = [])
    {
        $this->filter(1);
    }

    public function save()
    {
        $this->filter(1);
    }

    public function edit($id, $old = [])
    {
        $this->filter(3);
    }

    public function change_password()
    {
        $this->filter(3);

        $this->header = [
            'title' => 'Profilku',
            'sub_title' => 'Pengaturan Profilku',
            'nav_active' => 'app/my_profile',
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
                    'label' => 'Profilku',
                    'icon' => '',
                    'href' => '#',
                ],
                [
                    'label' => 'Ganti Password',
                    'icon' => '',
                    'href' => '#',
                ],
            ],
        ];

        $this->temp('app/my_profile/change_password', [
            'data' => [],
        ]);
    }

    public function update_password()
    {
        $this->filter(3);

        $password_old = $this->input->post('password_old');
        $password_new = $this->input->post('password_new');
        $password_new_confirmation = $this->input->post('password_new_confirmation');

        $user_id = enc($this->profile->my_profile()['user_id'], 1);
        $password_current = $this->user->find(false, [
            'a.id' => $user_id
        ]);

        if (password_verify($this->input->post('password_old'), $password_current[0]['password'])) {
            if($password_new == $password_new_confirmation){
                $this->user->save([
                    'id' => $user_id,
                    'password' => password_hash($password_new, PASSWORD_DEFAULT),
                    'is_repassword' => 0,
                ], 1);
                redirect(base_url('login'));
            }else{
                $this->session->set_flashdata('message', 'Password baru tidak cocok');
				redirect(base_url('app/my_profile/change_password'));
            }
        }else{
			redirect(base_url('login'));
        }

    }

    public function update()
    {
        $this->filter(3);

        $save = [
            'id' => enc($this->profile->my_profile()['id'], 1),
            'name' => $this->input->post('name'),
        ];

        $update = $this->profile->save($save);
        if($update['status'] == '200'){
            $this->session->set_flashdata('message', $update['message']);
            redirect(base_url('login'));
        }else{
            redirect(base_url('app/my_profile'));
        }

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
