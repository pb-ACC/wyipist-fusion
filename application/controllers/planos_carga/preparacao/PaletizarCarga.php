<?php

class PaletizarCarga extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Europe/Lisbon');
        //funcao para testar se � necess�rio kikar o user
        $this->load->model('auth/login_model');//, 'reg_model');
        $this->login_model->to_kick();
    }
   
    public function paletizar_carga($serie,$flag){

        $this->load->helper('url');
        $this->load->library('session');

        if($this->session->userdata('logged_in')) {
            
            $session_data = $this->session->userdata('logged_in');            
            $username = $session_data['username'];
            $funcionario_gpac = $session_data['funcionario_gpac']; 
            $encomenda = $this->input->post('encomenda');            
            $paletes = $this->input->post('paletes');     
            
            $motivo = $this->input->post('motivo');           
            $codigomotivo = $this->input->post('codigomotivo');           
            $obs = $this->input->post('obs');           

            $seriePL = $this->input->post('seriePL');         
            $serieEmp = $this->input->post('serieEmp');         
              
            $setorDestino = $this->input->post('setorDestino');           
            $setorCarga = $this->input->post('setorCarga');           
           
            //print_r($encomenda);
            //print_r($paletes);
            $this->load->model('planos_carga/preparacao/Paletizar_Carga');
            $countTBL = count($paletes, COUNT_RECURSIVE);
            if ($countTBL > 2) {                

                $Cliente = $encomenda[0]['Cliente'];
                $DocumentoCarga = $encomenda[0]['DocumentoCarga'];
                $NumeroDocumento = $encomenda[0]['NumeroDocumento'];
                $NumeroLinha = $encomenda[0]['NumeroLinha'];
                $QtdEN = $encomenda[0]['Quantidade'];
                $QtdPaletizada = $encomenda[0]['QtdPaletizada'];
                $QtdFalta = $encomenda[0]['QtdFalta'];      
                
                for ($i = 0; $i < count($paletes); $i++) {
                    $Sector = $paletes[$i]['Sector'];
                    $Local = $paletes[$i]['Local'];
                    $Artigo = $paletes[$i]['Artigo'];
                    $Referencia = $paletes[$i]['Referencia'];
                    $DescricaoArtigo = $paletes[$i]['DescricaoArtigo'];
                    $Lote = $paletes[$i]['Lote'];
                    $Calibre = $paletes[$i]['Calibre'];
                    $Formato = $paletes[$i]['Formato'];
                    $Qual = $paletes[$i]['Qual'];
                    $TipoEmbalagem = $paletes[$i]['TipoEmbalagem'];
                    $Superficie = $paletes[$i]['Superficie'];
                    $Decoracao = $paletes[$i]['Decoracao'];
                    $RefCor = $paletes[$i]['RefCor'];
                    $TabEspessura = $paletes[$i]['TabEspessura'];
                    $Nivel = $paletes[$i]['Nivel'];
                    $Quantidade = $paletes[$i]['Quantidade'];
                    $NovaQtd = $paletes[$i]['NovaQtd'];
                    $Unidade = $paletes[$i]['Unidade'];
                    $LinhaPL = $paletes[$i]['LinhaPL'];
                    $DocPL = $paletes[$i]['DocPL'];
                           
                    $this->Paletizar_Carga->paletizar_carga($serie,$flag,$Cliente,$DocumentoCarga,$NumeroDocumento,$NumeroLinha,$QtdEN,$QtdPaletizada,$QtdFalta,$Sector,$Local,$Artigo,
                                                            $Referencia,$DescricaoArtigo,$Lote,$Calibre,$Formato,$Qual,$TipoEmbalagem,$Superficie,$Decoracao,$RefCor,$TabEspessura,$Nivel,
                                                            $Quantidade,$NovaQtd,$Unidade,$LinhaPL,$DocPL,$motivo,$codigomotivo,$obs,$serieEmp,$seriePL,$setorDestino,$setorCarga,$username,$funcionario_gpac);    
                 }
                 
                 echo json_encode("conclui");
             }
        }else{
            redirect('start', 'refresh');
        }
    }  
    
    public function fecharGG($plano){

        $this->load->helper('url');
        $this->load->library('session');

        if($this->session->userdata('logged_in')) {
            
            $session_data = $this->session->userdata('logged_in');            
            $username = $session_data['username'];
            $funcionario_gpac = $session_data['funcionario_gpac']; 

            $this->load->model('planos_carga/preparacao/Paletizar_Carga');
            $this->Paletizar_Carga->fecharGG($plano,$username,$funcionario_gpac);    
                        
            echo json_encode("conclui");
        }else{
            redirect('start', 'refresh');
        }
    }    
}