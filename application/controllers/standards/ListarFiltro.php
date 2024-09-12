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
                $zonas=$this->GetZonas->zonaCelula($empresa,$emp);

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
                $this->load->model('standards/others/RadioButtons');
                $radio=$this->RadioButtons->escolha_setores_empresa(2,$user_type);

                $this->load->model('standards/others/GetZonas');                
                $zonas=$this->GetZonas->zonaCelula($empresa,$emp);

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
    
    public function filtraPlZn_emanuel($emp,$newSector){
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
                $zonas=$this->GetZonas->zonaCelula($empresa,$emp);

                $this->load->model('standards/stocks/GetPalets');
                //$setor = '\''.$newSector.'\'';             
                $setor = '\'ST008\', \'ST009\', \'ST009A\', \'ST009B\', \'ST009C\', \'ST009D\', \'ST009E\', \'ST010\', \'ST011\', \'ST012\', \'ST013\', \'ST014\', \'ST015\', \'ST016\', \'ST020\', \'ST200\', \'ST201\', \'ST202\', \'ST203\', \'ST204\', \'ST205\', \'ST206\', \'ST207\', \'ST290\'';               
                $paletes=$this->GetPalets->armazem($setor,'>0');

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
                $this->load->model('standards/others/RadioButtons');
                $radio=$this->RadioButtons->escolha_setores_empresa(2,$user_type);

                $this->load->model('standards/others/GetZonas');                
                $zonas=$this->GetZonas->zonaCelula($empresa,$emp);

                $this->load->model('standards/stocks/GetPalets');
                //$setor = '\''.$newSector.'\'';                              
                $setor = '\'FB003\', \'CL001\'';               
                $paletes=$this->GetPalets->armazem($setor,'>0');

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

    public function filtraPlZn_emanuel_trocalocalizacao($emp,$newSector){
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
                $zonas=$this->GetZonas->zonaCelula($empresa,$emp);

                $this->load->model('standards/stocks/GetPalets');
                //$setor = '\''.$newSector.'\'';             
                $setor = '\'ST008\', \'ST009\', \'ST009A\', \'ST009B\', \'ST009C\', \'ST009D\', \'ST009E\', \'ST010\', \'ST011\', \'ST012\', \'ST013\', \'ST014\', \'ST015\', \'ST016\', \'ST020\', \'ST200\', \'ST201\', \'ST202\', \'ST203\', \'ST204\', \'ST205\', \'ST206\', \'ST207\', \'ST290\'';               
                $paletes=$this->GetPalets->armazem($setor,'>0');

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
                $this->load->model('standards/others/RadioButtons');
                $radio=$this->RadioButtons->escolha_setores_empresa(2,$user_type);

                $this->load->model('standards/others/GetZonas');                
                $zonas=$this->GetZonas->zonaCelula($empresa,$emp);

                $this->load->model('standards/stocks/GetPalets');             
                $setor = '\''.$newSector.'\'';             
                $paletes=$this->GetPalets->armazem($setor,'>0');

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

    public function filtraPlZn_emanuel_anula($emp,$newSector){
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
                $zonas=$this->GetZonas->zonaCelula($empresa,$emp);

                $this->load->model('standards/stocks/GetPalets');
                //$setor = '\''.$newSector.'\'';             
                $setor = '\'ST008\', \'ST009\', \'ST009A\', \'ST009B\', \'ST009C\', \'ST009D\', \'ST009E\', \'ST010\', \'ST011\', \'ST012\', \'ST013\', \'ST014\', \'ST015\', \'ST016\', \'ST020\', \'ST200\', \'ST201\', \'ST202\', \'ST203\', \'ST204\', \'ST205\', \'ST206\', \'ST207\', \'ST290\'';               
                $paletes=$this->GetPalets->armazem($setor,'>0');

                $this->load->model('standards/others/Buttons');
                $button=$this->Buttons->buttons_empresa_anular_palete();

                $data = array( 
                    'zonas' => $zonas,                                          
                    'paletes' => $paletes,
                    'radio' => '',
                    'button' => $button
                );
                
            }else{
                $empresa = '\''.$emp.'\'';
                //echo $empresa;
                $this->load->model('standards/others/RadioButtons');
                $radio=$this->RadioButtons->escolha_setores_empresa_anularPL(2);

                $this->load->model('standards/others/GetZonas');                
                $zonas=$this->GetZonas->zonaCelula($empresa,$emp);

                $this->load->model('standards/stocks/GetPalets');
                //$setor = '\''.$newSector.'\'';                              
                $setor = '\'FB003\', \'CL001\'';                                
                $paletes=$this->GetPalets->armazem($setor,'>0');

                $this->load->model('standards/others/Buttons');
                $button=$this->Buttons->buttons_empresa_anular_palete();

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

    public function filtraPlZn_emanuel_corrige_stk_positivo($emp,$newSector){
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
                $zonas=$this->GetZonas->zonaCelula($empresa,$emp);

                $this->load->model('standards/stocks/GetPalets');
                //$setor = '\''.$newSector.'\'';             
                $setor = '\'ST008\', \'ST009\', \'ST009A\', \'ST009B\', \'ST009C\', \'ST009D\', \'ST009E\', \'ST010\', \'ST011\', \'ST012\', \'ST013\', \'ST014\', \'ST015\', \'ST016\', \'ST020\', \'ST200\', \'ST201\', \'ST202\', \'ST203\', \'ST204\', \'ST205\', \'ST206\', \'ST207\', \'ST290\'';               
                $paletes=$this->GetPalets->armazem($setor,'>0');                

                $this->load->model('standards/others/Buttons');
                $button=$this->Buttons->buttons_empresa_anular_palete();

                $data = array( 
                    'zonas' => $zonas,                                          
                    'paletes' => $paletes,
                    'radio' => '',
                    'button' => $button
                );
                
            }else{
                $empresa = '\''.$emp.'\'';
                //echo $empresa;
                $this->load->model('standards/others/RadioButtons');
                $radio=$this->RadioButtons->escolha_setores_empresa_anularPL(2);

                $this->load->model('standards/others/GetZonas');                
                $zonas=$this->GetZonas->zonaCelula($empresa,$emp);

                $this->load->model('standards/stocks/GetPalets');
                //$setor = '\''.$newSector.'\'';                              
                $setor = '\'FB003\', \'CL001\'';                                
                $paletes=$this->GetPalets->armazem($setor,'>0');

                $this->load->model('standards/others/Buttons');
                $button=$this->Buttons->buttons_empresa_anular_palete();

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

    public function filtraPlZn_emanuel_corrige_stk_negativo($emp,$newSector){
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
                $zonas=$this->GetZonas->zonaCelula($empresa,$emp);

                $this->load->model('standards/stocks/GetPalets');
                //$setor = '\''.$newSector.'\'';             
                $setor = '\'ST008\', \'ST009\', \'ST009A\', \'ST009B\', \'ST009C\', \'ST009D\', \'ST009E\', \'ST010\', \'ST011\', \'ST012\', \'ST013\', \'ST014\', \'ST015\', \'ST016\', \'ST020\', \'ST200\', \'ST201\', \'ST202\', \'ST203\', \'ST204\', \'ST205\', \'ST206\', \'ST207\', \'ST290\'';               
                $paletes=$this->GetPalets->armazem($setor,'<0');

                $this->load->model('standards/others/Buttons');
                $button=$this->Buttons->buttons_empresa_anular_palete();

                $data = array( 
                    'zonas' => $zonas,                                          
                    'paletes' => $paletes,
                    'radio' => '',
                    'button' => $button
                );
                
            }else{
                $empresa = '\''.$emp.'\'';
                //echo $empresa;
                $this->load->model('standards/others/RadioButtons');
                $radio=$this->RadioButtons->escolha_setores_empresa_anularPL(2);

                $this->load->model('standards/others/GetZonas');                
                $zonas=$this->GetZonas->zonaCelula($empresa,$emp);

                $this->load->model('standards/stocks/GetPalets');
                //$setor = '\''.$newSector.'\'';                              
                $setor = '\'FB003\', \'CL001\'';                                
                $paletes=$this->GetPalets->armazem($setor,'<0');

                $this->load->model('standards/others/Buttons');
                $button=$this->Buttons->buttons_empresa_anular_palete();

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

    public function filtraPlZn_emanuel_corrige_stk_zero($emp,$newSector){
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
                $zonas=$this->GetZonas->zonaCelula($empresa,$emp);

                $this->load->model('standards/stocks/GetPalets');
                //$setor = '\''.$newSector.'\'';             
                $setor = '\'ST008\', \'ST009\', \'ST009A\', \'ST009B\', \'ST009C\', \'ST009D\', \'ST009E\', \'ST010\', \'ST011\', \'ST012\', \'ST013\', \'ST014\', \'ST015\', \'ST016\', \'ST020\', \'ST200\', \'ST201\', \'ST202\', \'ST203\', \'ST204\', \'ST205\', \'ST206\', \'ST207\', \'ST290\'';               
                $paletes=$this->GetPalets->armazem($setor,'=0');

                $this->load->model('standards/others/Buttons');
                $button=$this->Buttons->buttons_empresa_anular_palete();

                $data = array( 
                    'zonas' => $zonas,                                          
                    'paletes' => $paletes,
                    'radio' => '',
                    'button' => $button
                );
                
            }else{
                $empresa = '\''.$emp.'\'';
                //echo $empresa;
                $this->load->model('standards/others/RadioButtons');
                $radio=$this->RadioButtons->escolha_setores_empresa_anularPL(2);

                $this->load->model('standards/others/GetZonas');                
                $zonas=$this->GetZonas->zonaCelula($empresa,$emp);

                $this->load->model('standards/stocks/GetPalets');
                //$setor = '\''.$newSector.'\'';                              
                $setor = '\'FB003\', \'CL001\'';                                
                $paletes=$this->GetPalets->armazem($setor,'=0');

                $this->load->model('standards/others/Buttons');
                $button=$this->Buttons->buttons_empresa_anular_palete();

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

    public function filtraPlZn_emanuel_lista($emp){
        $this->load->helper('url');
        $this->load->library('session');
        if($this->session->userdata('logged_in')) {            
            
            $this->load->model('standards/stocks/GetPalets');
            $paletes=$this->GetPalets->stock($emp);
            
            $this->load->model('standards/others/Dropdowns');
            $setores=$this->Dropdowns->setores_empresa($emp);
            $data = array( 
                'paletes' => $paletes,
                'setores' => $setores
            );
                
            echo json_encode($data);
        }else{
            redirect('start', 'refresh');
        }   
    }

    public function filtraPlZn_emanuel_lista_datas($emp){
        $this->load->helper('url');
        $this->load->library('session');
        if($this->session->userdata('logged_in')) {            
            
            $idata = $this->input->get('idate');
            $fdata = $this->input->get('fdate');

            $this->load->model('standards/stocks/GetPalets');
            $paletes=$this->GetPalets->stock_datas($emp,$idata,$fdata);
            
            $data = array( 
                'paletes' => $paletes            
            );
                
            echo json_encode($data);
        }else{
            redirect('start', 'refresh');
        }   
    }

    public function filtraPlZn_emanuel_referencias($emp){
        $this->load->helper('url');
        $this->load->library('session');
        if($this->session->userdata('logged_in')) {        
            
            $emp = strtoupper($emp);
            if($emp == 'CERAGNI'){                
                $empresa = '\''.$emp.'\'';
               // echo $empresa;

                $this->load->model('standards/others/GetZonas');                
                $zonas=$this->GetZonas->zonaCelula($empresa,$emp);
                
                $this->load->model('standards/stocks/GetReferences');
                $refs=$this->GetReferences->referencias($emp);                                

                $data = array( 
                    'zonas' => $zonas,                                          
                    'refs' => $refs
                );
                
            }else{
                $empresa = '\''.$emp.'\'';
                
                $this->load->model('standards/others/GetZonas');                
                $zonas=$this->GetZonas->zonaCelula($empresa,$emp);

                $this->load->model('standards/stocks/GetReferences');
                $refs=$this->GetReferences->referencias($emp);                                

                $data = array( 
                    'zonas' => $zonas,                                          
                    'refs' => $refs
                );
            }
                echo json_encode($data);
        }else{
            redirect('start', 'refresh');
        }   
    }
    
    public function filtraPlZn_emanuel_rececaomaterial($emp,$newSector){
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
                $zonas=$this->GetZonas->zonaCelula($empresa,$emp);

                $this->load->model('standards/stocks/GetPalets');
                //$setor = '\''.$newSector.'\'';                              
                $setor = '\'FB003\', \'CL001\'';     
                $paletes=$this->GetPalets->armazem($setor,'>0');

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

                $this->load->model('standards/others/GetZonas');                
                $zonas=$this->GetZonas->zonaCelula($empresa,$emp);

                $this->load->model('standards/stocks/GetPalets');
                //$setor = '\''.$newSector.'\'';                              
                $setor = '\'FB003\', \'CL001\'';     
                $paletes=$this->GetPalets->armazem($setor,'>0');

                $this->load->model('standards/others/Buttons');
                $button=$this->Buttons->buttons_empresa(2);

                $data = array( 
                    'zonas' => $zonas,                                          
                    'paletes' => $paletes,                    
                    'button' => $button
                );
            }
                echo json_encode($data);
        }else{
            redirect('start', 'refresh');
        }   
    }

    public function filtraPlZn_emanuel_planoscarga($emp){
        $this->load->helper('url');
        $this->load->library('session');
        if($this->session->userdata('logged_in')) {        
            
            $emp = strtoupper($emp);
            $this->load->model('planos_carga/preparacao/Preparacao_Carga');

            if($emp == 'CERAGNI'){                
                $tipoDoc='GG';
                $serie='CG';                    
                $estado='P';                                    
                $carga=$this->Preparacao_Carga->getPreparacaoCarga($tipoDoc,$serie,$estado);

                $data = array(     
                    'carga' => $carga
                );
                
            }else{
                $tipoDoc='GG';
                $serie='CT';                    
                $estado='P';                                    
                $carga=$this->Preparacao_Carga->getPreparacaoCarga($tipoDoc,$serie,$estado);

                $data = array(     
                    'carga' => $carga
                );
            }
                echo json_encode($data);
        }else{
            redirect('start', 'refresh');
        }   
    }
    
}