<?php

class Manage_Users extends CI_Model
{


    public function __construct()
    {
        parent::__construct();
        $this->load->database('login');
        $this->load->helper('url');
        $this->load->library('session');
        //$this->db->reconnect();
    }

    public function editUsers($id,$name,$phone,$email,$user,$user_gpac,$type_id,$empresa_type){

        if($this->verifyUserNameEdit($user, $empresa_type, $id)=="Utilizador inserido já existe!"){
            return "Utilizador inserido já existe!";
        }else if ($this->verifyGpacUserEdit($user_gpac, $empresa_type, $id)=="Utilizador GPAC inserido já existe!"){
            return "Utilizador GPAC inserido já existe!";
        }else {
            $this->db->query("update users set Nome='{$name}',telefone='{$phone}', email='{$email}',username=UPPER('{$user}'),client='{$empresa_type}',
                          funcionario_gpac=UPPER('{$user_gpac}'),user_type={$type_id} where id={$id}");
            return true;
        }
    }


    public function editUsersPerfil($id,$phone,$email,$pass){


            $this->db->query("update users 
                              set telefone='{$phone}', email='{$email}',password=md5('{$pass}')
                              where id={$id}");

            return true;

    }



    public function editUsersImageBD($id,$file){


        $this->db->query("update template_images set logo_user='{$file}' where id_user={$id}");


    }


    public function addUsers($nome,$contacto,$email,$cargo,$user,$user_type,$userGPAC,$empresa_type,$password,$file){

        $response=[];

        if($this->verifyUserName($user, $empresa_type)=="Utilizador inserido já existe!"){
            $response = [
                'type' => 'error',
                'text' => 'Utilizador inserido já existe!'
            ];    
            return $response;                    
        }else if ($this->verifyGpacUser($userGPAC, $empresa_type)=="Utilizador GPAC inserido já existe!"){
            $response = [
                'type' => 'error',
                'text' => 'Utilizador GPAC inserido já existe!'
            ];               
            return $response;
        }else {
            $this->load->database('default');
            $this->insertGPAC($empresa_type,$userGPAC);            
           // $this->createOperador($userGPAC,$nome,$contacto,$email,$user_type);
         //   $this->createSchema($userGPAC);

            $this->load->database('login');
            $this->db->query("INSERT into users (nome,telefone, email,username,password,funcionario_gpac,user_type, client, funcao, codigo_empresa, active)
                          VALUES ('{$nome}','{$contacto}','{$email}',UPPER('{$user}'),md5('{$password}'),UPPER('{$userGPAC}'),{$user_type},{$empresa_type},'{$cargo}',{$empresa_type},1)");
            
            $maxUser = $this->getMaxUserImage();
            $sql = "SELECT * from  template_images where ifnull(id_user,0)=0";
            $query = $this->db->query($sql);
            $result = $query->result();

            if (!empty($result)) {
                $this->db->query("update template_images set id_user={$maxUser} where ifnull(id_user,0)=0");
            } else {
                $this->db->query("insert into template_images (id_user,logo_user ) values ({$maxUser},'public/img/users/user.png') ");
            }
            
            $this->db->close();     
            
            $response = [
                'type' => 'success',
                'text' => 'Novo registo criado com sucesso'
            ];            
            return $response;
        }
    }

    public function createOperador($userGPAC,$nome,$contacto,$email,$user_type){
        
        $sql="INSERT into operador (Codigo,Nome,Telemovel,EMail,Password,Administrador,Layout,Foto,AssiDigi,CaminhoLocal,Grupo,MsAdmin,Terminal,PTesouraria,ThumbNail,CaminhoFoto,Contexto,ImplAdm,EnviaTicket,OutlookEMail,OutlookBox,Impressora,SQLSenha,ImpressoraPDF,DesativaALT,PodeEditarGrid,ContrasteGrid,NoBatteryMSG,SerieOperador,FicheiroPDF,CaminhoPDF,CopiaPDF,TicketInterno) 
              SELECT UPPER('{$userGPAC}'),'{$nome}','{$contacto}','{$email}','#',case when {$user_type} in (1,2) then 1 else 0 end,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null";
        //echo $sql;             
        $this->db->query($sql);
        $this->db->close();     
    }

    public function createSchema($userGPAC){
        
        $sql="EXEC dbo.CriarOperadores UPPER('{$userGPAC}')";
        //echo $sql;             
        $this->db->query($sql);
        $this->db->close(); 
    }

    public function insertGPAC($empresa_type,$userGPAC){
        
        $sql="INSERT into zxOperEmpresaWEB (Empresa,Operador) 
              SELECT case when {$empresa_type}=1 then 'CERAGNI' when {$empresa_type}=2 then 'CERTECA' else 'AMBAS' end, UPPER('{$userGPAC}')";
        //echo $sql;             
        $this->db->query($sql);
        $this->db->close();     
    }

    public function addUsersImageBD($file){


        $this->db->query("insert into template_images (logo_user ) values ('{$file}') ");


    }


    private function getMaxUserImage(){

        $sql="Select Max(id) id from  users ";


        $query = $this->db->query($sql);

        $result = $query->result();

        foreach ($result as $row) {

            $lastUser=$row->id;

        }



        return $lastUser;


    }


    private function verifyUserName($username, $client){


        $sql="SELECT * from  users where username=UPPER('{$username}') and client={$client}";

        //echo $sql;

        $query = $this->db->query($sql);

        $result = $query->result();

        if(!empty($result)){

            return "Utilizador inserido já existe!";

        }else{


            return "OK Username";

        }



    }


    private function verifyUserNameEdit($username, $client, $id){

        $sql="SELECT * from  users where username=UPPER('{$username}') and client={$client} and id<>{$id}";
        $query = $this->db->query($sql);
        $result = $query->result();

        if(!empty($result)){
            return "Utilizador inserido já existe!";
        }else{
            return "OK Username";
        }
    }


    private function verifyGpacUser($funcionario_gpac, $client){


        $sql="SELECT * from  users where funcionario_gpac=UPPER('{$funcionario_gpac}') and client={$client}";

        $query = $this->db->query($sql);

        $result = $query->result();

        if(!empty($result)){

            return "Utilizador GPAC inserido já existe!";

        }else{


            return "OK User GPAC";

        }


    }


    private function verifyGpacUserEdit($funcionario_gpac, $client, $id){

        $sql="SELECT * from  users where funcionario_gpac=UPPER('{$funcionario_gpac}') and client={$client} and id <> {$id}";
        $query = $this->db->query($sql);
        $result = $query->result();

        if(!empty($result)){
            return "Utilizador GPAC inserido já existe!";
        }else{
            return "OK User GPAC";
        }
    }


    public function deleteUsers($id){

        $this->db->query("UPDATE users 
                          set active=0
                          where id={$id}");

        return true;
        //$this->db->query("DELETE from users where id={$id}");


    }


}