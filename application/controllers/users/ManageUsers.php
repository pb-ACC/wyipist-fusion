<?php

class ManageUsers extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Europe/Lisbon');
        // $this->load->model('chart_valco_model');

        //funcao para testar se � necess�rio kikar o user
        $this->load->model('auth/login_model');//, 'reg_model');
        $this->login_model->to_kick();
    }

    public function index(){

        $this->load->helper('url');
        $this->load->library('session');

        if($this->session->userdata('logged_in')) {

            $session_data = $this->session->userdata('logged_in');

            $this->load->view('structure/header', $session_data);
            $this->load->view('structure/side_menu', $session_data);
            $this->load->view('template/users/add_users', $session_data);
            $this->load->view('structure/footer', $session_data);

        }else{
            redirect('start', 'refresh');
        }
    }

    public function editUsers(){

        $this->load->helper('url');
        $this->load->library('session');

        if($this->session->userdata('logged_in')) {            

            $id = $this->input->post('id');
            $name = $this->input->post('name');
            $phone = $this->input->post('phone');
            $email = $this->input->post('email');
            $user = $this->input->post('user');
            $user_gpac = $this->input->post('user_gpac');
            $type_id = $this->input->post('type_id');
            $empresa_type = $this->input->post('empresa_type');

           // echo $empresa_type;

            $this->load->model('users/Manage_Users');
            echo json_encode($this->Manage_Users->editUsers($id,$name,$phone,$email,$user,$user_gpac,$type_id,$empresa_type));
        }else{

            redirect('start', 'refresh');
        }


    }

    function editUsersPerfil(){

        $this->load->helper('url');
        $this->load->library('session');

        if($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');

            //$client=$session_data['client'];

            $id = $this->input->post('id');
            //$name = $this->input->post('name');
            $phone = $this->input->post('phone');
            $email = $this->input->post('email');
            $pass = $this->input->post('pass');


            $this->load->model('users/users_model');
            echo json_encode($this->users_model->editUsersPerfil($id,$phone,$email,$pass));
        }else{

            redirect('start', 'refresh');
        }


    }


    function editUsersImageBD(){

        $this->load->library('session');

        if($this->session->userdata('logged_in')) {
            $id = $this->input->post('id');
            $file = $this->input->post('file');
            $this->load->model('users/users_model');
            echo json_encode($this->users_model->editUsersImageBD($id,$file));
        }else{

            redirect('start', 'refresh');
        }


    }


    function addUsers(){

        $this->load->helper('url');
        $this->load->library('session');

        if($this->session->userdata('logged_in')) {

            $nome = $_POST['nome'];
            $contacto = $_POST['contacto'];
            $email = $_POST['email'];
            $cargo = $_POST['cargo'];
            $user = $_POST['user'];
            $user_type = $_POST['user_type'];
            $userGPAC = $_POST['userGPAC'];
            $empresa_type = $_POST['empresa_type'];
            $password = $_POST['password'];
            $file = $_FILES['file'];

            //echo $nome.' '.$contacto.' '.$email.' '.$cargo.' '.$user.' '.$user_type.' '.$userGPAC.' '.$empresa_type.' '.$password;
            $this->load->model('users/Manage_Users');
            echo json_encode($this->Manage_Users->addUsers($nome,$contacto,$email,$cargo,$user,$user_type,$userGPAC,$empresa_type,$password,$file));
            //redirect('users/add-user','refresh');

        }else{

            redirect('start', 'refresh');
        }
    }


    function addUsersImageBD(){

        $this->load->library('session');

        if($this->session->userdata('logged_in')) {
            $file = $this->input->post('file');


            $this->load->model('users/users_model');
            echo json_encode($this->users_model->addUsersImageBD($file));

        }else{

            redirect('start', 'refresh');
        }


    }


    function deleteUser(){

        $this->load->library('session');

        if($this->session->userdata('logged_in')) {
            $id = $this->input->post('id');

            $this->load->model('users/Manage_Users');
            echo json_encode($this->Manage_Users->deleteUsers($id));

        }else{

            redirect('start', 'refresh');
        }

    }

}