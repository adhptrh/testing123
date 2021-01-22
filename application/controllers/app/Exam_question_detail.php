<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Exam_question_detail extends MY_Controller
{

    /**
     * Peringatan ! selain fungsi index, create, save, edit, update, delete, dan restore
     * semua function HARUS protected-function
     *
     */

    public function __construct()
    {
        parent::__construct();
        $this->controller_id = 19;
        $this->load->model('Exam_question_detail_m', 'data');
        $this->load->model('Exam_question_m', 'exam_question');
        $this->load->model('Period_m', 'period');
        $this->load->model('Study_m', 'study');
    }

    function list($exam_question_id, $old = []) {
        $this->filter(1);
        $exam_question = $this->exam_question->find(enc($exam_question_id, 1));

        $this->header = [
            'title' => 'Butir Soal',
            'js_file' => 'eqd',
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
                    'label' => $exam_question['studi'],
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
            'old' => $old,
            'period' => $this->period->find(),
            'study' => $this->study->find(),
        ]);
    }

    public function create($exam_question_id, $old = [])
    {
        $this->filter(1);

        $this->header = [
            'title' => 'Butir Soal',
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
                    'label' => 'Butir Soal',
                    'icon' => 'fa-list',
                    'href' => base_url('app/exam_question_detail'),
                ],
                [
                    'label' => 'Tambah',
                    'icon' => '',
                    'href' => '#',
                ],
            ],
        ];

        $this->load->view('app/exam_question_detail/create', [
            'token' => $this->security->get_csrf_hash(),
        ]);
    }

    private function save_image($base64)
    {
        $this->filter(1);

        $rand = uniqid();
        $img = str_replace('data:image/png;base64,', '', $base64);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);

        // Membuat folder
        $dir = 'upload/img/' . date('Ym') . '/';
        if (!is_dir($dir)) {
            mkdir($dir);
        }

        $filename = $dir . $rand . '.png';

        if (file_put_contents($filename, $data)) {
            return $filename;
        } else {
            return false;
        }
    }

    public function save()
    {
        $this->filter(1);
        $post = $this->input->post('data');

        $content = '';
        foreach ($post['ops'] as $k => $v) {
            if (isset($v['insert']['image'])) {
                $save = $this->save_image($v['insert']['image']);
                $content .= ' ' . $save;
            } else {
                $content .= ' ' . $v['insert'];
            }
        }

        $create = $this->data->save([
            'period_id' => '',
            'study_id' => '',
            'question' => $content,
            'opsi_a' => '',
            'opsi_b' => '',
            'opsi_c' => '',
            'opsi_d' => '',
            'opsi_e' => '',
            'keyword' => '',
        ]);
        
        $create['token'] = $this->security->get_csrf_hash();

        echo json_encode($create);
    }

    public function edit($id, $old = [])
    {
        $this->filter(3);
    }

    public function update()
    {
        $this->filter(3);
    }

    public function delete($id)
    {
        $this->filter(4);
    }

    public function restore($id)
    {
        $this->filter(4);
    }
}
