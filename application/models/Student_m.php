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
        'field' => 'profile_id',
        'label' => 'Profile',
        'rules' => 'required',
      ],
      [
        'field' => 'nisn',
        'label' => 'NISN',
        'rules' => 'required',
      ],
    ];
  }

  public function find($id = false, $conditions = false, $show_del = false, $selected_id = 0)
  {
    $this->db->select('a.id, a.profile_id, a.nisn, a.is_login, a.is_del')
    ->select('d.name')
    ->select('b.name created_by, DATE_FORMAT(a.created_at, "%d-%m-%Y") created_at')
    ->select('c.name updated_by, DATE_FORMAT(a.updated_at, "%d-%m-%Y") updated_at')
    ->select('g.name kelas')
    ->select('e.id student_grade_id, e.grade_period_id')
    ->select('f.period_id')
    ->select('DATE_FORMAT(h.updated_at, "%d-%m-%Y %H:%i:%s") last_login')
    ->from($this->name . ' a')
    ->join('z_profiles b', 'b.id = a.created_by', 'left')
    ->join('z_profiles c', 'c.id = a.updated_by', 'left')
    ->join('z_profiles d', 'd.id = a.profile_id', 'left')
    ->join('student_extend_grades e', 'e.student_id = a.id', 'left')
    ->join('grade_extend_periods f', 'f.id = e.grade_period_id', 'left')
    ->join('grades g', 'g.id = f.grade_id', 'left')
    ->join('z_users h', 'h.profile_id = a.profile_id', 'left')
    ->group_by('a.id')
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
      $data['profile_id'] = enc($data['profile_id']);
      $data['student_grade_id'] = enc($data['student_grade_id']);
      $data['grade_period_id'] = enc($data['grade_period_id']);
      $data['period_id'] = enc($data['period_id']);


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
        $data[$k]['student_grade_id'] = enc($v['student_grade_id']);
        $data[$k]['grade_period_id'] = enc($v['grade_period_id']);
        $data[$k]['period_id'] = enc($v['period_id']);
      }

      return $data;
    }
  }

}
