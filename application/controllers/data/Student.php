<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Reader\Xls;

class Student extends MY_Controller
{

    /**
     * Peringatan ! selain fungsi index, create, save, edit, update, delete, dan restore
     * semua function HARUS protected-function
     *
     */

    protected $fail = 0, $success = 0;

    public function __construct()
    {
        parent::__construct();
        $this->controller_id = 12;
        $this->load->model('Student_m', 'data');
        $this->load->model('Profile_m', 'profile');
        $this->load->model('User_m', 'user');
    }

    public function index()
    {
        $this->filter(2);

        $this->header = [
            'title' => 'Siswa',
            'sub_title' => 'Pengaturan Siswa',
            'nav_active' => 'data/student',
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
                    'label' => 'Siswa',
                    'icon' => '',
                    'href' => '#',
                ],
            ],
        ];

        $this->temp('data/student/content', [
            'data' => $this->data->find(),
        ]);
    }

    public function create_excel()
    {
        $this->filter(1);

        $this->header = [
            'title' => 'Siswa',
            'sub_title' => 'Tambah Siswa',
            'sub_title' => 'Tambah Siswa',
            'nav_active' => 'data/student',
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
                    'label' => 'Siswa',
                    'icon' => 'fa-list',
                    'href' => base_url('data/student'),
                ],
                [
                    'label' => 'Import Excel',
                    'icon' => '',
                    'href' => '#',
                ],
            ],
        ];

        $this->temp('data/student/create_excel');
    }

    public function create($old = [])
    {
        $this->filter(1);

        $this->header = [
            'title' => 'Siswa',
            'sub_title' => 'Tambah Siswa',
            'sub_title' => 'Tambah Siswa',
            'nav_active' => 'data/student',
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
                    'label' => 'Siswa',
                    'icon' => 'fa-list',
                    'href' => base_url('data/student'),
                ],
                [
                    'label' => 'Tambah ',
                    'icon' => '',
                    'href' => '#',
                ],
            ],
        ];

        $this->temp('data/student/create', [
            'old' => $old,
        ]);
    }

    private function generate_random_string($length = 4)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function save_excel()
    {
        $fileName = $_FILES['file']['name'];

        $path = './upload/files/';
        $config['upload_path'] = $path; //path upload
        $config['file_name'] = $fileName; // nama file
        $config['allowed_types'] = 'xls|xlsx'; //tipe file yang diperbolehkan
        $config['max_size'] = 10000; // maksimal sizze

        $this->load->library('upload'); //meload librari upload
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('file')) {
            $this->session->set_flashdata('create_info_message', $this->upload->display_errors());
            $this->create_excel();
        } else {
            $inputFileName = $path . $fileName;

            $reader = new Xls;
            $spreadsheet = $reader->load($inputFileName);
            $sheetData = $spreadsheet->getActiveSheet()->toArray();

            $this->save_excel_process($sheetData);
            $this->session->set_flashdata('message', 'Data berhasil disimpan : ' . $this->success . ', gagal disimpan = ' . $this->fail);
            redirect(base_url('data/student'));
        }
    }

    public function save_excel_process($data)
    {
        foreach ($data as $k => $v) {
            $nisn = $v[1];
            $nama = $v[2];

            if ($k != 0) {
                $cek = $this->data->find(0, ['a.nisn' => $nisn], true);

                if ($cek) { // Data sudah ada
                    $this->fail++;
                } else {
                    $this->db->trans_begin();
                    // Input profile
                    $save = $this->profile->save([
                        'name' => $nama,
                    ]);

                    if ($save['status'] != '200') {
                        $this->db->trans_rollback();
                        $this->fail++;
                    } else {
                        $profile_id = $this->db->insert_id();

                        // Input username
                        $password = $this->generate_random_string();
                        $save = $this->user->save([
                            'profile_id' => $profile_id,
                            'username' => $nisn,
                            'password' => password_hash($password, PASSWORD_DEFAULT),
                            'pass_siswa' => $password,
                        ]);

                        if ($save['status'] != '200') {
                            $this->fail++;
                        } else {
                            // Input siswa
                            $save = $this->data->save([
                                'profile_id' => $profile_id,
                                'nisn' => $nisn,
                            ]);

                            if ($save['status'] != '200') {
                                $this->fail++;
                            } else {
                                $this->db->trans_commit();
                                $this->success++;
                            }
                        }
                    }
                }
            }
        }
    }

    public function save()
    {
        $this->filter(1);

        // Cek Siswa apakah sudah ada
        $data = $this->data->find(0, ['a.nisn' => $this->input->post('nisn')], true);

        if ($data) {
            if ($data[0]['is_del'] == '1') {
                $link = '<a href="' . base_url('data/student/restore/' . $data[0]['id']) . '" class="btn btn-sm btn-primary">Ya, kembalikan data ini</a>';
                $this->session->set_flashdata('create_info_message', 'Siswa ini sebelumnya sudah digunakan, namun sudah dihapus pada ' . $data[0]['updated_at'] . ' oleh ' . $data[0]['updated_by'] . ', apakah Anda ingin memulihkan data ini?' . $link);
            } else {
                $this->session->set_flashdata('create_info_message', 'Mohon gunakan Siswa lain, karena Siswa ini sudah terdaftar');
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

                // Input username
                $password = $this->generate_random_string();
                $save = $this->user->save([
                    'profile_id' => $profile_id,
                    'username' => $this->input->post('nisn'),
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'pass_siswa' => $password,
                ]);

                if ($save['status'] != '200') {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('create_info_message', $save['message']);
                    $this->create($this->input->post());
                } else {
                    // Input siswa
                    $save = $this->data->save([
                        'profile_id' => $profile_id,
                        'nisn' => $this->input->post('nisn'),
                    ]);

                    if ($save['status'] != '200') {
                        $this->db->trans_rollback();
                        $this->session->set_flashdata('create_info_message', $save['message']);
                        $this->create($this->input->post());
                    } else {
                        $this->db->trans_commit();
                        $this->session->set_flashdata('message', $save['message']);
                        redirect(base_url('data/student'));
                    }
                }
            }
        }
    }

    public function edit($id, $old = [])
    {
        $this->filter(3);

        $this->header = [
            'title' => 'Siswa',
            'sub_title' => 'Ubah Siswa',
            'nav_active' => 'data/student',
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
                    'label' => 'Siswa',
                    'icon' => 'fa-list',
                    'href' => base_url('data/student'),
                ],
                [
                    'label' => 'Edit',
                    'icon' => '',
                    'href' => '#',
                ],
            ],
        ];

        $data = $this->data->find(enc($id, 1));

        $this->temp('data/student/edit', [
            'data' => $data = $this->data->find(enc($id, 1)),
            'old' => $old,
        ]);
    }

    public function update()
    {
        $this->filter(3);

        $id = enc($this->input->post('id'), 1);

        // Cek Siswa apakah sudah ada
        $cek = $this->data->find(0, ['a.nisn' => $this->input->post('nisn')], true);

        if ($cek && enc($cek[0]['id'], 1) != $id) {
            if ($cek[0]['is_del'] == '1') {

                $link = '<a href="' . base_url('data/student/restore/' . $cek[0]['id']) . '" class="btn btn-sm btn-primary">Ya, kembalikan data ini</a>';
                $this->session->set_flashdata('update_info_message', 'Nomor peserta ini sebelumnya sudah digunakan, namun sudah dihapus pada ' . $cek[0]['updated_at'] . ' oleh ' . $cek[0]['updated_by'] . ', apakah Anda ingin memulihkan data ini? ' . $link);
            } else {
                $this->session->set_flashdata('update_info_message', 'Nomor peserta sudah terdaftar');
            }

            $this->edit($this->input->post('id'), $this->input->post());
        } else {

            $this->db->trans_begin();

            // Update profile
            $profile = $this->data->find($id);
            $profile_id = enc($profile['profile_id'], 1);

            $save = $this->profile->save([
                'id' => $profile_id,
                'name' => $this->input->post('name'),
            ]);

            if ($save['status'] != '200') {
                $this->db->trans_rollback();
                $this->session->set_flashdata('update_info_message', $save['message']);
                $this->edit($this->input->post('id'), $this->input->post());
            } else {

                // Update username------------------
                $save = $this->user->save([
                    'id' => enc($profile['user_id'], 1),
                    'profile_id' => $profile_id,
                    'username' => $this->input->post('nisn'),
                ]);

                if ($save['status'] != '200') {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('create_info_message', $save['message']);
                    $this->create($this->input->post());
                } else {
                    $save = $this->data->save([
                        'id' => $id,
                        'nisn' => $this->input->post('nisn'),
                        'profile_id' => $profile_id,
                    ]);

                    if ($save['status'] != '200') {
                        $this->db->trans_rollback();
                        $this->session->set_flashdata('update_info_message', $save['message']);
                        $this->edit($this->input->post('id'), $this->input->post());
                    } else {

                        $this->db->trans_commit();
                        $this->session->set_flashdata('message', $save['message']);
                        redirect(base_url('data/student'));
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
        redirect(base_url('data/student'));
    }

    public function restore($id)
    {
        $this->filter(4);
        $delete = $this->data->restore($id);

        $this->session->set_flashdata('message', $delete['message']);
        redirect(base_url('data/student'));
    }
}
