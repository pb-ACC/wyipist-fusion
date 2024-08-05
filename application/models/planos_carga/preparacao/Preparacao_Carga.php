<?php

class Preparacao_Carga extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database('default');
        $this->load->helper('url');
        $this->load->library('session');
        //$this->db->reconnect();
    }

    public function getPreparacaoCarga($tipoDoc,$serie,$estado){

        $sql = "SELECT cast(0 as int) Sel, F.Nome Cliente, D.Numero DocumentoCarga, '' NumeroDocumento, D.DataPrevista, '' VossaRef, '' NossaRef, D.VossaRef Responsavel, D.NossaRef NossaRefGG, '' DataEntrega, 
                       row_number() over(order by D.DataPrevista, D.Numero)-1 Ordem, D.Serie, convert(char,D.Data,105) Data, cast(null as datetime) UltimaEdicao
                from VdEncs B join Clientes      F on (B.Terceiro=F.Codigo)
                              join VdLEncs       C on (B.Numero=C.NumeroDocumento)
                              join PrGuias       D on (C.DocumentoCarga=D.Numero and D.Estado='{$estado}' and D.Serie='{$serie}' and D.Codigo='{$tipoDoc}')
                where isnull(C.DocumentoCarga,'')<>'' and isnull(C.Referencia,'')<>''
                group by F.Nome, D.Numero, D.DataPrevista, D.VossaRef, D.NossaRef, D.Serie, convert(char,D.Data,105)";
        $query = $this->db->query($sql);
        $result = $query->result();
        return $result;    
                
    }


    public function getLinhasGG($plano,$seriePL,$username,$funcionario_gpac){

        $this->load->dbforge();

        $user=strtoupper($funcionario_gpac);        
        if($user==''){
            $user=strtoupper($username);
        }        
        //RECOLHE_QTD_PALETE
        $tbl01 = $user.'.MS_QtdPL';
        $this->createTBL_QtdPL($tbl01);
        $sql01="INSERT INTO ". $tbl01 ." (LinhaDocumento,Documento,Quantidade)".
                "SELECT A.LinhaDocumento, A.Documento, round(sum(A.Quantidade),4) Quantidade
                 from PlLDocs A join PlDocs B on (A.NumeroDocumento=B.Numero)
                 where B.Serie='{$seriePL}' and B.Estado<>'A'
                 group by A.LinhaDocumento, A.Documento";        
        $this->db->query($sql01);
        //echo $query;

        //RECOLHE_DATA HORA ÚLTIMA EDIÇÃO
        $tbl02 = $user.'.MS_UltimaEdicao';
        $this->createTBL_UltEdi($tbl02);
        $sql02="INSERT INTO ". $tbl02 ." (Documento,HoraUltimaEdicao)".
               "SELECT Documento, max(B.HoraUltimaEdicao) 
                from DocLog B 
                WHERE B.HoraUltimaEdicao IS NOT NULL  
                group by B.Documento";        
        $this->db->query($sql02);

        //RECOLHE_DATA HORA ÚLTIMA EDIÇÃO
        $tbl03 = $user.'.MS_Main';
        $this->createTBL_main($tbl03);
        $sql03="INSERT INTO ". $tbl03 ." (x, Sel, Cliente, DocumentoCarga, NumeroDocumento, NumeroLinha, LinhaMae, Quantidade, Unidade, Referencia, Artigo, DescricaoArtigo, Apontamentos, Formato, RefCor, Qual, 
                                          TipoEmbalagem, Superficie, TabEspessura, Lote, Calibre, Decoracao, Acabamento, Coleccao, Chave, DataPrevista, VossaRef, NossaRef, Responsavel, NossaRefGG, DataEntregaCliente, 
                                          QtdLinhaEN, PesoLiquido, PesoBruto, TotalVolumes, QtdPaletizada, QtdFalta, Ordem, UltimaEdicao)".
               "SELECT '.', 0, F.Nome, C.DocumentoCarga, C.NumeroDocumento, C.NumeroLinha, C.LinhaMae, ROUND(C.Quantidade, 4), C.Unidade, C.Referencia, C.Artigo, C.DescricaoArtigo, C.Descricao, C.Formato, C.RefCor, C.Qual, C.TipoEmbalagem,
                       C.Superficie, C.TabEspessura, '', '', C.Decoracao, C.Acabamento, C.Coleccao, '',D.DataPrevista, B.VossaRef, B.NossaRef, B.VossaRef, B.NossaRef, C.DataEntrega, C.Quantidade, C.TotalPeso, C.TotalPeso2, C.TotalNVolumes, 0,
                       0, row_number() over(order by D.DataPrevista, D.Numero, C.Ordena)-1,''
                from VdEncs B join Clientes      F on (B.Terceiro=F.Codigo)
                              join VdLEncs       C on (B.Numero=C.NumeroDocumento)
                              join PrGuias       D on (C.DocumentoCarga=D.Numero and D.Numero='{$plano}')
                where isnull(C.DocumentoCarga,'')<>'' and isnull(C.Referencia,'')<>''
                group by F.Nome, D.Numero, C.DocumentoCarga, C.NumeroDocumento, C.NumeroLinha, C.LinhaMae, C.Unidade, C.Referencia, C.Artigo, C.DescricaoArtigo, C.Descricao, C.Formato, C.RefCor, C.Qual, C.TipoEmbalagem, C.Superficie, C.TabEspessura,
         C.Decoracao, C.Acabamento, C.Coleccao, D.DataPrevista, B.VossaRef, B.NossaRef, B.VossaRef, B.NossaRef, C.DataEntrega, C.Quantidade, C.TotalPeso, C.TotalPeso2, C.TotalNVolumes, C.Ordena";
        $this->db->query($sql03);
        //echo $sql03;
        
        $sql04="UPDATE A
                set A.QtdPaletizada=round(isnull(B.Quantidade,0),4)
                from ".$tbl03." A join ".$tbl01." B on (A.NumeroLinha=B.LinhaDocumento and A.NumeroDocumento=B.Documento)";
        $this->db->query($sql04);

        $sql05="UPDATE A
                set A.UltimaEdicao=convert(char(8),B.DataUltimaEdicao,112)+' '+convert(char(8),B.HoraUltimaEdicao,108)
                from ".$tbl03." A join ".$tbl02." B on (A.DocumentoCarga=B.Documento)";
        $this->db->query($sql05);


        $sql06="SELECT Sel,Cliente,DocumentoCarga,NumeroDocumento,NumeroLinha,LinhaMae,Quantidade,Unidade,Referencia,Artigo,DescricaoArtigo,Apontamentos,Formato,RefCor,Qual,TipoEmbalagem,Superficie,
                       TabEspessura,Lote,Calibre,Decoracao,Acabamento,Coleccao,Chave,DataPrevista,VossaRef,NossaRef,Responsavel,NossaRefGG,DataEntregaCliente,QtdLinhaEN,PesoLiquido,PesoBruto,TotalVolumes,
                       QtdPaletizada,QtdFalta,Ordem,UltimaEdicao
                FROM ". $tbl03;
        //echo $sql06;
        $query = $this->db->query($sql06);
        $result = $query->result();
        return $result;    

                /*$sql03 = "SELECT cast(0 as int) Sel, F.Nome Cliente, C.DocumentoCarga, C.NumeroDocumento, C.NumeroLinha, C.LinhaMae, round(C.Quantidade,4) Quantidade, C.Unidade, C.Referencia, C.Artigo, C.DescricaoArtigo, C.Descricao, 
                       C.Formato, C.RefCor, C.Qual, C.TipoEmbalagem, C.Superficie, C.TabEspessura, cast('' as varchar(15)) Lote, cast('' as varchar(15)) Calibre, cast('' as varchar(255)) Chave, D.DataPrevista, B.VossaRef, 
                       B.NossaRef, D.VossaRef Responsavel, D.NossaRef NossaRefGG, C.DataEntrega, row_number() over(order by D.DataPrevista, D.Numero, C.Ordena)-1 Ordem, C.Quantidade, C.TotalPeso, C.TotalPeso2, 
                       C.TotalNVolumes, cast(0 as float) QtdPaletizada, C.Decoracao, C.Acabamento, C.Coleccao, D.Serie, convert(char,D.Data,105) Data, cast(null as datetime) UltimaEdicao
                from VdEncs B join Clientes      F on (B.Terceiro=F.Codigo)
                              join VdLEncs       C on (B.Numero=C.NumeroDocumento)
                              join PrGuias       D on (C.DocumentoCarga=D.Numero and D.Estado='{$estado}' and D.Serie='{$serie}' and D.Codigo='{$tipoDoc}')
                where isnull(C.DocumentoCarga,'')<>'' and isnull(C.Referencia,'')<>''";
                */
    }
    
    public function createTBL_QtdPL($tbl){
                        
        $this->dbforge->drop_table($tbl,TRUE);        
        $fields = array(
            'LinhaDocumento' => array(
                            'type' => 'INT',
                            'null' => TRUE
                        ),            
            'Documento' => array(
                               'type' => 'VARCHAR',
                               'constraint' => '50',
                               'null' => TRUE,
                           ), 
            'Quantidade' => array(
                                'type' => 'FLOAT',
                                'null' => TRUE
                            )
            );
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table($tbl,TRUE);
    }

    public function createTBL_UltEdi($tbl){

        $this->dbforge->drop_table($tbl,TRUE);
        $fields = array(
            'Documento' => array(
                            'type' => 'VARCHAR',
                            'constraint' => '50',  
                            'null' => TRUE                           
                            ),                                                     
            'HoraUltimaEdicao' => array(
                               'type' => 'DATETIME',
                               'null' => TRUE
                           )
            );
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table($tbl,TRUE);
    }

    public function createTBL_main($tbl){
                        
        $this->dbforge->drop_table($tbl,TRUE);
        
        $fields = array(
            'x' => array(
                'type' => 'VARCHAR',
                'constraint' => '1',
                'null' => TRUE
            ),
            'Sel' => array(
                'type' => 'INT',
                'null' => TRUE
            ),
            'Cliente' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ),
            'DocumentoCarga' => array(
                'type' => 'VARCHAR',
                'constraint' => '17',
                'null' => TRUE
            ),
            'NumeroDocumento' => array(
                'type' => 'VARCHAR',
                'constraint' => '17',
                'null' => TRUE
            ),
            'NumeroLinha' => array(
                'type' => 'INT',
                'null' => TRUE
            ),
            'LinhaMae' => array(
                'type' => 'INT',
                'null' => TRUE
            ),
            'Quantidade' => array(
                'type' => 'FLOAT',
                'null' => TRUE
            ),
            'Unidade' => array(
                'type' => 'VARCHAR',
                'constraint' => '3',
                'null' => TRUE
            ),
            'Referencia' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE
            ),
            'Artigo' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE
            ),
            'DescricaoArtigo' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ),
            'Apontamentos' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ),
            'Formato' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE
            ),
            'RefCor' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE
            ),
            'Qual' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE
            ),
            'TipoEmbalagem' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE
            ),
            'Superficie' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE
            ),
            'TabEspessura' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE
            ),
            'Lote' => array(
                'type' => 'VARCHAR',
                'constraint' => '10',
                'null' => TRUE
            ),
            'Calibre' => array(
                'type' => 'VARCHAR',
                'constraint' => '10',
                'null' => TRUE
            ),
            'Decoracao' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE
            ),
            'Acabamento' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE
            ),
            'Coleccao' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE
            ),
            'Chave' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ),
            'DataPrevista' => array(
                'type' => 'DATETIME',
                'null' => TRUE
            ),
            'VossaRef' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => TRUE
            ),
            'NossaRef' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => TRUE
            ),
            'Responsavel' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => TRUE
            ),
            'NossaRefGG' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => TRUE
            ),
            'DataEntregaCliente' => array(
                'type' => 'DATETIME',
                'null' => TRUE
            ),
            'QtdLinhaEN' => array(
                'type' => 'FLOAT',
                'null' => TRUE
            ),
            'PesoLiquido' => array(
                'type' => 'FLOAT',
                'null' => TRUE
            ),
            'PesoBruto' => array(
                'type' => 'FLOAT',
                'null' => TRUE
            ),
            'TotalVolumes' => array(
                'type' => 'FLOAT',
                'null' => TRUE
            ),
            'QtdPaletizada' => array(
                'type' => 'FLOAT',
                'null' => TRUE
            ),
            'QtdFalta' => array(
                'type' => 'FLOAT',
                'null' => TRUE
            ),
            'Ordem' => array(
                'type' => 'INT',
                'null' => TRUE
            ),
            'UltimaEdicao' => array(
                'type' => 'DATETIME',
                'null' => TRUE
            )
        );
        
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table($tbl,TRUE);
    }

}