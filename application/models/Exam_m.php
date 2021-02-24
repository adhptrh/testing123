<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Exam_m extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->name = 'exams';
        $this->alias = 'Jawaban Siswa Final';

        $this->rules = [
            [
                'field' => 'student_grade_exam_id',
                'label' => 'ID Siswa Kelas Exam',
                'rules' => 'required',
            ],
            [
                'field' => 'exam_question_detail_id',
                'label' => 'ID Butir Soal',
                'rules' => 'required',
            ],
        ];
    }

    public function find($id = false, $conditions = false, $show_del = false, $selected_id = 0)
    {
        $this->db->select('a.id, a.is_del, a.exam_question_detail_id, a.answer')
            ->select('b.name created_by, DATE_FORMAT(a.created_at, "%d-%m-%Y") created_at')
            ->select('c.name updated_by, DATE_FORMAT(a.updated_at, "%d-%m-%Y") updated_at')
            ->select('d.question, d.opsi_a, d.opsi_b, d.opsi_c, d.opsi_d, d.opsi_e')
            ->from($this->name . ' a')
            ->join('z_profiles b', 'b.id = a.created_by', 'left')
            ->join('z_profiles c', 'c.id = a.updated_by', 'left')
            ->join('exam_question_extend_details d', 'd.id = a.exam_question_detail_id', 'left');

        if (!$show_del) {
            $this->db->where('a.is_del', '0');
        }

        $this->db->order_by('a.id', 'desc');

        // Jika cari berdasarkan id
        if ($id) {

            $this->db->where([
                'a.id' => $id,
            ]);

            $data = $this->db->get()->row_array();
            $data['id'] = enc($data['id']);
            $data['exam_question_detail_id'] = enc($data['exam_question_detail_id']);

            return $data;

        } else { // Jika cari semua
            if ($conditions) {
                $this->db->where($conditions);
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
                $data[$k]['exam_question_detail_id'] = enc($v['exam_question_detail_id']);
            }

            return $data;
        }
    }

    public function find_for_analytics($id = false, $conditions = false, $show_del = false, $selected_id = 0)
    {
        $this->db->select('a.id, a.is_del, a.exam_question_detail_id, a.answer, a.is_correct')
            ->select('b.name created_by, DATE_FORMAT(a.created_at, "%d-%m-%Y") created_at')
            ->select('c.name updated_by, DATE_FORMAT(a.updated_at, "%H:%i:%s") updated_at')
            ->select('d.keyword')
            ->from($this->name . ' a')
            ->join('z_profiles b', 'b.id = a.created_by', 'left')
            ->join('z_profiles c', 'c.id = a.updated_by', 'left')
            ->join('exam_question_extend_details d', 'd.id = a.exam_question_detail_id', 'left')
            ->order_by('d.id', 'ASC');

        if (!$show_del) {
            $this->db->where('a.is_del', '0');
        }

        $this->db->order_by('a.id', 'desc');

        // Jika cari berdasarkan id
        if ($id) {

            $this->db->where([
                'a.id' => $id,
            ]);

            $data = $this->db->get()->row_array();
            $data['id'] = enc($data['id']);
            // $data['exam_question_detail_id'] = enc($data['exam_question_detail_id']);

            return $data;

        } else { // Jika cari semua
            if ($conditions) {
                $this->db->where($conditions);
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
                // $data[$k]['exam_question_detail_id'] = enc($v['exam_question_detail_id']);
            }

            return $data;
        }
    }

}
