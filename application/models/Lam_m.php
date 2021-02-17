<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lam_m extends MY_Model {

  public function __construct()
  {
    parent::__construct();
    $this->name = 'z_access_modifiers';
    $this->alias = 'hak akses';

    $this->rules = [
      [
        'field' => 'level_id',
        'label' => 'Level',
        'rules' => 'required',
      ],
      [
        'field' => 'menu_id',
        'label' => 'Menu',
        'rules' => 'required',
      ],
    ];
  }

  public function find($id = false, $conditions = false, $show_del = false, $selected_id = 0)
  {
    $this->db->select('a.id, a.menu_id, a.level_id, a.xcreate, a.xread, a.xupdate, a.xdelete')
    ->select('b.name created_by, b.created_at')
    ->select('c.name updated_at, c.updated_at')
    ->from($this->name . ' a')
    ->join('z_profiles b', 'b.id = a.created_by', 'left')
    ->join('z_profiles c', 'c.id = a.updated_by', 'left')
    ->order_by('a.id', 'ASC');

    if(!$show_del){
      $this->db->where('a.is_del', '0');
    }

    $this->db->order_by('a.id', 'desc');

    // Jika cari berdasarkan id
    if($id){

      $this->db->where([
        'a.id' => $id
      ]);

      $data = $this->db->get()->row_array();
      $data['id'] = enc($data['id']);
      $data['level_id'] = enc($data['level_id']);
      $data['menu_id'] = enc($data['menu_id']);

      return $data;

    }else{ // Jika cari semua
      if($conditions){
        $this->db->where($conditions);
      }

      $this->db->order_by('a.id', 'desc');

      $data = $this->db->get()->result_array();

      foreach ($data as $k => $v) {
        if($selected_id == $v['id']){
          $data[$k]['selected'] = 'selected'; // Men-setting selected untuk select2
        }else{
          $data[$k]['selected'] = '';
        }

        $data[$k]['id'] = enc($v['id']);
        $data[$k]['level_id'] = enc($v['level_id']);
        $data[$k]['menu_id'] = enc($v['menu_id']);
      }

      return $data;
    }
  }

}
