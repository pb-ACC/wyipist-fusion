<?php

class ListarReferencias extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Europe/Lisbon');
        //funcao para testar se � necess�rio kikar o user
        $this->load->model('auth/login_model');//, 'reg_model');
        $this->login_model->to_kick();
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

    public function getReferencias(){
        $this->load->helper('url');
        $this->load->library('session');
        if($this->session->userdata('logged_in')) {
            
            //$user_type= $session_data['user_type'];

            $empresa= $this->getEmpresa();            
            //vecho $empresa[0]->Empresa;
            $this->load->model('standards/stocks/GetPalets');
            switch ($empresa[0]->TipoEmpresa) {
                case 1:
                    $empresa='CERAGNI';
                    $this->load->model('standards/stocks/GetReferences');
                    $refs=$this->GetReferences->referencias($empresa);
                    
                    $data = array( 
                        'select' => '',     
                        'refs' => $refs
                    );
                    echo json_encode($data);
                    break;
                case 2:
                    $empresa='CERTECA';
                    $this->load->model('standards/stocks/GetReferences');
                    $refs=$this->GetReferences->referencias($empresa);

                    $data = array( 
                        'select' => '', 
                        'refs' => $refs
                    );
                    echo json_encode($data);
                    break;
                case 3:
                    /*                    
                    $st01='ST010';
                    $st02='FB001';
                    $setor = '\''.$st01.'\''.','.'\''.$st02.'\'';     
                    */
                    $this->load->model('standards/others/Dropdowns');
                    $select=$this->Dropdowns->escolha_empresa($empresa[0]->TipoEmpresa);
            
                    $empresa='CERAGNI';
                    $this->load->model('standards/stocks/GetReferences');
                    $refs=$this->GetReferences->referencias($empresa);
                    
                    $data = array(
                        'select' => $select,
                        'refs' => $refs
                    );
                    //print_r($data['refs']);
                    echo json_encode($data);
                    break;
                case 4:
                    /*                    
                    $st01='ST010';
                    $st02='FB001';
                    $setor = '\''.$st01.'\''.','.'\''.$st02.'\'';     
                    */
                    $this->load->model('standards/others/Dropdowns');
                    $select=$this->Dropdowns->escolha_empresa($empresa[0]->TipoEmpresa);
            
                    $empresa='CERAGNI';
                    $this->load->model('standards/stocks/GetReferences');
                    $refs=$this->GetReferences->referencias($empresa);

                    $data = array(
                        'select' => $select,                    
                        'refs' => $refs
                    );
                    //print_r($data['refs']);
                    echo json_encode($data);
                    break;                    
            }

        }else{
            redirect('start', 'refresh');
        }   
    }  
}