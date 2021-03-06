<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Student_grade_m extends MY_Model {

  public function __construct()
  {
    parent::__construct();
    $this->name = 'student_extend_grades';
    $this->alias = 'Siswa-pada-Kelas';

    $this->rules = [
      [
        'field' => 'student_id',
        'label' => 'Siswa',
        'rules' => 'required',
      ],
      [
        'field' => 'grade_period_id',
        'label' => 'Kelas',
        'rules' => 'required',
      ],
    ];
  }

  public function find($id = false, $conditions = false, $show_del = false, $selected_id = 0, $where_in = false)
  {
    $this->db->select('a.id, a.order_id, a.student_id, a.is_del, a.grade_period_id, a.room')
    ->select('b.name created_by, DATE_FORMAT(a.created_at, "%d-%m-%Y") created_at')
    ->select('c.name updated_by, DATE_FORMAT(a.updated_at, "%d-%m-%Y") updated_at')
    ->select('d.period_id')
    ->select('f.nisn')
    ->select('g.name')
    ->select('h.name order')
    ->select('i.name grade')
    ->select('j.pass_siswa')
    ->from($this->name . ' a')
    ->join('z_profiles b', 'b.id = a.created_by', 'left')
    ->join('z_profiles c', 'c.id = a.updated_by', 'left')
    ->join('grade_extend_periods d', 'd.id = a.grade_period_id', 'left')
    ->join('periods e', 'e.id = d.period_id', 'left')
    ->join('students f', 'f.id = a.student_id', 'left')
    ->join('z_profiles g', 'g.id = f.profile_id', 'left')
    ->join('orders h', 'h.id = a.order_id', 'left')
    ->join('grades i', 'i.id = d.grade_id', 'left')
    ->join('z_users j', 'j.profile_id = g.id', 'left')
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
      $data['student_id'] = enc($data['student_id']);
      $data['period_id'] = enc($data['period_id']);
      $data['grade_period_id'] = enc($data['grade_period_id']);


      return $data;

    }else{ // Jika cari semua
      if($conditions){
        if($where_in){
          $this->db->where_in($where_in['key'], $where_in['filter']);
          $this->db->where($conditions);
        }else{
          $this->db->where($conditions);
        }
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
        $data[$k]['student_id'] = enc($v['student_id']);
        $data[$k]['period_id'] = enc($v['period_id']);
        $data[$k]['grade_period_id'] = enc($v['grade_period_id']);

      }

      return $data;
    }
  }

}
