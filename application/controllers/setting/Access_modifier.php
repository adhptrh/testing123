<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Access_modifier extends MY_Controller {

	/**
   * Peringatan ! selain fungsi index, create, save, edit, update, delete, dan restore
	 * semua function HARUS protected-function
   *
   */

	public function __construct()
  {
    parent::__construct();
		$this->controller_id = 6;
		$this->load->model('Menu_m', 'menu');
		$this->load->model('Lam_m', 'data');
		$this->load->model('Level_m', 'level');
  }

	public function index()
	{
		$this->filter(2);

		redirect(base_url('setting/access_modifier/level/'));
	}

	public function level()
	{
		$this->filter(2);

			$this->header = [
				'title' => 'Hak Akses',
				'sub_title' => 'Pengaturan Hak Akses',
				'nav_active' => 'setting/access_modifier',
				'breadcrumb' => [
					[
						'label' => 'XPanel',
						'icon' => 'fa-home',
						'href' => '#',
					],
					[
						'label' => 'Pengaturan',
						'icon' => 'fa-gear',
						'href' => '#',
					],
					[
						'label' => 'Hak Akses',
						'icon' => '',
						'href' => '#',
					],
				],
			];

			$this->temp('setting/access_modifier/content', [
					'level' => $this->level->find(),
			]);
	}

	public function create($level = null)
	{
		$this->filter(1);

		$this->header = [
			'title' => 'Hak Akses',
			'sub_title' => 'Pengaturan Hak Akses',
			'nav_active' => 'setting/access_modifier',
			'breadcrumb' => [
				[
					'label' => 'XPanel',
					'icon' => 'fa-home',
					'href' => '#',
				],
				[
					'label' => 'Pengaturan',
					'icon' => 'fa-gear',
					'href' => '#',
				],
				[
					'label' => 'Hak Akses',
					'icon' => 'fa-list',
					'href' => base_url('setting/access_modifier'),
				],
				[
					'label' => 'Tambah',
					'icon' => '',
					'href' => '#',
				],
			],
		];

		if($level){
			$dlevel = $this->level->find(0, 0, 0, enc($level, 1));
		}else{
			$dlevel = $this->level->find();
		}

		// ============= Re-array menu =============
		$menu = $this->menu->find();
		$lam = $this->data->find(0, ['a.level_id' => enc($level, 1)]);
		$data = [];

		// Create menu level 1
		foreach ($menu as $k => $v) {
			if(enc($v['parent'], 1) == 0){
				$data[] = $v;
			}
		}

		// Create ticks
		foreach ($menu as $k => $v) {

			$menu[$k]['xcreate'] = 0;
			$menu[$k]['xread'] = 0;
			$menu[$k]['xupdate'] = 0;
			$menu[$k]['xdelete'] = 0;

			foreach ($lam as $k1 => $v1) {
				if(enc($v1['menu_id'], 1) == enc($v['id'], 1)){
					// update default-crud-tick
					$menu[$k]['xcreate'] = $v1['xcreate'];
					$menu[$k]['xread'] = $v1['xread'];
					$menu[$k]['xupdate'] = $v1['xupdate'];
					$menu[$k]['xdelete'] = $v1['xdelete'];
				}
			}
		}

		// Create menu level 2
		foreach ($menu as $k => $v) {
			if(enc($v['parent'], 1) != 0){
				// Menyisipkan sub_menu kepada menu parent
				foreach ($data as $k1 => $v1) {
					if(enc($v1['id'], 1) == enc($v['parent'], 1)){
						$data[$k1]['submenu'][] = $v; // Sisipkan
					}
				}
			}
		}

		$this->temp('setting/access_modifier/create', [
			'level' => $dlevel,
			'data' => $data,
		]);
	}

	public function save()
	{
		$this->filter(1);

		$data = [];
		$level = '';
		$raw = $this->input->post();

		// Mengolah input-post menjadi $list_of_menu_roleallow dan $list_of_menus
		$list_of_menu_roleallow = [];
		$list_of_menus = [];
		foreach ($raw as $k => $v) {
			if($k != 'level'){
				$r = explode("-",$k);
				$list_of_menu_roleallow[] = $r;
				$list_of_menus[] = $r[1];
			}
		}

		// Membuat daftar id (remove-duplicate)
		$list_of_menu = array_unique($list_of_menus);

		// Membuat data yang akan disave
		$save = [];
		foreach ($list_of_menu as $k => $v) {
			foreach ($list_of_menu_roleallow as $k1 => $v1) {
				if($v1[1] == $v){
					$save[$k]['level_id'] = enc($raw['level'], 1);
					$save[$k]['menu_id'] = enc($v, 1);
					$save[$k][$v1[0]] = 1;
				}
			}
		}

		$this->db->trans_begin();
			$this->data->delete_where([
				'level_id' => enc($raw['level'], 1)
			]);

			foreach ($save as $k => $v) {
				$simpan = $this->data->save($v);
			}

		if ($this->db->trans_status() === FALSE)
		{
      $this->db->trans_rollback();
			$this->session->set_flashdata('message', 'Data gagal disimpan');
		}
		else
		{
      $this->db->trans_commit();
			$this->session->set_flashdata('message', $simpan['message']);
		}

		redirect(base_url('setting/access_modifier'));
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
		$delete = $this->data->delete($id);

		$this->session->set_flashdata('message', $delete['message']);
		redirect(base_url('setting/access_modifier'));

	}

	public function restore($id)
	{
		$this->filter(4);
		$delete = $this->data->restore($id);

		$this->session->set_flashdata('message', $delete['message']);
		redirect(base_url('setting/access_modifier'));
	}
}
