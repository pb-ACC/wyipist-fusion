<?php

class Users_model extends CI_Model
{


    public function __construct()
    {
        parent::__construct();
        $this->load->database('login');
        $this->load->helper('url');
        $this->load->library('session');
        //$this->db->reconnect();
    }

    public function listUsers(){

       $sql="SELECT A.id, A.username, A.Nome,A.email, A.telefone, C.type, A.user_type type_id, A.funcionario_gpac, A.password passwd, A.client 
             from users A join clients      B on (A.client=B.id) 
                          join client_types C on (A.user_type=C.id) 
             where A.active=1 and A.client<>3";
        $query = $this->db->query($sql);
        $result = $query->result();
        //print_r($result);
        return $result;
    }

    public function editUsers($id,$name,$phone,$email,$user,$pass,$user_gpac,$type_id, $client,$empresa_type){


        if($this->verifyUserNameEdit($user, $client, $id)=="Utilizador inserido já existe!"){

            return "Utilizador inserido já existe!";

        }else if ($this->verifyGpacUserEdit($user_gpac, $client, $id)=="Utilizador GPAC inserido já existe!"){


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


    public function addUsers($name,$phone,$email,$user,$pass,$user_gpac,$type_id, $client, $empresa_type){


        //echo $this->verifyUserName($user);


        if($this->verifyUserName($user, $client)=="Utilizador inserido já existe!"){

            return "Utilizador inserido já existe!";

        }else if ($this->verifyGpacUser($user_gpac, $client)=="Utilizador GPAC inserido já existe!"){


            return "Utilizador GPAC inserido já existe!";

        }else {


            $this->db->query("insert into users (nome,telefone, email,username,password,funcionario_gpac,user_type, client, active)
                          VALUES ('{$name}','{$phone}','{$email}',UPPER('{$user}'),md5('{$pass}'),UPPER('{$user_gpac}'),{$type_id},{$empresa_type},1)");

            $maxUser = $this->getMaxUserImage();

            $sql = "Select * from  template_images where ifnull(id_user,0)=0";

            $query = $this->db->query($sql);

            $result = $query->result();

            if (!empty($result)) {

                $this->db->query("update template_images set id_user={$maxUser} where ifnull(id_user,0)=0");

            } else {


                $this->db->query("insert into template_images (id_user,logo_user ) values ({$maxUser},'img/users/user.png') ");


            }

            return true;

        }

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


        $sql="Select * from  users where username=UPPER('{$username}') and client='{$client}'";

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


        $sql="Select * from  users where username=UPPER('{$username}') and client='{$client}' and id <> {$id}";

        //echo $sql;

        $query = $this->db->query($sql);

        $result = $query->result();

        if(!empty($result)){

            return "Utilizador inserido já existe!";

        }else{


            return "OK Username";

        }



    }


    private function verifyGpacUser($funcionario_gpac, $client){


        $sql="Select * from  users where funcionario_gpac=UPPER('{$funcionario_gpac}') and client='{$client}'";

        $query = $this->db->query($sql);

        $result = $query->result();

        if(!empty($result)){

            return "Utilizador GPAC inserido já existe!";

        }else{


            return "OK User GPAC";

        }


    }


    private function verifyGpacUserEdit($funcionario_gpac, $client, $id){


        $sql="Select * from  users where funcionario_gpac=UPPER('{$funcionario_gpac}') and client='{$client}' and id <> {$id}";

        $query = $this->db->query($sql);

        $result = $query->result();

        if(!empty($result)){

            return "Utilizador GPAC inserido já existe!";

        }else{


            return "OK User GPAC";

        }


    }


    public function deleteUsers($id){

        $this->db->query("update users 
                          set active=0
                          where id={$id}");

        return true;
        //$this->db->query("DELETE from users where id={$id}");


    }


}