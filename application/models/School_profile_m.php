<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class School_profile_m extends MY_Model {

  public function __construct()
  {
    parent::__construct();
    $this->name = 'profile';
    $this->alias = 'profil sekolah';

    $this->rules = [
      [
        'field' => 'name',
        'label' => 'Nama Sekolah',
        'rules' => 'required',
      ],
    ];
  }

  public function find($id = false, $conditions = false, $show_del = false, $selected_id = 0)
  {
    $this->db->select('a.id, a.name, a.long_name, a.address, a.is_del, a.headmaster headmaster_id')
    ->select('b.name created_by, DATE_FORMAT(a.created_at, "%d-%m-%Y") created_at')
    ->select('c.name updated_by, DATE_FORMAT(a.updated_at, "%d-%m-%Y") updated_at')
    ->select('e.name headmaster, e.nip')
    ->from($this->name . ' a')
    ->join('z_profiles b', 'b.id = a.created_by', 'left')
    ->join('z_profiles c', 'c.id = a.updated_by', 'left')
    ->join('z_human_resources d', 'd.id = a.headmaster', 'left')
    ->join('z_profiles e', 'e.id = d.profile_id', 'left');

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
      $data['headmaster_id'] = enc($data['headmaster_id']);

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
        $data[$k]['headmaster_id'] = enc($v['headmaster_id']);
      }

      return $data;
    }
  }

  public function empty_data()
  {
    $data = [
      'name' => '-',
      'headmaster' => '-',
      'nip' => '-',
      'address' => '-',
    ];

    return $data;
  }

}
