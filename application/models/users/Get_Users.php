<?php

class Get_Users extends CI_Model
{


    public function __construct()
    {
        parent::__construct();
        $this->load->database('login');
        $this->load->helper('url');
        $this->load->library('session');
        //$this->db->reconnect();
    }

    public function getUsers(){

       $sql="SELECT A.id, A.username, A.Nome,A.email, A.telefone, C.type, A.user_type type_id, A.funcionario_gpac, A.password passwd, A.client 
             from users A join clients      B on (A.client=B.id) 
                          join client_types C on (A.user_type=C.id) 
             where A.active=1 and A.client<>3";
        $query = $this->db->query($sql);
        $result = $query->result();
        //print_r($result);
        return $result;
    }
}