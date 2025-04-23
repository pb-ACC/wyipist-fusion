<?php

class FiltroEmpresa extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Europe/Lisbon');
        //funcao para testar se � necess�rio kikar o user
        $this->load->model('auth/login_model');//, 'reg_model');
        $this->login_model->to_kick();
    }

    public function getEmpresa(){
        $this->load->helper('url');
        $this->load->library('session');
        if($this->session->userdata('logged_in')) {

            $session_data = $this->session->userdata('logged_in');            
            $username = $session_data['username'];
            $funcionario_gpac = $session_data['funcionario_gpac'];
            $user_type = $session_data['user_type'];

            $this->load->model('standards/others/GetEmpresa');
            return $this->GetEmpresa->getEmpresa($username,$funcionario_gpac,$user_type);

        }else{
            redirect('start', 'refresh');
        }   
    }
   
    public function getDropdowns(){
        $this->load->helper('url');
        $this->load->library('session');
        if($this->session->userdata('logged_in')) {
            
            $session_data = $this->session->userdata('logged_in');            
            $user_type = $session_data['user_type'];

            $empresa= $this->getEmpresa();            
            //vecho $empresa[0]->Empresa;
            $this->load->model('standards/stocks/GetPalets');
            switch ($empresa[0]->TipoEmpresa) {
                case 1:
                    $this->load->model('standards/others/Buttons');
                    $button=$this->Buttons->buttons_empresa_anular_palete();

                    $data = array( 
                        'select' => '',
                        'radio' => '',
                        'button' => $button
                    );                    
                    echo json_encode($data);
                    break;
                case 2:                                        
                    $this->load->model('standards/others/RadioButtons');
                    $radio=$this->RadioButtons->escolha_setores_empresa_anularPL($empresa[0]->TipoEmpresa);
                    
                    $this->load->model('standards/others/Buttons');
                    $button=$this->Buttons->buttons_empresa_anular_palete();

                    $data = array( 
                        'select' => '',                                          
                        'radio' => $radio,   
                        'button' => $button
                    );
                    echo json_encode($data);
                    break;
                case 3:
                    $this->load->model('standards/others/Dropdowns');
                    $select=$this->Dropdowns->escolha_empresa($empresa[0]->TipoEmpresa);

                    $this->load->model('standards/others/RadioButtons');
                    $radio=$this->RadioButtons->escolha_setores_empresa_anularPL($empresa[0]->TipoEmpresa);
                    
                    $this->load->model('standards/others/Buttons');
                    $button=$this->Buttons->buttons_empresa_anular_palete();
                   
                    $data = array(
                        'select' => $select,
                        'radio' => $radio,
                        'button' => $button
                    );
                    //print_r($data['paletes']);
                    echo json_encode($data);
                    break;
                case 4:
                    $this->load->model('standards/others/Dropdowns');
                    $select=$this->Dropdowns->escolha_empresa($empresa[0]->TipoEmpresa);
                    $this->load->model('standards/others/RadioButtons');
                    $radio=$this->RadioButtons->escolha_setores_empresa_anularPL($empresa[0]->TipoEmpresa);
                    
                    $this->load->model('standards/others/Buttons');
                    $button=$this->Buttons->buttons_empresa_anular_palete();
                   
                    $data = array(
                        'select' => $select,
                        'radio' => $radio,
                        'button' => $button                   
                    );
                    //print_r($data['button']);
                    echo json_encode($data);
                    break;                    
            }

        }else{
            redirect('start', 'refresh');
        }   
    } 

    public function correcao_stock(){
        $this->load->helper('url');
        $this->load->library('session');
        if($this->session->userdata('logged_in')) {
            
            $session_data = $this->session->userdata('logged_in');            
            $user_type = $session_data['user_type'];

            $empresa= $this->getEmpresa();            
            //vecho $empresa[0]->Empresa;
            $this->load->model('standards/stocks/GetPalets');
            switch ($empresa[0]->TipoEmpresa) {
                case 1:
                    $this->load->model('standards/others/Buttons');
                    $button01=$this->Buttons->buttons_empresa_corrigir_stock(1);
                    $button02=$this->Buttons->buttons_empresa_corrigir_stock(2);

                    $data = array( 
                        'select' => '',
                        'radio'  => '',
                        'button' => array(
                            'button01' => $button01,
                            'button02' => $button02
                        )
                    );
                    echo json_encode($data);
                    break;
                case 2:                                        
                    $this->load->model('standards/others/RadioButtons');
                    $radio=$this->RadioButtons->escolha_setores_empresa_anularPL($empresa[0]->TipoEmpresa);
                    
                    $this->load->model('standards/others/Buttons');
                    $button01=$this->Buttons->buttons_empresa_corrigir_stock(1);
                    $button02=$this->Buttons->buttons_empresa_corrigir_stock(2);

                    $data = array( 
                        'select' => '',
                        'radio'  => $radio,
                        'button' => array(
                            'button01' => $button01,
                            'button02' => $button02
                        )
                    );
                    echo json_encode($data);
                    break;
                case 3:
                    $this->load->model('standards/others/Dropdowns');
                    $select=$this->Dropdowns->escolha_empresa($empresa[0]->TipoEmpresa);

                    $this->load->model('standards/others/RadioButtons');
                    $radio=$this->RadioButtons->escolha_setores_empresa_anularPL($empresa[0]->TipoEmpresa);
                    
                    $this->load->model('standards/others/Buttons');
                    $button01=$this->Buttons->buttons_empresa_corrigir_stock(1);
                    $button02=$this->Buttons->buttons_empresa_corrigir_stock(2);
                   
                    $data = array( 
                        'select' => $select,
                        'radio'  => $radio, 
                        'button' => array(
                            'button01' => $button01,
                            'button02' => $button02
                        )
                    );
                    //print_r($data['paletes']);
                    echo json_encode($data);
                    break;
                case 4:
                    $this->load->model('standards/others/Dropdowns');
                    $select=$this->Dropdowns->escolha_empresa($empresa[0]->TipoEmpresa);
                    $this->load->model('standards/others/RadioButtons');
                    $radio=$this->RadioButtons->escolha_setores_empresa_anularPL($empresa[0]->TipoEmpresa);
                    
                    $this->load->model('standards/others/Buttons');
                    $button01=$this->Buttons->buttons_empresa_corrigir_stock(1);
                    $button02=$this->Buttons->buttons_empresa_corrigir_stock(2);

                    $data = array( 
                        'select' => $select,
                        'radio'  => $radio, 
                        'button' => array(
                            'button01' => $button01,
                            'button02' => $button02
                        )
                    );
                    //print_r($data['button']);
                    echo json_encode($data);
                    break;                    
            }

        }else{
            redirect('start', 'refresh');
        }   
    } 
   
}