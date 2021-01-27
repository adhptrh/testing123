<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Employees extends MY_Controller
{

    /**
     * Peringatan ! selain fungsi index, create, save, edit, update, delete, dan restore
     * semua function HARUS protected-function
     *
     */

    public function __construct()
    {
        parent::__construct();
        $this->controller_id = 7;
        $this->load->model('User_m', 'user');
        $this->load->model('Profile_m', 'profile');
        $this->load->model('Level_m', 'level');
        $this->load->model('Hr_m', 'data');
        $this->load->model('Study_m', 'study');
        $this->load->model('Hr_study_m', 'hr_study');
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
            'nav_active' => 'data/employees',
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
                    'label' => 'Profil',
                    'icon' => '',
                    'href' => '#',
                ],
            ],
        ];

        $this->temp('data/employees/content', [
            'data' => $this->data->find(),
        ]);
    }

    public function create($old = [])
    {
        $this->filter(1);

        $this->header = [
            'title' => 'Profil',
            'sub_title' => 'Tambah Profil',
            'nav_active' => 'data/employees',
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
                    'label' => 'Profil',
                    'icon' => 'fa-list',
                    'href' => base_url('data/employees'),
                ],
                [
                    'label' => 'Tambah',
                    'icon' => '',
                    'href' => '#',
                ],
            ],
        ];

        $this->temp('data/employees/create', [
            'study' => $this->study->find(),
            'level' => $this->level->find(),
            'old' => $old,
        ]);
    }

    public function save()
    {
        $this->filter(1);

        // Cek username apakah sudah ada
        $data = $this->data->find(0, ['f.username' => $this->input->post('username')], true);

        if ($data) {
            if ($data[0]['is_del'] == '1') {
                $link = '<a href="' . base_url('data/employees/restore/' . $data[0]['id']) . '" class="btn btn-sm btn-primary">Ya, kembalikan data ini</a>';
                $this->session->set_flashdata('create_info_message', 'Username ini sebelumnya sudah digunakan, namun sudah dihapus pada ' . $data[0]['updated_at'] . ' oleh ' . $data[0]['updated_by'] . ', apakah Anda ingin memulihkan data ini?' . $link);
            } else {
                $this->session->set_flashdata('create_info_message', 'Mohon gunakan Username lain, karena Username ini sudah terdaftar');
            }
            $this->create($this->input->post());
        } else {

            $this->db->trans_begin();

            // Input profile
            $save = $this->profile->save([
                'name' => $this->input->post('name'),
            ]);

            if ($save['status'] != '200') {

                $this->db->trans_rollback();
                $this->session->set_flashdata('create_info_message', $save['message']);
                $this->create($this->input->post());

            } else {
                $profile_id = $this->db->insert_id();

                // Input user dan password
                $save = $this->user->save([
                    'username' => $this->input->post('username'),
                    'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                    'profile_id' => $profile_id,
                ]);

                if ($save['status'] != '200') {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('create_info_message', $save['message']);
                    $this->create($this->input->post());
                } else {

                    // Input HR
                    $save = $this->data->save([
                        'level_id' => enc($this->input->post('level'), 1),
                        'profile_id' => $profile_id,
                    ]);

                    $hr_id = $this->db->insert_id();

                    if ($save['status'] != '200') {

                        $this->db->trans_rollback();
                        $this->session->set_flashdata('create_info_message', $save['message']);
                        $this->create($this->input->post());

                    } else {
                        if ($this->input->post('study')) { // Jika ada studies
                            // Input Studies
                            $studies = $this->input->post('study');

                            $data = [];
                            foreach ($studies as $k => $v) {
                                $data[] = [
                                    'study_id' => enc($v, 1),
                                    'hr_id' => $hr_id,
                                ];
                            }
                            $save = $this->hr_study->save_batch($data);

                            if ($save['status'] != '200') {
                                $this->db->trans_rollback();
                                $this->session->set_flashdata('create_info_message', $save['message']);
                                $this->create($this->input->post());

                            } else {

                                $this->db->trans_commit();
                                $this->session->set_flashdata('message', $save['message']);
                                redirect('data/employees');
                            }

                        } else { // Jika tidak ada study

                            $this->db->trans_commit();
                            $this->session->set_flashdata('message', $save['message']);
                            redirect('data/employees');
                        }
                    }
                }
            }
        }
    }

    public function edit($id, $old = [])
    {
        $this->filter(3);

        $this->header = [
            'title' => 'Profil',
            'sub_title' => 'Ubah Profil',
            'nav_active' => 'data/employees',
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
                    'label' => 'Profil',
                    'icon' => 'fa-list',
                    'href' => base_url('data/employees'),
                ],
                [
                    'label' => 'Edit',
                    'icon' => '',
                    'href' => '#',
                ],
            ],
        ];

        // Mengelola data prodi
        $id = enc($id, 1);
        $data = $this->data->find($id);
        $study_selected = $this->hr_study->find(false, [
            'a.hr_id' => $id,
        ]);

        $dstudy_selected = [];
        foreach ($study_selected as $k => $v) {
            $dstudy_selected[] = enc($v['study_id'], 1);
        }

        $this->temp('data/employees/edit', [
            'data' => $data,
            'level' => $this->level->find(false, false, false, enc($data['level_id'], 1)),
            'study' => $this->study->find(false, false, false, $dstudy_selected),
            'old' => $old,
        ]);
    }

    public function update()
    {
        $this->filter(3);

        $id = enc($this->input->post('id'), 1);

        // Mendapatkan data hr
        $data = $this->data->find($id);

        // Cek Username apakah sudah ada
        $cek = $this->user->find(0, ['a.username' => enc($data['user_id'], 1)], true);

        if ($cek && enc($cek[0]['id'], 1) != enc($this->input->post('id'), 1)) {
            if ($cek[0]['is_del'] == '1') {

                $link = '<a href="' . base_url('data/employees/restore/' . $cek[0]['id']) . '" class="btn btn-sm btn-primary">Ya, kembalikan data ini</a>';
                $this->session->set_flashdata('update_info_message', 'Username ini sebelumnya sudah digunakan, namun sudah dihapus pada ' . $cek[0]['updated_at'] . ' oleh ' . $cek[0]['updated_by'] . ', apakah Anda ingin memulihkan data ini? ' . $link);
            } else {
                $this->session->set_flashdata('update_info_message', 'Username sudah terdaftar');
            }

            $this->edit($this->input->post('id'), $this->input->post());
        } else {

            $this->db->trans_begin();

            // Update profile
            $save = $this->profile->save([
                'id' => enc($data['profile_id'], 1),
                'name' => $this->input->post('name'),
            ]);

            if ($save['status'] != '200') {

                $this->db->trans_rollback();
                $this->session->set_flashdata('create_info_message', $save['message']);
                $this->create($this->input->post());

            } else {
                $profile_id = $this->db->insert_id();

                // Update user dan password
                $save = $this->user->save([
                    'username' => $this->input->post('username'),
                    'id' => enc($data['user_id'], 1),
                ]);

                if ($save['status'] != '200') {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('create_info_message', $save['message']);
                    $this->create($this->input->post());
                } else {

                    // Update hr
                    $save = $this->data->save([
                        'level_id' => enc($this->input->post('level'), 1),
                        'profile_id' => enc($data['profile_id'], 1),
                        'id' => $id,
                    ]);

                    if ($save['status'] != '200') {

                        $this->db->trans_rollback();
                        $this->session->set_flashdata('create_info_message', $save['message']);
                        $this->create($this->input->post());

                    } else {
                        if ($this->input->post('study')) { // Jika ada studies
                            // Update Studies
                            $studies = $this->input->post('study');

                            $data = [];
                            foreach ($studies as $k => $v) {
                                $data[] = [
                                    'study_id' => enc($v, 1),
                                    'hr_id' => $id,
                                ];
                            }

                            $delete = $this->hr_study->delete_where([
                                'hr_id' => $id,
                            ]);

                            $save = $this->hr_study->save_batch($data);

                            if ($save['status'] != '200') {
                                $this->db->trans_rollback();
                                $this->session->set_flashdata('create_info_message', $save['message']);
                                $this->create($this->input->post());

                            } else {

                                $this->db->trans_commit();
                                $this->session->set_flashdata('message', $save['message']);
                                redirect('data/employees');
                            }

                        } else { // Jika tidak ada study

                            $this->db->trans_commit();
                            $this->session->set_flashdata('message', $save['message']);
                            redirect('data/employees');
                        }
                    }
                }
            }
        }
    }

    public function delete($id)
    {
        $this->filter(4);
        $delete = $this->data->delete($id);

        $this->session->set_flashdata('message', $delete['message']);
        redirect(base_url('data/employees'));

    }

    public function restore($id)
    {
        $this->filter(4);
        $delete = $this->data->restore($id);

        $this->session->set_flashdata('message', $delete['message']);
        redirect(base_url('data/employees'));
    }
}