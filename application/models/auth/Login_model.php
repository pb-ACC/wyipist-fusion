<?php

class Login_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database('login');
        $this->load->helper('url');
        $this->load->library('session');
        //$this->db->reconnect();
    }

    public function check_valid_user($user,$pass)
    {
        //posso receber os dados por agrumento ou ir buscar directo..
        // $user = $this->input->post('username');
        // $pass = $this->input->post('password');

        /*
          Codigo empresa: 
          1 - Ceragni
          2- Certeca
          3- Ambas
        */
        //echo "entrei";        
        $sql = "SELECT A.id, A.username, A.nome, A.user_type type_id, A.user_type, A.funcionario_gpac, A.unique_id, A.funcao, C.logo_user, B.empresa, B.logo_empresa, A.client, A.codigo_empresa
                FROM users A join clients         B on (A.client=B.id) 
                             join template_images C on (A.id=C.id_user)
               where A.username=UPPER('$user') AND A.password=md5('$pass') and A.active=1";
       //echo $sql;

        $query = $this->db->query($sql);
        // $this->db->close();
        if($query->num_rows()==1)
        {
            //inserir o ID unico neste user
            $uniqueID = uniqid('', true);
            $this->db->query("UPDATE users SET unique_id='$uniqueID' WHERE username=UPPER('$user') AND password=md5('$pass')");

            //so para ir buscar o resultado atualizado
            $query = $this->db->query($sql);
            return $query->result();


        }
        else
            return FALSE;


    }

    // funcao que verifica se outra pessoa entrou com a conta atual, nesse caso é dada a ordem de expulsao ao user atual
    public function to_kick()
    {


        $this->load->database('login');


        //echo $username;
       // print_r($_SESSION['logged_in']);
        //echo isset($_SESSION['logged_in']);
        //echo 'size asdasdasdasd ' . count($_SESSION['logged_in']);

        if(isset($_SESSION['logged_in'])){//} && $_SESSION['logged_in'] == 1) {

            $session_data = $this->session->userdata('logged_in');
            $username = $session_data['username'];
            $session_id = $session_data['unique_id'];


            $sql = "SELECT unique_id FROM users where username=UPPER('$username')";

            //echo $sql;

            $query = $this->db->query($sql);
            $row = $query->row();
            $user_id = $row->unique_id; // n sei se é isto
           //  echo '<script>alert('.$session_id.');</script>';
             //echo '<script>alert('.$user_id.');</script>';
            
             $this->db->close();

            if ($user_id ===$session_id)
            {
                //return false; // sao o mesmo portanto o user pode continuar
                //vamos adicionar uma interaccao
                $this->load->database('login');
                $sql2 = "UPDATE user_log
                    set interaction = IFNULL(interaction,0) + 1
                    where unique_id = '$session_id'";
                $this->db->query($sql2);
                $this->db->close();
            }
            else
            {
                $this->session->unset_userdata('logged_in');
                $this->session->sess_destroy();

                echo json_encode("kick");
                exit();


            }


        } else if(!isset($_SESSION['logged_in']) || (isset($_SESION['logged_in']) && $_SESSION['logged_in'] == 0)){


            redirect('start', 'refresh');


        }





    }

}
