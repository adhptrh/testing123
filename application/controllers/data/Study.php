<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Study extends MY_Controller {

	/**
   * Peringatan ! selain fungsi index, create, save, edit, update, delete, dan restore
	 * semua function HARUS protected-function
   *
   */

	public function __construct()
  {
    parent::__construct();
		$this->controller_id = 14;
		$this->load->model('Study_m', 'data');
  }

	/**
	 * Mendapatkan profile dari session
	 *
	 */

	public function index()
	{
		$this->filter(2);

			$this->header = [
				'title' => 'Mata Ujian',
				'sub_title' => 'Pengaturan Mata Ujian',
				'nav_active' => 'data/study',
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
						'label' => 'Mata Ujian',
						'icon' => '',
						'href' => '#',
					],
				],
			];

			$this->temp('data/study/content', [
				'data' => $this->data->find(),
			]);
	}

	public function create($old = [])
	{
		$this->filter(1);

		$this->header = [
			'title' => 'Mata Ujian',
			'sub_title' => 'Tambah Mata Ujian',
			'nav_active' => 'data/study',
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
					'label' => 'Mata Ujian',
					'icon' => 'fa-list',
					'href' => base_url('data/study'),
				],
				[
					'label' => 'Tambah',
					'icon' => '',
					'href' => '#',
				],
			],
		];

		$this->temp('data/study/create', [
			'old' => $old,
		]);
	}

	public function save()
	{
		$this->filter(1);

		// Cek Mata Ujian apakah sudah ada
		$data = $this->data->find(0, ['a.name' => $this->input->post('name')], true);

		if($data){
		  if($data[0]['is_del'] == '1'){
				$link = '<a href="'.base_url('data/study/restore/' . $data[0]['id']).'" class="btn btn-sm btn-primary">Ya, kembalikan data ini</a>';
				$this->session->set_flashdata('create_info_message', 'Mata Ujian ini sebelumnya sudah digunakan, namun sudah dihapus pada '.$data[0]['updated_at'].' oleh '.$data[0]['updated_by'].', apakah Anda ingin memulihkan data ini?' . $link);
		  }else{
		  	$this->session->set_flashdata('create_info_message', 'Mohon gunakan Mata Ujian lain, karena Mata Ujian ini sudah terdaftar');
		  }
			$this->create($this->input->post());
		}else{
			$save = [
				'name' => $this->input->post('name'),
			];

			$save = $this->data->save($save);
			if($save['status'] == '200'){
				$this->session->set_flashdata('message', $save['message']);
				redirect(base_url('data/study'));
			}else{
				$this->create($this->input->post());
			}
		}
	}

	public function edit($id, $old = [])
	{
		$this->filter(3);

		$this->header = [
			'title' => 'Mata Ujian',
			'sub_title' => 'Ubah Mata Ujian',
			'nav_active' => 'data/study',
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
					'label' => 'Mata Ujian',
					'icon' => 'fa-list',
					'href' => base_url('data/study'),
				],
				[
					'label' => 'Edit',
					'icon' => '',
					'href' => '#',
				],
			],
		];

		$this->temp('data/study/edit', [
			'data' => $this->data->find(enc($id, 1)),
			'old' => $old,
		]);
	}

	public function update()
	{
		$this->filter(3);

		// Cek Mata Ujian apakah sudah ada
		$cek = $this->data->find(0, ['a.name' => $this->input->post('name')], true);

		if($cek && enc($cek[0]['id'], 1) != enc($this->input->post('id'), 1)){
			if($cek[0]['is_del'] == '1'){

				$link = '<a href="'.base_url('data/study/restore/' . $cek[0]['id']).'" class="btn btn-sm btn-primary">Ya, kembalikan data ini</a>';
				$this->session->set_flashdata('update_info_message', 'Mata Ujian ini sebelumnya sudah digunakan, namun sudah dihapus pada '.$cek[0]['updated_at'].' oleh '.$cek[0]['updated_by'].', apakah Anda ingin memulihkan data ini? ' . $link);
			}else{
				$this->session->set_flashdata('update_info_message', 'Mata Ujian sudah terdaftar');
			}

			$this->edit($this->input->post('id'), $this->input->post());
		}else{

			$save = [
				'id' => enc($this->input->post('id'), 1),
				'name' => $this->input->post('name'),
			];

			$update = $this->data->save($save);
			if($update['status'] == '200'){
				$this->session->set_flashdata('message', $update['message']);
				redirect(base_url('data/study'));
			}else{
				$this->edit($this->input->post('id'), $this->input->post());
			}
		}
	}

	public function delete($id)
	{
		$this->filter(4);
		$delete = $this->data->delete($id);

		$this->session->set_flashdata('message', $delete['message']);
		redirect(base_url('data/study'));
	}

	public function restore($id)
	{
		$this->filter(4);
		$delete = $this->data->restore($id);

		$this->session->set_flashdata('message', $delete['message']);
		redirect(base_url('data/study'));
	}
}
