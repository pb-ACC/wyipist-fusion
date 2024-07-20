<?php

class GetZonas extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database('default');
        $this->load->helper('url');
        $this->load->library('session');
    }

    public function zonaCelula($empresa){

        $sql = "SELECT Codigo,Sector,Zona,Celula,Fila,Posicao,case when Empresa='CERAGNI' then Codigo else CONCAT(Zona,Celula) end CodigoBarras, cast(0 as int) Sel,
                       case when Empresa='CERAGNI' then 'CT' else substring(Sector,1,2) end Identificador, (row_number() over(order by Codigo asc)-1) id
                       --case when Empresa='CERAGNI' then substring(Codigo,1,2) else substring(Sector,1,2) end Identificador
                FROM zx_Locais 
                WHERE Empresa in ({$empresa}) and isnull(Zona,'')<>'' and isnull(Celula,'') not in ('','*')";
                
        $query = $this->db->query($sql);
        $result = $query->result();
        return $result;
    }

}