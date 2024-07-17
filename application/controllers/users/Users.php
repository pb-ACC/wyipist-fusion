<?php

class Users extends CI_Controller
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

    function index(){

        $this->load->helper('url');
        $this->load->library('session');

        if($this->session->userdata('logged_in')) {

            $session_data = $this->session->userdata('logged_in');

            $this->load->view('structure/header', $session_data);
            $this->load->view('structure/side_menu', $session_data);
            $this->load->view('template/users/users', $session_data);
            $this->load->view('structure/footer', $session_data);

        }else{
            redirect('start', 'refresh');
        }
    }

    function listUsers(){

        $this->load->helper('url');
        $this->load->library('session');

        if($this->session->userdata('logged_in')) {
            
            //$session_data = $this->session->userdata('logged_in');
            //$client = $session_data['empresa'];

            $this->load->model('users/Users_model');
            echo json_encode($this->Users_model->listUsers());
        }else{
            redirect('start', 'refresh');
        }
    }


    function editUsers(){

        $this->load->helper('url');
        $this->load->library('session');

        if($this->session->userdata('logged_in')) {
            $session_data = $this->session->userdata('logged_in');

            $client=$session_data['client'];

            $id = $this->input->post('id');
            $name = $this->input->post('name');
            $phone = $this->input->post('phone');
            $email = $this->input->post('email');
            $user = $this->input->post('user');
            $pass = $this->input->post('pass');
            $user_gpac = $this->input->post('user_gpac');
            $type_id = $this->input->post('type_id');
            $empresa_type = $this->input->post('empresa_type');

            $this->load->model('users/users_model');
            echo json_encode($this->users_model->editUsers($id,$name,$phone,$email,$user,$pass,$user_gpac,$type_id,$client,$empresa_type));
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

            $session_data = $this->session->userdata('logged_in');

            //print_r($session_data);

            $client=$session_data['client'];

            $name = $this->input->post('name');
            $phone = $this->input->post('phone');
            $email = $this->input->post('email');
            $user = $this->input->post('user');
            $pass = $this->input->post('pass');
            $user_gpac = $this->input->post('user_gpac');
            $type_id = $this->input->post('type_id');
            $empresa_type = $this->input->post('empresa_type');

            $this->load->model('users/Users_model');
            echo json_encode($this->Users_model->addUsers($name,$phone,$email,$user,$pass,$user_gpac,$type_id, $client,$empresa_type));

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

            $this->load->model('users/users_model');
            echo json_encode($this->users_model->deleteUsers($id));

        }else{

            redirect('start', 'refresh');
        }

    }

}