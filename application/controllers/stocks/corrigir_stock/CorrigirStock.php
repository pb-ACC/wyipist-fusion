<?php

class CorrigirStock extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Europe/Lisbon');
        //funcao para testar se � necess�rio kikar o user
        $this->load->model('auth/login_model');//, 'reg_model');
        $this->login_model->to_kick();
    }


    function positivo(){

        $this->load->helper('url');
        $this->load->library('session');

        if($this->session->userdata('logged_in')) {

            $session_data = $this->session->userdata('logged_in');

            $this->load->view('structure/header', $session_data);
            $this->load->view('structure/side_menu', $session_data);
            $this->load->view('template/stocks/corrigir_stock/positivo/corrigir_stock', $session_data);
            $this->load->view('structure/footer', $session_data);

        }else{
            redirect('start', 'refresh');
        }
    }

    function negativo(){

        $this->load->helper('url');
        $this->load->library('session');

        if($this->session->userdata('logged_in')) {

            $session_data = $this->session->userdata('logged_in');

            $this->load->view('structure/header', $session_data);
            $this->load->view('structure/side_menu', $session_data);
            $this->load->view('template/stocks/corrigir_stock/negativo/corrigir_stock', $session_data);
            $this->load->view('structure/footer', $session_data);

        }else{
            redirect('start', 'refresh');
        }
    }

    function zero(){

        $this->load->helper('url');
        $this->load->library('session');

        if($this->session->userdata('logged_in')) {

            $session_data = $this->session->userdata('logged_in');

            $this->load->view('structure/header', $session_data);
            $this->load->view('structure/side_menu', $session_data);
            $this->load->view('template/stocks/corrigir_stock/zero/corrigir_stock', $session_data);
            $this->load->view('structure/footer', $session_data);

        }else{
            redirect('start', 'refresh');
        }
    }

    function agrupado(){

        $this->load->helper('url');
        $this->load->library('session');

        if($this->session->userdata('logged_in')) {

            $session_data = $this->session->userdata('logged_in');

            $this->load->view('structure/header', $session_data);
            $this->load->view('structure/side_menu', $session_data);
            $this->load->view('template/stocks/corrigir_stock/corrigir_stock', $session_data);
            $this->load->view('structure/footer', $session_data);

        }else{
            redirect('start', 'refresh');
        }
    }
}