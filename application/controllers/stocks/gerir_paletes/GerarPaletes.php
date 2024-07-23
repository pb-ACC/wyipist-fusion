<?php

class GerarPaletes extends CI_Controller
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
            $this->load->view('template/stocks/gerir_paletes/gerar_palete', $session_data);
            $this->load->view('structure/footer', $session_data);

        }else{
            redirect('start', 'refresh');
        }
    }
   
    public function guardar_palete(){

        $this->load->helper('url');
        $this->load->library('session');

        if($this->session->userdata('logged_in')) {
            
            $session_data = $this->session->userdata('logged_in');            
            $username = $session_data['username'];
            $funcionario_gpac = $session_data['funcionario_gpac']; 
            
            $tbl = $this->input->post('palete');
            $local = $this->input->post('local');
            $setor = $this->input->post('setor');
            $obsPL = $this->input->post('obsPL');
            
            $countTBL = count($tbl, COUNT_RECURSIVE);

            if ($countTBL > 2) {
                
                for ($i = 0; $i < count($tbl); $i++) {
                    $referencia = $tbl[$i]['referencia'];
                    $descricao = $tbl[$i]['descricao'];
                    $formato = $tbl[$i]['formato'];
                    $cb = $tbl[$i]['cb'];
                    $calibre = $tbl[$i]['calibre'];
                    $lote = $tbl[$i]['lote'];
                    $quantidade = $tbl[$i]['qtd'];
                    $serie = $tbl[$i]['serie'];
                    $nome_nivel = $tbl[$i]['nome_nivel'];
                    $codigo_nivel = $tbl[$i]['codigo_nivel'];

                    $this->load->model('stocks/gerir_paletes/Guardar_Paletes');
                    $this->Guardar_Paletes->guardar_palete($referencia,$descricao,$formato,$cb,$calibre,$lote,$quantidade,$serie,$nome_nivel,$codigo_nivel,$local,$setor,$obsPL,$username,$funcionario_gpac);                      
                 }
                 echo json_encode("inseriu");
             }                

        }else{

            redirect('start', 'refresh');
        }


    }
    
    public function recolhe_dados_palete(){
        
        $this->load->library('session');

        if($this->session->userdata('logged_in')) {
            
            $serie = $_SESSION["seriePalete"];
            $palete = $_SESSION["nPalete"];

            $this->load->model('standards/stocks/GetPalets');
            echo json_encode($this->GetPalets->recolhe_dados_palete($serie,$palete));

        }else{

            redirect('start', 'refresh');
        }

    }
}