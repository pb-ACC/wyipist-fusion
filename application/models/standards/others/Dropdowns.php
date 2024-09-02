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
        if($empresa == 3 || $empresa == 4){
            $value='<select id="empresasDP" onchange="changeEmpresa()" class="form-control">'.
                   '<option selected="selected">Ceragni</option>'.
                   '<option>Certeca</option>'.
                   '</select>';
         }
        return $value;
    }

    function escolha_setores_empresa($empresa,$user){
        $value='';
        if($empresa == 2 && $user == 5){
            $value='<div class="form-check">'.
                    '<input class="form-check-input" type="radio" name="radio1" value="1" style="width: 20px;height: 20px;" checked>'.
                    '<label class="form-check-label" style="margin-left: 15px;font-size: 18px;">Armazém Fábrica</label>'.
                    '</div>'.
                    '<div class="form-check">'.
                    '<input class="form-check-input" type="radio" name="radio2" value="2" style="width: 20px;height: 20px;" disabled>'.
                    '<label class="form-check-label" style="margin-left: 15px;font-size: 18px;">Centro Logístico</label>'.
                    '</div>';    
         }
         else if($empresa == 2 && $user == 6){
            $value='<div class="form-check">'.
                    '<input class="form-check-input" type="radio" name="radio1" value="1" style="width: 20px;height: 20px;" disabled>'.
                    '<label class="form-check-label" style="margin-left: 15px;font-size: 18px;">Armazém Fábrica</label>'.
                    '</div>'.
                    '<div class="form-check">'.
                    '<input class="form-check-input" type="radio" name="radio2" value="2" style="width: 20px;height: 20px;" checked>'.
                    '<label class="form-check-label" style="margin-left: 15px;font-size: 18px;">Centro Logístico</label>'.
                    '</div>';     
        }
        else if($empresa == 2 && ($user == 1 || $user == 2)){
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

    public function motivo_erro_palete(){
        $sql="SELECT Codigo, Descricao
              FROM MotivoErroPalete";     
        $query = $this->db->query($sql);
        $result = $query->result();
        return $result;
    }

    public function motivo_anula_palete(){
        $sql="SELECT Codigo, Descricao
              FROM zxAnulaPL";        
        $query = $this->db->query($sql);
        $result = $query->result();
        return $result;
    }

    public function setores_empresa($empresa){                
        $sql="SELECT Codigo, Descricao
              FROM Sectores
              where Empresa='{$empresa}'";        
        $query = $this->db->query($sql);
        $result = $query->result();
        return $result;
    }

    public function motivo_stock(){

        $sql = "SELECT cast('.' as varchar(1)) x, cast(0 as bit) Sel, Codigo, Descricao, cast('' as varchar(255)) Observacao, cast(row_number() over(order by Codigo) as int) Ordem
                from zx_MotivosStkAfe";
        $query = $this->db->query($sql);
        $result = $query->result();
        return $result;
    }

}