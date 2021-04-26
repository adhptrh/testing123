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
    
    $this->db->select('a.id, a.token, a.score, a.exam_schedule_id, a.is_del,  DATE_FORMAT(a.start_time, "%d-%m-%Y") date, DATE_FORMAT(a.start_time, "%H:%i:%s") start_time, DATE_FORMAT(a.finish_time, "%H:%i:%s") finish_time, a.correct, a.incorrect, a.numbers_before_answer, DATE_FORMAT(a.updated_at, "%d-%m-%Y, %H:%i:%m") updated_at')
    ->from($this->name . ' a')
    ->join('z_profiles b', 'b.id = a.created_by', 'left')
    ->join('z_profiles c', 'c.id = a.updated_by', 'left')
    ->join('student_extend_grades d', 'd.id = a.student_grade_id', 'left');

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

  public function find_with_score($id = false, $conditions = false, $show_del = false, $selected_id = 0)
  {
    /**
     * Query untuk mendapatkan data siswa + nilai berdasarkan periode
     */

    $this->db->select('a.id, a.nisn, DATE_FORMAT(a.updated_at, "%d-%m-%Y, %H:%i:%m") updated_at')
    ->select('e.numbers_before_answer,e.exam_schedule_id, e.correct, e.incorrect, e.score, e.id student_grade_exam_id, DATE_FORMAT(e.finish_time, "%H:%i:%s") finish_time')
    ->select('g.name, g.gender')
    ->select('h.name regency')
    ->select('j.name study')
    ->select('m.name major')
    ->select('DATE_FORMAT(f.start, "%d-%m-%Y %H:%i:%s") date')
    ->from('students a')
    ->join('z_profiles b', 'b.id = a.created_by', 'left')
    ->join('z_profiles c', 'c.id = a.updated_by', 'left')
    ->join('student_extend_grades d', 'd.student_id = a.id', 'left')
    ->join('student_grade_extend_exams e', 'e.student_grade_id = d.id', 'left')
    ->join('exam_schedules f', 'f.id = e.exam_schedule_id', 'left')
    ->join('z_profiles g', 'g.id = a.profile_id', 'left')
    ->join('address_regency h', 'h.id = g.regency_id', 'left')
    ->join('exam_questions i', 'i.id = f.exam_question_id', 'left')
    ->join('studies j', 'j.id = i.study_id', 'left')
    ->join('grade_extend_periods k', 'k.id = d.grade_period_id', 'left')
    ->join('grades l', 'l.id = k.grade_id', 'left')
    ->join('majors m', 'm.id = l.major_id', 'left');

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
      $data['student_grade_exam_id'] = enc($data['student_grade_exam_id']);
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
        $data[$k]['student_grade_exam_id'] = enc($v['student_grade_exam_id']);
        $data[$k]['exam_schedule_id'] = enc($v['exam_schedule_id']);

      }

      return $data;
    }
  }

}