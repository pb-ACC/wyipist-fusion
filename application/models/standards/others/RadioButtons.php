<?php

class RadioButtons extends CI_Model
{


    public function __construct()
    {
        parent::__construct();
        $this->load->database('default');
        $this->load->helper('url');
        $this->load->library('session');
        //$this->db->reconnect();
    }

    function escolha_setores_empresa($empresa,$user){
        $value='';
        if($empresa == 2){
            $value='<div class="form-check">'.
                    '<input class="form-check-input" type="radio" name="radio1" value="1" style="width: 20px;height: 20px;" checked>'.
                    '<label class="form-check-label" style="margin-left: 15px;font-size: 18px;">Armazém Fábrica</label>'.
                    '</div>'.
                    '<div class="form-check">'.
                    '<input class="form-check-input" type="radio" name="radio2" value="2" style="width: 20px;height: 20px;">'.
                    '<label class="form-check-label" style="margin-left: 15px;font-size: 18px;">Centro Logístico</label>'.
                    '</div>'.
                    '<div class="form-check">'.
                    '<input class="form-check-input" type="radio" name="radio3" value="3" style="width: 20px;height: 20px;">'.
                    '<label class="form-check-label" style="margin-left: 15px;font-size: 18px;">Produto com Defeito - Armazém</label>'.
                    '</div>';    
        }
        return $value;
    }

}