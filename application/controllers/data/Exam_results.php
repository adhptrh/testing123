<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Exam_results extends MY_Controller
{

    /**
     * Peringatan ! selain fungsi index, create, save, edit, update, delete, dan restore
     * semua function HARUS protected-function
     *
     */

    private $periods = [], $studies = [], $grades = [], $student_with_score = [];

    public function __construct()
    {
        parent::__construct();
        $this->controller_id = 24;
        $this->load->model('Period_m', 'period');
        $this->load->model('Exam_question_m', 'exam_question');
        $this->load->model('Exam_question_grade_m', 'exam_grade');
        $this->load->model('Student_grade_exam_m', 'student_exam');
    }

    private function set_periods($selected_id = 0)
    {
        if ($selected_id == 0) {
            $this->periods = $this->period->find();
        } else {
            $this->periods = $this->period->find(false, false, false, $selected_id);
        }
    }

    private function set_studies($period_id = 0, $selected_id = 0)
    {
        if ($selected_id == 0) {
            $this->studies = $this->exam_question->find();
        } else {
            $this->studies = $this->exam_question->find(false, [
                'a.period_id' => $period_id,
            ], false, $selected_id);
        }
    }

    private function set_grades($exam_question_id = false, $selected_id = false)
    {
        if ($selected_id) {
            $this->grades = $this->exam_grade->find(false, [
                'a.exam_question_id' => $exam_question_id,
            ], false, $selected_id);
        } else {
            if ($exam_question_id) {
                $this->grades = $this->exam_grade->find(false, [
                    'a.exam_question_id' => $exam_question_id,
                ]);
            } else {
                $this->grades = [];
            }
        }
    }

    private function set_student_with_score($exam_grade_id = false)
    {
        if ($exam_grade_id) {
            $eg = $this->exam_grade->find($exam_grade_id);

            $data = $this->student_exam->find_with_score(false, [
                'd.grade_period_id' => enc($eg['grade_period_id'], 1),
                'f.exam_question_id' => enc($eg['exam_question_id'], 1),
            ]);
            $this->student_with_score = $data;
        } else {
            $this->student_with_score = [];
        }
    }

    public function index()
    {
        $this->filter(2);

        $this->header = [
            'title' => 'Hasil Ujian',
            'js_file' => 'data/exam_result',
            'sub_title' => 'Analisa Hasil Ujian',
            'nav_active' => 'data/exam_results',
            'breadcrumb' => [
                [
                    'label' => 'XPanel',
                    'icon' => 'fa-home',
                    'href' => '#',
                ],
                [
                    'label' => 'Data',
                    'icon' => 'fa-gear',
                    'href' => '#',
                ],
                [
                    'label' => 'Hasil Ujian',
                    'icon' => '',
                    'href' => '#',
                ],
            ],
        ];

        $this->set_periods();
        $this->temp('data/exam_results/content', [
            'data' => [
                'periods' => $this->periods,
                'student_with_score' => $this->student_with_score,
            ],
        ]);
    }

    public function result($period_id = 0, $exam_question_id = false, $exam_grade_id = false)
    {
        $this->filter(2);

        $this->header = [
            'title' => 'Hasil Ujian',
            'js_file' => 'data/exam_result',
            'sub_title' => 'Analisa Hasil Ujian',
            'nav_active' => 'data/exam_results',
            'breadcrumb' => [
                [
                    'label' => 'XPanel',
                    'icon' => 'fa-home',
                    'href' => '#',
                ],
                [
                    'label' => 'Data',
                    'icon' => 'fa-gear',
                    'href' => '#',
                ],
                [
                    'label' => 'Hasil Ujian',
                    'icon' => '',
                    'href' => '#',
                ],
            ],
        ];

        if ($exam_question_id) {
            $exam_question_id = enc($exam_question_id, 1);
        } else {
            $exam_question_id = false;
        }

        if ($exam_grade_id) {
            $exam_grade_id = enc($exam_grade_id, 1);
        } else {
            $exam_grade_id = false;
        }

        $this->set_periods(enc($period_id, 1));
        $this->set_studies(enc($period_id, 1), $exam_question_id);
        $this->set_grades($exam_question_id, $exam_grade_id);
        $this->set_student_with_score($exam_grade_id);

        $this->temp('data/exam_results/content', [
            'data' => [
                'periods' => $this->periods,
                'studies' => $this->studies,
                'grades' => $this->grades,
                'student_with_score' => $this->student_with_score,
            ],
        ]);
    }
}
