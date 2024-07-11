<?php

class Login extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->lang->load('login_form_portuguese', 'portuguese'); // carregar as linguas
        $GLOBALS['language'] = 'pt'; //lingua a ser usada por defeito
    }

    public function index()
    {
        $this->load->helper('url');
        $this->load->library('session');

        if ($this->session->userdata('logged_in')) {
            redirect('home', 'refresh'); //estrutura normal
        } else {
            $this->load->helper(array('form', 'language'));
            $this->load->library(array('form_validation'));
            $this->load->model('auth/login__model');

            $this->form_validation->set_rules('username', 'Username', 'required');
            $this->form_validation->set_rules('password', 'Password', 'required|callback_valid_user_pass');

            $required = $this->lang->line('error_required');
            $this->form_validation->set_message('required', $required); // substitui o erro nativo do required pelo que defini

            if ($this->form_validation->run() == FALSE) // da falso se as regras nao estiverem a correr ou se houver erros, o que acontece sempre quado a pag abre
            {
                $language = $GLOBALS['language']; // e necessario pra saber em que lingua vamos mostrar o formulario inicial
                $data = array("language" => $language);
                $this->load->view('auth/login', $data);

            } else {
                redirect('home', 'refresh'); //pagina principal
            }
        }
    }

    // verifica se a pass e user existem e estao correctos
    public function valid_user_pass($password){

        //echo "valid";

        $username = $this->input->post('username'); // podia ter passado como argumento assim como a password

        //query BD to check if this is a valid user
        $result = $this->login__model->check_valid_user($username,$password);
        // print_r($result);
        if($result)
        {
            //$sessao = array(
            //acrescentar esta informacao a sessao

            foreach($result as $row)
            {
                $sessao = array(
                    'id' => $row->id,                    
                    'username' => $row->username,
                    'nome' => $row->nome,
                    'email' => $row->email,
                    'telefone' => $row->telefone,
                    'type_id' => $row->type_id,
                    'user_type' => $row->user_type,                    
                    'funcionario_gpac' => $row->funcionario_gpac,
                    'unique_id' => $row->unique_id,
                    'funcao' => $row->funcao,
                    'logo_user' => $row->logo_user,
                    'empresa' => $row->empresa,  
                    'logo_empresa' => $row->logo_empresa,
                    'client' => $row->client,   
                    'sector_user' => $row->sector_user, 
                    'sector_pnc' => $row->sector_pnc, 
                    'language' => $GLOBALS['language']
                );
                //print_r($sessao);
                //echo $sessao;
                $this->session->set_userdata('logged_in',$sessao); // criar entrada na sessao com a info do user
            }
            return TRUE;
        }
        else
        {
            $error_login = $this->lang->line('error_login');
            $this->form_validation->set_message('valid_user_pass', $error_login); // traduzir a mensagem de erro
            return FALSE;
        }
    }

    public function change_lang($lang = "pt")
    {
        $GLOBALS['language'] = $lang;
        switch($lang)
        {
            case "pt":
                $this->lang->load('login_form_portuguese','portuguese');
                break;

            default:
                $this->lang->load('login_form_english','english');
                break;
        }
        $this->index();
    }

}
