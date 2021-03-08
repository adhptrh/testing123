<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Study_m extends MY_Model {

  public function __construct()
  {
    parent::__construct();
    $this->load->model('Hr_m', 'hr');
    $this->load->model('Hr_study_m', 'hr_study');
    $this->name = 'studies';
    $this->alias = 'Mata Uji';

    $this->rules = [
      [
        'field' => 'name',
        'label' => 'Nama Mata Uji',
        'rules' => 'required',
      ],
    ];
  }

  public function find($id = false, $conditions = false, $show_del = false, $selected_id = 0)
  {
    /**
     * Filter jika guru berdasarkan studinya masing-masing
     */
    $filter_study = [];

    if($this->get_profile_level_id() == 3){
      // cari hr_id berdasakan profile_id
      $hr = $this->hr->find(false, [
        'a.profile_id' => $this->get_profile_id(),
      ]);

      // cari mapel berdasarkan hr_id
      $study = $this->hr_study->find(false, [
        'a.hr_id' => enc($hr[0]['id'], 1),
      ]);
      
      foreach ($study as $k => $v) {
        $filter_study[$k] = enc($v['study_id'], 1);
      }
    }

    $this->db->select('a.id, a.name, a.is_del')
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

      if($filter_study){
        $this->db->or_where_in('a.id', $filter_study);
      }

      $data = $this->db->get()->row_array();
      $data['id'] = enc($data['id']);


      return $data;

    }else{ // Jika cari semua
      if($conditions){
        $this->db->where($conditions);
        if($filter_study){
          $this->db->or_where_in('a.id', $filter_study);
        }
      }else{
        if($filter_study){
          $this->db->where_in('a.id', $filter_study);
        }
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
      }

      return $data;
    }
  }

}
