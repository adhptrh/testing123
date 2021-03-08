<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_m extends MY_Model {

  public function __construct()
  {
    parent::__construct();
    $this->name = 'z_users';
    $this->alias = 'pengguna';

    $this->rules = [
      [
        'field' => 'username',
        'label' => 'Username',
        'rules' => 'required',
      ],
      // [
      //   'field' => 'password',
      //   'label' => 'Password',
      //   'rules' => 'required',
      // ],
    ];
  }

  public function find($id = false, $conditions = false, $show_del = false, $selected_id = 0)
  {
    $this->db->select('a.id, a.profile_id, a.username, a.password, a.is_repassword, a.is_del')
    ->select('b.name created_by, b.created_at')
    ->select('c.name updated_at, c.updated_at')
    ->select('d.name')
    ->from('z_users a')
    ->join('z_profiles b', 'b.id = a.created_by', 'left')
    ->join('z_profiles c', 'c.id = a.updated_by', 'left')
    ->join('z_profiles d', 'd.id = a.profile_id');

    if($show_del){
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
      $data['profile_id'] = enc($data['profile_id']);

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
        $data[$k]['profile_id'] = enc($v['profile_id']);
      }

      return $data;
    }
  }

}
