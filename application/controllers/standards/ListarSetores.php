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
                    $setor = '\'ST008\', \'ST009\', \'ST009A\', \'ST009B\', \'ST009C\', \'ST009D\', \'ST009E\', \'ST011\', \'ST012\', \'ST013\', \'ST014\', \'ST015\', \'ST016\', \'ST020\', \'ST200\', \'ST201\', \'ST202\', \'ST203\', \'ST204\', \'ST205\', \'ST206\', \'ST207\', \'ST290\'';                       
                    echo json_encode($this->GetZonas->zonaCelula($empresa,'CERAGNI',$setor));
                    break;
                case 2:
                    $empresa='\'CERTECA\''; 
                    $setor='\'FB003\', \'CL001\'';                                 
                    echo json_encode($this->GetZonas->zonaCelula($empresa,'CERTECA',$setor));
                    break;
                case 3:                    
                    /*$emp01='CERAGNI';
                    $emp02='CERTECA';
                    $empresa = '\''.$emp01.'\''.','.'\''.$emp02.'\'';                         
                    */
                    $empresa='\'CERAGNI\'';  
                    $setor = '\'ST008\', \'ST009\', \'ST009A\', \'ST009B\', \'ST009C\', \'ST009D\', \'ST009E\', \'ST011\', \'ST012\', \'ST013\', \'ST014\', \'ST015\', \'ST016\', \'ST020\', \'ST200\', \'ST201\', \'ST202\', \'ST203\', \'ST204\', \'ST205\', \'ST206\', \'ST207\', \'ST290\'';                   
                    echo json_encode($this->GetZonas->zonaCelula($empresa,'CERAGNI',$setor));
                    break;
                case 4:                    
                    /*$emp01='CERAGNI';
                    $emp02='CERTECA';                    
                    $empresa = '\''.$emp01.'\''.','.'\''.$emp02.'\'';                         
                    */
                    $empresa='\'CERAGNI\''; 
                    $setor = '\'ST008\', \'ST009\', \'ST009A\', \'ST009B\', \'ST009C\', \'ST009D\', \'ST009E\', \'ST011\', \'ST012\', \'ST013\', \'ST014\', \'ST015\', \'ST016\', \'ST020\', \'ST200\', \'ST201\', \'ST202\', \'ST203\', \'ST204\', \'ST205\', \'ST206\', \'ST207\', \'ST290\'';                    
                    echo json_encode($this->GetZonas->zonaCelula($empresa,'CERAGNI',$setor));
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
                    $setor = '\'ST008\', \'ST009\', \'ST009A\', \'ST009B\', \'ST009C\', \'ST009D\', \'ST009E\', \'ST011\', \'ST012\', \'ST013\', \'ST014\', \'ST015\', \'ST016\', \'ST020\', \'ST200\', \'ST201\', \'ST202\', \'ST203\', \'ST204\', \'ST205\', \'ST206\', \'ST207\', \'ST290\'';                    
                    echo json_encode($this->GetZonas->zonaCelula($empresa,'CERAGNI',$setor));
                    break;
                case 2:
                    $empresa='\'CERTECA\''; 
                    $setor='\'FB003\', \'CL001\'';                     
                    echo json_encode($this->GetZonas->zonaCelula($empresa,'CERTECA',$setor));
                    break;
                case 3:                    
                    $empresa='\'CERAGNI\'';           
                    $setor = '\'ST008\', \'ST009\', \'ST009A\', \'ST009B\', \'ST009C\', \'ST009D\', \'ST009E\', \'ST011\', \'ST012\', \'ST013\', \'ST014\', \'ST015\', \'ST016\', \'ST020\', \'ST200\', \'ST201\', \'ST202\', \'ST203\', \'ST204\', \'ST205\', \'ST206\', \'ST207\', \'ST290\'';                    
                    echo json_encode($this->GetZonas->zonaCelula($empresa,'CERAGNI',$setor));
                    break;
                case 4:                    
                    $empresa='\'CERAGNI\'';   
                    $setor = '\'ST008\', \'ST009\', \'ST009A\', \'ST009B\', \'ST009C\', \'ST009D\', \'ST009E\', \'ST011\', \'ST012\', \'ST013\', \'ST014\', \'ST015\', \'ST016\', \'ST020\', \'ST200\', \'ST201\', \'ST202\', \'ST203\', \'ST204\', \'ST205\', \'ST206\', \'ST207\', \'ST290\'';                            
                    echo json_encode($this->GetZonas->zonaCelula($empresa,'CERAGNI',$setor));
                    break;                    
            }
        }else{
            redirect('start', 'refresh');
        }   
    }
    
}