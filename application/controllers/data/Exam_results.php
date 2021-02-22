<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

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

    public function export(string $exam_grade_id)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set Header Style
        $aligment = new Alignment();
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => $aligment::HORIZONTAL_CENTER,
            ],
        ];

        $sheet->getStyle('A1:E1')->applyFromArray($styleArray);

        // Set Header Name
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Siswa');
        $sheet->setCellValue('C1', 'Waktu Ujian');
        $sheet->setCellValue('D1', 'Nilai');
        $sheet->setCellValue('E1', 'Keterangan');

        // Write data
        $this->set_student_with_score(enc($exam_grade_id, 1));
        $last_row_cell = 1;
        foreach ($this->student_with_score as $k => $v) {
            $sheet->setCellValue('A' . ($k + 2), $k + 1);
            $sheet->setCellValue('B' . ($k + 2), $v['name']);
            $sheet->setCellValue('C' . ($k + 2), $v['date']);
            $sheet->setCellValue('D' . ($k + 2), $v['score']);
            $sheet->setCellValue('E' . ($k + 2), "Benar (" . $v['correct'] . ") - Salah (" . $v['incorrect'] . ")");
            $last_row_cell++;
        }

        // Set AutoSize
        foreach(range('A','E') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Set Border
        $border = new Border();
        $styleArray = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => $border::BORDER_THIN,
                    'color' => array('argb' => 'FFFF0000'),
                ),
            ),
        );

        $sheet->getStyle('A1:E' . $last_row_cell)->applyFromArray($styleArray);

        // Filename
        $filename = 'laporan-hasil-ujian';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');

        // Render and download
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
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

        if ($exam_grade_id) {
            $button = [
                'disabled' => '',
                'href' => base_url('data/exam_results/export/' . enc($exam_grade_id)),
            ];
        } else {
            $button = [
                'disabled' => 'disabled',
                'href' => '#',
            ];
        }

        $this->temp('data/exam_results/content', [
            'data' => [
                'periods' => $this->periods,
                'studies' => $this->studies,
                'grades' => $this->grades,
                'student_with_score' => $this->student_with_score,
                'button' => $button,
            ],
        ]);
    }
}
