<?php

class Gravar_CorrigirStock extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Europe/Lisbon');
        //funcao para testar se � necess�rio kikar o user
        $this->load->model('auth/login_model');//, 'reg_model');
        $this->login_model->to_kick();
    }


    public function confirm_correction(){

        $this->load->helper('url');
        $this->load->library('session');

        if($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');            
            
            $username = $session_data['username'];
            $funcionario_gpac = $session_data['funcionario_gpac']; 
            $producao = $this->input->post('producao');            
            $reabilitado = $this->input->post('reabilitado');            
            $obs = $this->input->post('obs');                        
            //$count_tblP = count($producao, COUNT_RECURSIVE);            
            //$count_tblR = count($reabilitado, COUNT_RECURSIVE);            

            $rb=0; $pl=0;            
            $CodigoGS='';
            $NumeroGS='';
            $data='';

            $this->load->model('stocks/corrigir_stock/Corrigir_Stock');
            if (!empty($producao)) {   
                $this->Corrigir_Stock->create_new_DocP($username,$funcionario_gpac,$obs,'P');  
                $CodigoGS=$_SESSION["CodigoGS_P"];                
                $NumeroGS=$_SESSION["NumeroGS_P"];      
                for ($i = 0; $i < count($producao); $i++) {
                    $DocPL = $producao[$i]['DocPL'];
                    $LinhaPL = $producao[$i]['LinhaPL'];
                    $Referencia = $producao[$i]['Referencia'];
                    $Artigo = $producao[$i]['Artigo'];
                    $DescricaoArtigo = $producao[$i]['DescricaoArtigo'];
                    $Quantidade = $producao[$i]['Quantidade'];
                    $Unidade = $producao[$i]['Unidade'];
                    $Sector = $producao[$i]['Sector'];
                    $Formato = $producao[$i]['Formato'];
                    $Qual = $producao[$i]['Qual'];
                    $TipoEmbalagem = $producao[$i]['TipoEmbalagem'];
                    $Superficie = $producao[$i]['Superficie'];
                    $Decoracao = $producao[$i]['Decoracao'];
                    $RefCor = $producao[$i]['RefCor'];
                    $Lote = $producao[$i]['Lote'];
                    $TabEspessura = $producao[$i]['TabEspessura'];
                    $Calibre = $producao[$i]['Calibre'];
                    $Local = $producao[$i]['Local'];
                    $Nivel = $producao[$i]['Nivel'];
                    $NovaQtd = $producao[$i]['NovaQtd'];
                    $Reabilitado = 0;
                    //echo $Reabilitado;                    
                    $this->Corrigir_Stock->confirm_correction($CodigoGS,$NumeroGS,$DocPL, $LinhaPL, $Referencia, $Artigo, $DescricaoArtigo, $Quantidade, $Unidade, $Sector, $Formato,
                                                              $Qual, $TipoEmbalagem, $Superficie, $Decoracao, $RefCor, $Lote, $TabEspessura, $Calibre, $Local, 
                                                              $Nivel, $NovaQtd, $Reabilitado, $username, $funcionario_gpac);                   
                 }
                 $data='pt1';
             }  
            if (!empty($reabilitado)) {   
                $this->Corrigir_Stock->create_new_DocR($username,$funcionario_gpac,$obs,'R');                
                $CodigoGS=$_SESSION["CodigoGS_R"];                
                $NumeroGS=$_SESSION["NumeroGS_R"];   
                for ($i = 0; $i < count($reabilitado); $i++) {
                    $DocPL = $reabilitado[$i]['DocPL'];
                    $LinhaPL = $reabilitado[$i]['LinhaPL'];
                    $Referencia = $reabilitado[$i]['Referencia'];
                    $Artigo = $reabilitado[$i]['Artigo'];
                    $DescricaoArtigo = $reabilitado[$i]['DescricaoArtigo'];
                    $Quantidade = $reabilitado[$i]['Quantidade'];
                    $Unidade = $reabilitado[$i]['Unidade'];
                    $Sector = $reabilitado[$i]['Sector'];
                    $Formato = $reabilitado[$i]['Formato'];
                    $Qual = $reabilitado[$i]['Qual'];
                    $TipoEmbalagem = $reabilitado[$i]['TipoEmbalagem'];
                    $Superficie = $reabilitado[$i]['Superficie'];
                    $Decoracao = $reabilitado[$i]['Decoracao'];
                    $RefCor = $reabilitado[$i]['RefCor'];
                    $Lote = $reabilitado[$i]['Lote'];
                    $TabEspessura = $reabilitado[$i]['TabEspessura'];
                    $Calibre = $reabilitado[$i]['Calibre'];
                    $Local = $reabilitado[$i]['Local'];
                    $Nivel = $reabilitado[$i]['Nivel'];
                    $NovaQtd = $reabilitado[$i]['NovaQtd'];
                    $Reabilitado = 1;
                    //echo $Reabilitado;                    
                    $this->Corrigir_Stock->confirm_correction($CodigoGS,$NumeroGS,$DocPL, $LinhaPL, $Referencia, $Artigo, $DescricaoArtigo, $Quantidade, $Unidade, $Sector, $Formato,
                                                              $Qual, $TipoEmbalagem, $Superficie, $Decoracao, $RefCor, $Lote, $TabEspessura, $Calibre, $Local, 
                                                              $Nivel, $NovaQtd, $Reabilitado, $username, $funcionario_gpac);                   
                 } 
                 $data=$data.' pt2';
            }            
            $data='inseriu '.$data;
          echo json_encode($data);
        }else{

            redirect('start', 'refresh');
        }


    }
    
}