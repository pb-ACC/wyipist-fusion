<?php

class Recolhe_Dados extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database('default');
        $this->load->helper('url');
        $this->load->library('session');
        //$this->db->reconnect();
    }

    public function recolhe_dados($st_arm, $st_prod, $st_ver, $st_exp, $cond, $username, $funcionario_gpac){
  
        $user=strtoupper($funcionario_gpac);        
        if($user==''){
            $user=strtoupper($username);
        }    
        $this->agrupa_tudo($st_arm, $st_prod, $st_ver, $st_exp, $cond, $user);
    }

    function agrupa_tudo($st_arm, $st_prod, $st_ver, $st_exp, $cond, $user){
        $this->load->dbforge();
        $tbl01 = $user.'.MS_RefP';
        $this->createTBL_refP($tbl01);

        $sql01 = "INSERT into ". $tbl01 ." (Referencia) ".
                 "SELECT Referencia from ReferArt 
                  where isnull(Referencia,'')<>'' ". $cond ." group by Referencia";
        $this->db->query($sql01);
        $this->db->close();


        $sql07="SELECT Referencia
                FROM ". $tbl01;
        //echo $sql01;
        $query = $this->db->query($sql07);
        $result = $query->result();
        return $result;   

    }

    /** TABLES */
    public function createTBL_refP($tbl){
                        
        $this->dbforge->drop_table($tbl,TRUE);
        
        $fields = array(
            'Referencia' => array(
                'type' => 'VARCHAR',
                'constraint' => '50'
            )
        );
        
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table($tbl,TRUE);
    }

}