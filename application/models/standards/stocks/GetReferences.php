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
                from Artigos A join ReferArt          B on ((case when isnull(B.RefCeragni,0)=0 and isnull(B.RefCerteca,0)=1 then 'CERTECA'
                                                                  when isnull(B.RefCeragni,0)=1 and isnull(B.RefCerteca,0)=0 then 'CERAGNI'
                                                                  when isnull(B.RefCeragni,0)=1 and isnull(B.RefCerteca,0)=1 then 'CERAGNI'
                                                                  when isnull(B.RefCeragni,0)=0 or isnull(B.RefCerteca,0)=1 then 'CERTECA'
                                                                  when isnull(B.RefCeragni,0)=1 or isnull(B.RefCerteca,0)=0 then 'CERAGNI' 
                                                                  else 'CERAGNI' 
                                                             end)='{$empresa}' and A.Codigo=B.Artigo)
                          left join Formato           C on (B.Formato=C.Codigo)
                          left join zxEscolhaAlargada D on (B.Referencia=D.Referencia) 
                where isnull(A.Inactivo,0)=0 and isnull(B.Inactivo,0)=0
                order by B.Artigo, B.Referencia"; 
        $query = $this->db->query($sql);
        $result = $query->result();
        return $result;
        //B.empresa='{$empresa}' 
    }

    public function referencias_segunda($empresa, $username, $funcionario_gpac){
        
        $this->load->dbforge();
        $user=strtoupper($funcionario_gpac);        
        if($user==''){
            $user=strtoupper($username);
        }    

        //DOC GERADOS
        $tbl01 = $user.'.MS_RefCOMST';
        $this->createTBL_RefCOMST($tbl01);

        $sql01="INSERT INTO ".$tbl01." (Artigo, Referencia, Descricao, Sel)".
                "SELECT B.Artigo, B.Referencia, B.Descricao, cast(0 as int) Sel
                from Artigos A join ReferArt          B on ((case when isnull(B.RefCeragni,0)=0 and isnull(B.RefCerteca,0)=1 then 'CERTECA'
                                                                  when isnull(B.RefCeragni,0)=1 and isnull(B.RefCerteca,0)=0 then 'CERAGNI'
                                                                  when isnull(B.RefCeragni,0)=1 and isnull(B.RefCerteca,0)=1 then 'CERAGNI'
                                                                  when isnull(B.RefCeragni,0)=0 or isnull(B.RefCerteca,0)=1 then 'CERTECA'
                                                                  when isnull(B.RefCeragni,0)=1 or isnull(B.RefCerteca,0)=0 then 'CERAGNI' 
                                                                  else 'CERAGNI' 
                                                             end)='{$empresa}' and A.Codigo=B.Artigo)                        
                where (B.Descricao like '%COM%' or B.Descricao like '%4ª%' or B.Descricao like '%COM/ST%' or B.Descricao like '%4º%' or B.Descricao like '%ST%')     
                order by B.Artigo, B.Referencia";
                //echo $sql09;
        $this->db->query($sql01);
        $this->db->close();

        $sql02 = "DELETE from ".$tbl01." where Referencia like 'L%' or Referencia like 'D%' or Referencia like 'R%'"; 
        $this->db->query($sql02);
        $this->db->close();

        $sql03 = "SELECT Artigo, Referencia, Descricao, Sel FROM ".$tbl01;
        $query = $this->db->query($sql03);
        $result = $query->result();
        return $result;
        //B.empresa='{$empresa}' 
    }

    public function createTBL_RefCOMST($tbl){                        
        $this->dbforge->drop_table($tbl,TRUE);        
        $fields = array(           
            'Artigo' => array(
                               'type' => 'VARCHAR',
                               'constraint' => '50',
                               'null' => TRUE,
            ),
            'Referencia' => array(
                               'type' => 'VARCHAR',
                               'constraint' => '50',
                               'null' => TRUE,
            ),
            'Descricao' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'Sel' => array(
                'type' => 'INT'
            )   
            );
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table($tbl,TRUE);
    }
      
}