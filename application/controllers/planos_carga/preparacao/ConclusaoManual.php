<?php

class ConclusaoManual extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Europe/Lisbon');
        //funcao para testar se � necess�rio kikar o user
        $this->load->model('auth/login_model');//, 'reg_model');
        $this->login_model->to_kick();
    }
   
    public function concluir_linha_manualmente(){

        $this->load->helper('url');
        $this->load->library('session');

        if($this->session->userdata('logged_in')) {
            
            $session_data = $this->session->userdata('logged_in');            
            $username = $session_data['username'];
            $funcionario_gpac = $session_data['funcionario_gpac']; 
            $tbl = $this->input->post('encomenda');            
            
            $countTBL = count($tbl, COUNT_RECURSIVE);
            if ($countTBL > 2) {
                
                for ($i = 0; $i < count($tbl); $i++) {
                    $NumeroLinha = $tbl[$i]['NumeroLinha'];
                    $NumeroDocumento = $tbl[$i]['NumeroDocumento'];
                    $DocumentoCarga = $tbl[$i]['DocumentoCarga'];
                    $Quantidade = $tbl[$i]['Quantidade'];
                    $QtdPaletizada = $tbl[$i]['QtdPaletizada'];

                    $this->load->model('planos_carga/preparacao/Conclusao_Manual');
                    $this->Conclusao_Manual->concluir_linha_manualmente($NumeroLinha, $NumeroDocumento, $DocumentoCarga, $Quantidade, $QtdPaletizada, $username, $funcionario_gpac);                   
                 }
                 echo json_encode("conclui");
             }               
        }else{
            redirect('start', 'refresh');
        }
    }    
}