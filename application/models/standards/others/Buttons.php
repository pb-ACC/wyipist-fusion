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
                   <button id="choose_palets" onclick="choose_palets()" type="button" class="btn btn-dark" style="width:inherit;margin-left: 5px;margin-right: 5px;margin-bottom: 5px;"> Picar Palete</button>
                   </div>
                   <div class="col-sm-12 col-sm-push-3 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
                   <button onclick="send_to_warehouse()" type="button" class="btn btn-secondary" style="width:inherit;margin-left: 5px;margin-right: 5px;margin-bottom: 5px;"> Enviar Armazém</button>
                   </div> 
                   <div class="col-sm-12 col-sm-push-3 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
                   <button id="amostras" onclick="send_to_samples()" type="button" class="btn" style="background-color:#b05574; color:white;border-color:#9c4c68; width:inherit;margin-left: 5px;margin-right: 5px;margin-bottom: 5px;"> Enviar Armazém Amostras</button>
                   </div> 
                   </div>';
         }
         else if($empresa == 2){
            $value='<div class="row">        
                    <div class="col-sm-12 col-sm-push-3 col-xs-12 col-md-3 col-md-push-3 col-lg-3 col-lg-push-3">
                    <button id="choose_palets" onclick="choose_palets()" type="button" class="btn btn-dark" style="width:inherit;margin-left: 5px;margin-right: 5px;margin-bottom: 5px;"> Picar Palete</button>
                    </div>
                    <div class="col-sm-12 col-sm-push-3 col-xs-12 col-md-3 col-md-push-3 col-lg-3 col-lg-push-3">
                    <button onclick="send_to_factory()" type="button" class="btn btn-info" style="width:inherit;margin-left: 5px;margin-right: 5px;margin-bottom: 5px;"> Enviar Armazém Fábrica</button>
                    </div>
                    <div class="col-sm-12 col-sm-push-3 col-xs-12 col-md-3 col-md-push-3 col-lg-3 col-lg-push-3">
                    <button onclick="send_to_logistic()" type="button" class="btn btn-light" style="background-color:teal; color:white; border-color:teal; width:inherit;margin-left: 5px;margin-right: 5px;margin-bottom: 5px;"> Enviar Centro Logístico</button>
                    </div> 
                    <div class="col-sm-12 col-sm-push-3 col-xs-12 col-md-3 col-md-push-3 col-lg-3 col-lg-push-3">
                    <button id="amostras" onclick="send_to_samples()" type="button" class="btn" style="background-color:#b05574; color:white;border-color:#9c4c68; width:inherit;margin-left: 5px;margin-right: 5px;margin-bottom: 5px;"> Enviar Armazém Amostras</button>
                    </div> 
                    </div>';
        }      
        else if($empresa == 3 || $empresa == 4){
            $value='<div class="row">        
                   <div class="col-sm-12 col-sm-push-3 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
                   <button id="choose_palets" onclick="choose_palets()" type="button" class="btn btn-dark" style="width:inherit;margin-left: 5px;margin-right: 5px;margin-bottom: 5px;"> Picar Palete</button>
                   </div>
                   <div class="col-sm-12 col-sm-push-3 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
                   <button onclick="send_to_warehouse()" type="button" class="btn btn-secondary" style="width:inherit;margin-left: 5px;margin-right: 5px;margin-bottom: 5px;"> Enviar Armazém</button>
                   </div> 
                   <div class="col-sm-12 col-sm-push-3 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
                   <button id="amostras" onclick="send_to_samples()" type="button" class="btn" style="background-color:#b05574; color:white;border-color:#9c4c68; width:inherit;margin-left: 5px;margin-right: 5px;margin-bottom: 5px;"> Enviar Armazém Amostras</button>
                   </div> 
                   </div>';
         }
        return $value;
    }

    public function buttons_empresa_anular_palete(){
        //echo $empresa.'sdfvsdv';
        $value='<div class="row">        
                   <div class="col-sm-12 col-sm-push-3 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
                   <button id="choose_palets" onclick="choose_palets()" type="button" class="btn btn-dark" style="width:inherit;margin-left: 5px;margin-right: 5px;margin-bottom: 5px;"> Picar Palete</button>
                   </div>
                   <div class="col-sm-12 col-sm-push-3 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
                   <button onclick="cancel_palette()" type="button" class="btn btn-success" style="width:inherit;margin-left: 5px;margin-right: 5px;margin-bottom: 5px;"> Continuar</button>
                   </div> 
                   </div>';
        return $value;
    }
}