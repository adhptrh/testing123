<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Exam_temp_m extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->name = 'exam_temps';
        $this->alias = 'Jawaban Siswa Sementara';

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

    public function find($id = false, $conditions = false, $show_del = false, $selected_id = 0, $is_student = false)
    {
        if ($is_student) {
            $this->db->select('a.id, a.is_del, a.exam_question_detail_id, a.answer, a.is_correct');
        } else {
            $this->db->select('a.id, a.is_del, a.exam_question_detail_id, a.answer, a.is_lock, d.timeleft_second, a.hit_at, a.updated_at');
        }

        $this->db->from($this->name . ' a')
            ->join('z_profiles b', 'b.id = a.created_by', 'left')
            ->join('z_profiles c', 'c.id = a.updated_by', 'left')
            ->join('`exam_question_extend_details d', 'd.id = a.exam_question_detail_id', 'left');

        if (!$show_del) {
            $this->db->where('a.is_del', '0');
        }

        // $this->db->order_by('a.id', 'desc');

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

            $this->db->order_by('a.id', 'asc');

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

    /**
     * Lock Question
     * Mengunci soal pada ujian-online
     */
    public function lock_question($id)
    {
        $save = $this->save(
            [
                'id' => $id,
                'is_lock' => '1',
                'hit_at' => date('Y-m-d H:i:s'),
            ],
            true
        );
    }

}
