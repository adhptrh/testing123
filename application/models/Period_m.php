<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Period_m extends MY_Model {

  public function __construct()
  {
    parent::__construct();
    $this->name = 'periods';
    $this->alias = 'Periode';

    $this->rules = [
      [
        'field' => 'name',
        'label' => 'Nama Periode',
        'rules' => 'required',
      ],
    ];
  }

  public function find($id = false, $conditions = false, $show_del = false, $selected_id = 0)
  {
    $this->db->select('a.id, a.name, a.is_del, a.status')
    ->select('b.name created_by, DATE_FORMAT(a.created_at, "%d-%m-%Y") created_at')
    ->select('c.name updated_by, DATE_FORMAT(a.updated_at, "%d-%m-%Y") updated_at')
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
      }

      return $data;
    }
  }

}
