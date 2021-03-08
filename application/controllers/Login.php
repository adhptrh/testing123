<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{
    private $is_siswa = 0;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('User_m', 'model');
        $this->load->model('Menu_m', 'menu');
        $this->load->model('Hr_m', 'hr');
        $this->load->model('Lam_m', 'lam');
        $this->load->model('Student_m', 'student');
        $this->load->model('Student_grade_m', 'student_grade');
        $this->load->model('Period_m', 'period');
    }

    public function index()
    {
        session_destroy();
        $this->load->view('login/content3');
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

    private function set_is_siswa($profile_id)
    {
        $student_id = $this->student->find(false, [
            'a.profile_id' => $profile_id,
        ]);

        if ($student_id) {
            $period_active = $this->period->find(false, ['a.status' == 1]);

            $cek = $this->student_grade->find(false, [
                'a.student_id' => enc($student_id[0]['id'], 1),
                'e.status' => 1, // Periode yang aktif
            ]);

            if ($cek) {
                $this->is_siswa = $cek[0]['id'];
            } else {
                // Siswa belum punya kelas pada periode ini
                $this->session->set_flashdata('message', 'Anda belum memiliki kelas');
                redirect(base_url('login'));
            }
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

                    if ($this->is_siswa) {
                        // Cek apakah sedang login
                        $cek_still_login = $this->student->find(false, [
                            'a.nisn' => $get_user[0]['username'],
                            'a.is_login' => 1,
                        ]);

                        if ($cek_still_login) {
                            // Terdeteksi sudah login
                            $this->session->set_flashdata('message', 'Anda terdeteksi sudah login, silahkan hubungi penyelenggara Ujian untuk RESET LOGIN');
                            redirect(base_url('login'));
                        } else {
                            // Tandai sedang login
                            $student = $this->student->find(false, [
                                'a.nisn' => $get_user[0]['username']
                            ]);

                            $update = $this->student->save([
                                'id' => enc($student[0]['id'], 1),
                                'is_login' => 1,
                            ], true);

                            if ($update['status'] == '200') {
                                redirect(base_url('/app/exam_schedule'));
                            } else {
                                // Tidak behasil update status login
                                $this->session->set_flashdata('message', 'Sistem gagal mengupdate status login, silahkan hubungi penyelenggara Ujian');
                                redirect(base_url('login'));
                            }
                        }
                    } else {
                        if($get_user[0]['is_repassword'] == 1){
                            redirect(base_url('/app/my_profile/change_password'));
                        }else{
                            redirect(base_url('/'));
                        }
                    }

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

    private function get_menu($profile)
    {
        $menudb = $this->menu->find();

        $get_lam_read_role = $this->lam->find(false, [
            'a.level_id' => enc($profile['level_id'], 1),
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
        $profile_id = enc($get_user[0]['profile_id'], 1);

        //Set is_siswa jika ini adalah siswa
        $this->set_is_siswa($profile_id);

        if ($this->is_siswa) { // Jika ini adalah siswa
            // List of Access Modifier
            $get_lam = $this->lam->find(false, [
                'a.level_id' => 4, // ID Level for siswa
            ]);

            $profile = array(
                'profile_id' => $get_user[0]['profile_id'],
                'name' => $get_user[0]['name'],
                'level' => 'Siswa',
                'level_id' => enc(4),
                'student_grade_id' => $this->is_siswa,
            );

        } else { // jika ini bukan siswa
            // Human Resources
            $get_hr = $this->hr->find(false, [
                'a.profile_id' => $profile_id,
            ]);

            // List of Access Modifier
            $get_lam = $this->lam->find(false, [
                'a.level_id' => enc($get_hr[0]['level_id'], 1),
            ]);

            $profile = array(
                'profile_id' => $get_user[0]['profile_id'],
                'name' => $get_hr[0]['name'],
                'level' => $get_hr[0]['level_name'],
                'level_id' => $get_hr[0]['level_id'],
                'student_grade_id' => 0,
            );
        }

        $token = $this->get_token();

        $this->session->set_userdata([
            'token' => $token,
            'profile' => $profile,
            'lam' => $get_lam,
            'menu' => $this->get_menu($profile),
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
