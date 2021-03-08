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
            'data' => [],
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

    public function update()
    {
        $this->filter(3);
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
