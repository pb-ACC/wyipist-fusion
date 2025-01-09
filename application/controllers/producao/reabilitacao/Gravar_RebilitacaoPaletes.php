<?php

class Gravar_RebilitacaoPaletes extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Europe/Lisbon');
        //funcao para testar se � necess�rio kikar o user
        $this->load->model('auth/login_model');//, 'reg_model');
        $this->login_model->to_kick();
    }


    function save_rehabilitation(){

        $this->load->helper('url');
        $this->load->library('session');

        if($this->session->userdata('logged_in')) {
            
            $session_data = $this->session->userdata('logged_in');            
            $username = $session_data['username'];
            $funcionario_gpac = $session_data['funcionario_gpac']; 
            
            $tbl = $this->input->post('palete');

            $countTBL = count($tbl, COUNT_RECURSIVE);

            if ($countTBL > 2) {
                
                for ($i = 0; $i < count($tbl); $i++) {
                    $DocPL = $tbl[$i]['DocPL'];
                    $LinhaPL = $tbl[$i]['LinhaPL'];
                    $Referencia = $tbl[$i]['Referencia'];
                    $Artigo = $tbl[$i]['Artigo'];
                    $DescricaoArtigo = $tbl[$i]['DescricaoArtigo'];
                    $Quantidade = $tbl[$i]['Quantidade'];
                    $NovaQtd = $tbl[$i]['NovaQtd'];
                    $Unidade = $tbl[$i]['Unidade'];
                    $Sector = $tbl[$i]['Sector'];
                    $Formato = $tbl[$i]['Formato'];
                    $Qual = $tbl[$i]['Qual'];
                    $TipoEmbalagem = $tbl[$i]['TipoEmbalagem'];
                    $Superficie = $tbl[$i]['Superficie'];
                    $Decoracao = $tbl[$i]['Decoracao'];
                    $RefCor = $tbl[$i]['RefCor'];
                    $Lote = $tbl[$i]['Lote'];
                    $TabEspessura = $tbl[$i]['TabEspessura'];
                    $Calibre = $tbl[$i]['Calibre'];                    
                    $Nivel = $tbl[$i]['Nivel'];

                    if($Sector == 'ST300'){
                        $novoST = 'ST301';
                    }else{
                        $novoST = 'FB009';
                    }
                  
                    $this->load->model('producao/Reabilitacao_Paletes');
                    $this->Reabilitacao_Paletes->save_rehabilitation($DocPL,$LinhaPL,$Referencia,$Artigo,$DescricaoArtigo,$Quantidade,$NovaQtd,$Unidade,$Sector,$Formato,$Qual,
                                                                     $TipoEmbalagem,$Superficie,$Decoracao,$RefCor,$Lote,$TabEspessura,$Calibre,$Nivel,$novoST,$username,
                                                                     $funcionario_gpac);                   
                 }
                 echo json_encode("inseriu");
             }                

        }else{

            redirect('start', 'refresh');
        }

    }

}