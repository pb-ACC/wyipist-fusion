<?php

class ListarFiltro extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Europe/Lisbon');
        //funcao para testar se � necess�rio kikar o user
        $this->load->model('auth/login_model');//, 'reg_model');
        $this->login_model->to_kick();
    }

    public function filtraPlZn($emp){
        $this->load->helper('url');
        $this->load->library('session');
        if($this->session->userdata('logged_in')) {            
                       
            $session_data = $this->session->userdata('logged_in');            
            $user_type = $session_data['user_type'];

            $emp = strtoupper($emp);
            if($emp == 'CERAGNI'){                
                $empresa = '\''.$emp.'\'';
               // echo $empresa;

                $this->load->model('standards/others/GetZonas');                
                $zonas=$this->GetZonas->zonaCelula($empresa);

                $this->load->model('standards/stocks/GetPalets');
                $setor='\'ST010\'';                    
                $paletes=$this->GetPalets->producao($setor);

                $this->load->model('standards/others/Buttons');
                $button=$this->Buttons->buttons_empresa(1);

                $data = array( 
                    'zonas' => $zonas,                                          
                    'paletes' => $paletes,
                    'radio' => '',
                    'button' => $button
                );
                
            }else{
                $empresa = '\''.$emp.'\'';
                //echo $empresa;
                $this->load->model('standards/others/Dropdowns');
                $radio=$this->Dropdowns->escolha_setores_empresa(2,$user_type);

                $this->load->model('standards/others/GetZonas');                
                $zonas=$this->GetZonas->zonaCelula($empresa);

                $this->load->model('standards/stocks/GetPalets');
                $setor='\'FB001\'';                    
                $paletes=$this->GetPalets->producao($setor);

                $this->load->model('standards/others/Buttons');
                $button=$this->Buttons->buttons_empresa(2);

                $data = array( 
                    'zonas' => $zonas,                                          
                    'paletes' => $paletes,                    
                    'radio' => $radio,
                    'button' => $button
                );
            }
                echo json_encode($data);
        }else{
            redirect('start', 'refresh');
        }   
    }
    
}