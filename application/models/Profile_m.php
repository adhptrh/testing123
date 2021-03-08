<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile_m extends MY_Model {

  public function __construct()
  {
    parent::__construct();
    $this->name = 'z_profiles';
    $this->alias = 'profil';

    $this->rules = [
      [
        'field' => 'name',
        'label' => 'Nama Profil',
        'rules' => 'required',
      ],
    ];
  }

  public function my_profile(){
    return $this->find($this->get_profile_id());
  }

  public function find($id = false, $conditions = false, $show_del = false, $selected_id = 0)
  {
    $this->db->select('a.id, a.name, a.is_del')
    ->select('b.name created_by, DATE_FORMAT(a.created_at, "%d-%m-%Y") created_at')
    ->select('c.name updated_by, DATE_FORMAT(a.updated_at, "%d-%m-%Y") updated_at')
    ->select('d.id user_id')
    ->from($this->name . ' a')
    ->join('z_profiles b', 'b.id = a.created_by', 'left')
    ->join('z_profiles c', 'c.id = a.updated_by', 'left')
    ->join('z_users d', 'd.profile_id = a.id', 'left');

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
        $data[$k]['user_id'] = enc($v['user_id']);
      }

      return $data;
    }
  }

}
