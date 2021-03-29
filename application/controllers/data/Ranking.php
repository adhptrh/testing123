<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class Ranking extends MY_Controller
{

    /**
     * Peringatan ! selain fungsi index, create, save, edit, update, delete, dan restore
     * semua function HARUS protected-function
     *
     */

    private $periods = [], $studies = [], $grades = [], $regencies = [], $student_with_score = [];
    private $genders = [
        [
            'id' => 1,
            'text' => 'Laki-Laki',
        ],
        [
            'id' => 2,
            'text' => 'Perempuan',
        ],
    ];

    public function __construct()
    {
        parent::__construct();
        $this->controller_id = 27;
        $this->load->model('Exam_m', 'exam');
        $this->load->model('Period_m', 'period');
        $this->load->model('Exam_question_m', 'exam_question');
        $this->load->model('Exam_question_grade_m', 'exam_grade');
        $this->load->model('Student_grade_exam_m', 'student_exam');
        $this->load->model('Regency_m', 'mregencies');
    }

    private function set_periods()
    {
        $period_id = $this->uri->segment(4, 0);
        if ($period_id) {
            $this->periods = $this->period->find(false, false, false, enc($period_id, 1));
        } else {
            $this->periods = $this->period->find();
        }
    }

    private function set_studies()
    {
        $period_id = $this->uri->segment(4, 0);
        $study_id = $this->uri->segment(5, 0);

        if($period_id){
            if ($study_id) {
                $this->studies = $this->exam_question->find(false, [
                    'a.period_id' => enc($period_id, 1),
                ], false, enc($study_id, 1));
            } else {
                $this->studies = $this->exam_question->find();
            }
        }
    }

    private function set_grades()
    {
        $study_id = $this->uri->segment(5, 0);
        $grade_id = $this->uri->segment(6, 0);

        if ($grade_id) {
            $this->grades = $this->exam_grade->find(false, [
                'a.exam_question_id' => enc($study_id, 1),
            ], false, enc($grade_id, 1));
        } else {
            if ($study_id) {
                $this->grades = $this->exam_grade->find(false, [
                    'a.exam_question_id' =>  enc($study_id, 1),
                ]);
            }
        }
    }

    private function set_genders()
    {
        if (!$this->uri->segment(7, 0)) {
            $this->genders = [];
        }
    }

    private function set_student_with_score()
    {
        $uri = $this->uri;
        $period_id = $uri->segment(4, 0);
        $study_id = $uri->segment(5, 0);
        $grade_id = $uri->segment(6, 0);
        $regency_id = $uri->segment(7, 0);
        $gender = $uri->segment(8, 0);

        if($gender){
            if ($study_id) {
                $eg = $this->exam_grade->find(enc($study_id, 1));
    
                $data = $this->student_exam->find_with_score(false, [
                    'd.grade_period_id' => enc($eg['grade_period_id'], 1),
                    'f.exam_question_id' => enc($eg['exam_question_id'], 1),
                    'g.regency_id' => enc($regency_id, 1),
                    'g.gender' => $gender,
                ]);
                $this->student_with_score = $data;
            }
        }
    }

    private function set_regencies()
    {
        $regency_id = $this->uri->segment(7, 0);
        $grade_id = $this->uri->segment(6, 0);

        if($grade_id){
            if($regency_id){
                $this->regencies = $this->mregencies->find(false, false, false, enc($regency_id, 1));
            }else{
                $this->regencies = $this->mregencies->find(false, [
                    'a.province_id' => 4
                ]);
            }
        }
    }

    public function index()
    {
        $this->filter(2);

        $this->header = [
            'title' => 'Hasil Ujian',
            'js_file' => 'data/ranking',
            'sub_title' => 'Analisa Hasil Ujian',
            'nav_active' => 'data/ranking',
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
        $this->temp('data/ranking/content', [
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

    public function result($period_id = 0, $exam_question_id = false, $exam_grade_id = false, $regency_id = false, $gender = false)
    {
        $this->filter(2);

        $this->header = [
            'title' => 'Hasil Ujian',
            'js_file' => 'data/ranking',
            'sub_title' => 'Analisa Hasil Ujian',
            'nav_active' => 'data/ranking',
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

        $this->set_periods();
        $this->set_studies();
        $this->set_grades();
        $this->set_regencies();
        $this->set_genders();
        $this->set_student_with_score();
        
        if ($regency_id) {
            $button = [
                'disabled' => '',
                'href' => base_url('data/ranking/export/'),
            ];
        } else {
            $button = [
                'disabled' => 'disabled',
                'href' => '#',
            ];
        }

        $this->temp('data/ranking/content', [
            'data' => [
                'periods' => $this->periods,
                'studies' => $this->studies,
                'grades' => $this->grades,
                'regencies' => $this->regencies,
                'genders' => $this->genders,
                'student_with_score' => $this->student_with_score,
                'button' => $button,
                'bdetail' => [
                    'exam_question_id' => enc($exam_question_id),
                    'exam_grade_id' => enc($exam_grade_id),
                ],
            ],
        ]);
    }

    public function export()
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
        $this->set_student_with_score();
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
}