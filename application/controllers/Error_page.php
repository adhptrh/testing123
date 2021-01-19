<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Error_page extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        session_destroy();
        $this->load->view('error/content');
    }

    private function validate()
    {
        $this->load->library('form_validation');

        $rules = [
            [
                'field' => 'username',
                'label' => 'Username',
                'rules' => 'required',
                'errors' => [
                    'required' => '%s harus diisi',
                ],
            ],
            [
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'required',
                'errors' => [
                    'required' => '%s harus diisi',
                ],
            ],
        ];
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run() == false) {
            return false;

        } else {
            return true;
        }
    }

    public function authorization()
    {
        if ($this->validate()) {
            $get_user = $this->model->find(false, ['a.username' => $this->input->post('username')]);
            // Apakah username terdaftar
            if ($get_user) {
                // Apakah password sesuai
                if (password_verify($this->input->post('password'), $get_user[0]['password'])) {

                    // Password benar
                    $this->assign_session($get_user);

                    redirect(base_url('/'));

                } else {

                    // Password salah
                    $this->session->set_flashdata('message', 'Username atau Password salah');
                    redirect(base_url('login'));
                }
            } else {
                // User tidak terdaftar
                $this->session->set_flashdata('message', 'Username atau Password salah');
                redirect(base_url('login'));
            }
        } else {
            $this->index();
        }
    }

    private function get_token()
    {
        /**
         * Mendapatkan token
         *
         */

        //Generate a random string.
        $token = openssl_random_pseudo_bytes(32);

        //Convert the binary data into hexadecimal representation.
        $token = bin2hex($token);

        return $token;
    }

    private function get_menu($get_hr)
    {
        $menudb = $this->menu->find();

        $get_lam_read_role = $this->lam->find(false, [
            'a.level_id' => enc($get_hr[0]['level_id'], 1),
            'a.xread' => 1,
        ]);

        foreach ($menudb as $k => $v) {
            if (enc($v['parent'], 1) != 0) {
                $cek = 0;
                foreach ($get_lam_read_role as $k1 => $v1) {
                    if (enc($v['id'], 1) == enc($v1['menu_id'], 1)) {
                        $cek++;
                    }
                }

                if (!$cek) {
                    unset($menudb[$k]);
                }
            }
        }

        return $menudb;
    }

    private function assign_session($get_user)
    {
        // Human Resources
        $get_hr = $this->hr->find(false, [
            'a.profile_id' => enc($get_user[0]['profile_id'], 1),
        ]);

        // List of Access Modifier
        $get_lam = $this->lam->find(false, [
            'a.level_id' => enc($get_hr[0]['level_id'], 1),
        ]);

        $profile = array(
            'profile_id' => $get_user[0]['id'],
            'name' => $get_hr[0]['name'],
            'level' => $get_hr[0]['level_name'],
            'level_id' => $get_hr[0]['level_id'],
        );

        $token = $this->get_token();

        $this->session->set_userdata([
            'token' => $token,
            'profile' => $profile,
            'lam' => $get_lam,
            'menu' => $this->get_menu($get_hr),
        ]);

        // Update token di table user
        $this->store_token($get_user, $token);
    }

    private function store_token($get_user, $token)
    {
        $update = $this->model->save([
            'token' => $token,
            'id' => enc($get_user[0]['id'], 1),
        ], true); // Update with skip validation
    }
}
