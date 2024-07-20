<?php

class Buttons extends CI_Model
{


    public function __construct()
    {
        parent::__construct();
        $this->load->database('default');
        $this->load->helper('url');
        $this->load->library('session');
        //$this->db->reconnect();
    }

    public function buttons_empresa($empresa){
        //echo $empresa.'sdfvsdv';
        $value='';
        if($empresa == 1){
            $value='<div class="row">        
                   <div class="col-sm-12 col-sm-push-3 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
                   <button id="choose_palets" onclick="choose_palets()" type="button" class="btn btn-secondary" style="width:inherit;margin-left: 5px;margin-right: 5px;margin-bottom: 5px;"> Picar Palete</button>
                   </div>
                   <div class="col-sm-12 col-sm-push-3 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
                   <button onclick="send_to_warehouse()" type="button" class="btn btn-warning" style="width:inherit;margin-left: 5px;margin-right: 5px;margin-bottom: 5px;"> Enviar Armazém</button>
                   </div> 
                   </div>';
         }
         else if($empresa == 2){
            $value='<div class="row">        
                    <div class="col-sm-12 col-sm-push-3 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
                    <button id="choose_palets" onclick="choose_palets()" type="button" class="btn btn-secondary" style="width:inherit;margin-left: 5px;margin-right: 5px;margin-bottom: 5px;"> Picar Palete</button>
                    </div>
                    <div class="col-sm-12 col-sm-push-3 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
                    <button onclick="send_to_factory()" type="button" class="btn btn-info" style="width:inherit;margin-left: 5px;margin-right: 5px;margin-bottom: 5px;"> Enviar Armazém Fábrica</button>
                    </div>
                    <div class="col-sm-12 col-sm-push-3 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
                    <button onclick="send_to_logistic()" type="button" class="btn btn-primary" style="width:inherit;margin-left: 5px;margin-right: 5px;margin-bottom: 5px;"> Enviar Centro Logístico</button>
                    </div> 
                    </div>';
        }      
        else if($empresa == 3 || $empresa == 4){
            $value='<div class="row">        
                   <div class="col-sm-12 col-sm-push-3 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
                   <button id="choose_palets" onclick="choose_palets()" type="button" class="btn btn-secondary" style="width:inherit;margin-left: 5px;margin-right: 5px;margin-bottom: 5px;"> Picar Palete</button>
                   </div>
                   <div class="col-sm-12 col-sm-push-3 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
                   <button onclick="send_to_warehouse()" type="button" class="btn btn-warning" style="width:inherit;margin-left: 5px;margin-right: 5px;margin-bottom: 5px;"> Enviar Armazém</button>
                   </div> 
                   </div>';
         }
        return $value;
    }

    public function buttons_empresa_anular_palete(){
        //echo $empresa.'sdfvsdv';
        $value='<div class="row">        
                   <div class="col-sm-12 col-sm-push-3 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
                   <button id="choose_palets" onclick="choose_palets()" type="button" class="btn btn-secondary" style="width:inherit;margin-left: 5px;margin-right: 5px;margin-bottom: 5px;"> Picar Palete</button>
                   </div>
                   <div class="col-sm-12 col-sm-push-3 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
                   <button onclick="cancel_palette()" type="button" class="btn btn-success" style="width:inherit;margin-left: 5px;margin-right: 5px;margin-bottom: 5px;"> Confirmar</button>
                   </div> 
                   </div>';
        return $value;
    }
}