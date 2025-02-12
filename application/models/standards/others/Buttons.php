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
                   <button onclick="send_to_warehouse()" type="button" class="btn btn-secondary" style="width:inherit;margin-left: 5px;margin-right: 5px;margin-bottom: 5px;"> Enviar Arm.</button>
                   </div> 
                   <div class="col-sm-12 col-sm-push-3 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
                   <button id="amostras" onclick="send_to_samples(this.getAttribute(\'data-id\'))" type="button" class="btn" style="background-color:#b05574; color:white; border-color:#9c4c68; width:inherit; margin-left: 5px; margin-right: 5px; margin-bottom: 5px;" data-id="ST888">Enviar Arm. Amostras</button>
                   </div> 
                   </div>';
         }
         else if($empresa == 2){
            $value = '<div class="row">        
                        <div class="col-12 col-md-3 col-lg-3">
                            <button id="choose_palets" onclick="choose_palets()" type="button" class="btn btn-dark" style="width:inherit; margin-left: 5px; margin-right: 5px; margin-bottom: 5px;">Picar Palete</button>
                        </div>
                        <div class="col-12 col-md-3 col-lg-3">
                            <button onclick="send_to_factory()" type="button" class="btn btn-info" style="width:inherit; margin-left: 5px; margin-right: 5px; margin-bottom: 5px;">Enviar Arm. Fábrica</button>
                        </div>
                        <div class="col-12 col-md-3 col-lg-3">
                            <button onclick="send_to_logistic()" type="button" class="btn btn-light" style="background-color:teal; color:white; border-color:teal; width:inherit; margin-left: 5px; margin-right: 5px; margin-bottom: 5px;">Enviar Centro Logístico</button>
                        </div> 
                        <div class="col-12 col-md-3 col-lg-3">
                            <button id="amostras" onclick="send_to_samples(this.getAttribute(\'data-id\'))" type="button" class="btn" style="background-color:#b05574; color:white; border-color:#9c4c68; width:inherit; margin-left: 5px; margin-right: 5px; margin-bottom: 5px;" data-id="CL888">Enviar Arm. Amostras</button>
                        </div> 
                    </div>';
        
        }      
        else if($empresa == 3 || $empresa == 4){
            $value='<div class="row">        
                   <div class="col-sm-12 col-sm-push-3 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
                   <button id="choose_palets" onclick="choose_palets()" type="button" class="btn btn-dark" style="width:inherit;margin-left: 5px;margin-right: 5px;margin-bottom: 5px;"> Picar Palete</button>
                   </div>
                   <div class="col-sm-12 col-sm-push-3 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
                   <button onclick="send_to_warehouse()" type="button" class="btn btn-secondary" style="width:inherit;margin-left: 5px;margin-right: 5px;margin-bottom: 5px;"> Enviar Arm.</button>
                   </div> 
                   <div class="col-sm-12 col-sm-push-3 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
                   <button id="amostras" onclick="send_to_samples(this.getAttribute(\'data-id\'))" type="button" class="btn" style="background-color:#b05574; color:white; border-color:#9c4c68; width:inherit; margin-left: 5px; margin-right: 5px; margin-bottom: 5px;" data-id="ST888">Enviar Arm. Amostras</button>
                   </div> 
                   </div>';
         }
        return $value;
    }

    public function buttons_empresa_movimentacao($empresa){
        //echo $empresa.'sdfvsdv';
        $value='';
        if($empresa == 1){
            $value='<div class="row">        
                   <div class="col-sm-12 col-sm-push-3 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
                   <button id="choose_palets" onclick="choose_palets()" type="button" class="btn btn-dark" style="width:inherit;margin-left: 5px;margin-right: 5px;margin-bottom: 5px;"> Picar Palete</button>
                   </div>
                   <div class="col-sm-12 col-sm-push-3 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
                   <button onclick="send_to_warehouse()" type="button" class="btn btn-secondary" style="width:inherit;margin-left: 5px;margin-right: 5px;margin-bottom: 5px;"> Enviar Arm.</button>
                   </div> 
                   <div class="col-sm-12 col-sm-push-3 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
                   <button id="amostras" onclick="send_to_samples(this.getAttribute(\'data-id\'))" type="button" class="btn" style="background-color:#b05574; color:white; border-color:#9c4c68; width:inherit; margin-left: 5px; margin-right: 5px; margin-bottom: 5px;" data-id="ST888">Enviar Arm. Amostras</button>
                   </div> 

                    <div class="col-sm-12 col-sm-push-3 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
                    <button id="desclas" onclick="send_to_disqualified(this.getAttribute(\'data-id\'))" type="button" class="btn" style="background-color:#b6a3f7; color:white; border-color:#A99ED6; width:inherit; margin-left: 5px; margin-right: 5px; margin-bottom: 5px;" data-id="ST777">Enviar Arm. Mat. Desclass.</button>
                    </div> 
                    <div class="col-sm-12 col-sm-push-3 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
                    <button id="reclama" onclick="send_to_complaints(this.getAttribute(\'data-id\'))" type="button" class="btn" style="background-color:#dabf90; color:white; border-color:#C1B88A; width:inherit; margin-left: 5px; margin-right: 5px; margin-bottom: 5px;" data-id="ST666">Enviar Arm. Reclamações</button>
                    </div> 
                    <div class="col-sm-12 col-sm-push-3 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
                    <button id="rehabCG" onclick="send_to_rehabilitates(this.getAttribute(\'data-id\'))" type="button" class="btn" style="background-color:#7f4f24; color:white; border-color:#6f441f; width:inherit; margin-left: 5px; margin-right: 5px; margin-bottom: 5px;" data-id="ST300">Enviar Arm. Reabilitado</button>
                    </div> 

                   </div>';
         }
         else if($empresa == 2){
            $value = '<div class="row">        
                        <div class="col-sm-12 col-sm-push-2 col-xs-12 col-md-2 col-md-push-2 col-lg-2 col-lg-push-2">
                            <button id="choose_palets" onclick="choose_palets()" type="button" class="btn btn-dark" style="width:inherit; margin-left: 5px; margin-right: 5px; margin-bottom: 5px;">Picar Palete</button>
                        </div>
                        <div class="col-sm-12 col-sm-push-2 col-xs-12 col-md-2 col-md-push-2 col-lg-2 col-lg-push-2">
                            <button onclick="send_to_factory()" type="button" class="btn btn-info" style="width:inherit; margin-left: 5px; margin-right: 5px; margin-bottom: 5px;">Enviar Arm. Fábrica</button>
                        </div>
                        <div class="col-sm-12 col-sm-push-2 col-xs-12 col-md-2 col-md-push-2 col-lg-2 col-lg-push-2">
                            <button onclick="send_to_logistic()" type="button" class="btn btn-light" style="background-color:teal; color:white; border-color:teal; width:inherit; margin-left: 5px; margin-right: 5px; margin-bottom: 5px;">Enviar Centro Logístico</button>
                        </div> 
                        <div class="col-sm-12 col-sm-push-2 col-xs-12 col-md-2 col-md-push-2 col-lg-2 col-lg-push-2">
                            <button id="amostras" onclick="send_to_samples(this.getAttribute(\'data-id\'))" type="button" class="btn" style="background-color:#b05574; color:white; border-color:#9c4c68; width:inherit; margin-left: 5px; margin-right: 5px; margin-bottom: 5px;" data-id="CL888">Enviar Arm. Amostras</button>
                        </div>                                                  
                        <div class="col-sm-12 col-sm-push-2 col-xs-12 col-md-2 col-md-push-2 col-lg-2 col-lg-push-2">
                            <button id="desclas" onclick="send_to_disqualified_cl(this.getAttribute(\'data-id\'))" type="button" class="btn" style="background-color:#b6a3f7; color:white; border-color:#A99ED6; width:inherit; margin-left: 5px; margin-right: 5px; margin-bottom: 5px;" data-id="CL777">Enviar Arm. Mat. Desclass.</button>
                        </div> 
                        <div class="col-sm-12 col-sm-push-2 col-xs-12 col-md-2 col-md-push-2 col-lg-2 col-lg-push-2">
                            <button id="reclama" onclick="send_to_complaints(this.getAttribute(\'data-id\'))" type="button" class="btn" style="background-color:#dabf90; color:white; border-color:#C1B88A; width:inherit; margin-left: 5px; margin-right: 5px; margin-bottom: 5px;" data-id="CL666">Enviar Arm. Reclamações</button>
                        </div> 
                        <div class="col-sm-12 col-sm-push-4 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
                            <button id="divergeFB" onclick="send_to_divergences_fb(this.getAttribute(\'data-id\'))" type="button" class="btn" style="background-color:#FF7F50; color:white; border-color:#D15F40; width:inherit; margin-left: 5px; margin-right: 5px; margin-bottom: 5px;" data-id="ST554">Enviar Arm. Diverg. - Fábrica</button>
                        </div>
                        <div class="col-sm-12 col-sm-push-4 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
                            <button id="divergeCL" onclick="send_to_divergences_cl(this.getAttribute(\'data-id\'))" type="button" class="btn" style="background-color:#FF7F50; color:white; border-color:#D15F40; width:inherit; margin-left: 5px; margin-right: 5px; margin-bottom: 5px;" data-id="ST555">Enviar Arm. Diverg. - CL</button>
                        </div>
                        <div class="col-sm-12 col-sm-push-4 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
                            <button id="rehabCT" onclick="send_to_rehabilitates(this.getAttribute(\'data-id\'))" type="button" class="btn" style="background-color:#7f4f24; color:white; border-color:#6f441f; width:inherit; margin-left: 5px; margin-right: 5px; margin-bottom: 5px;" data-id="FB008">Enviar Arm. Reabilitado</button>
                        </div>
                        
                    </div>';
        
        }      
        else if($empresa == 3 || $empresa == 4){
            $value='<div class="row">        
                   <div class="col-sm-12 col-sm-push-3 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
                   <button id="choose_palets" onclick="choose_palets()" type="button" class="btn btn-dark" style="width:inherit;margin-left: 5px;margin-right: 5px;margin-bottom: 5px;"> Picar Palete</button>
                   </div>
                   <div class="col-sm-12 col-sm-push-3 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
                   <button onclick="send_to_warehouse()" type="button" class="btn btn-secondary" style="width:inherit;margin-left: 5px;margin-right: 5px;margin-bottom: 5px;"> Enviar Arm.</button>
                   </div> 
                   <div class="col-sm-12 col-sm-push-3 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
                   <button id="amostras" onclick="send_to_samples(this.getAttribute(\'data-id\'))" type="button" class="btn" style="background-color:#b05574; color:white; border-color:#9c4c68; width:inherit; margin-left: 5px; margin-right: 5px; margin-bottom: 5px;" data-id="ST888">Enviar Arm. Amostras</button>
                   </div> 

                    <div class="col-sm-12 col-sm-push-3 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
                    <button id="desclas" onclick="send_to_disqualified(this.getAttribute(\'data-id\'))" type="button" class="btn" style="background-color:#b6a3f7; color:white; border-color:#A99ED6; width:inherit; margin-left: 5px; margin-right: 5px; margin-bottom: 5px;" data-id="CL888">Enviar Arm. Mat. Desclass.</button>
                    </div> 
                    <div class="col-sm-12 col-sm-push-3 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
                    <button id="reclama" onclick="send_to_complaints(this.getAttribute(\'data-id\'))" type="button" class="btn" style="background-color:#dabf90; color:white; border-color:#C1B88A; width:inherit; margin-left: 5px; margin-right: 5px; margin-bottom: 5px;" data-id="CL888">Enviar Arm. Reclamações</button>
                    </div> 
                    <div class="col-sm-12 col-sm-push-3 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
                    <button id="rehabCG" onclick="send_to_rehabilitates(this.getAttribute(\'data-id\'))" type="button" class="btn" style="background-color:#7f4f24; color:white; border-color:#6f441f; width:inherit; margin-left: 5px; margin-right: 5px; margin-bottom: 5px;" data-id="ST300">Enviar Arm. Reabilitado</button>
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

    public function buttons_empresa_confirmar_palete($parm){
        //echo $empresa.'sdfvsdv';
        $value='<div class="row">        
                   <div class="col-sm-12 col-sm-push-3 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
                   <button id="choose_palets" onclick="choose_palets()" type="button" class="btn btn-dark" style="width:inherit;margin-left: 5px;margin-right: 5px;margin-bottom: 5px;"> Picar Palete</button>
                   </div>
                   <div class="col-sm-12 col-sm-push-3 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
                   <button onclick="save_confirm_palette()" type="button" class="btn btn-success" style="width:inherit;margin-left: 5px;margin-right: 5px;margin-bottom: 5px;"> Continuar</button>
                   </div> 
                   </div>';
        return $value;
    }

    public function modal_buttons_empresa_anular_palete_gg($parm){
        $value='';
        if($parm == 1){
            $value='<button onclick="select_all_paletes()" id="select-all" type="button" class="btn btn-light" style="width:inherit;margin-left: 5px;margin-right: 5px;margin-bottom: 5px;background-color:#E6D9A2;border-color:#D4C38F"><i class="fas fa-clipboard-check"></i> Selecionar</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i> Cancelar</button>		
                    <button onclick="save_paletes()" type="button" class="btn btn-success"><i class="fas fa-arrow-right"></i> Continuar</button>';
        }else if($parm == 0){
            $value='<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i> Cancelar</button>		
                    <button onclick="cancell_sel_paletes()" type="button" class="btn btn-primary"><i class="fas fa-save"></i> Confirmar</button>';
        }
        return $value;
    }

    public function buttons_empresa_producao($empresa){
        //echo $empresa.'sdfvsdv';
        $value='';
        if($empresa == 1){
            $value='<div class="row">        
                        <div class="col-sm-12 col-sm-push-3 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
                            <button id="choose_palets" onclick="choose_palets()" type="button" class="btn btn-dark" style="width:inherit;margin-left: 5px;margin-right: 5px;margin-bottom: 5px;"> Picar Palete</button>
                        </div>                   
                        <div class="col-sm-12 col-sm-push-3 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
                            <button onclick="send_to_rehabilitates(this.getAttribute(\'data-id\'))" type="button" class="btn btn-success" style="width:inherit;margin-left: 5px;margin-right: 5px;margin-bottom: 5px;" data-id="ST301">Continuar</button>                   
                        </div> 
                   </div>';
         }
         else if($empresa == 2){
            $value = '<div class="row">        
                        <div class="col-sm-12 col-sm-push-3 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
                            <button id="choose_palets" onclick="choose_palets()" type="button" class="btn btn-dark" style="width:inherit; margin-left: 5px; margin-right: 5px; margin-bottom: 5px;">Picar Palete</button>
                        </div>
                        <div class="col-sm-12 col-sm-push-3 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
                            <button onclick="send_to_rehabilitates(this.getAttribute(\'data-id\'))" type="button" class="btn btn-success" style="width:inherit;margin-left: 5px;margin-right: 5px;margin-bottom: 5px;" data-id="FB009">Continuar</button>                   
                        </div>
                    </div>';
        
        }      
        else if($empresa == 3 || $empresa == 4){
            $value='<div class="row">        
                        <div class="col-sm-12 col-sm-push-3 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
                            <button id="choose_palets" onclick="choose_palets()" type="button" class="btn btn-dark" style="width:inherit;margin-left: 5px;margin-right: 5px;margin-bottom: 5px;"> Picar Palete</button>
                        </div>                   
                        <div class="col-sm-12 col-sm-push-3 col-xs-12 col-md-4 col-md-push-4 col-lg-4 col-lg-push-4">
                            <button onclick="send_to_rehabilitates(this.getAttribute(\'data-id\'))" type="button" class="btn btn-success" style="width:inherit;margin-left: 5px;margin-right: 5px;margin-bottom: 5px;" data-id="ST301">Continuar</button>                   
                        </div> 
                    </div>';
         }
        return $value;
    }
}