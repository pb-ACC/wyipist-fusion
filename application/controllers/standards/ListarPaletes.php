<?php

class ListarPaletes extends CI_Controller
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

            $this->load->model('standards/others/GetEmpresa');
            return $this->GetEmpresa->getEmpresa($username,$funcionario_gpac);

        }else{
            redirect('start', 'refresh');
        }   
    }

    public function getPalets(){
        $this->load->helper('url');
        $this->load->library('session');
        if($this->session->userdata('logged_in')) {
            
            //$user_type= $session_data['user_type'];

            $empresa= $this->getEmpresa();            
            //vecho $empresa[0]->Empresa;
            $this->load->model('standards/stocks/GetPalets');
            switch ($empresa[0]->TipoEmpresa) {
                case 1:
                    $setor='\'ST010\'';                    
                    
                    $this->load->model('standards/stocks/GetPalets');
                    $paletes=$this->GetPalets->producao($setor);

                    $this->load->model('standards/others/Buttons');
                    $button=$this->Buttons->buttons_empresa($empresa[0]->TipoEmpresa);

                    $data = array( 
                        'select' => '',     
                        'button' => $button,                
                        'paletes' => $paletes
                    );
                    echo json_encode($data);
                    break;
                case 2:
                    $setor='\'FB001\'';
                    $this->load->model('standards/stocks/GetPalets');
                    $paletes=$this->GetPalets->producao($setor);

                    $this->load->model('standards/others/Buttons');
                    $button=$this->Buttons->buttons_empresa($empresa[0]->TipoEmpresa);

                    $data = array( 
                        'select' => '', 
                        'button' => $button,  
                        'paletes' => $paletes
                    );
                    echo json_encode($data);
                    break;
                case 3:
                    /*                    
                    $st01='ST010';
                    $st02='FB001';
                    $setor = '\''.$st01.'\''.','.'\''.$st02.'\'';     
                    */
                    $setor='\'ST010\''; 
                    $this->load->model('standards/others/Dropdowns');
                    $select=$this->Dropdowns->escolha_empresa($empresa[0]->TipoEmpresa);
            
                    $this->load->model('standards/stocks/GetPalets');
                    $paletes=$this->GetPalets->producao($setor);

                    $this->load->model('standards/others/Buttons');
                    $button=$this->Buttons->buttons_empresa($empresa[0]->TipoEmpresa);

                    $data = array(
                        'select' => $select,
                        'button' => $button,  
                        'paletes' => $paletes
                    );
                    //print_r($data['paletes']);
                    echo json_encode($data);
                    break;
                case 4:
                    /*                    
                    $st01='ST010';
                    $st02='FB001';
                    $setor = '\''.$st01.'\''.','.'\''.$st02.'\'';     
                    */
                    $setor='\'ST010\''; 
                    $this->load->model('standards/others/Dropdowns');
                    $select=$this->Dropdowns->escolha_empresa($empresa[0]->TipoEmpresa);
            
                    $this->load->model('standards/stocks/GetPalets');
                    $paletes=$this->GetPalets->producao($setor);

                    $data = array(
                        'select' => $select,
                        'button' => '',
                        'paletes' => $paletes
                    );
                    //print_r($data['paletes']);
                    echo json_encode($data);
                    break;                    
            }

        }else{
            redirect('start', 'refresh');
        }   
    }  
    
    public function getPalets_trocaLocal(){
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
                    $setor = '\'ST008\', \'ST009\', \'ST009A\', \'ST009B\', \'ST009C\', \'ST009D\', \'ST009E\', \'ST010\', \'ST011\', \'ST012\', \'ST013\', \'ST014\', \'ST015\', \'ST016\', \'ST020\', \'ST200\', \'ST201\', \'ST202\', \'ST203\', \'ST204\', \'ST205\', \'ST206\', \'ST207\', \'ST290\'';               
                    $this->load->model('standards/stocks/GetPalets');
                    $paletes=$this->GetPalets->armazem($setor);

                    $this->load->model('standards/others/Buttons');
                    $button=$this->Buttons->buttons_empresa($empresa[0]->TipoEmpresa);

                    $data = array( 
                        'select' => '',
                        'radio' => '',
                        'button' => $button,                     
                        'paletes' => $paletes
                    );                    
                    echo json_encode($data);
                    break;
                case 2:
                    $setor='\'FB003\'';
                    $this->load->model('standards/stocks/GetPalets');
                    $paletes=$this->GetPalets->armazem($setor);

                    $this->load->model('standards/others/RadioButtons');
                    $radio=$this->RadioButtons->escolha_setores_empresa($empresa[0]->TipoEmpresa,$user_type);
                    
                    $this->load->model('standards/others/Buttons');
                    $button=$this->Buttons->buttons_empresa($empresa[0]->TipoEmpresa);

                    $data = array( 
                        'select' => '',                                          
                        'radio' => $radio,   
                        'button' => $button,                                       
                        'paletes' => $paletes
                    );
                    echo json_encode($data);
                    break;
                case 3:
                    /*                    
                    $st01='ST010';
                    $st02='FB001';
                    $setor = '\''.$st01.'\''.','.'\''.$st02.'\'';     
                    */
                    $setor = '\'ST008\', \'ST009\', \'ST009A\', \'ST009B\', \'ST009C\', \'ST009D\', \'ST009E\', \'ST010\', \'ST011\', \'ST012\', \'ST013\', \'ST014\', \'ST015\', \'ST016\', \'ST020\', \'ST200\', \'ST201\', \'ST202\', \'ST203\', \'ST204\', \'ST205\', \'ST206\', \'ST207\', \'ST290\'';               
                    $this->load->model('standards/others/Dropdowns');
                    $select=$this->Dropdowns->escolha_empresa($empresa[0]->TipoEmpresa);  

                    $this->load->model('standards/others/RadioButtons');
                    $radio=$this->RadioButtons->escolha_setores_empresa($empresa[0]->TipoEmpresa,$user_type);     
                    
                    $this->load->model('standards/others/Buttons');
                    $button=$this->Buttons->buttons_empresa($empresa[0]->TipoEmpresa);
            
                    $this->load->model('standards/stocks/GetPalets');
                    $paletes=$this->GetPalets->armazem($setor);

                    $data = array(
                        'select' => $select,
                        'radio' => $radio,
                        'button' => $button,
                        'paletes' => $paletes
                    );
                    //print_r($data['paletes']);
                    echo json_encode($data);
                    break;
                case 4:
                    /*                    
                    $st01='ST010';
                    $st02='FB001';
                    $setor = '\''.$st01.'\''.','.'\''.$st02.'\'';     
                    */
                    $setor = '\'ST008\', \'ST009\', \'ST009A\', \'ST009B\', \'ST009C\', \'ST009D\', \'ST009E\', \'ST010\', \'ST011\', \'ST012\', \'ST013\', \'ST014\', \'ST015\', \'ST016\', \'ST020\', \'ST200\', \'ST201\', \'ST202\', \'ST203\', \'ST204\', \'ST205\', \'ST206\', \'ST207\', \'ST290\'';               
                    $this->load->model('standards/others/Dropdowns');
                    $select=$this->Dropdowns->escolha_empresa($empresa[0]->TipoEmpresa);
                    $this->load->model('standards/others/RadioButtons');
                    $radio=$this->RadioButtons->escolha_setores_empresa($empresa[0]->TipoEmpresa,$user_type);
                    
                    $this->load->model('standards/others/Buttons');
                    $button=$this->Buttons->buttons_empresa($empresa[0]->TipoEmpresa);
                    //echo $button;
                    $this->load->model('standards/stocks/GetPalets');
                    $paletes=$this->GetPalets->armazem($setor);

                    $data = array(
                        'select' => $select,
                        'radio' => $radio,
                        'button' => $button,
                        'paletes' => $paletes
                    );
                    //print_r($data['button']);
                    echo json_encode($data);
                    break;                    
            }

        }else{
            redirect('start', 'refresh');
        }   
    }  

    public function getPalets_anulaPL(){
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
                    $setor = '\'ST008\', \'ST009\', \'ST009A\', \'ST009B\', \'ST009C\', \'ST009D\', \'ST009E\', \'ST010\', \'ST011\', \'ST012\', \'ST013\', \'ST014\', \'ST015\', \'ST016\', \'ST020\', \'ST200\', \'ST201\', \'ST202\', \'ST203\', \'ST204\', \'ST205\', \'ST206\', \'ST207\', \'ST290\'';               
                    $this->load->model('standards/stocks/GetPalets');
                    $paletes=$this->GetPalets->armazem($setor);

                    $this->load->model('standards/others/Buttons');
                    $button=$this->Buttons->buttons_empresa_anular_palete();

                    $data = array( 
                        'select' => '',
                        'radio' => '',
                        'button' => $button,                     
                        'paletes' => $paletes
                    );                    
                    echo json_encode($data);
                    break;
                case 2:
                    $setor='\'FB003\'';
                    $this->load->model('standards/stocks/GetPalets');
                    $paletes=$this->GetPalets->armazem($setor);

                    $this->load->model('standards/others/RadioButtons');
                    $radio=$this->RadioButtons->escolha_setores_empresa_anularPL($empresa[0]->TipoEmpresa);
                    
                    $this->load->model('standards/others/Buttons');
                    $button=$this->Buttons->buttons_empresa_anular_palete();

                    $data = array( 
                        'select' => '',                                          
                        'radio' => $radio,   
                        'button' => $button,                                       
                        'paletes' => $paletes
                    );
                    echo json_encode($data);
                    break;
                case 3:
                    /*                    
                    $st01='ST010';
                    $st02='FB001';
                    $setor = '\''.$st01.'\''.','.'\''.$st02.'\'';     
                    */
                    $setor = '\'ST008\', \'ST009\', \'ST009A\', \'ST009B\', \'ST009C\', \'ST009D\', \'ST009E\', \'ST010\', \'ST011\', \'ST012\', \'ST013\', \'ST014\', \'ST015\', \'ST016\', \'ST020\', \'ST200\', \'ST201\', \'ST202\', \'ST203\', \'ST204\', \'ST205\', \'ST206\', \'ST207\', \'ST290\'';               
                    $this->load->model('standards/others/Dropdowns');
                    $select=$this->Dropdowns->escolha_empresa($empresa[0]->TipoEmpresa);  

                    $this->load->model('standards/others/RadioButtons');
                    $radio=$this->RadioButtons->escolha_setores_empresa_anularPL($empresa[0]->TipoEmpresa);
                    
                    $this->load->model('standards/others/Buttons');
                    $button=$this->Buttons->buttons_empresa_anular_palete();
            
                    $this->load->model('standards/stocks/GetPalets');
                    $paletes=$this->GetPalets->armazem($setor);

                    $data = array(
                        'select' => $select,
                        'radio' => $radio,
                        'button' => $button,
                        'paletes' => $paletes
                    );
                    //print_r($data['paletes']);
                    echo json_encode($data);
                    break;
                case 4:
                    /*                    
                    $st01='ST010';
                    $st02='FB001';
                    $setor = '\''.$st01.'\''.','.'\''.$st02.'\'';     
                    */
                    $setor = '\'ST008\', \'ST009\', \'ST009A\', \'ST009B\', \'ST009C\', \'ST009D\', \'ST009E\', \'ST010\', \'ST011\', \'ST012\', \'ST013\', \'ST014\', \'ST015\', \'ST016\', \'ST020\', \'ST200\', \'ST201\', \'ST202\', \'ST203\', \'ST204\', \'ST205\', \'ST206\', \'ST207\', \'ST290\'';               
                    $this->load->model('standards/others/Dropdowns');
                    $select=$this->Dropdowns->escolha_empresa($empresa[0]->TipoEmpresa);
                    $this->load->model('standards/others/RadioButtons');
                    $radio=$this->RadioButtons->escolha_setores_empresa_anularPL($empresa[0]->TipoEmpresa);
                    
                    $this->load->model('standards/others/Buttons');
                    $button=$this->Buttons->buttons_empresa_anular_palete();
                    //echo $button;
                    $this->load->model('standards/stocks/GetPalets');
                    $paletes=$this->GetPalets->armazem($setor);

                    $data = array(
                        'select' => $select,
                        'radio' => $radio,
                        'button' => $button,
                        'paletes' => $paletes
                    );
                    //print_r($data['button']);
                    echo json_encode($data);
                    break;                    
            }

        }else{
            redirect('start', 'refresh');
        }   
    }  

    public function getPalets_stock(){
        $this->load->helper('url');
        $this->load->library('session');
        if($this->session->userdata('logged_in')) {
            
            //$user_type= $session_data['user_type'];

            $empresa= $this->getEmpresa();            
            //vecho $empresa[0]->Empresa;
            $this->load->model('standards/stocks/GetPalets');
            switch ($empresa[0]->TipoEmpresa) {
                case 1:
                    $this->load->model('standards/stocks/GetPalets');
                    $paletes=$this->GetPalets->stock('CERAGNI');

                    $this->load->model('standards/others/Buttons');
                    $button=$this->Buttons->buttons_empresa($empresa[0]->TipoEmpresa);

                    $this->load->model('standards/others/Dropdowns');
                    $setores=$this->Dropdowns->setores_empresa('CERAGNI');

                    $data = array( 
                        'select' => '',     
                        'button' => $button,                
                        'setores' => $setores,                
                        'paletes' => $paletes
                    );
                    echo json_encode($data);
                    break;
                case 2:
                    $this->load->model('standards/stocks/GetPalets');
                    $paletes=$this->GetPalets->stock('CERTECA');

                    $this->load->model('standards/others/Buttons');
                    $button=$this->Buttons->buttons_empresa($empresa[0]->TipoEmpresa);

                    $this->load->model('standards/others/Dropdowns');
                    $setores=$this->Dropdowns->setores_empresa('CERTECA');

                    $data = array( 
                        'select' => '', 
                        'button' => $button,  
                        'setores' => $setores,                
                        'paletes' => $paletes
                    );
                    echo json_encode($data);
                    break;
                case 3:
                    /*                    
                    $st01='ST010';
                    $st02='FB001';
                    $setor = '\''.$st01.'\''.','.'\''.$st02.'\'';     
                    */
                    $this->load->model('standards/others/Dropdowns');
                    $select=$this->Dropdowns->escolha_empresa($empresa[0]->TipoEmpresa);
            
                    $this->load->model('standards/stocks/GetPalets');
                    $paletes=$this->GetPalets->stock('CERAGNI');

                    $this->load->model('standards/others/Buttons');
                    $button=$this->Buttons->buttons_empresa($empresa[0]->TipoEmpresa);

                    $this->load->model('standards/others/Dropdowns');
                    $setores=$this->Dropdowns->setores_empresa('CERAGNI');

                    $data = array(
                        'select' => $select,
                        'button' => $button, 
                        'setores' => $setores,                 
                        'paletes' => $paletes
                    );
                    //print_r($data['paletes']);
                    echo json_encode($data);
                    break;
                case 4:
                    /*                    
                    $st01='ST010';
                    $st02='FB001';
                    $setor = '\''.$st01.'\''.','.'\''.$st02.'\'';     
                    */                    
                    $this->load->model('standards/others/Dropdowns');
                    $select=$this->Dropdowns->escolha_empresa($empresa[0]->TipoEmpresa);
            
                    $this->load->model('standards/stocks/GetPalets');
                    $paletes=$this->GetPalets->stock('CERAGNI');

                    $this->load->model('standards/others/Dropdowns');
                    $setores=$this->Dropdowns->setores_empresa('CERAGNI');

                    $data = array(
                        'select' => $select,
                        'button' => '',
                        'setores' => $setores,                
                        'paletes' => $paletes
                    );
                    //print_r($data['paletes']);
                    echo json_encode($data);
                    break;                    
            }

        }else{
            redirect('start', 'refresh');
        }   
    }  

    public function getPalets_stock_datas(){
        $this->load->helper('url');
        $this->load->library('session');
        if($this->session->userdata('logged_in')) {
            
            //$user_type= $session_data['user_type'];

            $empresa= $this->getEmpresa();            
            //vecho $empresa[0]->Empresa;
            $this->load->model('standards/stocks/GetPalets');
            switch ($empresa[0]->TipoEmpresa) {
                case 1:
                    $this->load->model('standards/stocks/GetPalets');
                    $paletes=$this->GetPalets->stock_datas('CERAGNI',date("Y-m-d") ,date("Y-m-d"));

                    $this->load->model('standards/others/Buttons');
                    $button=$this->Buttons->buttons_empresa($empresa[0]->TipoEmpresa);

                    $this->load->model('standards/others/Dropdowns');
                    $setores=$this->Dropdowns->setores_empresa('CERAGNI');

                    $data = array( 
                        'select' => '',     
                        'button' => $button,                
                        'setores' => $setores,                
                        'paletes' => $paletes
                    );
                    echo json_encode($data);
                    break;
                case 2:
                    $this->load->model('standards/stocks/GetPalets');
                    $paletes=$this->GetPalets->stock_datas('CERTECA',date("Y-m-d") ,date("Y-m-d"));
                    $this->load->model('standards/others/Buttons');
                    $button=$this->Buttons->buttons_empresa($empresa[0]->TipoEmpresa);

                    $this->load->model('standards/others/Dropdowns');
                    $setores=$this->Dropdowns->setores_empresa('CERTECA');
                    

                    $data = array( 
                        'select' => '', 
                        'button' => $button,  
                        'setores' => $setores,                
                        'paletes' => $paletes
                    );
                    echo json_encode($data);
                    break;
                case 3:
                    /*                    
                    $st01='ST010';
                    $st02='FB001';
                    $setor = '\''.$st01.'\''.','.'\''.$st02.'\'';     
                    */
                    $this->load->model('standards/others/Dropdowns');
                    $select=$this->Dropdowns->escolha_empresa($empresa[0]->TipoEmpresa);
            
                    $this->load->model('standards/stocks/GetPalets');
                    $paletes=$this->GetPalets->stock_datas('CERAGNI',date("Y-m-d") ,date("Y-m-d"));

                    $this->load->model('standards/others/Buttons');
                    $button=$this->Buttons->buttons_empresa($empresa[0]->TipoEmpresa);

                    $this->load->model('standards/others/Dropdowns');
                    $setores=$this->Dropdowns->setores_empresa('CERAGNI');

                    $data = array(
                        'select' => $select,
                        'button' => $button, 
                        'setores' => $setores,                 
                        'paletes' => $paletes
                    );
                    //print_r($data['paletes']);
                    echo json_encode($data);
                    break;
                case 4:
                    /*                    
                    $st01='ST010';
                    $st02='FB001';
                    $setor = '\''.$st01.'\''.','.'\''.$st02.'\'';     
                    */                    
                    $this->load->model('standards/others/Dropdowns');
                    $select=$this->Dropdowns->escolha_empresa($empresa[0]->TipoEmpresa);
            
                    $this->load->model('standards/stocks/GetPalets');
                    $paletes=$this->GetPalets->stock_datas('CERAGNI',date("Y-m-d") ,date("Y-m-d"));

                    $this->load->model('standards/others/Dropdowns');
                    $setores=$this->Dropdowns->setores_empresa('CERAGNI');

                    $data = array(
                        'select' => $select,
                        'button' => '',
                        'setores' => $setores,                
                        'paletes' => $paletes
                    );
                    //print_r($data['paletes']);
                    echo json_encode($data);
                    break;                    
            }

        }else{
            redirect('start', 'refresh');
        }   
    }  
}