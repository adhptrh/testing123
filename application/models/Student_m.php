<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Student_m extends MY_Model {

  public function __construct()
  {
    parent::__construct();
    $this->name = 'students';
    $this->alias = 'Siswa';

    $this->rules = [
      [
        'field' => 'major_id',
        'label' => 'Jurusan',
        'rules' => 'required',
      ],
      [
        'field' => 'profile_id',
        'label' => 'Profile',
        'rules' => 'required',
      ],
      [
        'field' => 'nopes',
        'label' => 'Nomor Peserta',
        'rules' => 'required',
      ],
    ];
  }

  public function find($id = false, $conditions = false, $show_del = false, $selected_id = 0)
  {
    $this->db->select('a.id, a.profile_id, a.nopes, a.is_del')
    ->select('d.name')
    ->select('e.id major_id, e.name prodi')
    ->select('b.name created_by, DATE_FORMAT(a.created_at, "%d-%m-%Y") created_at')
    ->select('c.name updated_by, DATE_FORMAT(a.updated_at, "%d-%m-%Y") updated_at')
    ->from($this->name . ' a')
    ->join('z_profiles b', 'b.id = a.created_by', 'left')
    ->join('z_profiles c', 'c.id = a.updated_by', 'left')
    ->join('z_profiles d', 'd.id = a.profile_id', 'left')
    ->join('majors e', 'e.id = a.major_id', 'left')
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
      $data['major_id'] = enc($data['major_id']);
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
        $data[$k]['major_id'] = enc($v['major_id']);
        $data[$k]['profile_id'] = enc($v['profile_id']);
      }

      return $data;
    }
  }

}
