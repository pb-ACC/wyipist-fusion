<?php

class GetEmpresa extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database('default');
        $this->load->helper('url');
        $this->load->library('session');
    }

    public function getEmpresa($username,$funcionario_gpac,$user_type){

        $sql = "SELECT case when Empresa='CERAGNI' and {$user_type} in (1,2) then 3 
                            when Empresa='CERAGNI' and {$user_type} not in (1,2) then 1 
                            when Empresa='CERTECA' then 2 
                            else 3 end TipoEmpresa, 
                        Empresa 
                FROM zxOperEmpresaWEB 
                WHERE Operador='{$username}' or Operador='{$funcionario_gpac}'
                GROUP BY case when Empresa='CERAGNI' and {$user_type} in (1,2) then 3 
                              when Empresa='CERAGNI' and {$user_type} not in (1,2) then 1 
                              when Empresa='CERTECA' then 2 
                            else 3 end, Empresa";
                
        $query = $this->db->query($sql);
        $result = $query->result();
        return $result;
    }

}
