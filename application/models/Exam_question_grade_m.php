<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Exam_question_grade_m extends MY_Model {

  public function __construct()
  {
    parent::__construct();
    $this->name = 'exam_question_extend_grades';
    $this->alias = 'Distribusi Soal terhadap Kelas';

    $this->rules = [
      [
        'field' => 'grade_period_id',
        'label' => 'Kelas',
        'rules' => 'required',
      ],
      [
        'field' => 'exam_question_id',
        'label' => 'Soal',
        'rules' => 'required',
      ],
    ];
  }

  public function find($id = false, $conditions = false, $show_del = false, $selected_id = 0)
  {
    $this->db->select('a.id, a.grade_period_id, a.exam_question_id, a.is_del')
    ->select('b.name created_by, DATE_FORMAT(a.created_at, "%d-%m-%Y") created_at')
    ->select('c.name updated_by, DATE_FORMAT(a.updated_at, "%d-%m-%Y") updated_at')
    ->select('d.period_id')
    ->select('e.name grade')
    ->from($this->name . ' a')
    ->join('z_profiles b', 'b.id = a.created_by', 'left')
    ->join('z_profiles c', 'c.id = a.updated_by', 'left')
    ->join('grade_extend_periods d', 'd.id = a.grade_period_id', 'left')
    ->join('grades e', 'e.id = d.grade_id', 'left')
    ->join('exam_questions f', 'f.id = a.exam_question_id', 'left')
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
      $data['exam_question_id'] = enc($data['exam_question_id']);
      $data['period_id'] = enc($data['period_id']);
      $data['grade_period_id'] = enc($data['grade_period_id']);


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
        $data[$k]['exam_question_id'] = enc($v['exam_question_id']);
        $data[$k]['period_id'] = enc($v['period_id']);
        $data[$k]['grade_period_id'] = enc($v['grade_period_id']);
      }

      return $data;
    }
  }

}
