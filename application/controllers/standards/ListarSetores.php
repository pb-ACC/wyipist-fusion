<?php

class ListarSetores extends CI_Controller
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
            $user_type = $session_data['user_type'];

            $this->load->model('standards/others/GetEmpresa');
            return $this->GetEmpresa->getEmpresa($username,$funcionario_gpac,$user_type);

        }else{
            redirect('start', 'refresh');
        }   
    }

    public function getZonas(){
        $this->load->helper('url');
        $this->load->library('session');
        if($this->session->userdata('logged_in')) {            
            //$user_type= $session_data['user_type'];
            $empresa= $this->getEmpresa();            
            //vecho $empresa[0]->Empresa;
            $this->load->model('standards/others/GetZonas');
            switch ($empresa[0]->TipoEmpresa) {
                case 1:
                    $empresa='\'CERAGNI\'';           
                    echo json_encode($this->GetZonas->zonaCelula($empresa,'CERAGNI'));
                    break;
                case 2:
                    $empresa='\'CERTECA\'';                
                    echo json_encode($this->GetZonas->zonaCelula($empresa,'CERTECA'));
                    break;
                case 3:                    
                    /*$emp01='CERAGNI';
                    $emp02='CERTECA';
                    $empresa = '\''.$emp01.'\''.','.'\''.$emp02.'\'';                         
                    */
                    $empresa='\'CERAGNI\'';      
                    echo json_encode($this->GetZonas->zonaCelula($empresa,'CERAGNI'));
                    break;
                case 4:                    
                    /*$emp01='CERAGNI';
                    $emp02='CERTECA';                    
                    $empresa = '\''.$emp01.'\''.','.'\''.$emp02.'\'';                         
                    */
                    $empresa='\'CERAGNI\'';      
                    echo json_encode($this->GetZonas->zonaCelula($empresa,'CERAGNI'));
                    break;                    
            }
        }else{
            redirect('start', 'refresh');
        }   
    }

    public function getZonas_pl(){
        $this->load->helper('url');
        $this->load->library('session');
        if($this->session->userdata('logged_in')) {            
            //$user_type= $session_data['user_type'];
            $empresa= $this->getEmpresa();            
            //vecho $empresa[0]->Empresa;
            $this->load->model('standards/others/GetZonas');
            switch ($empresa[0]->TipoEmpresa) {
                case 1:
                    $empresa='\'CERAGNI\'';           
                    echo json_encode($this->GetZonas->zonaCelula($empresa,'CERAGNI'));
                    break;
                case 2:
                    $empresa='\'CERTECA\'';                
                    echo json_encode($this->GetZonas->zonaCelula($empresa,'CERTECA'));
                    break;
                case 3:                    
                    $empresa='\'CERAGNI\'';           
                    echo json_encode($this->GetZonas->zonaCelula($empresa,'CERAGNI'));
                    break;
                case 4:                    
                    $empresa='\'CERAGNI\'';           
                    echo json_encode($this->GetZonas->zonaCelula($empresa,'CERAGNI'));
                    break;                    
            }
        }else{
            redirect('start', 'refresh');
        }   
    }
    
}