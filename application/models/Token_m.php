<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Token_m extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->name = 'token';
        $this->alias = 'Token';
    }

    public function get()
    {
        $this->db->select('a.token')->from($this->name . ' a');
        $data = $this->db->get()->row_array();
        return $data['token'];
    }

    public function info()
    {
        $this->db->select('NOW() as datetime');
        return $this->db->get()->row_array();
    }

}
