<?php

class Paletizar_Carga extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database('default');
        $this->load->helper('url');
        $this->load->library('session');
        //$this->db->reconnect();
    }

    public function paletizar_carga($serie,$flag,$Cliente,$DocumentoCarga,$NumeroDocumento,$NumeroLinha,$QtdEN,$QtdPaletizada,$QtdFalta,$Sector,$Local,$Artigo,$Referencia,
                                    $DescricaoArtigo,$Lote,$Calibre,$Formato,$Qual,$TipoEmbalagem,$Superficie,$Decoracao,$RefCor,$TabEspessura,$Nivel,$Quantidade,$NovaQtd,$Unidade,
                                    $LinhaPL,$DocPL,$motivo,$codigomotivo,$obs,$serieEmp,$seriePL,$setorDestino,$setorCarga,$username,$funcionario_gpac){
        $this->load->dbforge();
        $user=strtoupper($funcionario_gpac);        
        if($user==''){
            $user=strtoupper($username);
        }    

        $iNomeCL=$this->getNomeCL($Cliente);
        foreach ($iNomeCL as $val) {
            $nomeCL = $val->Nome;
        }
        
        //DOC GERADOS
        $tbl01 = $user.'.MS_DocsGerados';
        $this->createTBL_DocsGerados($tbl01);

        //cria PL de cliente
        $iDoc=$this->getMax_PL($serieEmp);
        foreach ($iDoc as $val) {
            $CodigoPL = $val->Documento;
            $SeriePL = $val->Serie;  
            $NumeradorPL = $val->Novo_Numerador;          
            $NumeroPL = $val->Numero;
        }
        $_SESSION["DocPL_cliente"]=$NumeroPL;
        $sql01="UPDATE A
        set A.DocPL='{$NumeroPL}'
        from ".$tbl01." A";
        $this->db->query($sql01);
        

        /*PLAETES*/
        $this->createPL($NumeroPL,$CodigoPL,$serieEmp,$user);
        $this->update_Serie_PL($NumeradorPL,$CodigoPL,$serieEmp);
        //doclog
        $this->insert_doclogPL($NumeroPL,$CodigoPL,$user);

        //insere linhas PLS
        $this->insert_linhas_PL($NumeroPL,$CodigoPL,$DocumentoCarga,$NumeroDocumento,$NumeroLinha,$Sector,$Local,$Artigo,$Referencia,$DescricaoArtigo,$Lote,$Calibre,$Formato,$Qual,
                                $TipoEmbalagem,$Superficie,$Decoracao,$RefCor,$TabEspessura,$Nivel,$NovaQtd,$Unidade,$user,$nomeCL);
        //estadolog
        $this->insert_estadologPL($NumeroPL,$user);                              

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
         
        //entrada em stock da PLS criada (Stkldocs)
        $this->movimento_stock_paletes($NumeroSP,$CodigoSP,$NumeroPL,$CodigoPL,$serie,$flag,$Cliente,$DocumentoCarga,$NumeroDocumento,$NumeroLinha,$QtdEN,$QtdPaletizada,$QtdFalta,
                                       $Sector,$Local,$Artigo,$Referencia,$DescricaoArtigo,$Lote,$Calibre,$Formato,$Qual,$TipoEmbalagem,$Superficie,$Decoracao,$RefCor,$TabEspessura,
                                       $Nivel,$Quantidade,$NovaQtd,$Unidade,$LinhaPL,$DocPL,$motivo,$codigomotivo,$obs,$seriePL,$setorDestino,$setorCarga,$user);

        $this->update_linhas_EN($NumeroDocumento,$NumeroLinha,$QtdPaletizada);

        //verifica se existe mais do que uma linha por DocPL
        $newPL = $this->valida_linhas_PL($NumeroPL);        

                foreach ($newPL as $val) {
                    $AntigoDocPL = $val->NumeroDocumento;
                    $MaxLinha = $val->Linha;
                    $TotLinhas = $val->TotLinhas;
                    
                    $etapa='INICIO DA ALTERÇÃO';
                    $this->insere_zxLogsCriacaoPL_Forcada($MaxLinha,$AntigoDocPL,$NumeroPL,$NumeroSP,$etapa,$user);   
                  //  for($gg=$TotLinhas;$gg>0;$gg--){
        
                            //cria PLS
                            $DocPLS=$this->getMax_PL($serie);
                            //print_r($DocSP);
                            
                            foreach ($DocPLS as $val) {
                                $CodigoPL = $val->Documento;
                                $SeriePL = $val->Serie;  
                                $NumeradorPL = $val->Novo_Numerador;          
                                $NumeroPL = $val->Numero;
                            }
                            $_SESSION["DocPL_cliente"]=$NumeroPL;
                            $this->createPL($NumeroPL,$CodigoPL,$SeriePL,'',$user);
                            $this->update_Serie_PL($NumeradorPL,$CodigoPL,$SeriePL);
                            //doclog
                            $this->insert_doclogPL($NumeroPL,$CodigoPL,$user);
        
                            //insere linhas PLS
                            $this->update_linhas_PL($AntigoDocPL,$MaxLinha,$NumeroPL);
                            //estadolog
                            $this->insert_estadologPL($NumeroPL,$user);                              
        
                            //entrada em stock da PLS criada (Stkldocs)
                            $this->upldate_palete_SP($NumeroSP,$AntigoDocPL,$MaxLinha,$NumeroPL);
        
                            $etapa='FIM DA ALTERÇÃO';
                            $this->insere_zxLogsCriacaoPL_Forcada($MaxLinha,$AntigoDocPL,$NumeroPL,$NumeroSP,$etapa,$user);   
                }
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

    public function getMax_PL($serie){
        $sql="SELECT TOP 1 Documento, Serie, Numerador+1 Novo_Numerador, Documento+Serie+CAST(Numerador+1 as varchar(20)) Numero
              FROM Series
              WHERE Documento='PL' and Serie='{$serie}'";         
        
        $query = $this->db->query($sql);        
        $result = $query->result();
        
        return $result;
    }

    public function createPL($Numero,$Codigo,$Serie,$user){
        $sql01="INSERT INTO PlDocs (Numero,Codigo, Serie, Data, Estado, Obs1, Obs2, Obs3, Terceiro, Desconto, Moeda, Cambio, TotalVolumeM3, TotalNVolumes, TotalVolumeCarga, TotalPeso, TotalMercadoria, 
                                  TotalDescontos, TotalDescFin, TotalILiquido, TotalIva, TotalLiquido, TotalMercadoriaNM, TotalDescontosNM, TotalDescFinNM, TotalILiquidoNM, TotalIvaNM, TotalLiquidoNM, 
                                  NossaRef, VossaRef, AssDig, Comissao, TotalComissao, TotalComissaoNM, OperadorMOV, Cor, EstadoOperador, CodigoIdent, TipoTerceiro, ContraMarca, NumSeqTurno, PHC)        
            VALUES ('{$Numero}', '{$Codigo}', '{$Serie}',  convert(char(8),getdate(),112), 'F', '', '', '', '', '0', 'EUR', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', 0, 0, '{$user}', 16744448, '', '', '', 'Web', -1, 0)";
		$this->db->query($sql01); 
        $this->db->close(); 
    }

    public function update_Serie_PL($Numerador,$Codigo,$Serie){

		$sql ="UPDATE Series
			   SET Numerador={$Numerador}
			   WHERE Documento='{$Codigo}' and Serie='{$Serie}'";   
		$this->db->query($sql);	
        $this->db->close();
	}

    public function insert_doclogPL($Numero,$Codigo,$user){
        $sql01="INSERT INTO DocLog (Codigo, Documento, OperadorCriacao, DataCriacao, HoraCriacao, OperadorUltimaEdicao, DataUltimaEdicao, HoraUltimaEdicao, OperadorValidacao, MaquinaCriacao, LocalCriacaoDoc, 
                                  MaquinaUltimaEdicao, MaquinaValidacao, OperadorApagou, MaquinaApagou)         
            VALUES ('{$Codigo}','{$Numero}', '{$user}', convert(char(8),getdate(),112), convert(char(8),getdate(),108), '{$user}', convert(char(8),getdate(),112), convert(char(8),getdate(),108), '', 
                    'SERVERSQL', '18907', 'SERVERSQL', '', '', '')";
		$this->db->query($sql01); 
        $this->db->close(); 

        $sql02="INSERT INTO EstadoLog (Documento, Accao, Maquina, DataHoraMov, OperadorMOV, Estado, Sistema, OperSistH)         
                select Numero, 'BLOQUEOU', 'WEB', getdate(), '{$user}', 'F', 0, 'WEB'
                from PlDocs
                where Numero='{$Numero}'";
		$this->db->query($sql02); 
        $this->db->close(); 
    }

    public function insert_linhas_PL($NumeroPL,$CodigoPL,$DocumentoCarga,$NumeroDocumento,$NumeroLinha,$Sector,$Local,$Artigo,$Referencia,$DescricaoArtigo,$Lote,$Calibre,$Formato,
                                     $Qual,$TipoEmbalagem,$Superficie,$Decoracao,$RefCor,$TabEspessura,$Nivel,$NovaQtd,$Unidade,$user,$nomeCL){

        $sql04="INSERT INTO PlLDocs (CodigoDocumento, NumeroDocumento, LinhaDocumento, Documento, TipoMovimento, Quantidade, Sector, DataEntrega, NumeroSerieInferior, 
                                     NumeroSerieSuperior, Referencia, Artigo, DescricaoArtigo, Comprimento, Largura, Espessura, Acabamento, Especificidade, Versao, 
                                     Linha, MateriaPrima, Unidade, VolumeM3, Descricao, Preco, PrecoNM, Desconto, Iva, TaxaIva, NVolumes, VolumeCarga, Peso,
                                     FactorEmbalagem, TotalNVolumes, TotalVolumeCarga, TotalPeso, TotalMercadoria, TotalDescontos, DescFin, TotalIliquido, TotalIva, 
                                     TotalLiquido, TotalMercadoriaNM, TotalDescontosNM, DescFinNM, TotalIliquidoNM, TotalIvaNM, TotalLiquidoNM, Prioridade, Nivel, 
                                     Status, OperadorMOV, DataHoraMOV, QuantidadeUnidade, Cor, Palete, DataPalete, ComprimentoPalete, LarguraPalete, EspessuraPalete,
                                     ComprimentoBasePalete, LarguraBasePalete, EspessuraBasePalete, PesoBasePalete, CompCorte, LargCorte, EspeCorte, Modelo, Coleccao, 
                                     RazaoIsencao, Comissao, TotalComissao, TotalComissaoNM, NumeroOperacao, ProcessaEtiqueta, ProcessaLinha, CompilaInterface, 
                                     GuardaIdenti, Plataforma, LinhaPai, Ordena, FCor, FStilo, LinhaArtCliForn, TabelaPreco, LinhaCBarra, Formato, Qual, TipoEmbalagem, 
                                     Superficie, Decoracao, RefCor, Lote, Peso2, TotalPeso2, QtdCaixa, TabEspessura, PaleteOrigem, KeyScript, Local, RefP, Calibre, PHC, NivelPalete)".
                "SELECT '{$CodigoPL}', '{$NumeroPL}', {$NumeroLinha}, '{$NumeroDocumento}', '00', {$NovaQtd}, '{$Sector}', null, '{$DocumentoCarga}', '{$nomeCL}', '{$Referencia}', A.Artigo, '{$DescricaoArtigo}', 0, 0, 0, A.Acabamento, 
                        '', '', '', '', A.Unidade, 0, '{$DescricaoArtigo}', 0, 0, '','00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '{$user}', 
                        getdate(), {$NovaQtd}, 0, '00001', convert(char(8),getdate(),112), 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', A.Coleccao, '', '0', 0, 0, 0, 0, 0, 0, 
                        0, '', 0, 0, 0, '', 0, '', '', '{$Formato}', A.Qual, A.TipoEmbalagem, '', '', A.RefCor, UPPER('{$Lote}'), 0, 0, 1, A.TabEspessura, '', '', '', 
                        '{$Referencia}', '{$Calibre}', 0, '{$Nivel}'
                 FROM ReferArt A 
                 WHERE A.Referencia='{$Referencia}'";        

        $this->db->query($sql04);
        $this->db->close();

        $sql05 ="UPDATE PlDocs
                 SET Estado='F', Cor=16744448, Contramarca='Web', NumSeqTurno=-1
                 WHERE Numero='{$NumeroPL}'";   

        $this->db->query($sql05);
        $this->db->close();

        $sql06 ="UPDATE PlLDocs
                 SET LinhaMae=NumeroLinha, LinhaPai=NumeroLinha, Ordena=NumeroLinha
                 where NumeroDocumento='{$NumeroPL}'";
        $this->db->query($sql06);
        $this->db->close();  

        $sql07 ="UPDATE A
                 set A.Preco=B.Preco, A.PrecoNM=B.PrecoNM, A.Desconto=B.Desconto, A.Iva=B.Iva, A.TaxaIva=B.TaxaIva, A.Peso=B.Peso, A.Peso2=B.Peso2
                 from PlLDocs A join VdLEncs B on (A.NumeroDocumento='{$NumeroPL}' and A.LinhaDocumento=B.NumeroLinha and A.Documento=B.NumeroDocumento)
                 where A.NumeroDocumento='{$NumeroPL}'";
        $this->db->query($sql07);
        $this->db->close();                   
    }

    public function insert_estadologPL($Numero,$user){

        $sql07 ="INSERT INTO EstadoLog (Documento, Accao, Maquina, DataHoraMov, OperadorMOV, Estado, Sistema, OperSistH)
                 SELECT Numero, 'BLOQUEOU', '', getdate(), '{$user}', 'F', 0, ''
                 FROM PlDocs
                 WHERE Numero='{$Numero}'";
        $this->db->query($sql07);
        $this->db->close();  

    }  

    public function valida_linhas_PL($NumeroPL){
        $sql="SELECT NumeroDocumento, max(NumeroLinha) Linha, count(NumeroLinha) TotLinhas
              FROM PllDocs
              WHERE NumeroDocumento='{$NumeroPL}'
              GROUP BY NumeroDocumento
              HAVING count(NumeroLinha)>1";         
        
        $query = $this->db->query($sql);        
        $result = $query->result();
        
        return $result;
    }

    public function update_linhas_PL($Palete,$MaxLinha,$NumeroPL){

		$sql ="UPDATE PllDocs
			   SET NumeroDocumento='{$NumeroPL}'
			   WHERE NumeroDocumento='{$Palete}' and NumeroLinha={$MaxLinha}";   
		$this->db->query($sql);	
        $this->db->close();

    }

    public function insere_zxLogsCriacaoPL_Forcada($MaxLinha,$AntigoDocPL,$NumeroPL,$DocSP,$etapa,$user){

        $sql08="INSERT INTO zxLogsCriacaoPL_Forcada(LinhaPL, DocPL_antigo, DocPL_novo, DocSP, Causa, Etapa, OperadorMOV, DataHoraMOV)  ".
                "VALUES ({$MaxLinha}, '{$AntigoDocPL}', '{$NumeroPL}', '{$DocSP}', 'MIGRAÇÃO DE PALETE', '{$etapa}', '{$user}',getdate())";
        $this->db->query($sql08);
        $this->db->close();                  

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

    public function upldate_palete_SP($SP,$Palete,$MaxLinha,$NumeroPL){
        $sql ="UPDATE StklDocs
                SET DocPL='{$NumeroPL}', Palete='{$NumeroPL}'
                WHERE NumeroDocumento='{$SP}' and DocPL='{$Palete}' and LinhaPL={$MaxLinha}";   
        $this->db->query($sql);	
        $this->db->close();
    }

    public function movimento_stock_paletes($NumeroSP,$CodigoSP,$NumeroPL,$CodigoPL,$serie,$flag,$Cliente,$DocumentoCarga,$NumeroDocumento,$NumeroLinha,$QtdEN,$QtdPaletizada,$QtdFalta,
                                            $Sector,$Local,$Artigo,$Referencia,$DescricaoArtigo,$Lote,$Calibre,$Formato,$Qual,$TipoEmbalagem,$Superficie,$Decoracao,$RefCor,
                                            $TabEspessura,$Nivel,$Quantidade,$NovaQtd,$Unidade,$LinhaPL,$DocPL,$motivo,$codigomotivo,$obs,$seriePL,$setorDestino,$setorCarga,$user){
        //$reluni='M2 = M2';
        $this->load->dbforge();
        //DOC GERADOS
        $tbl01 = $user.'.MS_ExeSP';
        $this->createTBL_ExeSP($tbl01);

        // PL | PLP
        $sql09="INSERT INTO ".$tbl01." (LinhaDocumento, NumeroDocumento, Documento, TipoMovimento, Sector, NumeroSerieInferior, NumeroSerieSuperior, Quantidade,
                                        QuantidadeUnidade, Referencia, Artigo, DescricaoArtigo, Formato, RefCor, Qual, TipoEmbalagem, Superficie, Lote, Calibre,
                                        Decoracao, Acabamento, Coleccao, TabEspessura, RefP, Referencia, Unidade, Preco, PrecoNM, Desconto, Iva, TaxaIva,
                                        Local, KeyScript, Palete, DocPL, LinhaPL, TotalMercadoria, TotalDescontos, DescFin, TotalIliquido, TotalIva, TotalLiquido, TotalMercadoriaNM,
                                        TotalDescontosNM, DescFinNM, TotalIliquidoNM, TotalIvaNM, TotalLiquidoNM, OperadorMOV, DataHoraMOV)".
                "SELECT 0, '{$NumeroSP}', '', '12', '{$Sector}', 'PALETIZAÇÃO CLIENTE 2', '', {$NovaQtd}, {$NovaQtd}, '{$Referencia}', '{$Artigo}', '{$DescricaoArtigo}', '{$Formato}', 
                       '{$RefCor}', '{$Qual}', '{$TipoEmbalagem}', '{$Superficie}', '{$Lote}', '{$Calibre}', '{$Decoracao}', B.Acabamento', B.Coleccao, '{$TabEspessura}', '{$Referencia}', 
                       '{$Unidade}', B.Custo, B.Custo, '0', '00', 0, '{$Local}', 'Paletizar_Carga#movimento_stock_paletes_sql09', '{$DocPL}', '{$DocPL}', {$LinhaPL}, B.Custo*{$NovaQtd}, 0, 0, 
                       B.Custo*{$NovaQtd}, 0, B.Custo*{$NovaQtd}, B.Custo*{$NovaQtd}, 0, 0, B.Custo*{$NovaQtd}, 0, B.Custo*{$NovaQtd},'{$user}', getdate()
                FROM PlLDocs B
                where B.NumeroDocumento='{$DocPL}'";
                //echo $sql09;
            $this->db->query($sql09);
            $this->db->close();

            // PLC | PLPC
            $sql10="INSERT INTO ".$tbl01." (LinhaDocumento, NumeroDocumento, Documento, TipoMovimento, Sector, NumeroSerieInferior, NumeroSerieSuperior, Quantidade,
                                            QuantidadeUnidade, Referencia, Artigo, DescricaoArtigo, Formato, RefCor, Qual, TipoEmbalagem, Superficie, Lote, Calibre,
                                            Decoracao, Acabamento, Coleccao, TabEspessura, RefP, Referencia, Unidade, Preco, PrecoNM, Desconto, Iva, TaxaIva,
                                            Local, KeyScript, Palete, DocPL, LinhaPL, TotalMercadoria, TotalDescontos, DescFin, TotalIliquido, TotalIva, TotalLiquido, TotalMercadoriaNM,
                                            TotalDescontosNM, DescFinNM, TotalIliquidoNM, TotalIvaNM, TotalLiquidoNM, OperadorMOV, DataHoraMOV)".
                "SELECT 0, '{$NumeroSP}', '', '02', '{$setorDestino}', 'PALETIZAÇÃO CLIENTE 2', '', {$NovaQtd}, {$NovaQtd}, '{$Referencia}', '{$Artigo}', '{$DescricaoArtigo}', '{$Formato}', 
                       '{$RefCor}', '{$Qual}', '{$TipoEmbalagem}', '{$Superficie}', '{$Lote}', '{$Calibre}', '{$Decoracao}', B.Acabamento', B.Coleccao, '{$TabEspessura}', '{$Referencia}', 
                       '{$Unidade}', B.Custo, B.Custo, '0', '00', 0, '{$Local}', 'Paletizar_Carga#movimento_stock_paletes_sql09', '{$NumeroPL}', '{$NumeroPL}', {$NumeroLinha}, B.Custo*{$NovaQtd}, 0, 0, 
                       B.Custo*{$NovaQtd}, 0, B.Custo*{$NovaQtd}, B.Custo*{$NovaQtd}, 0, 0, B.Custo*{$NovaQtd}, 0, B.Custo*{$NovaQtd},'{$user}', getdate()
                FROM PlLDocs B
                where B.NumeroDocumento='{$NumeroPL}'";
                    //echo $sql09;
            $this->db->query($sql10);
            $this->db->close();

            
            //se for ceragni dá entrada da PL no armazém da certeca
            $sql11="INSERT INTO ".$tbl01." (LinhaDocumento, NumeroDocumento, Documento, TipoMovimento, Sector, NumeroSerieInferior, NumeroSerieSuperior, Quantidade,
                                            QuantidadeUnidade, Referencia, Artigo, DescricaoArtigo, Formato, RefCor, Qual, TipoEmbalagem, Superficie, Lote, Calibre,
                                            Decoracao, Acabamento, Coleccao, TabEspessura, RefP, Referencia, Unidade, Preco, PrecoNM, Desconto, Iva, TaxaIva,
                                            Local, KeyScript, Palete, DocPL, LinhaPL, TotalMercadoria, TotalDescontos, DescFin, TotalIliquido, TotalIva, TotalLiquido, TotalMercadoriaNM,
                                            TotalDescontosNM, DescFinNM, TotalIliquidoNM, TotalIvaNM, TotalLiquidoNM, OperadorMOV, DataHoraMOV)".
                   "SELECT 0, '{$NumeroSP}', '', '02', '{$setorCarga}', 'PALETIZAÇÃO CLIENTE 2', '', {$NovaQtd}, {$NovaQtd}, '{$Referencia}', '{$Artigo}', '{$DescricaoArtigo}', '{$Formato}', 
                          '{$RefCor}', '{$Qual}', '{$TipoEmbalagem}', '{$Superficie}', '{$Lote}', '{$Calibre}', '{$Decoracao}', B.Acabamento', B.Coleccao, '{$TabEspessura}', '{$Referencia}', 
                          '{$Unidade}', B.Custo, B.Custo, '0', '00', 0, '{$Local}', 'Paletizar_Carga#movimento_stock_paletes_sql09', '{$DocPL}', '{$DocPL}', {$LinhaPL}, B.Custo*{$NovaQtd}, 0, 0, 
                          B.Custo*{$NovaQtd}, 0, B.Custo*{$NovaQtd}, B.Custo*{$NovaQtd}, 0, 0, B.Custo*{$NovaQtd}, 0, B.Custo*{$NovaQtd},'{$user}', getdate()
                    FROM PlLDocs B
                    where B.NumeroDocumento='{$DocPL}' and {$flag}=1";
                    //echo $sql09;
            $this->db->query($sql11);
            $this->db->close();

            //se for certeca dá entrada da PLP no armazém da ceragni
            $sql12="INSERT INTO ".$tbl01." (LinhaDocumento, NumeroDocumento, Documento, TipoMovimento, Sector, NumeroSerieInferior, NumeroSerieSuperior, Quantidade,
                                            QuantidadeUnidade, Referencia, Artigo, DescricaoArtigo, Formato, RefCor, Qual, TipoEmbalagem, Superficie, Lote, Calibre,
                                            Decoracao, Acabamento, Coleccao, TabEspessura, RefP, Referencia, Unidade, Preco, PrecoNM, Desconto, Iva, TaxaIva,
                                            Local, KeyScript, Palete, DocPL, LinhaPL, TotalMercadoria, TotalDescontos, DescFin, TotalIliquido, TotalIva, TotalLiquido, TotalMercadoriaNM,
                                            TotalDescontosNM, DescFinNM, TotalIliquidoNM, TotalIvaNM, TotalLiquidoNM, OperadorMOV, DataHoraMOV)".
                   "SELECT 0, '{$NumeroSP}', '', '02', '{$setorCarga}', 'PALETIZAÇÃO CLIENTE 2', '', {$NovaQtd}, {$NovaQtd}, '{$Referencia}', '{$Artigo}', '{$DescricaoArtigo}', '{$Formato}', 
                          '{$RefCor}', '{$Qual}', '{$TipoEmbalagem}', '{$Superficie}', '{$Lote}', '{$Calibre}', '{$Decoracao}', B.Acabamento', B.Coleccao, '{$TabEspessura}', '{$Referencia}', 
                          '{$Unidade}', B.Custo, B.Custo, '0', '00', 0, '{$Local}', 'Paletizar_Carga#movimento_stock_paletes_sql09', '{$DocPL}', '{$DocPL}', {$LinhaPL}, B.Custo*{$NovaQtd}, 0, 0, 
                          B.Custo*{$NovaQtd}, 0, B.Custo*{$NovaQtd}, B.Custo*{$NovaQtd}, 0, 0, B.Custo*{$NovaQtd}, 0, B.Custo*{$NovaQtd},'{$user}', getdate()
                    FROM PlLDocs B
                    where B.NumeroDocumento='{$DocPL}' and {$flag}=2";
                    //echo $sql09;
            $this->db->query($sql12);
            $this->db->close();
            
            $sql13="INSERT INTO StkLDocs (LinhaDocumento, NumeroDocumento, Documento, TipoMovimento, Sector, NumeroSerieInferior, NumeroSerieSuperior, Quantidade,
                                          QuantidadeUnidade, Referencia, Artigo, DescricaoArtigo, Formato, RefCor, Qual, TipoEmbalagem, Superficie, Lote, Calibre,
                                          Decoracao, Acabamento, Coleccao, TabEspessura, RefP, Referencia, Unidade, Preco, PrecoNM, Desconto, Iva, TaxaIva,
                                          Local, KeyScript, Palete, DocPL, LinhaPL, TotalMercadoria, TotalDescontos, DescFin, TotalIliquido, TotalIva, TotalLiquido, TotalMercadoriaNM,
                                          TotalDescontosNM, DescFinNM, TotalIliquidoNM, TotalIvaNM, TotalLiquidoNM, OperadorMOV, DataHoraMOV)".
                   "SELECT LinhaDocumento, NumeroDocumento, Documento, TipoMovimento, Sector, NumeroSerieInferior, NumeroSerieSuperior, Quantidade, QuantidadeUnidade, Referencia, 
                           Artigo, DescricaoArtigo, Formato, RefCor, Qual, TipoEmbalagem, Superficie, Lote, Calibre, Decoracao, Acabamento, Coleccao, TabEspessura, RefP, Referencia, 
                           Unidade, Preco, PrecoNM, Desconto, Iva, TaxaIva, Local, KeyScript, Palete, DocPL, LinhaPL, TotalMercadoria, TotalDescontos, DescFin, TotalIliquido, TotalIva, 
                           TotalLiquido, TotalMercadoriaNM, TotalDescontosNM, DescFinNM, TotalIliquidoNM, TotalIvaNM, TotalLiquidoNM, OperadorMOV, DataHoraMOV
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

            $sql07 ="UPDATE PlDocs
                     SET Reverte=1
                     WHERE Numero='{$DocPL}' and {$flag} in (1,2)";   
            $this->db->query($sql07);
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

    public function update_linhas_EN($NumeroDocumento,$NumeroLinha,$QtdPaletizada){
        $sql01 ="UPDATE A
                 set A.QtdPaletizada=isnull({$QtdPaletizada},0)
                 from VdLEncs A 
                 where A.NumeroDocumento='{$NumeroDocumento}' and C.NumeroLinha={$NumeroLinha}";
            $this->db->query($sql01);	
            $this->db->close();
    }

    public function fecharGG($documentoCarga,$username,$funcionario_gpac){

        $user=strtoupper($funcionario_gpac);        
        if($user==''){
            $user=strtoupper($username);
        }    

        $sql01 ="UPDATE A
                 set A.Estado='F', A.Cor=C.Cor
                 from PrGuias A join Estado  C on (A.Numero='{$documentoCarga}' and C.Codigo='F' and A.Codigo=C.TipoDoc)";
        $this->db->query($sql01);	
        $this->db->close();
        

        $sql02 ="INSERT INTO EstadoLog (Documento, Accao, Maquina, DataHoraMov, OperadorMOV, Estado, Sistema, OperSistH)
                 select A.Numero, 'BLOQUEOU', 'WEB', getdate(), '{$user}', 'F', 0, 'WEB'
                 from PrGuias A where A.Numero='{$documentoCarga}'";
        $this->db->query($sql02);
        $this->db->close();  
        
    }

    public function getNomeCL($Cliente){
        $sql="SELECT TOP 1 Nome
              FROM Clientes
              WHERE Codigo='{$Cliente}'";                 
        $query = $this->db->query($sql);        
        $result = $query->result();        
        return $result;
    }
}