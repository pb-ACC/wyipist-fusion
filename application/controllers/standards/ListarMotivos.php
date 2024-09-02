<?php

class ListarMotivos extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Europe/Lisbon');
        //funcao para testar se � necess�rio kikar o user
        $this->load->model('auth/login_model');//, 'reg_model');
        $this->login_model->to_kick();
    }

    public function getMotivos(){
        $this->load->helper('url');
        $this->load->library('session');
        if($this->session->userdata('logged_in')) {

            $this->load->model('standards/others/Dropdowns');
            echo json_encode($this->Dropdowns->motivo_erro_palete());

        }else{
            redirect('start', 'refresh');
        }   
    }

    public function getMotivos_anula(){

        $this->load->helper('url');
        $this->load->library('session');

        if($this->session->userdata('logged_in')) {
            
            $this->load->model('standards/others/Dropdowns');
            echo json_encode($this->Dropdowns->motivo_anula_palete());

        }else{

            redirect('start', 'refresh');
        }
    }  

    function getMotivo_stock(){

        $this->load->helper('url');
        $this->load->library('session');

        if($this->session->userdata('logged_in')) {
            
            $this->load->model('standards/others/Dropdowns');
            echo json_encode($this->Dropdowns->motivo_stock());

        }else{
            redirect('start', 'refresh');
        }
    }
}