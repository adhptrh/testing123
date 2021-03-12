<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Exam_schedule_m extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->name = 'exam_schedules';
        $this->alias = 'Jadwal Ujian';
        // $this->load->model('Student_grade_m', 'student_grade');

        $this->rules = [
            [
                'field' => 'exam_question_id',
                'label' => 'Soal',
                'rules' => 'required',
            ],
            [
                'field' => 'order_id',
                'label' => 'Sesi',
                'rules' => 'required',
            ],
            [
                'field' => 'date',
                'label' => 'Tanggal',
                'rules' => 'required',
            ],
            [
                'field' => 'start',
                'label' => 'Jadwal Mulai',
                'rules' => 'required',
            ],
            [
                'field' => 'finish',
                'label' => 'Jadwal Selesai',
                'rules' => 'required',
            ],
        ];
    }

    public function find($id = false, $conditions = false, $show_del = false, $selected_id = 0)
    {
        $this->db->select('a.id, a.is_random, a.is_del, DATE_FORMAT(a.start, "%H:%i") start')
            ->select('DATE_FORMAT(TIMEDIFF(a.finish, a.start), "%H Jam %i menit") durasi')
            ->select('a.number_of_exam, DATE_FORMAT(a.finish, "%H:%i") finish')
            ->select('UNIX_TIMESTAMP(NOW()) time_server_now')
            ->select('UNIX_TIMESTAMP(a.finish) time_left')
            ->select('UNIX_TIMESTAMP(a.start) time_start')
            ->select('IF(NOW() >= a.start AND NOW() <= a.finish, 1, 0) intime')
            ->select('a.order_id, a.exam_question_id')
            ->select('DATE_FORMAT(a.date, "%d-%m-%Y") date')
            ->select('b.name created_by, DATE_FORMAT(a.created_at, "%d-%m-%Y") created_at')
            ->select('c.name updated_by, DATE_FORMAT(a.updated_at, "%d-%m-%Y") updated_at')
            ->select('d.period_id')
            ->select('e.name order')
            ->select('f.name study')
            ->select('g.grade, g.grade_period_id')
            ->select('h.jsoal stock_of_exams')
            ->select('j.name period_name')
            ->from($this->name . ' a')
            ->join('z_profiles b', 'b.id = a.created_by', 'left')
            ->join('z_profiles c', 'c.id = a.updated_by', 'left')
            ->join('exam_questions d', 'd.id = a.exam_question_id', 'left')
            ->join('orders e', 'e.id = a.order_id', 'left')
            ->join('studies f', 'f.id = d.study_id', 'left')
            ->join('(
            SELECT    a.id, a.exam_question_id, GROUP_CONCAT(b.id SEPARATOR "-") grade_period_id,
                      GROUP_CONCAT(c.name SEPARATOR "<br/>") grade
            FROM      exam_question_extend_grades a
            JOIN      grade_extend_periods b ON b.id = a.grade_period_id
            JOIN      grades c ON c.id = b.grade_id
            WHERE     a.is_del = "0"
            GROUP BY  a.exam_question_id
          ) g', 'g.exam_question_id = a.exam_question_id', 'left')
            ->join('(
            SELECT    a.id, a.exam_question_id, count(a.id) jsoal
            FROM      exam_question_extend_details a
            WHERE     a.is_del = "0"
            GROUP BY  a.exam_question_id
          ) h', 'h.exam_question_id = a.exam_question_id', 'left')
            ->join('exam_question_extend_grades i', 'i.exam_question_id = a.exam_question_id', 'left')
            ->join('periods j', 'j.id = d.period_id', 'left')
        // ->group_by('a.exam_question_id')
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

            $data = $this->db->get()->row_array();
            $data['id'] = enc($data['id']);
            $data['order_id'] = enc($data['order_id']);
            $data['period_id'] = enc($data['period_id']);
            $data['exam_question_id'] = enc($data['exam_question_id']);

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
                $data[$k]['order_id'] = enc($v['order_id']);
                $data[$k]['period_id'] = enc($v['period_id']);
                $data[$k]['exam_question_id'] = enc($v['exam_question_id']);
            }

            return $data;
        }
    }

}
