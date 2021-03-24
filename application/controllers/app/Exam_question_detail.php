<?php
defined('BASEPATH') or exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ReadXlsx;
// use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Exam_question_detail extends MY_Controller
{

    /**
     * Peringatan ! selain fungsi index, create, save, edit, update, delete, dan restore
     * semua function HARUS minimal protected-function
     *
     */

    // private $examdetail = [];

    public function __construct()
    {
        parent::__construct();
        $this->controller_id = 19;
        $this->load->model('Exam_question_detail_m', 'data');
        $this->load->model('Exam_question_m', 'exam_question');
        $this->load->model('Period_m', 'period');
        $this->load->model('Study_m', 'study');
        $this->load->model('Grade_period_m', 'grade');
        $this->load->helper('string');
    }

    function list($exam_question_id) {
        $this->filter(1);

        $eid = enc($exam_question_id, 1);

        $exam_question = $this->exam_question->find($eid);

        // Cek jumlah opsi
        $number_of_options = $this->exam_question->find(enc($exam_question_id, 1));

        $this->header = [
            'title' => 'Butir Soal',
            'js_file' => 'app/eqd',
            'sub_title' => 'Tambah Butir Soal',
            'nav_active' => 'app/exam_question',
            'breadcrumb' => [
                [
                    'label' => 'XPanel',
                    'icon' => 'fa-home',
                    'href' => '#',
                ],
                [
                    'label' => 'Aplikasi',
                    'icon' => 'fa-gear',
                    'href' => '#',
                ],
                [
                    'label' => 'Soal',
                    'icon' => 'fa-gear',
                    'href' => '#',
                ],
                [
                    'label' => $exam_question['exam'],
                    'icon' => 'fa-list',
                    'href' => base_url('app/exam_question_detail'),
                ],
                [
                    'label' => 'Butir Soal',
                    'icon' => '',
                    'href' => '#',
                ],
            ],
        ];

        $this->temp('app/exam_question_detail/content', [
            'id' => $exam_question_id,
            'token' => $this->security->get_csrf_hash(),
            'exam_question' => $this->exam_question->find($eid),
            'number_of_options' => $number_of_options['number_of_options'],
        ]);
    }

    public function reload($id)
    {
        $this->filter(2);
        $exam_question_id = enc($id, 1);

        echo json_encode([
            'data' => $this->data->find(false, [
                'exam_question_id' => $exam_question_id,
            ]),
            'base_url' => base_url(),
        ]);
    }

    public function create($exam_question_id)
    {
        $this->filter(1);

        // Cek jumlah opsi
        $number_of_options = $this->exam_question->find(enc($exam_question_id, 1));

        if ($number_of_options['number_of_options'] == 5) {
            $options = $this->exam_question->options(5);
        } else {
            $options = $this->exam_question->options(4);
        }

        $this->load->view('app/exam_question_detail/create', [
            'token' => $this->security->get_csrf_hash(),
            'master_soal_id' => $exam_question_id,
            'number_of_options' => $number_of_options['number_of_options'],
            'options' => $options,
        ]);
    }

    private function save_image($base64)
    {
        /* Mendefenisikan image
         * menyimpannya di storage
         * Return filename
         */

        $rand = uniqid();

        // Membuat folder
        $dir = 'upload/img/' . date('Ym') . '/';
        if (!is_dir($dir)) {
            mkdir($dir);
        }

        $filename = $dir . $rand . '.png';

        $source = fopen($base64, 'r');
        $destination = fopen($filename, 'w');

        stream_copy_to_stream($source, $destination);

        fclose($source);
        fclose($destination);

        return $filename;
    }

    // private function content_creation($data)
    // {
    //     /* 
    //      * DEPRECATED
    //      * Menguraikan kontent
    //      * jika ada object image kemudian disimpan di storage
    //      * menggambungkan semua object
    //      * return content
    //      */

    //     $contents = '';
    //     foreach ($data['ops'] as $k => $v) {
    //         if (isset($v['insert']['image'])) {
    //             $save_image = $this->save_image($v['insert']['image']);
    //             $contents .= ' ' . $save_image;
    //         } else {
    //             $contents .= ' ' . $v['insert'];
    //         }
    //     }

    //     return $contents;
    // }

    private function content_creation($data)
    {
        /**
         * Menguraikan kontent
         * jika ada object image kemudian disimpan di storage
         * menggambungkan semua object
         * return content
         */

        // $contents = '';
        foreach ($data['ops'] as $k => $v) {
            if (isset($v['insert']['image'])) {
                $save_image = $this->save_image($v['insert']['image']);
                $data['ops'][$k]['insert']['image'] = $save_image;
                // $contents .= ' ' . $save_image;
            } else {
                // $contents .= ' ' . $v['insert'];
            }
        }

        return json_encode($data);
    }

    public function save()
    {
        $this->filter(1);
        $post = $this->input->post('data');
        $eqi = enc($post['master_soal_id'], 1);

        // Cek jumlah opsi
        $number_of_options = $this->exam_question->find($eqi);

        if ($number_of_options['number_of_options'] == 5) {
            $data = [
                'exam_question_id' => $eqi,
                'question' => $this->content_creation($post['soal']),
                'opsi_a' => $this->content_creation($post['opsi_a']),
                'opsi_b' => $this->content_creation($post['opsi_b']),
                'opsi_c' => $this->content_creation($post['opsi_c']),
                'opsi_d' => $this->content_creation($post['opsi_d']),
                'opsi_e' => $this->content_creation($post['opsi_e']),
                // 'question' => $post['soal'],
                // 'opsi_a' => $post['opsi_a'],
                // 'opsi_b' => $post['opsi_b'],
                // 'opsi_c' => $post['opsi_c'],
                // 'opsi_d' => $post['opsi_d'],
                // 'opsi_e' => $post['opsi_e'],
                'keyword' => $post['keyword'],
            ];
        } else {
            $data = [
                'exam_question_id' => $eqi,
                'question' => $this->content_creation($post['soal']),
                'opsi_a' => $this->content_creation($post['opsi_a']),
                'opsi_b' => $this->content_creation($post['opsi_b']),
                'opsi_c' => $this->content_creation($post['opsi_c']),
                'opsi_d' => $this->content_creation($post['opsi_d']),
                'keyword' => $post['keyword'],
            ];
        }

        $create = $this->data->save($data);

        $create['token'] = $this->security->get_csrf_hash();
        $create['query'] = $this->db->last_query();
        $create['data'] = $data;
        $create['soal'] = $post['soal'];

        echo json_encode($create);
    }

    public function create_excel($exam_question_id)
    {
        $this->filter(1);

        $eid = enc($exam_question_id, 1);

        $exam_question = $this->exam_question->find($eid);

        $this->header = [
            'title' => 'Upload Butir Soal',
            'sub_title' => 'Upload Tambah Butir Soal',
            'nav_active' => 'app/exam_question',
            'breadcrumb' => [
                [
                    'label' => 'XPanel',
                    'icon' => 'fa-home',
                    'href' => '#',
                ],
                [
                    'label' => 'Aplikasi',
                    'icon' => 'fa-gear',
                    'href' => '#',
                ],
                [
                    'label' => 'Soal',
                    'icon' => 'fa-gear',
                    'href' => '#',
                ],
                [
                    'label' => $exam_question['exam'],
                    'icon' => 'fa-list',
                    'href' => base_url('app/exam_question_detail'),
                ],
                [
                    'label' => 'Import Excel',
                    'icon' => '',
                    'href' => '#',
                ],
            ],
        ];

        $this->temp('app/exam_question_detail/create_excel', [
            'exam_question_id_enc' => $exam_question_id,
        ]);
    }

    public function create_excel_temp($exam_question_id)
    {
        $file = './upload/exam_template_master.xls';
        $template = IOFactory::load($file);

        $header = $this->exam_question->find(enc($exam_question_id, 1));

        $sheet = $template->getActiveSheet();
        $sheet->setCellValue('A1', 'Kode Soal : ' . $exam_question_id);
        $sheet->setCellValue('A4', 'Mata Pelajaran : ' . $header['exam']);
        $sheet->setCellValue('A5', 'Periode : ' . $header['period']);
        $sheet->setCellValue('A6', 'Kelas : ' . str_replace("<br/>", ", ", $header['grade']));

        // Filename
        $date = new DateTime();
        $filename = str_replace(' ', '_', $header['exam'] . $header['period'] . '_' . $date->getTimestamp());
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');

        // // Render and download
        $writer = new Xlsx($template);
        $writer->save('php://output');
    }

    public function save_excel($exam_question_id)
    {
        $exam_question_id = enc($exam_question_id, 1);
        $fileName = $_FILES['file']['name'];

        $path = './upload/files/';
        $config['upload_path'] = $path; //path upload
        $config['file_name'] = $fileName; // nama file
        $config['allowed_types'] = 'xls|xlsx'; //tipe file yang diperbolehkan
        $config['max_size'] = 10000; // maksimal sizze

        $this->load->library('upload'); //meload librari upload
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('file')) {
            $this->session->set_flashdata('create_info_message', $this->upload->display_errors());
            $this->create_excel(enc($exam_question_id));
        } else {
            $inputFileName = $path . $fileName;

            $reader = new ReadXlsx();
            $spreadsheet = $reader->load($inputFileName);
            $sheetData = $spreadsheet->getActiveSheet()->toArray();

            // Cek apakah template ini adalah sah
            $kode = explode("Kode Soal : ", $sheetData[0][0]);
            if (enc($kode[1], 1) == $exam_question_id) {
                $this->save_excel_process($sheetData, $exam_question_id);
                redirect(base_url('app/exam_question_detail/list/' . enc($exam_question_id)));
            } else {
                $this->session->set_flashdata('create_info_message', 'Template tidak sesuai');
                $this->create_excel(enc($exam_question_id));
            }
        }
    }

    private function save_excel_process($sheetData, $exam_question_id)
    {
        $data = [];
        $key = 0;
        foreach ($sheetData as $k => $v) {
            if ($k >= 8 && $v[1] != '') {
                switch ($v[7]) {
                    case 'A':
                        $keyword = 1;
                        break;
                    case 'B':
                        $keyword = 2;
                        break;
                    case 'C':
                        $keyword = 3;
                        break;
                    case 'R':
                        $keyword = 4;
                        break;
                    case 'E':
                        $keyword = 5;
                        break;
                    default:
                        $keyword = 1;
                }
                $data[$key] = [
                    'exam_question_id' => $exam_question_id,
                    'question' => $v[1],
                    'opsi_a' => $v[2],
                    'opsi_b' => $v[3],
                    'opsi_c' => $v[4],
                    'opsi_d' => $v[5],
                    'opsi_e' => $v[6],
                    'keyword' => $keyword,
                ];
                $key++;
            }
        }

        if (count($data) > 0) {
            $save = $this->data->save_batch($data);
            if ($save['status'] == '200') {
                $this->session->set_flashdata('message', $save['message']);
            } else {
                $this->session->set_flashdata('create_info_message', $save['message']);
            }
        } else {
            $this->session->set_flashdata('create_info_message', 'Tidak terdapat soal pada template');
        }
    }

    public function create_from_another_period($exam_question_id, $study_id = false, $period_id = false, $old = [])
    {
        $this->filter(1);

        $eid = enc($exam_question_id, 1);

        $exam_question = $this->exam_question->find($eid);

        $this->header = [
            'title' => 'Import Butir Soal',
            'js_file' => 'app/eqd_import_another_period',
            'sub_title' => 'Import Butir Soal',
            'nav_active' => 'app/exam_question',
            'breadcrumb' => [
                [
                    'label' => 'XPanel',
                    'icon' => 'fa-home',
                    'href' => '#',
                ],
                [
                    'label' => 'Aplikasi',
                    'icon' => 'fa-gear',
                    'href' => '#',
                ],
                [
                    'label' => 'Soal',
                    'icon' => 'fa-gear',
                    'href' => '#',
                ],
                [
                    'label' => $exam_question['exam'],
                    'icon' => 'fa-list',
                    'href' => base_url('app/exam_question_detail'),
                ],
                [
                    'label' => 'Import dari Periode Lain',
                    'icon' => '',
                    'href' => '#',
                ],
            ],
        ];

        $current = $this->exam_question->find($eid);
        $period = $this->period->find();

        /**
         * Filter period
         * menghapus period exam_question ini dari daftar
         */
        $dperiod = [];
        foreach ($period as $k => $v) {
            $period_id_in_loop = enc($v['id'], 1);
            if ($period_id_in_loop != enc($current['period_id'], 1)) {
                if ($period_id) {
                    if (enc($period_id, 1) == $period_id_in_loop) {
                        $v['selected'] = 'selected';
                    }
                }
                $dperiod[] = $v;
            }
        }

        /**
         * Mendapatkan data daftar soal yang akan dimport
         */

        $target = [];
        if ($study_id && $period_id) {
            $target = $this->exam_question->find(false, [
                'a.period_id' => enc($period_id, 1),
                'a.study_id' => enc($study_id, 1),
            ]);
        }

        $this->temp('app/exam_question_detail/import_from_antoher_period', [
            'old' => $old,
            'period' => $dperiod,
            'exam_question_id' => $exam_question_id,
            'study_id' => $current['study_id'],
            'data' => $target,
        ]);
    }

    public function import_from_another_period($exam_question_id_source, $exam_question_id_target)
    {
        $eqi_source = enc($exam_question_id_source, 1);
        $eqi_target = enc($exam_question_id_target, 1);

        $source = $this->data->find(false, [
            'a.exam_question_id' => $eqi_source,
        ]);

        $data = [];
        foreach ($source as $k => $v) {
            unset($v['id']);
            unset($v['selected']);
            $v['exam_question_id'] = $eqi_target;
            $data[] = $v;
        }

        $save = $this->data->save_batch($data);
        if ($save['status'] == 200) {
            $this->session->set_flashdata('message', $save['message']);
            redirect(base_url('app/exam_question_detail/list/' . $exam_question_id_target));
        } else {
            $this->session->set_flashdata('message', $save['message']);
            redirect(base_url('app/exam_question_detial/create_from_another_period/' . $eqi_target));
        }
    }

    public function edit($id)
    {
        $this->filter(3);

        $data = $this->data->find(enc($id, 1));

        // Cek jumlah opsi
        $number_of_options = $this->exam_question->find(enc($data['exam_question_id'], 1));

        if ($number_of_options['number_of_options'] == 5) {
            $options = $this->exam_question->options(5);
        } else {
            $options = $this->exam_question->options(4);
        }

        $this->load->view('app/exam_question_detail/edit', [
            'token' => $this->security->get_csrf_hash(),
            'data' => $data,
            'number_of_options' => $number_of_options['number_of_options'],
            'options' => $options,
        ]);
    }

    public function data_for_edit($id)
    {
        $this->filter(3);
        $data = $this->data->find(enc($id, 1));
        echo json_encode([
            'soal' => $this->data->str_to_quill($data['question']),
            'opsi_a' => $this->data->str_to_quill($data['opsi_a']),
            'opsi_b' => $this->data->str_to_quill($data['opsi_b']),
            'opsi_c' => $this->data->str_to_quill($data['opsi_c']),
            'opsi_d' => $this->data->str_to_quill($data['opsi_d']),
            'opsi_e' => $this->data->str_to_quill($data['opsi_e']),
            'kunci' => $data['keyword'],
            'token' => $this->security->get_csrf_hash(),
        ]);
    }

    public function update()
    {
        $this->filter(3);
        $post = $this->input->post('data');

        $eqi = enc($post['master_soal_id'], 1);

        // Cek jumlah opsi
        $number_of_options = $this->exam_question->find($eqi);

        if ($number_of_options['number_of_options'] == 5) {
            $data = [
                'id' => enc($post['id'], 1),
                'exam_question_id' => $eqi,
                'question' => $this->content_creation($post['soal']),
                'opsi_a' => $this->content_creation($post['opsi_a']),
                'opsi_b' => $this->content_creation($post['opsi_b']),
                'opsi_c' => $this->content_creation($post['opsi_c']),
                'opsi_d' => $this->content_creation($post['opsi_d']),
                'opsi_e' => $this->content_creation($post['opsi_e']),
                'keyword' => $post['keyword'],
            ];
        } else {
            $data = [
                'id' => enc($post['id'], 1),
                'exam_question_id' => $eqi,
                'question' => $this->content_creation($post['soal']),
                'opsi_a' => $this->content_creation($post['opsi_a']),
                'opsi_b' => $this->content_creation($post['opsi_b']),
                'opsi_c' => $this->content_creation($post['opsi_c']),
                'opsi_d' => $this->content_creation($post['opsi_d']),
                'keyword' => $post['keyword'],
            ];
        }

        $update = $this->data->save($data);

        $update['token'] = $this->security->get_csrf_hash();
        $update['data'] = $data;

        echo json_encode($update);
    }

    public function delete($id)
    {
        $this->filter(4);

        $delete = $this->data->delete($id);
        $delete['token'] = $this->security->get_csrf_hash();
        echo json_encode($delete);
    }

    public function restore($id)
    {
        $this->filter(4);
    }

    public function upload($file_type = "jpg|png")
    {
        // $upload = $this->xupload();
        // echo json_encode($upload);

        // Membuat folder
        $dir = './upload/img/' . date('Ym') . '/';
        if (!is_dir($dir)) {
            mkdir($dir);
        }

        $path = $dir;
        $config['upload_path'] = $path; //path upload
        $config['encrypt_name'] = true;
        $config['allowed_types'] = $file_type; //tipe file yang diperbolehkan
        $config['max_size'] = 10000; // maksimal sizze

        $this->load->library('upload'); //meload librari upload
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('file')) {
            echo json_encode([
                'error' => [
                    'message' => $this->upload->display_errors(),
                ],
            ]);
        } else {
            echo json_encode([
                'url' => base_url('upload/img/' . date('Ym') . '/' . $this->upload->data('filename')),
            ]);
        }
    }
}
