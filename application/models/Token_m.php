<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Token_m extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->name = 'exam_schedules';
        $this->alias = 'Jadwal Ujian';
    }

    public function info()
    {
        $this->db->select('NOW() as datetime');
        return $this->db->get()->row_array();
    }

}
