<?php

class EtiquetaPL extends CI_Model
{


    public function __construct()
    {
        parent::__construct();
        $this->load->database('login');
        $this->load->helper('url');
        $this->load->library('session');
        //$this->db->reconnect();
    }

    public function get_report($tipodoc)
    {
        $sql = "SELECT text
                FROM content_html
                where doc_type='{$tipodoc}'";
        //echo $sql;
        $query = $this->db->query($sql);        
        $result = $query->result();
        
        return $result;
    }

}