<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hr_m extends MY_Model {

  public function __construct()
  {
    parent::__construct();
    $this->name = 'z_human_resources';
    $this->alias = 'pegawai';

    $this->rules = [
      [
        'field' => 'level_id',
        'label' => 'Level',
        'rules' => 'required',
      ],
      [
        'field' => 'profile_id',
        'label' => 'Pengguna',
        'rules' => 'required',
      ],
    ];
  }

  public function find($id = false, $conditions = false, $show_del = false, $selected_id = 0)
  {
    $this->db->select('a.id, a.is_del, a.level_id, a.profile_id')
    ->select('b.name created_by, b.created_at')
    ->select('c.name updated_by, c.updated_at')
    ->select('d.name')
    ->select('e.name level_name')
    ->select('f.username, f.id user_id')
    ->from($this->name . ' a')
    ->join('z_profiles b', 'b.id = a.created_by', 'left')
    ->join('z_profiles c', 'c.id = a.updated_by', 'left')
    ->join('z_profiles d', 'd.id = a.profile_id', 'left')
    ->join('z_levels e', 'e.id = a.level_id', 'left')
    ->join('z_users f', 'f.profile_id = a.profile_id', 'left');

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
      $data['profile_id'] = enc($data['profile_id']);
      $data['user_id'] = enc($data['user_id']);

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
        $data[$k]['profile_id'] = enc($v['profile_id']);
        $data[$k]['user_id'] = enc($v['user_id']);
      }

      return $data;
    }
  }

}
