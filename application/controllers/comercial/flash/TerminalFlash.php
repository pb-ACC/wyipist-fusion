<?php

class TerminalFlash extends CI_Controller
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
            $this->load->view('template/comercial/flash/flash', $session_data);
            $this->load->view('structure/footer', $session_data);

        }else{
            redirect('start', 'refresh');
        }
    }    

    function getStock ($emp){
        $this->load->helper('url');
        $this->load->library('session');

        if($this->session->userdata('logged_in')) {
            
            $session_data = $this->session->userdata('logged_in');    
            
            $_SESSION["empTC"]=$emp;

            $this->load->view('structure/header', $session_data);
            $this->load->view('structure/side_menu', $session_data);
            $this->load->view('template/comercial/flash/stock_flash', $session_data);
            $this->load->view('structure/footer', $session_data);

        }else{
            redirect('start', 'refresh');
        }
    }

        function getStock_company ($emp){
        $this->load->helper('url');
        $this->load->library('session');

        if($this->session->userdata('logged_in')) {
            $st_arm=''; $st_prod=''; $st_ver=''; $st_exp='';

            switch ($emp) {
                case 'Ceragni':
                    $st_arm = '\'ST015\', \'ST009\', \'ST009A\', \'ST009B\', \'ST009C\', \'ST009D\', \'ST009E\', \'ST020\'';                       
                    $st_prod = '\'ST010\''; 
                    $st_ver = '\'ST009\', \'ST009A\', \'ST009B\', \'ST009C\', \'ST009D\', \'ST009E\'';      
                    $st_exp = '\'ST012\'';
                    $cond01 = " AND isnull(RefCeragni,0)=1 ";
                    $cond02 = "<>0 ";
                    echo json_encode($this->recolhe_dados($st_arm, $st_prod, $st_ver, $st_exp, $cond01, $cond02));
                    break;
                case 'Certeca':
                    $st_arm = '\'CL001\', \'FB003\'';                       
                    $st_prod = '\'FB001\''; 
                    $st_ver = '\'YYY\'';
                    $st_exp = '\'CL006\', \'FB006\'';      
                    $cond01 = " AND isnull(RefCerteca,0)=1 ";
                    $cond02 = "<>0 ";
                    echo json_encode($this->recolhe_dados($st_arm, $st_prod, $st_ver, $st_exp, $cond01, $cond02));
                    break;
                default:
                    # code...
                    $st_arm='\'ST015\', \'ST009\', \'ST009A\', \'ST009B\', \'ST009C\', \'ST009D\', \'ST009E\', \'ST020\', \'CL001\', \'FB003\'';
                    $st_prod = '\'ST010\', \'FB001\'';
                    $st_ver = '\'ST009\', \'ST009A\', \'ST009B\', \'ST009C\', \'ST009D\', \'ST009E\', \'YYY\'';                       
                    $st_exp = '\'ST012\', \'CL006\', \'FB006\'';              
                    //$cond01 = " AND isnull(RefCeragni,0)=1 AND isnull(RefCerteca,0)=1";
                    $cond01 = " AND (isnull(RefCeragni,0)=1 OR isnull(RefCerteca,0)=1) ";
                    $cond02 = "<>0 ";
                    echo json_encode($this->recolhe_dados($st_arm, $st_prod, $st_ver, $st_exp, $cond01, $cond02));
                    break;
            }
            

        }else{
            redirect('start', 'refresh');
        }
    }

    function recolhe_dados($st_arm, $st_prod, $st_ver, $st_exp, $cond01, $cond02){
        $this->load->helper('url');
        $this->load->library('session');
        $session_data = $this->session->userdata('logged_in');            
        $username = $session_data['username'];
        $funcionario_gpac = $session_data['funcionario_gpac']; 

        if($this->session->userdata('logged_in')) {

            $this->load->model('comercial/flash/Recolhe_Dados');
            $dados = $this->Recolhe_Dados->recolhe_dados($st_arm, $st_prod, $st_ver, $st_exp, $cond01, $cond02, $username, $funcionario_gpac);     
            $data = array('dados' => $dados);
            return $data;

        }else{
            redirect('start', 'refresh');
        }  
    }
}