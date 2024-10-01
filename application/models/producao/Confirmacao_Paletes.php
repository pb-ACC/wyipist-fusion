<?php

use LDAP\Result;

class Confirmacao_Paletes extends CI_Model
{


    public function __construct()
    {
        parent::__construct();
        $this->load->database('default');
        $this->load->helper('url');
        $this->load->library('session');
        //$this->db->reconnect();
    }

    public function save_confirmation ($DocPL,$username,$funcionario_gpac)
    {
        $user=strtoupper($funcionario_gpac);        
        if($user==''){
            $user=strtoupper($username);
        }            
   
        $sql ="UPDATE PlDocs
               SET ValPL=1, DHValPL=getdate(), OpValPL='{$user}'
               where Numero='{$DocPL}'";

        $this->db->query($sql);
        $this->db->close();  
    }
}