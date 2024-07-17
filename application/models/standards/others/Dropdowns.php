<?php

class Dropdowns extends CI_Model
{


    public function __construct()
    {
        parent::__construct();
        $this->load->database('default');
        $this->load->helper('url');
        $this->load->library('session');
        //$this->db->reconnect();
    }

    function escolha_empresa($empresa){
        $value='';
        if($empresa == 3){
            $value='<select id="empresasDP" onchange="changeEmpresa()" class="form-control">'.
                   '<option selected="selected">Ceragni</option>'.
                   '<option>Certeca</option>'.
                   '</select>';
         }
        return $value;
    }

    public function motivo_erro_palete(){
        $sql="SELECT Codigo, Descricao
              FROM MotivoErroPalete";     
        $query = $this->db->query($sql);
        $result = $query->result();
        return $result;
    }

}