<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Student extends MY_Controller
{

    /**
     * Peringatan ! selain fungsi index, create, save, edit, update, delete, dan restore
     * semua function HARUS protected-function
     *
     */

    public function __construct()
    {
        parent::__construct();
        $this->controller_id = 12;
        $this->load->model('Student_m', 'data');
        $this->load->model('Student_grade_m', 'student_grade');
        $this->load->model('Grade_period_m', 'grade');
        $this->load->model('Profile_m', 'profile');
        $this->load->model('Period_m', 'period');
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

    public function create($old = [])
    {
        $this->filter(1);

        $this->header = [
            'title' => 'Siswa',
            'js_file' => 'data/student',
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
                    'label' => 'Tambah',
                    'icon' => '',
                    'href' => '#',
                ],
            ],
        ];

        $this->temp('data/student/create', [
            'period' => $this->period->find(),
            'old' => $old,
        ]);
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

                // Input siswa
                $save = $this->data->save([
                    'profile_id' => $profile_id,
                    'nisn' => $this->input->post('nisn'),
                ]);

                $student_id = $this->db->insert_id();

                if ($save['status'] != '200') {
                    $this->db->trans_rollback();
                    $this->session->set_flashdata('create_info_message', $save['message']);
                    $this->create($this->input->post());
                } else {
                    // Input student_grade
                    $save = $this->student_grade->save([
                        'student_id' => $student_id,
                        'grade_period_id' => enc($this->input->post('grade'), 1),
                    ]);
                    if ($save['status'] == '200') {
                        $this->db->trans_commit();
                        $this->session->set_flashdata('message', $save['message']);
                        redirect(base_url('data/student'));
                    } else {
                        $this->create($this->input->post());
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
            'js_file' => 'data/student',
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
            'grade' => $this->grade->find(false, false, false, enc($data['grade_period_id'], 1)),
            'period' => $this->period->find(false, false, false, enc($data['period_id'], 1)),
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

            // Input profile
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

                // Input siswa
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
                    // delete student_grade old
                    $this->student_grade->delete_where([
                        'student_id' => $id,
                    ]);

                    // Input student_grade
                    $save = $this->student_grade->save([
                        'student_id' => $id,
                        'grade_period_id' => enc($this->input->post('grade'), 1),
                    ]);

                    if ($save['status'] == '200') {
                        $this->db->trans_commit();
                        $this->session->set_flashdata('message', $save['message']);
                        redirect(base_url('data/student'));
                    } else {
                        $this->create($this->input->post());
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
