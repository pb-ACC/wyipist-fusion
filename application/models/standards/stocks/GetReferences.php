<?php

class GetReferences extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database('default');
        $this->load->helper('url');
        $this->load->library('session');
        //$this->db->reconnect();
    }


    public function referencias($empresa){
        $sql = "SELECT B.Artigo, B.Referencia, B.Descricao, substring(C.Descricao,1,50) Formato, C.Codigo CodigoFormato, cast(row_number() over(order by B.Artigo, B.Referencia)-1 AS int) Id, 
                       case when B.Empresa='CERAGNI' then isnull(B.EAN13,'') else isnull(B.CodigoBarras,'') end CodigoBarras, isnull(B.TipoEcra,'') TipoEcra, 
                       cast(case when B.Empresa='CERAGNI' then '' else 'P' end as varchar(1)) Serie, isnull(B.TipoEcra,'') TipoEcra, 
                       cast(0 as int) Sel
                from Artigos A join ReferArt          B on ((case when isnull(B.RefCeragni,0)=0 then 'CERTECA' else 'CERAGNI' end)='{$empresa}' and A.Codigo=B.Artigo)
                          left join Formato           C on (B.Formato=C.Codigo)
                          left join zxEscolhaAlargada D on (B.Referencia=D.Referencia) 
                where isnull(A.Inactivo,0)=0 and isnull(B.Inactivo,0)=0
                order by B.Artigo, B.Referencia"; 
        $query = $this->db->query($sql);
        $result = $query->result();
        return $result;
        //B.empresa='{$empresa}' 
    }
      
}