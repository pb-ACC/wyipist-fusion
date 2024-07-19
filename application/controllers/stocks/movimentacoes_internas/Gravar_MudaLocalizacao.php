<?php

class Gravar_MudaLocalizacao extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Europe/Lisbon');
        //funcao para testar se � necess�rio kikar o user
        $this->load->model('auth/login_model');//, 'reg_model');
        $this->login_model->to_kick();
    }


    function save_new_position(){

        $this->load->helper('url');
        $this->load->library('session');

        if($this->session->userdata('logged_in')) {
            
            $session_data = $this->session->userdata('logged_in');            
            $username = $session_data['username'];
            $funcionario_gpac = $session_data['funcionario_gpac']; 
            $tbl = $this->input->post('palete');
            $novo_local = $this->input->post('local');
            $novo_sector = $this->input->post('setor');
             
            $countTBL = count($tbl, COUNT_RECURSIVE);

            if ($countTBL > 2) {
                
                for ($i = 0; $i < count($tbl); $i++) {
                    $DocPL = $tbl[$i]['DocPL'];
                    $LinhaPL = $tbl[$i]['LinhaPL'];
                    $Referencia = $tbl[$i]['Referencia'];
                    $Artigo = $tbl[$i]['Artigo'];
                    $DescricaoArtigo = $tbl[$i]['DescricaoArtigo'];
                    $Quantidade = $tbl[$i]['Quantidade'];
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
                    $Local = $tbl[$i]['Local'];
                    $Nivel = $tbl[$i]['Nivel'];

                    //$novo_sector=$tbl[$i]['Sector'];

                    $this->load->model('stocks/movimentacoes_internas/MovimentacoesInternas_troca');
                    $this->MovimentacoesInternas_troca->save_new_position($DocPL, $LinhaPL, $Referencia, $Artigo, $DescricaoArtigo, $Quantidade, $Unidade, $Sector, $Formato,
                                                                                 $Qual, $TipoEmbalagem, $Superficie, $Decoracao, $RefCor, $Lote, $TabEspessura, $Calibre, $Local, $novo_sector,
                                                                                 $Nivel,$novo_local,$username,$funcionario_gpac);                   
                 }
                 echo json_encode("inseriu");
             }                

        }else{

            redirect('start', 'refresh');
        }


    }
    
}