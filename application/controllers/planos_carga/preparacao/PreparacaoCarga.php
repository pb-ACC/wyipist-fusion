<?php

class PreparacaoCarga extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Europe/Lisbon');
        //funcao para testar se � necess�rio kikar o user
        $this->load->model('auth/login_model');//, 'reg_model');
        $this->login_model->to_kick();
    }


    function index(){

        $this->load->helper('url');
        $this->load->library('session');

        if($this->session->userdata('logged_in')) {

            $session_data = $this->session->userdata('logged_in');

            $this->load->view('structure/header', $session_data);
            $this->load->view('structure/side_menu', $session_data);
            $this->load->view('template/planos_carga/preparacao/preparacao_carga', $session_data);
            $this->load->view('structure/footer', $session_data);

        }else{
            redirect('start', 'refresh');
        }
    }

    public function getEmpresa(){
        $this->load->helper('url');
        $this->load->library('session');
        if($this->session->userdata('logged_in')) {

            $session_data = $this->session->userdata('logged_in');            
            $username = $session_data['username'];
            $funcionario_gpac = $session_data['funcionario_gpac'];

            $this->load->model('standards/others/GetEmpresa');
            return $this->GetEmpresa->getEmpresa($username,$funcionario_gpac);

        }else{
            redirect('start', 'refresh');
        }   
    }

    public function getPlanoCarga(){
        $this->load->helper('url');
        $this->load->library('session');
        if($this->session->userdata('logged_in')) {            
            //$user_type= $session_data['user_type'];
            $empresa= $this->getEmpresa();            
            //vecho $empresa[0]->Empresa;     
            $session_data = $this->session->userdata('logged_in');            
            $username = $session_data['username'];
            $funcionario_gpac = $session_data['funcionario_gpac'];       
            switch ($empresa[0]->TipoEmpresa) {
                case 1:
                    $tipoDoc='GG';
                    $serie='CG';                    
                    $estado='P';                   
                    $this->load->model('planos_carga/preparacao/Preparacao_Carga');
                    $carga=$this->Preparacao_Carga->getPreparacaoCarga($tipoDoc,$serie,$estado); 

                    $data = array( 
                        'select' => '',     
                        'carga' => $carga
                    );
                    echo json_encode($data);
                    break;
                case 2:
                    $tipoDoc='GG';
                    $serie='CT';                    
                    $estado='P';
                    $this->load->model('planos_carga/preparacao/Preparacao_Carga');
                    $carga=$this->Preparacao_Carga->getPreparacaoCarga($tipoDoc,$serie,$estado); 

                    $data = array( 
                        'select' => '', 
                        'carga' => $carga
                    );
                    echo json_encode($data);
                    break;
                case 3:
                    $tipoDoc='GG';
                    $serie='CG';                    
                    $estado='P';
                    $this->load->model('planos_carga/preparacao/Preparacao_Carga');
                    $carga=$this->Preparacao_Carga->getPreparacaoCarga($tipoDoc,$serie,$estado); 

                    $this->load->model('standards/others/Dropdowns');
                    $select=$this->Dropdowns->escolha_empresa($empresa[0]->TipoEmpresa);
                    
                    $data = array(
                        'select' => $select,
                        'carga' => $carga
                    );
                    //print_r($data['paletes']);
                    echo json_encode($data);
                    break;
                case 4:
                    $tipoDoc='GG';
                    $serie='CG';                    
                    $estado='P';     
                    $this->load->model('planos_carga/preparacao/Preparacao_Carga');
                    $carga=$this->Preparacao_Carga->getPreparacaoCarga($tipoDoc,$serie,$estado); 

                    $this->load->model('standards/others/Dropdowns');
                    $select=$this->Dropdowns->escolha_empresa($empresa[0]->TipoEmpresa);
                                
                    $data = array(
                        'select' => $select,
                        'carga' => $carga
                    );
                    //print_r($data['paletes']);
                    echo json_encode($data);
                    break;                    
            }

        }else{
            redirect('start', 'refresh');
        }   
    }  
    
}