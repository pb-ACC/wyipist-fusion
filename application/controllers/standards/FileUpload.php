<?php


class FileUpload extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Europe/Lisbon');
        $this->load->helper('url');
        $this->load->library('session');
        //funcao para testar se é necessário kikar o user
        $this->load->model('auth/login_model', 'reg_model');
        $this->reg_model->to_kick();
    }


    public function index(){


        $this->load->helper('url');
        $this->load->library('session');

        if ($this->session->userdata('logged_in')) {


            //$documento = $this->input->post('numeroDoc');
            //$codigo = $this->input->post('codigo');
            $fornecedor = $this->input->post('fornecedor');
            $numeroDocumento = $this->input->post('numeroDocumento');

            $this->session->set_userdata('numeroDocumento_anexo', $numeroDocumento);
            $this->session->set_userdata('fornecedor_anexo', $fornecedor);


            //echo "entrei";


            //echo $documento;
            //echo $codigo;
            //$teste2='Fornecedores/'.$codigo.'/'.$documento.'/';
            //echo $teste2;

            //$teste='Fornecedores/'.$codigo.'/'.$documento.'/';






            //$this->load->view('template/subcontratos', $session_data);

        } else {

            redirect('start', 'refresh');


        }



    }

    public function postLinhasEncomendasQual(){


    $this->load->helper('url');
    $this->load->library('session');

    if ($this->session->userdata('logged_in')) {


        //$documento = $this->input->post('numeroDoc');
        //$codigo = $this->input->post('codigo');
        $fornecedor = $this->input->post('fornecedor');
        $numeroDocumento = $this->input->post('numeroDocumento');
        $id = $this->input->post('id');
        $cabecalhoCQ = $this->input->post('cabecalhoCQ');
        //echo $cabecalhoCQ;
        $this->session->set_userdata('numeroDocumento_anexo', $numeroDocumento);
        $this->session->set_userdata('fornecedor_anexo', $fornecedor);
        $this->session->set_userdata('id_linha_anexo', $id);
        $this->session->set_userdata('cabecalhoCQ', $cabecalhoCQ);

        echo $this->session->userdata('cabecalhoCQ')."depois";

        //echo "entrei";


        //echo $documento;
        //echo $codigo;
        //$teste2='Fornecedores/'.$codigo.'/'.$documento.'/';
        //echo $teste2;

        //$teste='Fornecedores/'.$codigo.'/'.$documento.'/';






        //$this->load->view('template/subcontratos', $session_data);

    } else {

        redirect('start', 'refresh');


    }



}

    public function postLinhasEncomendas(){


        $this->load->helper('url');
        $this->load->library('session');

        if ($this->session->userdata('logged_in')) {


            //$documento = $this->input->post('numeroDoc');
            //$codigo = $this->input->post('codigo');
            $fornecedor = $this->input->post('fornecedor');
            $numeroDocumento = $this->input->post('numeroDocumento');
            $id = $this->input->post('id');
            $this->session->set_userdata('numeroDocumento_anexo', $numeroDocumento);
            $this->session->set_userdata('fornecedor_anexo', $fornecedor);
            $this->session->set_userdata('id_linha_anexo', $id);

            //echo "entrei";


            //echo $documento;
            //echo $codigo;
            //$teste2='Fornecedores/'.$codigo.'/'.$documento.'/';
            //echo $teste2;

            //$teste='Fornecedores/'.$codigo.'/'.$documento.'/';






            //$this->load->view('template/subcontratos', $session_data);

        } else {

            redirect('start', 'refresh');


        }



    }


    public function postTickets(){


        $this->load->helper('url');
        $this->load->library('session');

        if ($this->session->userdata('logged_in')) {


            //$documento = $this->input->post('numeroDoc');
            //$codigo = $this->input->post('codigo');
            $codigoTicket = $this->input->post('codigoTicket');
            $this->session->set_userdata('ticket_anexo', $codigoTicket);

            //echo "entrei";


            //echo $documento;
            //echo $codigo;
            //$teste2='Fornecedores/'.$codigo.'/'.$documento.'/';
            //echo $teste2;

            //$teste='Fornecedores/'.$codigo.'/'.$documento.'/';






            //$this->load->view('template/subcontratos', $session_data);

        } else {

            redirect('start', 'refresh');


        }



    }



    function postChatImageClientes(){

        if ($this->session->userdata('logged_in')) {

            $this->load->helper('url');
            $this->load->library('session');

            $id= $this->input->post('id');
            $this->session->set_userdata('id_ticket', $id);
            $data_hora= $this->input->post('data_hora');
            $this->session->set_userdata('data_hora', $data_hora);
            $usr_img_chat= $this->input->post('user_image');
            $this->session->set_userdata('usr_img_chat', $usr_img_chat);
            $chat_interno= $this->input->post('chat_interno');
            $this->session->set_userdata('chat_interno', $chat_interno);
            $cliente= $this->input->post('cliente');
            $this->session->set_userdata('cliente_upimage', $cliente);

            //echo $id;


        } else {

            redirect('start', 'refresh');


        }

    }





    function uploadAddFornecedor(){

        $logotipo_fornecedor =  $this->input->post('logotipo_fornecedor');

        //echo $logotipo_fornecedor;


        $msg = "";
        $targetFile = "public/img/fornecedores/logos/" . basename($_FILES['attachments']['name'][0]);

        //echo basename($_FILES['attachments']['name'][0]);
        echo $_FILES['attachments']['tmp_name'][0];

        if (file_exists($targetFile))
            $msg = array("status" => 0, "msg" => "file already exists");
        else if (move_uploaded_file($_FILES['attachments']['tmp_name'][0], $targetFile))
            //copy($targetFile,$targetFile2);
            $msg = array("status" => 1, "msg" => "file upladed", "path1" => $targetFile);

        exit(json_encode($msg));



    }

    function uploadEditFornecedor(){

        $logotipo_fornecedor =  $this->input->post('logotipo_fornecedor');

        //echo $logotipo_fornecedor;


        $msg = "";
        $targetFile = "public/img/fornecedores/logos/" . basename($_FILES['attachments']['name'][0]);

        //echo basename($_FILES['attachments']['name'][0]);
        echo $_FILES['attachments']['tmp_name'][0];

        if (file_exists($targetFile))
            $msg = array("status" => 0, "msg" => "file already exists");
        else if (move_uploaded_file($_FILES['attachments']['tmp_name'][0], $targetFile))
            //copy($targetFile,$targetFile2);
            $msg = array("status" => 1, "msg" => "file upladed", "path1" => $targetFile);

        exit(json_encode($msg));


    }


    function uploadAddUtilizador(){

        $logotipo_utilizador =  $this->input->post('logotipo_utilizador');

        echo $logotipo_utilizador;


        $msg = "";
        $targetFile = "public/img/users/" . basename($_FILES['attachments']['name'][0]);

        //echo basename($_FILES['attachments']['name'][0]);
        echo $_FILES['attachments']['tmp_name'][0];

        if (file_exists($targetFile))
            $msg = array("status" => 0, "msg" => "file already exists");
        else if (move_uploaded_file($_FILES['attachments']['tmp_name'][0], $targetFile))
            //copy($targetFile,$targetFile2);
            $msg = array("status" => 1, "msg" => "file upladed", "path1" => $targetFile);

        exit(json_encode($msg));


    }

    function uploadEditUtilizador(){

        $logotipo_utilizador =  $this->input->post('logotipo_utilizador');

       // echo $logotipo_utilizador;


        $msg = "";
        $targetFile = "public/img/users/" . basename($_FILES['attachments']['name'][0]);

        //echo basename($_FILES['attachments']['name'][0]);
        //echo $_FILES['attachments']['tmp_name'][0];

        if (file_exists($targetFile))
            $msg = array("status" => 0, "msg" => "file already exists");
        else if (move_uploaded_file($_FILES['attachments']['tmp_name'][0], $targetFile))
            //copy($targetFile,$targetFile2);
            $msg = array("status" => 1, "msg" => "file upladed", "path1" => $targetFile);

        exit(json_encode($msg));


    }


    function uploadImportFornecedor(){

        $this->load->database('default');

        $session_data = $this->session->userdata('logged_in');
        $client_id=$session_data['client'];

        $cliente="fornecedores_".$client_id;

        $flag=true;

        $fileName=$_FILES['attachments']['tmp_name'][0];

        $file = fopen($fileName, "r");
        $file2 = fopen($fileName, "r");

        if($this->verificaFornecedores($file2,$cliente)==0){

            $file = fopen($fileName, "r");

            while (($column = fgetcsv($file, 10000, ";")) !== FALSE) {

                $column = array_map("utf8_encode", $column); //added

                //echo "chavalo";

                if($flag) { $flag = false; continue; }

                $this->db->query("INSERT into {$cliente} (Codigo,Nome,NIF,Morada,Localidade,CodigoPostal,Pais,Contacto,Email,Responsavel,logotipo)
                   values ('" . $column[0] . "','" . $column[1] . "','" . $column[2] . "','" . $column[3] . "','" . $column[4] . "','" . $column[5] . "','" . $column[6] . "','" . $column[7] . "','" . $column[8] . "','" . $column[9] . "','img/fornecedores/logos/sem-imagem-avatar.png')");


            }

            exit(json_encode(true));

        }else{

            exit(json_encode($this->verificaFornecedores($file,$cliente)));


        }




    }




    private function verificaFornecedores($file,$cliente){


        $flag=true;

        $count=0;

        while (($column = fgetcsv($file, 10000, ";")) !== FALSE) {

            if($flag) { $flag = false; continue; }

            $codigo_fornecedor=$column[0];
            $nif_fornecedor=$column[2];


            if($this->verificaCodigoFornecedor($codigo_fornecedor,$cliente)=="O Fornecedor inserido já existe!"){

                $count=$count+1;

            }else if ($this->verificaNIFFornecedor($nif_fornecedor,$cliente)=="Já existe um Fornecedor com o NIF inserido!"){


                $count=$count+1;

            }else {


            }

        }


        return $count;


    }


    private function verificaCodigoFornecedor($codigo_fornecedor,$cliente){


        $sql="Select * from  {$cliente} where Codigo='{$codigo_fornecedor}'";

        //echo $sql;

        $query = $this->db->query($sql);

        $result = $query->result();

        if(!empty($result)){

            return "O Fornecedor inserido já existe!";

        }else{


            return "OK Fornecedor";

        }



    }

    private function verificaNIFFornecedor($nif_fornecedor,$cliente){


        $sql="Select * from  {$cliente} where NIF='{$nif_fornecedor}'";

        //echo $sql;

        $query = $this->db->query($sql);

        $result = $query->result();

        if(!empty($result)){

            return "Já existe um Fornecedor com o NIF inserido!";

        }else{


            return "OK NIF Fornecedor";

        }



    }



    function uploadImportArtigo(){

        $this->load->database('default');

        $session_data = $this->session->userdata('logged_in');
        $client_id=$session_data['client'];

        $cliente="artigos_".$client_id;
        $familias="familia_artigos_".$client_id;
        $imagem_artigo="public/img/artigos/no_image.png";

        $flag=true;

        $fileName=$_FILES['attachments']['tmp_name'][0];

        $testeArtigos=$this->verificaArtigos($fileName,$cliente);
        $testeFamilia=$this->verificaFamiliaArtigos($fileName,$familias);
        $testeUnidades=$this->verificaUnidadesArtigos($fileName,'unidades');
        //$testeFamilia=0;
        $file = fopen($fileName, "r");

        if($testeArtigos==0 && $testeFamilia==0 && $testeUnidades==0){


            //echo "chavalo";

            while (($column = fgetcsv($file, 10000, ";")) !== FALSE) {

                $column = array_map("utf8_encode", $column); //added

                if($flag) { $flag = false; continue; }

                $this->db->query("INSERT into {$cliente} (Codigo,Descricao,Unidade,Familia,Preco,Foto,Observacoes,Qualidade)
                   values ('{$column[0]}','{$column[1]}','{$column[2]}','{$column[3]}',{$column[4]},'{$imagem_artigo}','{$column[5]}',(Select CodigoQualidade from {$familias} where Codigo='{$column[3]}')  )");


            }

            fclose($file);

            exit(json_encode($testeArtigos."+".$testeFamilia."+".$testeUnidades));

        }else{

            fclose($file);

            exit(json_encode($testeArtigos."+".$testeFamilia."+".$testeUnidades));


        }




    }


    private function verificaArtigos($fileName,$cliente){


        $flag=true;

        $count=0;

        $file = fopen($fileName, "r");


        while (($column = fgetcsv($file, 10000, ";")) !== FALSE) {

            if($flag) { $flag = false; continue; }

            $codigo_artigo=$column[0];


            if($this->verificaCodigoArtigo($codigo_artigo,$cliente)=="O Artigo inserido já existe!"){

                $count=$count+1;

            }

        }

        fclose($file);

        return $count;


    }

    private function verificaFamiliaArtigos($fileName,$cliente){


        $flag=true;

        $count=0;

        $file = fopen($fileName, "r");

        while (($column = fgetcsv($file, 10000, ";")) !== FALSE) {

            if($flag) { $flag = false; continue; }

            //echo $codigo_familia=$column[3];
            $codigo_familia=$column[3];

            //echo $this->verificaCodigoFamiliaArtigo($codigo_familia,$cliente);

            if($this->verificaCodigoFamiliaArtigo($codigo_familia,$cliente)=="nao existe"){

                //echo $count;
                $count=$count+1;


            }

        }

        fclose($file);

        //echo "verifica" . $count;
        return $count;


    }

    private function verificaUnidadesArtigos($fileName,$cliente){


        $flag=true;

        $count=0;

        $file = fopen($fileName, "r");

        while (($column = fgetcsv($file, 10000, ";")) !== FALSE) {

            if($flag) { $flag = false; continue; }

            //echo $codigo_familia=$column[3];
            $codigo_familia=$column[2];

            //echo $this->verificaCodigoFamiliaArtigo($codigo_familia,$cliente);

            if($this->verificaCodigoUnidadeArtigo($codigo_familia,$cliente)=="nao existe"){

                $count=$count+1;
                //echo $count + 54654;

            }

        }

        fclose($file);

        //echo "verifica" . $count;
        return $count;


    }

    private function verificaCodigoArtigo($codigo_artigo,$cliente){


        $sql="Select * from  {$cliente} where Codigo='{$codigo_artigo}'";

        //echo $sql;

        $query = $this->db->query($sql);

        $result = $query->result();

        if(!empty($result)){

            return "O Artigo inserido já existe!";

        }else{


            return "OK Artigo";

        }



    }


    private function verificaCodigoFamiliaArtigo($codigo_artigo,$cliente){


        $sql="Select * from  {$cliente} where Codigo='{$codigo_artigo}'";

        //echo $sql;

        $query = $this->db->query($sql);

        $result = $query->result();

        if(!empty($result)){

            return "existe!";

        }else{


            return "nao existe";

        }



    }

    private function verificaCodigoUnidadeArtigo($codigo_artigo,$cliente){


        $sql="Select * from  {$cliente} where Codigo='{$codigo_artigo}'";

        //echo $sql;

        $query = $this->db->query($sql);

        $result = $query->result();

        if(!empty($result)){

            return "existe!";

        }else{


            return "nao existe";

        }



    }






    function uploadAddItem(){

        $this->load->helper('url');
        $this->load->library('session');

        $session_data = $this->session->userdata('logged_in');
        $client = $session_data['client'];


        $msg = "";

        $path="public/img/artigos/" . $client;

        $targetFile = "public/img/artigos/" . $client."/" . basename($_FILES['attachments']['name'][0]);


        if (!file_exists($path)) {
            mkdir($path, 0777, true);
            chmod($path,777);
        }

        //echo basename($_FILES['attachments']['name'][0]);
        echo $_FILES['attachments']['tmp_name'][0];

        if (file_exists($targetFile))
            $msg = array("status" => 0, "msg" => "file already exists");
        else if (move_uploaded_file($_FILES['attachments']['tmp_name'][0], $targetFile))
            //copy($targetFile,$targetFile2);
            $msg = array("status" => 1, "msg" => "file upladed", "path1" => $targetFile);

        exit(json_encode($msg));


    }

    function uploadEditItem(){

        $this->load->helper('url');
        $this->load->library('session');

        $session_data = $this->session->userdata('logged_in');
        $client = $session_data['client'];


        $msg = "";

        $path="public/img/artigos/" . $client;

        $targetFile = "public/img/artigos/" . $client."/" . basename($_FILES['attachments']['name'][0]);


        if (!file_exists($path)) {
            mkdir($path, 0777, true);
            chmod($path,777);
        }

        //echo basename($_FILES['attachments']['name'][0]);
        echo $_FILES['attachments']['tmp_name'][0];

        if (file_exists($targetFile))
            $msg = array("status" => 0, "msg" => "file already exists");
        else if (move_uploaded_file($_FILES['attachments']['tmp_name'][0], $targetFile))
            //copy($targetFile,$targetFile2);
            $msg = array("status" => 1, "msg" => "file upladed", "path1" => $targetFile);

        exit(json_encode($msg));


    }



    function uploadAddAnexoEncomenda(){

        $this->load->helper('url');
        $this->load->library('session');

        $session_data = $this->session->userdata('logged_in');
        $client = $session_data['client'];
        $fornecedor=$this->session->userdata('fornecedor_anexo');
        $numeroDocumento=$this->session->userdata('numeroDocumento_anexo');


        $msg = "";

        $path='public/img/encomendas/' . $client . '/' . $fornecedor . '/' . $numeroDocumento . '/Encomenda/';

        $targetFile = $path . basename($_FILES['attachments']['name'][0]);


        if (!file_exists($path)) {
            mkdir($path, 0777, true);
            chmod($path,0777);
        }

        //echo basename($_FILES['attachments']['name'][0]);
        echo $_FILES['attachments']['tmp_name'][0];

        if (file_exists($targetFile))
            $msg = array("status" => 0, "msg" => "file already exists");
        else if (move_uploaded_file($_FILES['attachments']['tmp_name'][0], $targetFile))
            //copy($targetFile,$targetFile2);
            $msg = array("status" => 1, "msg" => "file upladed", "path1" => $targetFile);

        exit(json_encode($msg));


    }


    function uploadAddAnexoEncomendaLinhas(){

        $this->load->helper('url');
        $this->load->library('session');

        $session_data = $this->session->userdata('logged_in');
        $client = $session_data['client'];
        $fornecedor=$this->session->userdata('fornecedor_anexo');
        $numeroDocumento=$this->session->userdata('numeroDocumento_anexo');
        $id=$this->session->userdata('id_linha_anexo');

        $msg = "";

        $path='public/img/encomendas/' . $client . '/' . $fornecedor . '/' . $numeroDocumento . '/Linhas/'. $id . '/';

        $targetFile = $path . basename($_FILES['attachments']['name'][0]);


        if (!file_exists($path)) {
            mkdir($path, 0777, true);
            chmod($path,0777);
        }

        //echo basename($_FILES['attachments']['name'][0]);
        echo $_FILES['attachments']['tmp_name'][0];

        if (file_exists($targetFile))
            $msg = array("status" => 0, "msg" => "file already exists");
        else if (move_uploaded_file($_FILES['attachments']['tmp_name'][0], $targetFile))
            //copy($targetFile,$targetFile2);
            $msg = array("status" => 1, "msg" => "file upladed", "path1" => $targetFile);

        exit(json_encode($msg));


    }

    function uploadAddAnexoEncomendaLinhasQual(){

        $this->load->helper('url');
        $this->load->library('session');

        $session_data = $this->session->userdata('logged_in');
        $client = $session_data['client'];
        $fornecedor=$this->session->userdata('fornecedor_anexo');
        $numeroDocumento=$this->session->userdata('numeroDocumento_anexo');
        $id=$this->session->userdata('id_linha_anexo');
        $cabecalhoCQ=$this->session->userdata('cabecalhoCQ');




        $msg = "";

        $path='public/img/encomendas/' . $client . '/' . $fornecedor . '/' . $numeroDocumento . '/CQ/'. $id .'/'. $cabecalhoCQ .'/';


        echo $path;


        $targetFile = $path . basename($_FILES['attachments']['name'][0]);


        if (!file_exists($path)) {
            mkdir($path, 0777, true);
            chmod($path,0777);
        }

        //echo basename($_FILES['attachments']['name'][0]);
        echo $_FILES['attachments']['tmp_name'][0];

        if (file_exists($targetFile))
            $msg = array("status" => 0, "msg" => "file already exists");
        else if (move_uploaded_file($_FILES['attachments']['tmp_name'][0], $targetFile))
            //copy($targetFile,$targetFile2);
            $msg = array("status" => 1, "msg" => "file upladed", "path1" => $targetFile);

        exit(json_encode($msg));


    }



    function uploadAddAnexoTicket(){

        $this->load->helper('url');
        $this->load->library('session');

        $session_data = $this->session->userdata('logged_in');
        $client = $session_data['client'];
        $fornecedor=1;
        $numeroDocumento=$this->session->userdata('ticket_anexo');


        $msg = "";

        $path='public/img/tickets/' . $client . '/' . $fornecedor . '/' . $numeroDocumento . '/';

        $targetFile = $path . basename($_FILES['attachments']['name'][0]);


        if (!file_exists($path)) {
            mkdir($path, 0777, true);
            chmod($path,0777);
        }

        //echo basename($_FILES['attachments']['name'][0]);
        echo $_FILES['attachments']['tmp_name'][0];

        if (file_exists($targetFile))
            $msg = array("status" => 0, "msg" => "file already exists");
        else if (move_uploaded_file($_FILES['attachments']['tmp_name'][0], $targetFile))
            //copy($targetFile,$targetFile2);
            $msg = array("status" => 1, "msg" => "file upladed", "path1" => $targetFile);

        exit(json_encode($msg));


    }

    function uploadAddChatImageClientes(){


        $this->load->helper('url');
        $this->load->library('session');
        $session_data = $this->session->userdata('logged_in');



        $id_ticket=$this->session->userdata('id_ticket');
        $user = $session_data['username'];
        $id = $session_data['id'];
        $data_hora = $this->session->userdata('data_hora');
        $user_img=$this->session->userdata('usr_img_chat');
        $chat_interno=$this->session->userdata('chat_interno');
        $cliente=$this->session->userdata('cliente_upimage');


        $path='public/img/chat_tickets/' . $id_ticket . '/';


        $targetFile = $path . basename($_FILES['attachments']['name'][0]);


        if (!file_exists($path)) {
            mkdir($path, 0777, true);
            chmod($path,0777);
        }

        //echo basename($_FILES['attachments']['name'][0]);
        //echo $_FILES['attachments']['tmp_name'][0];

        if (file_exists($targetFile)) {
            $msg = array("status" => 0, "msg" => "file already exists");
        }else if(move_uploaded_file($_FILES['attachments']['tmp_name'][0], $targetFile)) {
            //copy($targetFile,$targetFile2);
            $msg = array("status" => 1, "msg" => "file upladed", "path1" => $targetFile);
        }


            $this->load->database('default',TRUE);

            $this->db->query("insert into chat_content_Cliente (id_ticket,id_user,nome_user,hora_display,datahora,endereco_server,user_image,tipo_mensagem,chat_interno)
                                 VALUES ({$id_ticket},{$id},'{$user}','{$data_hora}',NOW(),'{$targetFile}','{$user_img}',2,{$chat_interno})");


        $maxnum=$this->saveTextoChat2Upload($id,$id_ticket,$user,$chat_interno);

        $maxx=$maxnum[0]->id;


        if($chat_interno==0) {


            $this->db->query("insert into chat_status (id_ticket,chat_interno,user_id)
                          select A.id_ticket,A.chat_interno,B.id
                          from chat_content_Cliente A,users B
                          where B.id<>{$id} and B.client in (1,{$cliente}) and A.id={$maxx}");

        }else{


            $this->db->query("insert into chat_status (id_ticket,chat_interno,user_id)
                          select A.id_ticket,A.chat_interno,B.id
                          from chat_content_Cliente A,users B
                          where B.id<>{$id} and B.client=1 and A.id={$maxx}");


        }



            echo (json_encode($targetFile));




    }



    function saveTextoChat2Upload($id,$id_ticket,$username,$chat_interno){

        $this->load->database('default',TRUE);



        $sql="Select MAX(id) id from chat_content_Cliente where chat_interno={$chat_interno}";//" and Codigo='04CEPRTUP024-01'";//



        //echo $sql;

        $query = $this->db->query($sql);

        $result = $query->result();

        return $result;

    }


}