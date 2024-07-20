<?php

class GetStock extends CI_Controller
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
            $this->load->view('template/stocks/listas/listar_stock', $session_data);
            $this->load->view('structure/footer', $session_data);

        }else{
            redirect('start', 'refresh');
        }
    }

    public function filter_by_sectors(){

        $this->load->helper('url');
        $this->load->library('session');

        if($this->session->userdata('logged_in')) {
            $sectors = $this->input->get('sectors');
            //$st = '\''.$sectors.'\'';
           // echo $setores;
            $this->load->model('stocks/listas/Lista_Stock');
            echo json_encode($this->Lista_Stock->filter_by_sectors($sectors));

        }else{

            redirect('start', 'refresh');
        }
    }

    public function filter_by_pallet($palete){

        $this->load->helper('url');
        $this->load->library('session');

        if($this->session->userdata('logged_in')) {
                        
            $this->load->model('stocks/listas/Lista_Stock');
            echo json_encode($this->Lista_Stock->filter_by_pallet($palete));

        }else{

            redirect('start', 'refresh');
        }
    }
}