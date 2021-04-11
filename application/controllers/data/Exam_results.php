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
        $this->load->model('Exam_m', 'exam');
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
        if ($period_id == 0) {
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
                'button' => [
                    'disabled' => 'disabled',
                    'href' => '#',
                ],
            ],
        ]);
    }

    public function detail($student_grade_exam_id, $exam_question_id = 0, $exam_grade_id = 0){

        /**
         * Menampilkan detail jawaban siswa
         * Variable :
         * enc student_grade_exam_id untuk mendapatkan detail jawaban
         * enc exam_question_id untuk mendapatkan data study
         * enc exam_grade_id untuk mendapatkan data kelas
         */
        $this->filter(2);

        $this->header = [
            'title' => 'Hasil Ujian',
            'js_file' => 'data/exam_result_detail',
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
                    'label' => 'Detail Hasil Ujian',
                    'icon' => '',
                    'href' => '#',
                ],
            ],
        ];
        
        $sgei = enc($student_grade_exam_id, 1);
        $details = $this->exam->find_for_analytics(false, [
            'a.student_grade_exam_id' => $sgei
        ]);

        $student = $this->student_exam->find_with_score(false, [
            'e.id' => $sgei
        ]);
        $exam_question = $this->exam_question->find(enc($exam_question_id, 1));
        $grade = $this->exam_grade->find(enc($exam_grade_id, 1));
        $dateTime = $this->student_exam->find($sgei);

        $this->temp('data/exam_results/detail', [
            'data' => [
                'data' => $details,
                'summary' => [
                    'name' => $student[0]['name'],
                    'nisn' => $student[0]['nisn'],
                    'grade' => $grade['grade'],
                    'study' => $exam_question['exam'],
                    'date' => $dateTime['date'],
                    'time' => $dateTime['start_time'] . ' s.d ' . $dateTime['finish_time'],
                ],
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

        $sheet->getStyle('A1:K1')->applyFromArray($styleArray);

        // Set Header Name
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Mata Uji');
        $sheet->setCellValue('C1', 'Kabupaten');
        $sheet->setCellValue('D1', 'Nama Siswa');
        $sheet->setCellValue('E1', 'L/P');
        $sheet->setCellValue('F1', 'Waktu Ujian');
        $sheet->setCellValue('G1', 'Nilai');
        $sheet->setCellValue('H1', 'Jumlah Benar');
        $sheet->setCellValue('I1', 'Jumlah Salah');
        $sheet->setCellValue('J1', 'Soal Terjawab');
        $sheet->setCellValue('K1', 'Soal Tidak Terjawab');

        // Write data
        $this->set_student_with_score(enc($exam_grade_id, 1));
        $last_row_cell = 1;
        foreach ($this->student_with_score as $k => $v) {
            $numbers_answered = ($v['correct'] + $v['incorrect']);
            $sheet->setCellValue('A' . ($k + 2), $k + 1);
            $sheet->setCellValue('B' . ($k + 2), $v['study']);
            $sheet->setCellValue('C' . ($k + 2), $v['regency']);
            $sheet->setCellValue('D' . ($k + 2), $v['name']);
            $sheet->setCellValue('E' . ($k + 2), $gender = ($v['gender'] == '1') ? 'L' : 'P');
            $sheet->setCellValue('F' . ($k + 2), $v['date']);
            $sheet->setCellValue('G' . ($k + 2), $v['score']);
            $sheet->setCellValue('H' . ($k + 2), $v['correct']);
            $sheet->setCellValue('I' . ($k + 2), $v['incorrect']);
            $sheet->setCellValue('J' . ($k + 2), $numbers_answered);
            $sheet->setCellValue('K' . ($k + 2), $v['numbers_before_answer']);
            $last_row_cell++;
        }

        // Set AutoSize
        foreach(range('A','K') as $columnID) {
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

        $sheet->getStyle('A1:K' . $last_row_cell)->applyFromArray($styleArray);

        // Filename
        $filename = 'laporan-hasil-ujian';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');

        // Render and download
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function detail_question($exam_id)
    {
        $this->filter(2);
        $exam_id = enc($exam_id, 1);
        $data = $this->exam->find_for_analytics($exam_id);
        echo json_encode([
            'question' => $data['question'],
            'opsi_a' => $data['opsi_a'],
            'opsi_b' => $data['opsi_b'],
            'opsi_c' => $data['opsi_c'],
            'opsi_d' => $data['opsi_d'],
            'opsi_e' => $data['opsi_e'],
            'keyword' => $data['keyword'],
            'answer' => $data['answer'],
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
                'bdetail' => [
                    'exam_question_id' => enc($exam_question_id),
                    'exam_grade_id' => enc($exam_grade_id),
                ],
            ],
        ]);
    }
}
