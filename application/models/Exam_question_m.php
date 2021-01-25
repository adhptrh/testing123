<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Exam_question_m extends MY_Model {

  public function __construct()
  {
    parent::__construct();
    $this->name = 'exam_questions';
    $this->alias = 'Soal';

    $this->rules = [
      [
        'field' => 'period_id',
        'label' => 'Periode',
        'rules' => 'required',
      ],
      [
        'field' => 'study_id',
        'label' => 'Mata Uji',
        'rules' => 'required',
      ],
    ];
  }

  public function find($id = false, $conditions = false, $show_del = false, $selected_id = 0)
  {
    $this->db->select('a.id, a.period_id, a.study_id, a.is_del')
    ->select('b.name created_by, DATE_FORMAT(a.created_at, "%d-%m-%Y") created_at')
    ->select('c.name updated_by, DATE_FORMAT(a.updated_at, "%d-%m-%Y") updated_at')
    ->select('d.name period')
    ->select('e.name studi')
    ->select('count(f.id) jsoal')
    ->from($this->name . ' a')
    ->join('z_profiles b', 'b.id = a.created_by', 'left')
    ->join('z_profiles c', 'c.id = a.updated_by', 'left')
    ->join('periods d', 'd.id = a.period_id', 'left')
    ->join('studies e', 'e.id = a.study_id', 'left')
    ->join('exam_question_extend_details f', 'f.exam_question_id = a.id', 'left')
    ->group_by('a.id')
    ->order_by('a.id', 'ASC');

    if(!$show_del){
      $this->db->where('a.is_del', '0')
      ->where('f.is_del', '0');
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
      $data['study_id'] = enc($data['study_id']);


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
        $data[$k]['period_id'] = enc($v['period_id']);
        $data[$k]['study_id'] = enc($v['study_id']);
      }

      return $data;
    }
  }

}
