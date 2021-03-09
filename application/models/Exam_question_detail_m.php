<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Exam_question_detail_m extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->name = 'exam_question_extend_details';
        $this->alias = 'Butir Soal';

        $this->rules = [
            [
                'field' => 'exam_question_id',
                'label' => 'Soal ID',
                'rules' => 'required',
            ],
            [
                'field' => 'question',
                'label' => 'Pertanyaan',
                'rules' => 'required',
            ],
            [
                'field' => 'opsi_a',
                'label' => 'Pilihan A',
                'rules' => 'required',
            ],
            [
                'field' => 'opsi_b',
                'label' => 'Pilihan B',
                'rules' => 'required',
            ],
            [
                'field' => 'opsi_c',
                'label' => 'Pilihan C',
                'rules' => 'required',
            ],
            [
                'field' => 'opsi_d',
                'label' => 'Pilihan D',
                'rules' => 'required',
            ],
            [
                'field' => 'opsi_e',
                'label' => 'Pilihan E',
                'rules' => 'required',
            ],
            [
                'field' => 'keyword',
                'label' => 'Kunci Jawaban',
                'rules' => 'required',
            ],
        ];
    }

    public function find_for_student_id_only($id = false, $conditions = false, $show_del = false, $selected_id = 0, $limit = false)
    {
        $this->db->select('a.id')
            ->from($this->name . ' a')
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

            return $data;

        } else { // Jika cari semua
            if ($conditions) {
                $this->db->where($conditions);
            }

            if ($limit) {
                $this->db->limit($limit);
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
            }

            return $data;
        }
    }

    public function find_for_student_details($id = false, $conditions = false, $show_del = false, $selected_id = 0)
    {
        $this->db->select('a.id, a.question, a.opsi_a, a.opsi_b, a.opsi_c, a.opsi_d, a.opsi_e')
            // ->select('b.name created_by, DATE_FORMAT(a.created_at, "%d-%m-%Y") created_at')
            // ->select('c.name updated_by, DATE_FORMAT(a.updated_at, "%d-%m-%Y") updated_at')
            ->from($this->name . ' a')
            // ->join('z_profiles b', 'b.id = a.created_by', 'left')
            // ->join('z_profiles c', 'c.id = a.updated_by', 'left')
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
            }

            return $data;
        }
    }

    public function find($id = false, $conditions = false, $show_del = false, $selected_id = 0)
    {
        $this->db->select('a.id, a.exam_question_id, a.question, a.opsi_a, a.opsi_b, a.opsi_c, a.opsi_d, a.opsi_e, a.keyword, a.is_del')
            ->select('b.name created_by, DATE_FORMAT(a.created_at, "%d-%m-%Y") created_at')
            ->select('c.name updated_by, DATE_FORMAT(a.updated_at, "%d-%m-%Y") updated_at')
            ->from($this->name . ' a')
            ->join('z_profiles b', 'b.id = a.created_by', 'left')
            ->join('z_profiles c', 'c.id = a.updated_by', 'left')
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
                $data[$k]['exam_question_id'] = enc($v['exam_question_id']);
            }

            return $data;
        }
    }

    public function str_to_quill($str)
    {
        $str_length = strlen($str);
        $count_of_pos = substr_count($str, '.png');
        $pos_image = [];
        $pos_text = [];
        $pos_all = [];

        // Mencari posisi2 awal image
        $pos_current = 0;
        for ($i = 0; $i < $count_of_pos; $i++) {
            $pos1 = strpos($str, "upload/img", $pos_current);
            $pos_image[$i]['start'] = $pos1;
            $pos_current += ($pos1 + 1);
        }

        // Mencari posisi2 akhir image
        $pos_current = 0;
        for ($i = 0; $i < $count_of_pos; $i++) {
            $pos1 = strpos($str, ".png", $pos_current);
            $pos_image[$i]['end'] = ($pos1 + 4);
            $pos_current += ($pos1 + 1);
        }

        // Concat all item
        if (count($pos_image) == 0) { // Jika gak ada image

            // Insert text
            $pos_all[0]['start'] = 0;
            $pos_all[0]['end'] = $str_length;
            $pos_all[0]['type'] = 0;

        } elseif (count($pos_image) == 1) { // jika hanya ada 1 image
            foreach ($pos_image as $k => $v) {
                if ($v['start'] == 0) { // Jika pas sebelumnya gak ada text
                    if ($v['end'] < $str_length) { // Jika setelahnya masih ada content
                        // Insert image
                        $n = count($pos_all);
                        $pos_all[$n]['start'] = $v['start'];
                        $pos_all[$n]['end'] = $v['end'];
                        $pos_all[$n]['type'] = 1;

                        // Insert text
                        $pos_all[$n + 1]['start'] = ($v['end'] + 1);
                        $pos_all[$n + 1]['end'] = $str_length;
                        $pos_all[$n + 1]['type'] = 0;

                    } else { // Jika setelahnya tidak ada content
                        // Insert image
                        $n = count($pos_all);
                        $pos_all[$n]['start'] = $v['start'];
                        $pos_all[$n]['end'] = $v['end'];
                        $pos_all[$n]['type'] = 1;
                    }
                } else { // Jika pas sebelumnya ada text
                    if ($v['end'] < $str_length) { // Jika setelahnya masih ada content
                        // Insert text
                        $n = count($pos_all);
                        $pos_all[$n]['start'] = 0;
                        $pos_all[$n]['end'] = ($v['start'] - 1);
                        $pos_all[$n]['type'] = 0;

                        // Insert image
                        $pos_all[$n + 1]['start'] = $v['start'];
                        $pos_all[$n + 1]['end'] = $v['end'];
                        $pos_all[$n + 1]['type'] = 1;

                        // Insert text
                        $pos_all[$n + 2]['start'] = ($v['end'] + 1);
                        $pos_all[$n + 2]['end'] = $str_length;
                        $pos_all[$n + 2]['type'] = 0;

                    } else { // Jika setelahnya tidak ada content
                        // Insert text
                        $n = count($pos_all);
                        $pos_all[$n]['start'] = 0;
                        $pos_all[$n]['end'] = ($v['start'] - 1);
                        $pos_all[$n]['type'] = 0;

                        // Insert image
                        $pos_all[$n + 1]['start'] = $v['start'];
                        $pos_all[$n + 1]['end'] = $v['end'];
                        $pos_all[$n + 1]['type'] = 1;
                    }
                }
            }
        } elseif (count($pos_image) > 1) { // Jika ada banyak image
            $end_pos_image = end($pos_image);
            foreach ($pos_image as $k => $v) {
                if ($k == 0) { // Jika yang pertama
                    if ($v['start'] == 0) { // Posisi image mengawali semua content

                        // Insert image
                        $pos_all[$k]['start'] = $v['start'];
                        $pos_all[$k]['end'] = $v['end'];
                        $pos_all[$k]['type'] = 1;

                    } else { // Posisi image setelah content text
                        // Insert text
                        $pos_all[$k]['start'] = 0;
                        $pos_all[$k]['end'] = ($v['start'] - 1);
                        $pos_all[$k]['type'] = 0;

                        // Insert image
                        $pos_all[$k + 1]['start'] = $v['start'];
                        $pos_all[$k + 1]['end'] = $v['end'];
                        $pos_all[$k + 1]['type'] = 1;
                    }
                } elseif ($v['start'] != $end_pos_image['start']) { // Jika yang selanjutnya tetapi bukan yang terakhir

                    if (($v['start'] - 1) == $pos_image[$k - 1]['end']) { // Jika pas sebelumnya ada image
                        // Insert image
                        $n = count($pos_all);
                        $pos_all[$n]['start'] = $v['start'];
                        $pos_all[$n]['end'] = $v['end'];
                        $pos_all[$n]['type'] = 1;

                    } else { // Jika pas sebelumnya gak ada image
                        // Insert text
                        $n = count($pos_all);
                        $pos_all[$n]['start'] = ($pos_all[$n - 1]['end'] + 1);
                        $pos_all[$n]['end'] = ($v['start'] - 1);
                        $pos_all[$n]['type'] = 0;

                        // Insert image
                        $pos_all[$n + 1]['start'] = $v['start'];
                        $pos_all[$n + 1]['end'] = $v['end'];
                        $pos_all[$n + 1]['type'] = 1;
                    }
                } else { // JIka yang terakhir

                    if (($v['start'] - 1) == $pos_image[$k - 1]['end']) { // Jika pas sebelumnya ada image
                        if ($v['end'] < $str_length) { // Jika setelahnya masih ada content
                            // Insert image
                            $n = count($pos_all);
                            $pos_all[$n]['start'] = $v['start'];
                            $pos_all[$n]['end'] = $v['end'];
                            $pos_all[$n]['type'] = 1;

                            // Insert text
                            $pos_all[$n + 1]['start'] = ($v['end'] + 1);
                            $pos_all[$n + 1]['end'] = $str_length;
                            $pos_all[$n + 1]['type'] = 0;

                        } else { // Jika setelahnya tidak ada content
                            // Insert image
                            $n = count($pos_all);
                            $pos_all[$n]['start'] = $v['start'];
                            $pos_all[$n]['end'] = $v['end'];
                            $pos_all[$n]['type'] = 1;
                        }
                    } else { // Jika pas sebelumnya gak ada image
                        if ($v['end'] < $str_length) { // Jika setelahnya masih ada content
                            // Insert text
                            $n = count($pos_all);
                            $pos_all[$n]['start'] = ($pos_all[$n - 1]['end'] + 1);
                            $pos_all[$n]['end'] = ($v['start'] - 1);
                            $pos_all[$n]['type'] = 0;

                            // Insert image
                            $pos_all[$n + 1]['start'] = $v['start'];
                            $pos_all[$n + 1]['end'] = $v['end'];
                            $pos_all[$n + 1]['type'] = 1;

                            // Insert text
                            $pos_all[$n + 2]['start'] = ($v['end'] + 1);
                            $pos_all[$n + 2]['end'] = $str_length;
                            $pos_all[$n + 2]['type'] = 0;

                        } else { // Jika setelahnya tidak ada content
                            // Insert text
                            $n = count($pos_all);
                            $pos_all[$n]['start'] = ($pos_all[$n]['end'] + 1);
                            $pos_all[$n]['end'] = ($v['start'] - 1);
                            $pos_all[$n]['type'] = 0;

                            // Insert image
                            $pos_all[$n + 1]['start'] = $v['start'];
                            $pos_all[$n + 1]['end'] = $v['end'];
                            $pos_all[$n + 1]['type'] = 1;
                        }
                    }
                }
            }
        }

        // Concate all item
        $content = [];
        foreach ($pos_all as $k => $v) {
            // $pos_all[$k]['content'] = substr($str, $v['start'], ($v['end'] - $v['start']));

            if ($v['type'] == 0) {
                $data = [
                    'insert' => substr($str, $v['start'], ($v['end'] - $v['start'])),
                ];
            } else {
                // $base64 = $this->img_to_base64(base_url(substr($str, $v['start'], ($v['end'] - $v['start']))));
                $base64 = $this->img_to_base64('./' . substr($str, $v['start'], ($v['end'] - $v['start'])));
                $data = [
                    'insert' => [
                        'image' => $base64,
                    ],
                ];
            }

            $content[$k] = $data;
        }

        return $content;
    }

    private function img_to_base64($path)
    {
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }

}
