<?php

class Motivo extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Europe/Lisbon');
        // $this->load->model('chart_valco_model');

        //funcao para testar se � necess�rio kikar o user
        $this->load->model('login_model');//, 'reg_model');
        $this->login_model->to_kick();
    }

    function save_motivo(){

        $this->load->helper('url');
        $this->load->library('session');

        if($this->session->userdata('logged_in')) {
            
            $session_data = $this->session->userdata('logged_in');            
            $username = $session_data['username'];
            $funcionario_gpac = $session_data['funcionario_gpac']; 
            $user_type = $session_data['user_type'];

            $motivo = $this->input->post('motivo');
            $codigomotivo = $this->input->post('codigomotivo');
            $palete = $this->input->post('palete');
            $obs = $this->input->post('obs');

            $this->load->model('standards/stocks/Motivo');
            echo json_encode($this->Motivo->save_motivo($palete,$codigomotivo,$motivo,$obs,$username,$funcionario_gpac,$user_type));

        }else{

            redirect('start', 'refresh');
        }


    }

    function save_motivo_and_movstock(){

        $this->load->helper('url');
        $this->load->library('session');

        if($this->session->userdata('logged_in')) {
            
            $session_data = $this->session->userdata('logged_in');            
            $username = $session_data['username'];
            $funcionario_gpac = $session_data['funcionario_gpac']; 
            $user_type = $session_data['user_type'];

            $motivo = $this->input->post('motivo');
            $codigomotivo = $this->input->post('codigomotivo');
            $palete = $this->input->post('palete');
            $sectorDestino = $this->input->post('sectorDestino');
            $obs = $this->input->post('obs');

            $this->load->model('standards/stocks/Motivo');
            echo json_encode($this->Motivo->save_motivo_and_movstock($palete,$sectorDestino,$codigomotivo,$motivo,$obs,$username,$funcionario_gpac,$user_type));

        }else{

            redirect('start', 'refresh');
        }


    }
}