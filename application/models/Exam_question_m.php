<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Exam_question_m extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Hr_m', 'hr');
        $this->load->model('Hr_study_m', 'hr_study');
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
        /**
         * Filter jika guru berdasarkan studinya masing-masing
         */
        $filter_study = [];

        if ($this->get_profile_level_id() == 3) {
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

        $this->db->select('a.id, a.period_id, a.study_id, a.is_del')
            ->select('b.name created_by, DATE_FORMAT(a.created_at, "%d-%m-%Y") created_at')
            ->select('c.name updated_by, DATE_FORMAT(a.updated_at, "%d-%m-%Y") updated_at')
            ->select('d.name period')
            ->select('e.name exam')
            ->select('f.jsoal')
            ->select('g.grade')
            ->from($this->name . ' a')
            ->join('z_profiles b', 'b.id = a.created_by', 'left')
            ->join('z_profiles c', 'c.id = a.updated_by', 'left')
            ->join('periods d', 'd.id = a.period_id', 'left')
            ->join('studies e', 'e.id = a.study_id', 'left')
            ->join('(
              SELECT    a.id, a.exam_question_id, count(a.id) jsoal
              FROM      exam_question_extend_details a
              WHERE     a.is_del = "0"
              GROUP BY  a.exam_question_id
            ) f', 'f.exam_question_id = a.id', 'left')
            ->join('(
              SELECT    a.id, a.exam_question_id,
                        GROUP_CONCAT(c.name SEPARATOR "<br/>") grade
              FROM      exam_question_extend_grades a
              JOIN      grade_extend_periods b ON b.id = a.grade_period_id
              JOIN      grades c ON c.id = b.grade_id
              WHERE     a.is_del = "0"
              GROUP BY  a.exam_question_id
            ) g', 'g.exam_question_id = a.id', 'left')
            ->group_by('a.id')
            ->order_by('a.id', 'ASC');

        if (!$show_del) {
            $this->db->where('a.is_del', '0');
        }

        $this->db->order_by('a.id', 'desc');

        // Jika cari berdasarkan id
        if ($id) {

            $this->db->where([
                'a.id' => $id,
            ]);

            if ($filter_study) {
                $this->db->or_where_in('e.id', $filter_study);
            }

            $data = $this->db->get()->row_array();
            $data['id'] = enc($data['id']);
            $data['period_id'] = enc($data['period_id']);
            $data['study_id'] = enc($data['study_id']);

            return $data;

        } else { // Jika cari semua
            if($conditions){
                $this->db->where($conditions);
                if($filter_study){
                  $this->db->or_where_in('e.id', $filter_study);
                }
              }else{
                if($filter_study){
                  $this->db->where_in('e.id', $filter_study);
                }
              }

            $this->db->order_by('a.id', 'desc');

            $data = $this->db->get()->result_array();

            foreach ($data as $k => $v) {
                if ($selected_id == $v['id']) {
                    $data[$k]['selected'] = 'selected'; // Men-setting selected untuk select2
                } else {
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
