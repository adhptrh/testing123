<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Student_grade_exam_m extends MY_Model {

  public function __construct()
  {
    parent::__construct();
    $this->name = 'student_grade_extend_exams';
    $this->alias = 'Siswa-Kelas-Pada-Ujian';

    $this->rules = [
      [
        'field' => 'student_grade_id',
        'label' => 'Siswa',
        'rules' => 'required',
      ],
      [
        'field' => 'exam_schedule_id',
        'label' => 'Soal',
        'rules' => 'required',
      ],
    ];
  }

  public function find($id = false, $conditions = false, $show_del = false, $selected_id = 0)
  {
    $this->db->select('a.id, a.exam_schedule_id, a.is_del, a.finish_time, a.correct, a.incorrect, a.numbers_before_answer, DATE_FORMAT(a.updated_at, "%d-%m-%Y, %H:%i:%m") updated_at')
    ->select('f.name siswa')
    ->from($this->name . ' a')
    ->join('z_profiles b', 'b.id = a.created_by', 'left')
    ->join('z_profiles c', 'c.id = a.updated_by', 'left')
    ->join('student_extend_grades d', 'd.id = a.student_grade_id', 'left')
    ->join('students e', 'e.id = d.student_id', 'left')
    ->join('z_profiles f', 'f.id = e.profile_id', 'left')
    ->order_by('a.id');

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
      $data['exam_schedule_id'] = enc($data['exam_schedule_id']);


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
        $data[$k]['exam_schedule_id'] = enc($v['exam_schedule_id']);

      }

      return $data;
    }
  }

}