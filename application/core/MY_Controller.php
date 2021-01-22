<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{

    /**
     * Peringatan ! Jangan mengubah isi controller ini
     * Ini adalah mother_class yang akan mengatur hal-hal
     * yang harus ada disetiap semua controller
     *
     */

    protected $controller_id, $header = [];

    public function __construct()
    {
        parent::__construct();
        $this->sure_check(); // Cek apakah login nya sah
    }

    protected function sure_check()
    {
        /**
         * Mengecek keabsahan login
         * dengan mencocokan session-username dan session-token ke database
         */

        $this->load->model('User_m', 'model');

        $check = [
            'a.id' => enc($this->get_profile()->profile_id, 1),
            'a.token' => $this->get_token(),
        ];

        $lookup = $this->model->find(0, $check);

        if (!$lookup) {
            redirect(base_url('login'));
        }
    }

    protected function filter($way)
    {
        /**
         * Membatasi akses user terhadap controller
         * berdasarkan session list_of_access_modifier ($lam)
         *
         */
        $lam = $this->get_lam();

        $cek = 0;
        foreach ($lam as $k => $v) {
            // allow create?
            if ($way == 1 && $this->controller_id == enc($v['menu_id'], 1) && $v['xcreate'] == 1) {
                $cek++;
            }

            // allow read?
            if ($way == 2 && $this->controller_id == enc($v['menu_id'], 1) && $v['xread'] == 1) {
                $cek++;
            }

            // allow edit?
            if ($way == 3 && $this->controller_id == enc($v['menu_id'], 1) && $v['xupdate'] == 1) {
                $cek++;
            }

            // allow delete?
            if ($way == 4 && $this->controller_id == enc($v['menu_id'], 1) && $v['xdelete'] == 1) {
                $cek++;
            }
        }

        if (!$cek) {
            redirect(base_url('login'));
        }
    }

    protected function temp($content, $data = [])
    {
        $this->load->view('layout/header', [
            'header' => $this->header,
            'profile' => $this->get_profile(),
        ]);
        $this->load->view('layout/nav', [
            'menu' => $this->get_menu(),
        ]);
        $this->load->view($content, $data);
        $this->load->view('layout/footer');
    }

    private function get_token()
    {
        /**
         * Mendapatkan token dari session
         *
         */

        return $this->session->userdata('token');
    }

    private function get_menu()
    {
        /**
         * Mendapatkan menu dari session
         *
         */

        $data = $this->session->userdata('menu');
        $menu = [];

        // Create menu level 1
        foreach ($data as $k => $v) {
            if (enc($v['parent'], 1) == 0) {
                $menu[] = $v;
            }
        }

        // Create menu level 2
        foreach ($data as $k => $v) {
            if (enc($v['parent'], 1) != 0) {

                // Menyisipkan sub_menu kepada menu parent
                foreach ($menu as $mk => $mv) {
                    if (enc($mv['id'], 1) == enc($v['parent'], 1)) {
                        $menu[$mk]['submenu'][] = $v; // Sisipkan
                    }
                }
            }
        }

        return $menu;
    }

    private function get_profile()
    {
        /**
         * Mendapatkan profile dari session
         *
         */
        return (object) $this->session->userdata('profile');
    }

    private function get_lam()
    {
        /**
         * Mendapatkan list_of_access_modifier (lam) dari session
         *
         */
        return (object) $this->session->userdata('lam');
    }

    protected function multiple_insert($data = [])
    {
        if ($data['main']) {
            $this->db->trans_begin();
            $fk = [];
            foreach ($data['main'] as $k => $v) {
                if ($k == 0) { // Jika insert ke model 0
                    $save = $v['model']->save($v['data']);
                    if ($save['status'] != '200') {
                        $this->db->trans_rollback();
                        $this->session->set_flashdata('create_info_message', 'Data gagal disimpan');
                        $this->create($this->input->post());
                        break;
                    } else {
                        foreach ($v['key_available'] as $k1 => $v1) {
                            if ($v1 == 'id') {
                                $fk[$k][] = $this->db->insert_id();
                            } else {
                                $fk[$k][] = $v['data'][$v1];
                            }
                        }
                    }

                } else { // Insert ke model selanjutnya
                    $temp = $v['data'];
                    if ($v['foreign_key']) {
                        $temp[$v['foreign_key']['field']] = $fk[$v['foreign_key']['value'][0]][$v['foreign_key']['value'][1]];
                    }

                    $save = $v['model']->save($temp);

                    if ($save['status'] != '200') {
                        $this->db->trans_rollback();
                        $this->session->set_flashdata('create_info_message', 'Data gagal disimpan');
                        $this->create($this->input->post());
                        break;
                    } else {
                        foreach ($v['key_available'] as $k1 => $v1) {
                            if ($v1 == 'id') {
                                $fk[$k][] = $this->db->insert_id();
                            } else {
                                $fk[$k][] = $v['data'][$v1];
                            }
                        }

                        if ($k == count($data['main']) - 1) {
                            $this->db->trans_commit();
                            $this->session->set_flashdata('message', 'Data berhasil disimpan');
                            redirect($data['back_to_home']);
                        }
                    }
                }
            }
        } else {
            return false;
        }
    }

}
