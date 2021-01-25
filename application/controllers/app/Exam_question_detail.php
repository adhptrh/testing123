<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Exam_question_detail extends MY_Controller
{

    /**
     * Peringatan ! selain fungsi index, create, save, edit, update, delete, dan restore
     * semua function HARUS minimal protected-function
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

    public function list($exam_question_id) {
        $this->filter(1);

        $eid = enc($exam_question_id, 1);
        $exam_question = $this->exam_question->find($eid);

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
            'token' => $this->security->get_csrf_hash(),
            'exam_question' => $this->exam_question->find($eid),
        ]);
    }

    public function reload($id){
        $this->filter(2);
        $exam_question_id = enc($id, 1);

        echo json_encode([
            'data' => $this->data->find(false, [
                'exam_question_id' => $exam_question_id
            ]),
            'base_url' => base_url(),
        ]);
    }

    public function create($exam_question_id)
    {
        $this->filter(1);

        $this->load->view('app/exam_question_detail/create', [
            'token' => $this->security->get_csrf_hash(),
            'master_soal_id' => $exam_question_id,
        ]);
    }

    private function save_image($base64)
    {
        /* Mendefenisikan image
         * menyimpannya di storage
         * Return filename
         */

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

    private function content_creation($data)
    {
        /* Menguraikan kontent
         * jika ada object image kemudian disimpan di storage
         * menggambungkan semua object
         * return content
         */

        $contents = '';
        foreach ($data['ops'] as $k => $v) {
            if (isset($v['insert']['image'])) {
                $save_image = $this->save_image($v['insert']['image']);
                $contents .= ' ' . $save_image;
            } else {
                $contents .= ' ' . $v['insert'];
            }
        }

        return $contents;
    }

    public function save()
    {
        $this->filter(1);
        $post = $this->input->post('data');

        $data = [
            'exam_question_id' => enc($post['master_soal_id'], 1),
            'question' => $this->content_creation($post['soal']),
            'opsi_a' => $this->content_creation($post['opsi_a']),
            'opsi_b' => $this->content_creation($post['opsi_b']),
            'opsi_c' => $this->content_creation($post['opsi_c']),
            'opsi_d' => $this->content_creation($post['opsi_d']),
            'opsi_e' => $this->content_creation($post['opsi_e']),
            'keyword' => $post['keyword'],
        ];

        $create = $this->data->save($data);

        $create['token'] = $this->security->get_csrf_hash();

        echo json_encode($create);
    }

    public function edit($id)
    {
        $this->filter(3);

        $data = $this->data->find(enc($id, 1));

        $this->load->view('app/exam_question_detail/edit', [
            'token' => $this->security->get_csrf_hash(),
            'data' => $data,
        ]);
    }

    public function update()
    {
        $this->filter(3);
        $post = $this->input->post('data');

        $data = [
            'id' => enc($post['id'], 1),
            'exam_question_id' => enc($post['master_soal_id'], 1),
            'question' => $this->content_creation($post['soal']),
            'opsi_a' => $this->content_creation($post['opsi_a']),
            'opsi_b' => $this->content_creation($post['opsi_b']),
            'opsi_c' => $this->content_creation($post['opsi_c']),
            'opsi_d' => $this->content_creation($post['opsi_d']),
            'opsi_e' => $this->content_creation($post['opsi_e']),
            'keyword' => $post['keyword'],
        ];

        $update = $this->data->save($data);

        $update['token'] = $this->security->get_csrf_hash();

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
}
