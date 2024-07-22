<?php

/**
 * Created by PhpStorm.
 * User: p-bri
 * Date: 17/05/2019
 * Time: 16:23
 */
class Templates extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Europe/Lisbon');
        // $this->load->model('chart_valco_model');

        //funcao para testar se � necess�rio kikar o user
        $this->load->model('login__model');//, 'reg_model');
        $this->login__model->to_kick();
    }

    public function get_report(){
        
        $this->load->library('session');

        if($this->session->userdata('logged_in')) {
            
            $tipodoc = $this->input->post('tipodoc');

            $this->load->model('standards/reports/Templates');
            echo json_encode($this->Templates->get_report($tipodoc));

        }else{

            redirect('start', 'refresh');
        }

    }

}