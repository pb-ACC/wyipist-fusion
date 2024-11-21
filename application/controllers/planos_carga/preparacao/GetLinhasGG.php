<?php

class GetLinhasGG extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Europe/Lisbon');
        //funcao para testar se � necess�rio kikar o user
        $this->load->model('auth/login_model');//, 'reg_model');
        $this->login_model->to_kick();
    }

    public function linhasGG($serie,$plano){
        $this->load->helper('url');
        $this->load->library('session');

        if($this->session->userdata('logged_in')) {
            
            $session_data = $this->session->userdata('logged_in');            
            $_SESSION["PlanoGG"]=$plano;
            $_SESSION["SerieGG"]=$serie;

            $this->load->view('structure/header', $session_data);
            $this->load->view('structure/side_menu', $session_data);
            $this->load->view('template/planos_carga/preparacao/linhas_plano_carga', $session_data);
            $this->load->view('structure/footer', $session_data);

        }else{
            redirect('start', 'refresh');
        }
    }

    public function getLinhasGG($plano,$serie){

        $this->load->helper('url');
        $this->load->library('session');

        if($this->session->userdata('logged_in')) {
            
            $session_data = $this->session->userdata('logged_in');            
            $username = $session_data['username'];
            $funcionario_gpac = $session_data['funcionario_gpac'];             
            
            if($serie == 'CG'){
                $seriePL='C';
            }else if($serie == 'CT'){
                $seriePL='PC';
            }
            
            //echo $seriePL;

            $this->load->model('planos_carga/preparacao/Preparacao_Carga');
            echo json_encode($this->Preparacao_Carga->getLinhasGG($plano,$seriePL,$username,$funcionario_gpac));

        }else{

            redirect('start', 'refresh');
        }
    }    
}