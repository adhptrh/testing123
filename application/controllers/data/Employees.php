<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employees extends MY_Controller {

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
			'level' => $this->level->find(),
			'old' => $old,
		]);
	}

	public function save()
	{
		$this->filter(1);

		// Cek username apakah sudah ada
		$data = $this->data->find(0, ['f.username' => $this->input->post('username')], true);

		if($data){
		  if($data[0]['is_del'] == '1'){
				$link = '<a href="'.base_url('data/employees/restore/' . $data[0]['id']).'" class="btn btn-sm btn-primary">Ya, kembalikan data ini</a>';
				$this->session->set_flashdata('create_info_message', 'Username ini sebelumnya sudah digunakan, namun sudah dihapus pada '.$data[0]['updated_at'].' oleh '.$data[0]['updated_by'].', apakah Anda ingin memulihkan data ini?' . $link);
		  }else{
		  	$this->session->set_flashdata('create_info_message', 'Mohon gunakan Username lain, karena Username ini sudah terdaftar');
		  }
			$this->create($this->input->post());
		}else{

			$data = [
				'main' => [
					[
						'model' => $this->profile,
						'data' => [
							'name' => $this->input->post('name'),
						],
						'key_available' => ['id'],
					],
					[
						'model' => $this->user,
						'data' => [
							'username' => $this->input->post('username'),
							'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
						],
						'key_available' => [],
						'foreign_key' => [
							'field' => 'profile_id',
							'value' => [0,0],
						],
					],
					[
						'model' => $this->data,
						'data' => [
							'level_id' => enc($this->input->post('level'), 1),
						],
						'key_available' => [],
						'foreign_key' => [
							'field' => 'profile_id',
							'value' => [0,0],
						],
					],
				],
				'back_to_home' => base_url('data/employees'),
			];

			$this->multiple_insert($data);
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

		$this->temp('data/employees/edit', [
			'data' => $data = $this->data->find(enc($id, 1)),
			'level' => $this->level->find(false, false, false, enc($data['level_id'], 1)),
			'old' => $old,
		]);
	}

	public function update()
	{
		$this->filter(3);

		$id = enc($this->input->post('id'), 1);

		// Mendapatkan data profile
		$profile = $this->data->find($id);

		// Cek Username apakah sudah ada
		$cek = $this->user->find(0, ['a.username' => enc($profile['user_id'], 1)], true);

		if($cek && enc($cek[0]['id'], 1) != enc($this->input->post('id'), 1)){
			if($cek[0]['is_del'] == '1'){

				$link = '<a href="'.base_url('data/employees/restore/' . $cek[0]['id']).'" class="btn btn-sm btn-primary">Ya, kembalikan data ini</a>';
				$this->session->set_flashdata('update_info_message', 'Username ini sebelumnya sudah digunakan, namun sudah dihapus pada '.$cek[0]['updated_at'].' oleh '.$cek[0]['updated_by'].', apakah Anda ingin memulihkan data ini? ' . $link);
			}else{
				$this->session->set_flashdata('update_info_message', 'Username sudah terdaftar');
			}

			$this->edit($this->input->post('id'), $this->input->post());
		}else{

			$data = [
				'main' => [
					[
						'model' => $this->profile,
						'data' => [
							'id' => $id,
							'name' => $this->input->post('name'),
						],
						'key_available' => ['id'],
					],
					[
						'model' => $this->user,
						'data' => [
							'id' => enc($profile['user_id'], 1),
							'username' => $this->input->post('username'),
						],
						'key_available' => [],
						'foreign_key' => [],
					],
					[
						'model' => $this->data,
						'data' => [
							'level_id' => enc($this->input->post('level'), 1),
							'profile_id' => $id,
							'id' => enc($profile['hr_id'], 1),
						],
						'key_available' => [],
						'foreign_key' => [],
					],
				],
				'back_to_home' => base_url('data/employees'),
			];

			$this->multiple_insert($data);
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
