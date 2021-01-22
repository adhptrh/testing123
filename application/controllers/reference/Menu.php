<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Menu extends MY_Controller
{

    /**
     * Peringatan ! selain fungsi index, create, save, edit, update, delete, dan restore
     * semua function HARUS protected-function
     *
     */

    public function __construct()
    {
        parent::__construct();
        $this->controller_id = 3;
        $this->load->model('Menu_m', 'data');
    }

    /**
     * Mendapatkan profile dari session
     *
     */

    public function index()
    {
        $this->filter(2);

        $this->header = [
            'title' => 'Menu',
            'sub_title' => 'Pengaturan Menu',
            'nav_active' => 'reference/menu',
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
                    'label' => 'Menu',
                    'icon' => '',
                    'href' => '#',
                ],
            ],
        ];

        $menu = [];
        $data = $this->data->find();

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

        $this->temp('reference/menu/content', [
            'data' => $menu,
        ]);
    }

    public function create($old = [])
    {
        $this->filter(1);

        $this->header = [
            'title' => 'Menu',
            'sub_title' => 'Tambah Menu',
            'nav_active' => 'reference/menu',
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
                    'label' => 'Menu',
                    'icon' => 'fa-list',
                    'href' => base_url('reference/menu'),
                ],
                [
                    'label' => 'Tambah',
                    'icon' => '',
                    'href' => '#',
                ],
            ],
        ];

        if (isset($old['parent'])) {
            $parent_selected = $this->data->find(0, ['parent' => 0], false, enc($old['parent'], 1));
        } else {
            $parent_selected = [];
        }

        $this->temp('reference/menu/create', [
            'data' => $this->data->find(0, ['parent' => 0]),
            'parent_selected' => $parent_selected,
            'old' => $old,
        ]);
    }

    public function save()
    {
        $this->filter(1);

        // Cek Prefix apakah sudah ada
        $data = $this->data->find(0, ['prefix' => $this->input->post('prefix')], true);

        if ($data) {
            if ($data[0]['is_del'] == '1') {
                $link = '<a href="' . base_url('reference/menu/restore/' . $data[0]['id']) . '" class="btn btn-sm btn-primary">Ya, kembalikan data ini</a>';
                $this->session->set_flashdata('create_info_message', 'Prefix ini sebelumnya sudah digunakan, namun sudah dihapus pada ' . $data[0]['updated_at'] . ' oleh ' . $data[0]['updated_by'] . ', apakah Anda ingin memulihkan data ini?' . $link);
            } else {
                $this->session->set_flashdata('create_info_message', 'Mohon gunakan Prefix lain, karena Prefix ini sudah terdaftar');
            }
            $this->create($this->input->post());
        } else {
            $save = [
                'parent' => enc($parent = $this->input->post('parent'), 1),
                'name' => $this->input->post('name'),
                'prefix' => $this->input->post('prefix'),
                'icon' => $this->input->post('icon'),
                'sort' => $this->input->post('sort'),
            ];

            $save = $this->data->save($save);
            if ($save['status'] == '200') {
                $this->session->set_flashdata('message', $save['message']);
                redirect(base_url('reference/menu'));
            } else {
                $this->create($this->input->post());
            }
        }
    }

    public function edit($id, $old = [])
    {
        $this->filter(3);

        $this->header = [
            'title' => 'Menu',
            'sub_title' => 'Ubah Menu',
            'nav_active' => 'reference/menu',
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
                    'label' => 'Menu',
                    'icon' => 'fa-list',
                    'href' => base_url('reference/menu'),
                ],
                [
                    'label' => 'Edit',
                    'icon' => '',
                    'href' => '#',
                ],
            ],
        ];

        $data = $this->data->find(enc($id, 1));
        $parent_selected = $this->data->find(0, ['parent' => 0], false, enc($data['parent'], 1));

        $this->temp('reference/menu/edit', [
            'data' => $this->data->find(enc($id, 1)),
            'parent_selected' => $parent_selected,
            'old' => $old,
        ]);
    }

    public function update()
    {
        $this->filter(3);

        // Cek Prefix apakah sudah ada
        $cek = $this->data->find(0, ['prefix' => $this->input->post('prefix')], true);

        if ($cek && enc($cek[0]['id'], 1) != enc($this->input->post('id'), 1)) {
            if ($cek[0]['is_del'] == '1') {

                $link = '<a href="' . base_url('reference/menu/restore/' . $cek[0]['id']) . '" class="btn btn-sm btn-primary">Ya, kembalikan data ini</a>';
                $this->session->set_flashdata('update_info_message', 'Prefix ini sebelumnya sudah digunakan, namun sudah dihapus pada ' . $cek[0]['updated_at'] . ' oleh ' . $cek[0]['updated_by'] . ', apakah Anda ingin memulihkan data ini? ' . $link);
            } else {
                $this->session->set_flashdata('update_info_message', 'Prefix sudah terdaftar');
            }

            $this->edit($this->input->post('id'), $this->input->post());
        } else {

            $save = [
                'id' => enc($this->input->post('id'), 1),
                'parent' => enc($parent = $this->input->post('parent'), 1),
                'name' => $this->input->post('name'),
                'prefix' => $this->input->post('prefix'),
                'icon' => $this->input->post('icon'),
                'sort' => $this->input->post('sort'),
            ];

            $udpate = $this->data->save($save);
            if ($udpate['status'] == '200') {
                $this->session->set_flashdata('message', $update['message']);
                redirect(base_url('reference/menu'));
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
        redirect(base_url('reference/menu'));
    }

    public function restore($id)
    {
        $this->filter(4);
        $delete = $this->data->restore($id);

        $this->session->set_flashdata('message', $delete['message']);
        redirect(base_url('reference/menu'));
    }
}
