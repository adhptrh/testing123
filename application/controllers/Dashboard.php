<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

	/**
   * Peringatan ! selain fungsi index, create, save, edit, update, dan delete
	 * semua function HARUS protected-function
   *
   */

	public function __construct()
  {
    parent::__construct();
		$this->controller_id = 8;
		// $this->load->model('model_name', 'model');
  }

	/**
	 * Mendapatkan profile dari session
	 *
	 */

	public function index()
	{
		$this->filter(2);

			$this->header = [
				'title' => 'Dashboard',
				'sub_title' => 'Simpulan Informasi',
				'nav_active' => 'dashboard',
				'breadcrumb' => [
					[
						'label' => 'XPanel',
						'icon' => 'fa-home',
						'href' => '#',
					],
					[
						'label' => 'Beranda',
						'icon' => '',
						'href' => '#',
					],
				],
			];

			$this->temp('dashboard/content');
	}

	public function create()
	{
		$this->filter(1);
	}

	public function save()
	{
		$this->filter(1);
	}

	public function edit()
	{
		$this->filter(3);
	}

	public function update()
	{
		$this->filter(3);
	}

	public function delete()
	{
		$this->filter(4);
	}
}
