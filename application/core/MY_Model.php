<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_Model extends CI_Model
{

    /**
     * Peringatan ! Jangan mengubah isi model ini
     * Ini adalah mother_class yang akan mengatur hal-hal
     * yang harus ada disetiap semua model
     *
     */

    protected $profile_id;
    public $name, $alias, $rules;

    public function __construct()
    {
        parent::__construct();
    }

    protected function get_profile_id()
    {
        return enc($this->session->userdata('profile')['profile_id'], 1);
    }

    protected function get_profile_level_id()
    {
        return enc($this->session->userdata('profile')['level_id'], 1);
    }

    protected function get_student_grade_id()
    {
        return enc($this->session->userdata('profile')['student_grade_id'], 1);
    }

    protected function get_time()
    {
        return date('Y-m-d H:i:s');
    }

    protected function validate($rules, $data)
    {
        $this->load->library('form_validation');
        $this->form_validation->reset_validation();
        $this->form_validation->set_data($data);
        $this->form_validation->set_rules($rules);
        $this->form_validation->set_message('required', 'Data %s tidak ada');
        $this->form_validation->set_message('greater_than', 'Data {field} harus lebih besar dari {param}');
        $this->form_validation->set_message('is_unique', '{field} sudah terdaftar');

        return $this->form_validation->run();
    }

    public function save($data, $skip_validation = false)
    {
        /*
         * --------------------------------------------------------------------------
         * SAVE
         * --------------------------------------------------------------------------
         *
         * Comment Here
         *
         */

        if (isset($data['id'])) { // Jika update
            $data['updated_at'] = $this->get_time();
            $data['updated_by'] = $this->get_profile_id();

            if ($skip_validation) {
                $this->db->where('id', $data['id']);
                if ($this->db->update($this->name, $data)) {
                    $respon = [
                        'status' => '200',
                        'message' => 'Data ' . $this->alias . ' berhasil disimpan',
                        'id' => enc($data['id']),
                    ];
                }
            } else {
                if ($this->validate($this->rules, $data) == false) {
                    $respon = [
                        'status' => '400',
                        'message' => validation_errors(),
                        'id' => 0,
                    ];
                } else {
                    $this->db->where('id', $data['id']);
                    if ($this->db->update($this->name, $data)) {
                        $respon = [
                            'status' => '200',
                            'message' => 'Data ' . $this->alias . ' berhasil disimpan',
                            'id' => enc($data['id']),
                        ];
                    }
                }
            }

        } else { // Jika insert

            $data['created_at'] = $this->get_time();
            $data['created_by'] = $this->get_profile_id();

            if ($this->validate($this->rules, $data) == false) {
                $respon = [
                    'status' => '400',
                    'message' => validation_errors(),
                    'id' => 0,
                ];
            } else {
                if ($this->db->insert($this->name, $data)) {
                    $respon = [
                        'status' => '200',
                        'message' => 'Data ' . $this->alias . ' berhasil disimpan',
                        'id' => enc($this->db->insert_id()),
                    ];
                }
            }
        }

        return $respon;
    }

    public function save_batch($data, $skip_validation = false)
    {
        /*
         * --------------------------------------------------------------------------
         * SAVE
         * --------------------------------------------------------------------------
         *
         * Comment Here
         *
         */

        if (isset($data['id'])) { // Jika update
            $data['updated_at'] = $this->get_time();
            $data['updated_by'] = $this->get_profile_id();

            if ($skip_validation) {
                // Coming Soon
            } else {
                // Coming Soon
            }

        } else { // Jika insert

            $batch = [];
            foreach ($data as $k => $v) {

                if ($this->validate($this->rules, $v) == false) {
                    $respon = [
                        'status' => '400',
                        'message' => validation_errors(),
                    ];
                    break;
                } else {
                    $respon = [
                        'status' => '200',
                        'message' => 'Data-data ' . $this->alias . ' berhasil divalidasi5',
                    ];

                    // Re Array Data
                    $batch[$k] = $v;
                    $batch[$k]['created_at'] = $this->get_time();
                    $batch[$k]['created_by'] = $this->get_profile_id();
                }
            }

            if ($respon['status'] == '200') {
                if ($this->db->insert_batch($this->name, $batch)) {
                    $respon = [
                        'status' => '200',
                        'message' => 'Data-data ' . $this->alias . ' berhasil disimpan',
                    ];
                }
            }
        }

        return $respon;
    }

    public function delete($id, $hard = 0)
    {
        /*
         * --------------------------------------------------------------------------
         * DELETE
         * --------------------------------------------------------------------------
         *
         * Comment Here
         *
         */

        if ($hard == 0) {
            //Jika sof-tdel
            $delete = $this->db->where('id', enc($id, 1))
                ->update($this->name, [
                    'is_del' => '1',
                    'updated_at' => $this->get_time(),
                    'updated_by' => $this->get_profile_id(),
                ]);
        } else {
            //Jika hard-del
            $delete = $this->db->where('id', enc($id, 1))->delete($this->name);
        }

        if ($delete) {
            return [
                'status' => '200',
                'message' => 'Data ' . $this->alias . ' berhasil dihapus',
            ];
        } else {
            return [
                'status' => '400',
                'message' => 'Data ' . $this->alias . ' tidak berhasil dihapus',
            ];
        }
    }

    public function delete_where($where)
    {
        /*
         * --------------------------------------------------------------------------
         * DELETE berdasaran kondisi-kondisi tertentu
         * --------------------------------------------------------------------------
         *
         * Comment Here
         *
         */

        $delete = $this->db->where($where)
            ->delete($this->name);

        if ($delete) {
            return [
                'status' => '200',
                'message' => 'Data ' . $this->alias . ' berhasil dihapus',
            ];
        } else {
            return [
                'status' => '400',
                'message' => 'Data ' . $this->alias . ' tidak berhasil dihapus',
            ];
        }
    }

    public function replace($data)
    {
        /*
         * --------------------------------------------------------------------------
         * REPLACE berdasaran id
         * --------------------------------------------------------------------------
         *
         * Comment Here
         *
         */

        $data['updated_at'] = $this->get_time();
        $data['updated_by'] = $this->get_profile_id();

        if ($this->validate($this->rules, $data) == false) {
            $respon = [
                'status' => '400',
                'message' => validation_errors(),
            ];
        } else {
            if ($this->db->replace($this->name, $data)) {
                $respon = [
                    'status' => '200',
                    'message' => 'Data ' . $this->alias . ' berhasil direplace',
                ];
            }
        }

        return $respon;
    }

    public function restore($id)
    {
        /*
         * --------------------------------------------------------------------------
         * RESTORE
         * --------------------------------------------------------------------------
         *
         * Comment Here
         *
         */
        $restore = $this->db->where('id', enc($id, 1))
            ->update($this->name, [
                'is_del' => '0',
                'updated_at' => $this->get_time(),
                'updated_by' => $this->get_profile_id(),
            ]);

        if ($restore) {
            return [
                'status' => '200',
                'message' => 'Data ' . $this->alias . ' berhasil diretore',
            ];
        } else {
            return [
                'status' => '400',
                'message' => 'Data ' . $this->alias . ' tidak berhasil diretore',
            ];
        }
    }

}
