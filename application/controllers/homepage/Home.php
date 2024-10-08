<?php

/**
 * Created by PhpStorm.
 * User: p-bri
 * Date: 29/10/2019
 * Time: 11:45
 */
class Home extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Europe/Lisbon');
        //funcao para testar se � necess�rio kikar o user
        $this->load->model('auth/login_model');//, 'reg_model');
        $this->login_model->to_kick();
    }

    function index(){
        $this->load->helper('url');
        $this->load->library('session');

        if($this->session->userdata('logged_in'))
        {
            $session_data = $this->session->userdata('logged_in');
			$ip = $_SERVER['REMOTE_ADDR'];
			$session_data['ip'] = $ip; // vamos adicionar o ip para o modo de manutencao saber atuar
            
			$this->load->model('auth/login_model');
			$this->load->view('structure/header', $session_data);
			$this->load->view('structure/side_menu', $session_data);
            if ($session_data['user_type'] == 1 || $session_data['user_type'] == 2) {            
                $this->load->view('homepage/dashboardAdmin', $session_data);
            } elseif ($session_data['user_type'] == 3) {
                $this->load->view('homepage/dashboardCom', $session_data);
            } elseif ($session_data['user_type'] == 4) {
                $this->load->view('homepage/dashboardGab', $session_data);
            } elseif ($session_data['user_type'] == 5) {
                $this->load->view('homepage/dashboardFin', $session_data);
            } elseif ($session_data['user_type'] == 6 || $session_data['user_type'] == 7) {
                $this->load->view('homepage/dashboardFab', $session_data);
            }			
			$this->load->view('structure/footer', $session_data);
        }
        else
        {            //If no session, redirect to login page
            redirect('start', 'refresh');
        }
    }

    function logout()
    {
        $this->load->helper('url');
        $this->load->library('session');
        $session_data = $this->session->userdata('logged_in');

        /* $files = glob('img/'.$session_data['username'].'/*'); // get all file names
         foreach($files as $file){ // iterate files
             if(is_file($file))
                 unlink($file); // delete file
         }
        $dir='img/'.$session_data['username'].'/';
        $this->delete_files($dir);*/
        $this->session->unset_userdata('logged_in');
        $this->session->sess_destroy();
        //session_destroy();
        redirect('start', 'refresh');
    }
    
}
