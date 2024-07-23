<?php

class Templates extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Europe/Lisbon');
        // $this->load->model('chart_valco_model');

        //funcao para testar se � necess�rio kikar o user
        $this->load->model('auth/login_model');//, 'reg_model');
        $this->login_model->to_kick();
    }

    public function get_report(){
        
        $this->load->library('session');

        if($this->session->userdata('logged_in')) {
            
            $tipodoc = $this->input->get('tipodoc');

            $this->load->model('standards/reports/EtiquetaPL');
            echo json_encode($this->EtiquetaPL->get_report($tipodoc));

        }else{

            redirect('start', 'refresh');
        }

    }

}