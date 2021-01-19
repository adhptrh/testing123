<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Period extends MY_Controller {

	/**
   * Peringatan ! selain fungsi index, create, save, edit, update, delete, dan restore
	 * semua function HARUS protected-function
   *
   */

	public function __construct()
  {
    parent::__construct();
		$this->controller_id = 16;
		$this->load->model('Period_m', 'data');
  }

	/**
	 * Mendapatkan profile dari session
	 *
	 */

	public function index()
	{
		$this->filter(2);

			$this->header = [
				'title' => 'Periode',
				'sub_title' => 'Pengaturan Periode',
				'nav_active' => 'setting/period',
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
						'label' => 'Periode',
						'icon' => '',
						'href' => '#',
					],
				],
			];

			$this->temp('setting/period/content', [
				'data' => $this->data->find(),
			]);
	}

	public function create($old = [])
	{
		$this->filter(1);

		$this->header = [
			'title' => 'Periode',
			'sub_title' => 'Tambah Periode',
			'nav_active' => 'setting/period',
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
					'label' => 'Periode',
					'icon' => 'fa-list',
					'href' => base_url('setting/period'),
				],
				[
					'label' => 'Tambah',
					'icon' => '',
					'href' => '#',
				],
			],
		];

		$this->temp('setting/period/create', [
			'old' => $old,
		]);
	}

	public function save()
	{
		$this->filter(1);

		// Cek Periode apakah sudah ada
		$data = $this->data->find(0, ['a.name' => $this->input->post('name')], true);

		if($data){
		  if($data[0]['is_del'] == '1'){
				$link = '<a href="'.base_url('setting/period/restore/' . $data[0]['id']).'" class="btn btn-sm btn-primary">Ya, kembalikan data ini</a>';
				$this->session->set_flashdata('create_info_message', 'Periode ini sebelumnya sudah digunakan, namun sudah dihapus pada '.$data[0]['updated_at'].' oleh '.$data[0]['updated_by'].', apakah Anda ingin memulihkan data ini?' . $link);
		  }else{
		  	$this->session->set_flashdata('create_info_message', 'Mohon gunakan Periode lain, karena Periode ini sudah terdaftar');
		  }
			$this->create($this->input->post());
		}else{
			$save = [
				'name' => $this->input->post('name'),
			];

			$save = $this->data->save($save);
			if($save['status'] == '200'){
				$this->session->set_flashdata('message', $save['message']);
				redirect(base_url('setting/period'));
			}else{
				$this->create($this->input->post());
			}
		}
	}

	public function edit($id, $old = [])
	{
		$this->filter(3);

		$this->header = [
			'title' => 'Periode',
			'sub_title' => 'Ubah Periode',
			'nav_active' => 'setting/period',
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
					'label' => 'Periode',
					'icon' => 'fa-list',
					'href' => base_url('setting/period'),
				],
				[
					'label' => 'Edit',
					'icon' => '',
					'href' => '#',
				],
			],
		];

		$this->temp('setting/period/edit', [
			'data' => $this->data->find(enc($id, 1)),
			'old' => $old,
		]);
	}

	public function update()
	{
		$this->filter(3);

		// Cek Periode apakah sudah ada
		$cek = $this->data->find(0, ['a.name' => $this->input->post('name')], true);

		if($cek && enc($cek[0]['id'], 1) != enc($this->input->post('id'), 1)){
			if($cek[0]['is_del'] == '1'){

				$link = '<a href="'.base_url('setting/period/restore/' . $cek[0]['id']).'" class="btn btn-sm btn-primary">Ya, kembalikan data ini</a>';
				$this->session->set_flashdata('update_info_message', 'Periode ini sebelumnya sudah digunakan, namun sudah dihapus pada '.$cek[0]['updated_at'].' oleh '.$cek[0]['updated_by'].', apakah Anda ingin memulihkan data ini? ' . $link);
			}else{
				$this->session->set_flashdata('update_info_message', 'Periode sudah terdaftar');
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
				redirect(base_url('setting/period'));
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
		redirect(base_url('setting/period'));
	}

	public function restore($id)
	{
		$this->filter(4);
		$delete = $this->data->restore($id);

		$this->session->set_flashdata('message', $delete['message']);
		redirect(base_url('setting/period'));
	}
}
