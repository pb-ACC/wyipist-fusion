<?php

class AnulacaoCarga extends CI_Controller
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
            $this->load->view('template/planos_carga/anulacao/anulacao_carga', $session_data);
            $this->load->view('structure/footer', $session_data);

        }else{
            redirect('start', 'refresh');
        }
    }
    
    public function valida_movimentosPL($palete){

        $this->load->helper('url');
        $this->load->library('session');

        if($this->session->userdata('logged_in')) {

            $this->load->model('planos_carga/anulacao/Anulacao_Carga');
            $pl=$this->Anulacao_Carga->valida_movimentosPL($palete);    
                        
            $data = array( 
                'palete' => $pl
            );

            echo json_encode($data);

        }else{
            redirect('start', 'refresh');
        }
    }   
    
    public function get_valor_reverte($palete){

        $this->load->helper('url');
        $this->load->library('session');

        if($this->session->userdata('logged_in')) {

            $this->load->model('planos_carga/anulacao/Anulacao_Carga');
            $pl=$this->Anulacao_Carga->get_valor_reverte($palete);    
                        
            $data = array( 
                'reverte' => $pl
            );

            echo json_encode($data);

        }else{
            redirect('start', 'refresh');
        }
    }   

    public function anula_palete(){

        $this->load->helper('url');
        $this->load->library('session');

        if($this->session->userdata('logged_in')) {

            $session_data = $this->session->userdata('logged_in');            
            $username = $session_data['username'];
            $funcionario_gpac = $session_data['funcionario_gpac']; 
                        
            $cliente = $this->input->post('cliente');           
            $encomenda = $this->input->post('encomenda');           
            $linha = $this->input->post('linha');           
            $paletes = $this->input->post('paletes');    
            $setor_cliente = $this->input->post('setor_cliente');  
            $setor_exp = $this->input->post('setor_exp');              
            $reverte = $this->input->post('reverte');  
            $movimenta = $this->input->post('movimenta');  

            $this->load->model('planos_carga/anulacao/Anulacao_Carga');

            $countTBL = count($paletes, COUNT_RECURSIVE);
            if ($countTBL > 2) {                
                for ($i = 0; $i < count($paletes); $i++) {
                    $palete_cliente = $paletes[$i]['NumeroDocumento'];
                    $palete_origem = $paletes[$i]['PaleteOrigem'];
                    $local = $paletes[$i]['Local'];

                    $this->Anulacao_Carga->anula_palete($cliente,$encomenda,$linha,$palete_cliente,$palete_origem,$setor_cliente,$setor_exp,$reverte,$movimenta,$local,$username,$funcionario_gpac);  
                }
                echo json_encode("inseriu");                  
            } 
        }else{
            redirect('start', 'refresh');
        }
    }    
}