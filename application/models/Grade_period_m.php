<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Grade_period_m extends MY_Model {

  public function __construct()
  {
    parent::__construct();
    $this->name = 'grade_extend_periods';
    $this->alias = 'Kelas';

    $this->rules = [
      [
        'field' => 'grade_id',
        'label' => 'Nama Kelas',
        'rules' => 'required',
      ],
      [
        'field' => 'period_id',
        'label' => 'Periode',
        'rules' => 'required',
      ],
    ];
  }

  public function find($id = false, $conditions = false, $show_del = false, $selected_id = 0)
  {
    $this->db->select('a.id, a.grade_id, a.period_id, a.is_del')
    ->select('b.name created_by, DATE_FORMAT(a.created_at, "%d-%m-%Y") created_at')
    ->select('c.name updated_by, DATE_FORMAT(a.updated_at, "%d-%m-%Y") updated_at')
    ->select('d.name periode')
    ->select('e.name kelas')
    ->select('f.name jurusan')
    ->from($this->name . ' a')
    ->join('z_profiles b', 'b.id = a.created_by', 'left')
    ->join('z_profiles c', 'c.id = a.updated_by', 'left')
    ->join('periods d', 'd.id = a.period_id', 'left')
    ->join('grades e', 'e.id = a.grade_id', 'left')
    ->join('majors f', 'f.id = e.major_id', 'left')
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
      $data['period_id'] = enc($data['period_id']);
      $data['grade_id'] = enc($data['grade_id']);


      return $data;

    }else{ // Jika cari semua
      if($conditions){
        $this->db->where($conditions);
      }

      $this->db->order_by('a.id', 'desc');

      $data = $this->db->get()->result_array();

      foreach ($data as $k => $v) {
        if(is_array($selected_id)){ // Untuk multi select
          foreach ($selected_id as $k1 => $v1) {
            if($v1 == $v['id']){
              $data[$k]['selected'] = 'selected'; // Men-setting selected untuk select2
              break;
            }else{
              $data[$k]['selected'] = '';
            }
          }
        }else{
          if($selected_id == $v['id']){
            $data[$k]['selected'] = 'selected'; // Men-setting selected untuk select2
          }else{
            $data[$k]['selected'] = '';
          }
        }

        $data[$k]['id'] = enc($v['id']);
        $data[$k]['period_id'] = enc($v['period_id']);
        $data[$k]['grade_id'] = enc($v['grade_id']);
      }

      return $data;
    }
  }

}
