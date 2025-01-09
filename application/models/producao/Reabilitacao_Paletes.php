<?php

class Reabilitacao_Paletes extends CI_Model
{


    public function __construct()
    {
        parent::__construct();
        $this->load->database('default');
        $this->load->helper('url');
        $this->load->library('session');
        //$this->db->reconnect();
    }

    public function save_rehabilitation($DocPL,$LinhaPL,$Referencia,$Artigo,$DescricaoArtigo,$Quantidade,$NovaQtd,$Unidade,$Sector,$Formato,$Qual,$TipoEmbalagem,$Superficie,$Decoracao,$RefCor,$Lote,$TabEspessura,$Calibre,
                                        $Nivel,$novoST,$username,$funcionario_gpac)     
    {
        $this->load->dbforge();
        $user=strtoupper($funcionario_gpac);        
        if($user==''){
            $user=strtoupper($username);
        }            

        //DOC GERADOS
        $tbl01 = $user.'.MS_DocsGerados';
        $this->createTBL_DocsGerados($tbl01);
                /*STOCKS*/
        //cria SP
        $DocSP=$this->getMax_SP();
        //print_r($DocSP);        
        foreach ($DocSP as $val) {
			$CodigoSP = $val->Documento;
            $SerieSP = $val->Serie;  
            $NumeradorSP = $val->Novo_Numerador;          
            $NumeroSP = $val->Numero;
		}
        $_SESSION["DocSP"]=$NumeroSP;
        $sql02="UPDATE A
        set A.DocSP='{$NumeroSP}'
        from ".$tbl01." A";
        $this->db->query($sql02);
           
        $this->createSP($NumeroSP,$CodigoSP,$SerieSP,$user);
        $this->update_Series_SP($NumeradorSP,$CodigoSP,$SerieSP);

        /*movimentos*/
        $this->movimento_stock_paletes($NumeroSP,$CodigoSP,$DocPL,$LinhaPL,$Referencia,$Artigo,$DescricaoArtigo,$Quantidade,$NovaQtd,$Unidade,$Sector,$Formato,$Qual,$TipoEmbalagem,$Superficie,$Decoracao,$RefCor,$Lote,
                                       $TabEspessura,$Calibre,$Nivel,$novoST,$user);
    }

    public function createTBL_DocsGerados($tbl){                        
        $this->dbforge->drop_table($tbl,TRUE);        
        $fields = array(           
            'DocPL' => array(
                               'type' => 'VARCHAR',
                               'constraint' => '25',
                               'null' => TRUE,
            ),
            'DocSP' => array(
                               'type' => 'VARCHAR',
                               'constraint' => '25',
                               'null' => TRUE,
                           )
            );
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table($tbl,TRUE);
    }

    public function getMax_SP(){
        $sql="SELECT TOP 1 Documento, Serie, Numerador+1 Novo_Numerador, Documento+Serie+CAST(Numerador+1 as varchar(20)) Numero
              FROM Series
              WHERE Documento='SP' and Serie=''";         
        
        $query = $this->db->query($sql);        
        $result = $query->result();
        
        return $result;
    }

    public function createSP($Numero,$Codigo,$Serie,$user){

        $sql="INSERT INTO StkDocs (Numero,Codigo, Serie, Data, DataHoraMOV, OperadorMOV, Moeda, Cambio, Desconto, TipoTerceiro)
            VALUES ('{$Numero}', '{$Codigo}', '{$Serie}',convert(char(8),getdate(),112), getdate(), '{$user}', 'EUR', 1, '0', 'DO')";
		$this->db->query($sql); 
        $this->db->close();       
	}
    
    public function update_Series_SP($Numerador,$Codigo,$Serie){

		$sql ="UPDATE Series
			   SET Numerador={$Numerador}
			   WHERE Documento='{$Codigo}' and Serie='{$Serie}'";   
		$this->db->query($sql);	
        $this->db->close();
	}

    public function movimento_stock_paletes($NumeroSP,$CodigoSP,$DocPL,$LinhaPL,$Referencia,$Artigo,$DescricaoArtigo,$Quantidade,$NovaQtd,$Unidade,$Sector,$Formato,$Qual,$TipoEmbalagem,$Superficie,$Decoracao,$RefCor,$Lote,
                                            $TabEspessura,$Calibre,$Nivel,$novoST,$user){
        //$reluni='M2 = M2';
        $this->load->dbforge();
        //DOC GERADOS
        $tbl01 = $user.'.MS_ExeSP';
        $this->createTBL_ExeSP($tbl01);

        // sai PL do reabilitado
        $sql09="INSERT INTO ".$tbl01." (LinhaDocumento, NumeroDocumento, Documento, TipoMovimento, Sector, NumeroSerieInferior, NumeroSerieSuperior, Quantidade,
                                        QuantidadeUnidade, Referencia, Artigo, DescricaoArtigo, Formato, RefCor, Qual, TipoEmbalagem, Superficie, Lote, Calibre,
                                        Decoracao, Acabamento, Coleccao, TabEspessura, RefP, Unidade, Preco, PrecoNM, Desconto, Iva, TaxaIva,
                                        Local, KeyScript, Palete, DocPL, LinhaPL, TotalMercadoria, TotalDescontos, DescFin, TotalIliquido, TotalIva, TotalLiquido, TotalMercadoriaNM,
                                        TotalDescontosNM, DescFinNM, TotalIliquidoNM, TotalIvaNM, TotalLiquidoNM, OperadorMOV, DataHoraMOV)".
                "SELECT 0, '{$NumeroSP}', '', '12', '{$Sector}', 'REABILITADO 1', '', {$Quantidade}, {$Quantidade}, '{$Referencia}', '{$Artigo}', '{$DescricaoArtigo}', '{$Formato}', 
                       '{$RefCor}', '{$Qual}', '{$TipoEmbalagem}', '{$Superficie}', '{$Lote}', '{$Calibre}', '{$Decoracao}', B.Acabamento, B.Coleccao, '{$TabEspessura}', '{$Referencia}', 
                       '{$Unidade}', B.Preco, B.PrecoNM, '0', '00', 0, '', 'Paletizar_Carga#movimento_stock_paletes_sql09', '{$DocPL}', '{$DocPL}', {$LinhaPL}, B.Preco*{$Quantidade}, 0, 0, 
                       B.PrecoNM*{$Quantidade}, 0, B.PrecoNM*{$Quantidade}, B.PrecoNM*{$Quantidade}, 0, 0, B.PrecoNM*{$Quantidade}, 0, B.PrecoNM*{$Quantidade},'{$user}', getdate()
                FROM PlLDocs B
                where B.NumeroDocumento='{$DocPL}'";
            //echo $sql09;
            $this->db->query($sql09);
            $this->db->close();

            // PL entra para espera de movimentação
            $sql10="INSERT INTO ".$tbl01." (LinhaDocumento, NumeroDocumento, Documento, TipoMovimento, Sector, NumeroSerieInferior, NumeroSerieSuperior, Quantidade,
                                            QuantidadeUnidade, Referencia, Artigo, DescricaoArtigo, Formato, RefCor, Qual, TipoEmbalagem, Superficie, Lote, Calibre,
                                            Decoracao, Acabamento, Coleccao, TabEspessura, RefP, Unidade, Preco, PrecoNM, Desconto, Iva, TaxaIva,
                                            Local, KeyScript, Palete, DocPL, LinhaPL, TotalMercadoria, TotalDescontos, DescFin, TotalIliquido, TotalIva, TotalLiquido, TotalMercadoriaNM,
                                            TotalDescontosNM, DescFinNM, TotalIliquidoNM, TotalIvaNM, TotalLiquidoNM, OperadorMOV, DataHoraMOV)".
                "SELECT 0, '{$NumeroSP}', '', '02', '{$novoST}', 'REABILITADO 1', '', {$NovaQtd}, {$NovaQtd}, '{$Referencia}', '{$Artigo}', '{$DescricaoArtigo}', '{$Formato}', 
                       '{$RefCor}', '{$Qual}', '{$TipoEmbalagem}', '{$Superficie}', '{$Lote}', '{$Calibre}', '{$Decoracao}', B.Acabamento, B.Coleccao, '{$TabEspessura}', '{$Referencia}', 
                       '{$Unidade}', B.Preco, B.PrecoNM, '0', '00', 0, '', 'Paletizar_Carga#movimento_stock_paletes_sql09', '{$DocPL}', '{$DocPL}', {$LinhaPL}, B.PrecoNM*{$NovaQtd}, 0, 0, 
                       B.PrecoNM*{$NovaQtd}, 0, B.PrecoNM*{$NovaQtd}, B.PrecoNM*{$NovaQtd}, 0, 0, B.PrecoNM*{$NovaQtd}, 0, B.PrecoNM*{$NovaQtd},'{$user}', getdate()
                FROM PlLDocs B
                where B.NumeroDocumento='{$DocPL}'";
                    //echo $sql09;
            $this->db->query($sql10);
            $this->db->close();

            
            $sql13="INSERT INTO StkLDocs (LinhaDocumento, CodigoDocumento, NumeroDocumento, Documento, TipoMovimento, Sector, NumeroSerieInferior, NumeroSerieSuperior, Quantidade,
                                          QuantidadeUnidade, Referencia, Artigo, DescricaoArtigo, Formato, RefCor, Qual, TipoEmbalagem, Superficie, Lote, Calibre,
                                          Decoracao, Acabamento, Coleccao, TabEspessura, RefP, Unidade, Preco, PrecoNM, Desconto, Iva, TaxaIva,
                                          Local, KeyScript, Palete, DocPL, LinhaPL, TotalMercadoria, TotalDescontos, DescFin, TotalIliquido, TotalIva, TotalLiquido, TotalMercadoriaNM,
                                          TotalDescontosNM, DescFinNM, TotalIliquidoNM, TotalIvaNM, TotalLiquidoNM, OperadorMOV, DataHoraMOV,UnidadeStock,RelUni,FactorRelUni,DataStockMOV,Status,Changed,QuantidadeUnStock)".
                   "SELECT LinhaDocumento, 'SP', NumeroDocumento, Documento, TipoMovimento, Sector, NumeroSerieInferior, NumeroSerieSuperior, Quantidade, QuantidadeUnidade, Referencia, 
                           Artigo, DescricaoArtigo, Formato, RefCor, Qual, TipoEmbalagem, Superficie, Lote, Calibre, Decoracao, Acabamento, Coleccao, TabEspessura, RefP, 
                           Unidade, Preco, PrecoNM, Desconto, Iva, TaxaIva, Local, KeyScript, Palete, DocPL, LinhaPL, TotalMercadoria, TotalDescontos, DescFin, TotalIliquido, TotalIva, 
                           TotalLiquido, TotalMercadoriaNM, TotalDescontosNM, DescFinNM, TotalIliquidoNM, TotalIvaNM, TotalLiquidoNM, OperadorMOV, DataHoraMOV, Unidade, CONCAT(Unidade,' = ',Unidade), 1, 
                           DataHoraMOV, 'SP', 1, Quantidade
                    FROM ".$tbl01."";
                    //echo $sql09;
            $this->db->query($sql13);
            $this->db->close();

            $sql05 ="UPDATE StklDocs
                SET LinhaMae=NumeroLinha
                WHERE NumeroDocumento='{$NumeroSP}'";   
            $this->db->query($sql05);
            $this->db->close();

            $sql06 ="UPDATE StkLDocs
                    SET LoteInt=substring(cast(replicate('0',15-len(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(Lote,'a',''),'b',''),'c',''),'d',''),'e',''),'f',''),'g',''),'h',''),'i',''),'j',''),'k',''),'l',''),'m',''),'n',''),'o',''),'p',''),'q',''),'r',''),'s',''),'t',''),'u',''),'v',''),'w',''),'x',''),'y',''),'z',''),' ',''),',','.'),'ç',''),'?','')))+rtrim(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(Lote,'a',''),'b',''),'c',''),'d',''),'e',''),'f',''),'g',''),'h',''),'i',''),'j',''),'k',''),'l',''),'m',''),'n',''),'o',''),'p',''),'q',''),'r',''),'s',''),'t',''),'u',''),'v',''),'w',''),'x',''),'y',''),'z',''),' ',''),',','.'),'ç',''),'?','')) as varchar(15)),1,15)
                    where NumeroDocumento='{$NumeroSP}' and isnull(LoteInt,'')=''";
            $this->db->query($sql06);
            $this->db->close();  

            $sql ="UPDATE PlDocs
                    SET Reabilitado=1, DHReabilitado=getdate(), OPReabilitado='{$user}'
                    where Numero='{$DocPL}'";

            $this->db->query($sql);
            $this->db->close();  
    }

    public function createTBL_ExeSP($tbl){                        
        // Drop da tabela se ela existir
        $this->dbforge->drop_table($tbl, TRUE);
        // Definição dos campos da tabela
        $fields = array(
            'LinhaDocumento' => array(
                'type' => 'INT',                
                'null' => TRUE,
            ),
            'NumeroDocumento' => array(
                'type' => 'VARCHAR',
                'constraint' => '17',
                'null' => TRUE,
            ),
            'Documento' => array(
                'type' => 'VARCHAR',
                'constraint' => '17',
                'null' => TRUE,
            ),
            'TipoMovimento' => array(
                'type' => 'VARCHAR',
                'constraint' => '2',
                'null' => TRUE,
            ),
            'Sector' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE,
            ),
            'NumeroSerieInferior' => array(
                'type' => 'VARCHAR',
                'constraint' => '30',
                'null' => TRUE,
            ),
            'NumeroSerieSuperior' => array(
                'type' => 'VARCHAR',
                'constraint' => '30',
                'null' => TRUE,
            ),
            'Quantidade' => array(
                'type' => 'FLOAT',
                'null' => TRUE,
            ),
            'QuantidadeUnidade' => array(
                'type' => 'FLOAT',
                'null' => TRUE,
            ),
            'Referencia' => array(
                'type' => 'VARCHAR',
                'constraint' => '30',
                'null' => TRUE,
            ),
            'Artigo' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE,
            ),
            'DescricaoArtigo' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'Formato' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE,
            ),
            'RefCor' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE,
            ),
            'Qual' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE,
            ),
            'TipoEmbalagem' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE,
            ),
            'Superficie' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE,
            ),
            'Lote' => array(
                'type' => 'VARCHAR',
                'constraint' => '10',
                'null' => TRUE,
            ),
            'Calibre' => array(
                'type' => 'VARCHAR',
                'constraint' => '10',
                'null' => TRUE,
            ),
            'Decoracao' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE,
            ),
            'Acabamento' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE,
            ),
            'Coleccao' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE,
            ),
            'TabEspessura' => array(
                'type' => 'VARCHAR',
                'constraint' => '10',
                'null' => TRUE,
            ),
            'RefP' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE,
            ),
            'Unidade' => array(
                'type' => 'VARCHAR',
                'constraint' => '5',
                'null' => TRUE,
            ),
            'Preco' => array(
                'type' => 'FLOAT',
                'null' => TRUE,
            ),
            'PrecoNM' => array(
                'type' => 'FLOAT',
                'null' => TRUE,
            ),
            'Desconto' => array(
                'type' => 'VARCHAR',
                'constraint' => '40',
                'null' => TRUE,
            ),
            'Iva' => array(
                'type' => 'VARCHAR',
                'constraint' => '2',
                'null' => TRUE,
            ),
            'TaxaIva' => array(
                'type' => 'FLOAT',
                'null' => TRUE,
            ),
            'Local' => array(
                'type' => 'VARCHAR',
                'constraint' => '10',
                'null' => TRUE,
            ),
            'KeyScript' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'Palete' => array(
                'type' => 'VARCHAR',
                'constraint' => '17',
                'null' => TRUE,
            ),
            'DocPL' => array(
                'type' => 'VARCHAR',
                'constraint' => '17',
                'null' => TRUE,
            ),
            'LinhaPL' => array(
                'type' => 'INT',
                'null' => TRUE,
            ),
            'TotalMercadoria' => array(
                'type' => 'FLOAT',
                'null' => TRUE,
            ),
            'TotalDescontos' => array(
                'type' => 'FLOAT',
                'null' => TRUE,
            ),
            'DescFin' => array(
                'type' => 'FLOAT',
                'null' => TRUE,
            ),
            'TotalIliquido' => array(
                'type' => 'FLOAT',
                'null' => TRUE,
            ),
            'TotalIva' => array(
                'type' => 'FLOAT',
                'null' => TRUE,
            ),
            'TotalLiquido' => array(
                'type' => 'FLOAT',
                'null' => TRUE,
            ),
            'TotalMercadoriaNM' => array(
                'type' => 'FLOAT',
                'null' => TRUE,
            ),
            'TotalDescontosNM' => array(
                'type' => 'FLOAT',
                'null' => TRUE,
            ),
            'DescFinNM' => array(
                'type' => 'FLOAT',
                'null' => TRUE,
            ),
            'TotalIliquidoNM' => array(
                'type' => 'FLOAT',
                'null' => TRUE,
            ),
            'TotalIvaNM' => array(
                'type' => 'FLOAT',
                'null' => TRUE,
            ),
            'TotalLiquidoNM' => array(
                'type' => 'FLOAT',
                'null' => TRUE,
            ),
            'OperadorMOV' => array(
                'type' => 'VARCHAR',
                'constraint' => '10',
                'null' => TRUE,
            ),
            'DataHoraMOV' => array(
                'type' => 'DATETIME',
                'null' => TRUE,
            ),
        );
        // Adiciona os campos à tabela
        $this->dbforge->add_field($fields);
        // Cria a tabela
        $this->dbforge->create_table($tbl, TRUE);
    }
}