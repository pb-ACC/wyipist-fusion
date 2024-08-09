<?php

class Conclusao_Manual extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database('default');
        $this->load->helper('url');
        $this->load->library('session');
        //$this->db->reconnect();
    }

    public function concluir_linha_manualmente($NumeroLinha, $NumeroDocumento, $DocumentoCarga, $Quantidade, $QtdPaletizada, $username, $funcionario_gpac){

        $user=strtoupper($funcionario_gpac);        
        if($user==''){
            $user=strtoupper($username);
        }

        //ATUALIZA QTDPALETIZADA E CONCLUI LINHA
        $sql01 = "UPDATE A
                  set A.QtdPaletizada=A.Quantidade, A.Concluido=1
                  from VdLEncs A on (A.NumeroLinha={$NumeroLinha} and A.NumeroDocumento='{$NumeroDocumento}')";
        $this->db->query($sql01);
		        
        $sql02 = "INSERT into zx_LogConcluido (LinhaDocumento, Documento, Operador, DataHora, Accao)
                  VALUES ({$NumeroLinha}, '{$NumeroDocumento}', '{$user}', getdate(), 'CONCLUI LINHA MANUALMENTE PELA WEB:Conclusao_Manual#concluir_linha_manualmente_sql02')";
		$this->db->query($sql02);
		
        //FECHA ENCOMENDAS
        $sql03 = "UPDATE A
                  set A.Concluido=0
                  from VdLEncs A
                  where A.Concluido is null";
        $this->db->query($sql03);		

        $this->load->dbforge();

        //GTS FEITAS
        $tbl01 = $user.'.MS_GTsFeitas';
        $this->createTBL_GTsFeitas($tbl01);
        $sql04="INSERT INTO ". $tbl01 ." (LinhaDocumento,Documento,Quantidade)".
               "SELECT B.LinhaDocumento, B.Documento, sum(B.Quantidade) Quantidade
               from VdDocs A join VdLDocs B on (A.Codigo='GT' and A.Estado<>'A' and A.Numero=B.DocumentoTransporte)
               group by B.LinhaDocumento, B.Documento";        
        $this->db->query($sql04);

        //ENS POR FECHAR
        $tbl02 = $user.'.MS_ENsPorFechar';
        $this->createTBL_ENsPorFechar($tbl02);
        $sql05="INSERT INTO ". $tbl02 ." (NumeroLinha,NumeroDocumento,Quantidade,Estado,QtdGT)".
               "SELECT B.NumeroLinha, B.NumeroDocumento, B.Quantidade, A.Estado, 0
                from VdEncs A join VdLEncs B on (A.Estado<>'A' and A.Numero=B.NumeroDocumento)
                where isnull(B.Concluido,0)=0";        
        $this->db->query($sql05);

        $sql06="UPDATE A
                set A.QtdGT=isnull(B.Quantidade,0)
                from ".$tbl02." A join ".$tbl01." B on (A.NumeroLinha=B.LinhaDocumento and A.NumeroDocumento=B.Documento)";
        $this->db->query($sql06);

        $sql07="UPDATE A
                set A.QtdGT=A.Quantidade
                from ".$tbl02." A where A.Estado='E' and isnull(A.QtdGT,0)<A.Quantidade";
        $this->db->query($sql07);

        $sql08="DELETE from ".$tbl02." where QtdGT<Quantidade";
        $this->db->query($sql08);

        // Verifica se a tabela ainda contém registros após o DELETE
        $this->db->from($tbl02);
        $num_rows01 = $this->db->count_all_results();

        if ($num_rows01 > 0) {

            $sql09="UPDATE A
                    set A.Concluido=1
                    from ".$tbl02." B join VdLEncs A on (A.NumeroLinha=B.NumeroLinha and A.NumeroDocumento=B.NumeroDocumento)";
            $this->db->query($sql09);

            $sql10 = "INSERT into zx_LogConcluido (LinhaDocumento, Documento, Operador, DataHora, Accao)
                      select NumeroLinha, NumeroDocumento, 'GPAC-AUTO', getdate(), 'CONCLUI LINHA MANUALMENTE PELA WEB:Conclusao_Manual#concluir_linha_manualmente_sql10' 
                      from ".$tbl02." ";
            $this->db->query($sql10);     

            $this->passoulinhaen($user);      

        } else {
            $this->passoulinhaen($user);      
        }   

    }

    public function passoulinhaen($user){

            //FECHA EN
            $tbl03 = $user.'.MS_FechaEN';
            $this->createTBL_FechaEN($tbl03);
            $sql11="INSERT INTO ". $tbl03 ." (Numero,TotLinhas,TotLinhasConc)".
                   "SELECT A.Numero, count(B.NumeroLinha) TotLinhas, cast(0 as float) TotLinhasConc
                    from VdEncs A join VdLEncs B on (A.Estado not in ('A','E') and A.Numero=B.NumeroDocumento)
                    where B.Artigo<>'TRANSPORTE'
                    group by A.Numero";        
            $this->db->query($sql11);

            $sql12="UPDATE A
                    set A.TotLinhasConc=isnull((select count(B.NumeroLinha) from VdLEncs B where A.Numero=B.NumeroDocumento and isnull(B.Concluido,0)=1),0)
                    from ".$tbl03." A";
            $this->db->query($sql12);

            $sql13="DELETE from ".$tbl03." where TotLinhasConc<TotLinhas";
            $this->db->query($sql13);

            // Verifica se a tabela ainda contém registros após o DELETE
            $this->db->from($tbl03);
            $num_rows02 = $this->db->count_all_results();

            if ($num_rows02 > 0) {
                // A tabela ainda contém dados
                $sql14="INSERT INTO EstadoLog (Documento, Accao, Maquina, DataHoraMov, OperadorMOV, Estado, Sistema, OperSistH)
                        select Numero, 'BLOQUEOU', 'TERMINAL WEB', getdate(), 'GPAC-AUTO', 'E', 0, 'WEB'
                        from ".$tbl03." ";
                $this->db->query($sql14);     
                
                $sql15="UPDATE B
                        set B.Estado='E'
                        from ".$tbl03." A join VdEncs B on (A.Numero=B.Numero)";
                $this->db->query($sql15);
                
                $sql16="UPDATE A
                        set A.Cor=B.Cor
                        from VdEncs A join Estado B on (A.Codigo=B.TipoDoc and A.Estado=B.Codigo)
                        where A.Numero in (select Numero from ".$tbl03.")";
                $this->db->query($sql16);
                $this->db->close();                
            } else {
                // A tabela está vazia
                $this->db->close();                
            }
    }

    public function createTBL_GTsFeitas($tbl){
        $this->dbforge->drop_table($tbl,TRUE);
        $fields = array(
            'LinhaDocumento' => array(
                            'type' => 'INT',
                            'null' => TRUE                           
                            ),   
            'Documento' => array(
                            'type' => 'VARCHAR',
                            'constraint' => '50',  
                            'null' => TRUE                           
                            ),                                                                                 
            'Quantidade' => array(
                               'type' => 'FLOAT',
                               'null' => TRUE
                           )
            );
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table($tbl,TRUE);
    }

    public function createTBL_ENsPorFechar($tbl){
        $this->dbforge->drop_table($tbl,TRUE);
        $fields = array(
            'NumeroLinha' => array(
                            'type' => 'INT',
                            'null' => TRUE                           
                            ),   
            'NumeroDocumento' => array(
                            'type' => 'VARCHAR',
                            'constraint' => '50',  
                            'null' => TRUE                           
                            ),                                                                                 
            'Estado' => array(
                            'type' => 'VARCHAR',
                            'constraint' => '5',  
                            'null' => TRUE                           
                            ),                                                                                                             
            'QtdGT' => array(
                               'type' => 'FLOAT',
                               'null' => TRUE
                           )
            );
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table($tbl,TRUE);
    }

    public function createTBL_FechaEN($tbl){
        $this->dbforge->drop_table($tbl,TRUE);
        $fields = array(            
            'Numero' => array(
                            'type' => 'VARCHAR',
                            'constraint' => '50',  
                            'null' => TRUE                           
                            ),                                                                                 
            'TotLinhas' => array(
                            'type' => 'INT',
                            'null' => TRUE                           
                            ),                                                                                                             
            'TotLinhasConc' => array(
                               'type' => 'FLOAT',
                               'null' => TRUE
                           )
            );
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table($tbl,TRUE);
    }

}