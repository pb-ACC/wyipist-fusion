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
                    $serie = '\'CG\'';
                    
                    $this->session->set_userdata('st01', $st_arm);
                    $this->session->set_userdata('st02', $st_prod);
                    $this->session->set_userdata('st03', $st_exp);

                    echo json_encode($this->recolhe_dados($st_arm, $st_prod, $st_ver, $st_exp, $cond01, $cond02, $serie));
                    break;
                case 'Certeca':
                    $st_arm = '\'CL001\', \'FB003\'';                       
                    $st_prod = '\'FB001\''; 
                    $st_ver = '\'YYY\'';
                    $st_exp = '\'CL006\', \'FB006\'';      
                    $cond01 = " AND isnull(RefCerteca,0)=1 ";
                    $cond02 = "<>0 ";
                    $serie = '\'CT\'';

                    $this->session->set_userdata('st01', $st_arm);
                    $this->session->set_userdata('st02', $st_prod);
                    $this->session->set_userdata('st03', $st_exp);

                    echo json_encode($this->recolhe_dados($st_arm, $st_prod, $st_ver, $st_exp, $cond01, $cond02, $serie));
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
                    $serie = '\'CG\', \'CT\'';

                    $this->session->set_userdata('st01', $st_arm);
                    $this->session->set_userdata('st02', $st_prod);
                    $this->session->set_userdata('st03', $st_exp);

                    echo json_encode($this->recolhe_dados($st_arm, $st_prod, $st_ver, $st_exp, $cond01, $cond02, $serie));
                    break;
            }
            

        }else{
            redirect('start', 'refresh');
        }
    }

    function recolhe_dados($st_arm, $st_prod, $st_ver, $st_exp, $cond01, $cond02, $serie){
        $this->load->helper('url');
        $this->load->library('session');
        $session_data = $this->session->userdata('logged_in');            
        $username = $session_data['username'];
        $funcionario_gpac = $session_data['funcionario_gpac']; 

        if($this->session->userdata('logged_in')) {

            $this->load->model('comercial/flash/Recolhe_Dados');
            $dados = $this->Recolhe_Dados->recolhe_dados($st_arm, $st_prod, $st_ver, $st_exp, $cond01, $cond02, $serie, $username, $funcionario_gpac);     
            $data = array('dados' => $dados);
            return $data;

        }else{
            redirect('start', 'refresh');
        }  
    }

    function filtra_dados_modals(){
        $this->load->helper('url');
        $this->load->library('session');
        $session_data = $this->session->userdata('logged_in');      
        
        if($this->session->userdata('logged_in')) {

            $username = $session_data['username'];
            $funcionario_gpac = $session_data['funcionario_gpac']; 

            $user=strtoupper($funcionario_gpac);        
            if($user==''){
                $user=strtoupper($username);
            }   


            $iLinha = $this->input->get('iLinha');
            $iDocL = $this->input->get('iDocL');
            $refp = $this->input->get('refp');      
            $tbl01 = $user.'.MS_TabX1';
            $tbl02 = $user.'.MS_StkSect';
            $tbl03 = $user.'.MS_InfoAfetacaoX1';
            $tbl09 = $user.'.MS_ENsX1';
            $tbl10 = $user.'.MS_WEX1';

            $this->load->model('comercial/flash/Recolhe_Dados');
            $lote = $this->Recolhe_Dados->filtra_dados_lote($iLinha, $iDocL, $refp, $this->session->userdata('st01'), $this->session->userdata('st02'),  $this->session->userdata('st03'), $tbl01, $tbl02, $tbl03, $user);          
            $palete = $this->Recolhe_Dados->filtra_dados_palete($iLinha, $iDocL, $refp, $this->session->userdata('st01'), $this->session->userdata('st02'),  $this->session->userdata('st03'), $tbl01, $tbl02, $tbl03, $user);          
            $enc = $this->Recolhe_Dados->filtra_dados_enc($iLinha, $iDocL, $refp, $tbl01, $tbl09, $user);          
            $preenc = $this->Recolhe_Dados->filtra_dados_preenc($iLinha, $iDocL, $refp, $tbl01, $tbl10);          

            
            $data = array('lote' => $lote, 'palete' => $palete, 'enc' => $enc, 'preenc' => $preenc);

            echo json_encode($data);

        }else{
            redirect('start', 'refresh');
        }  
    }
}