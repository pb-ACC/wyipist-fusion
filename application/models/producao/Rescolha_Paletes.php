<?php

class Rescolha_Paletes extends CI_Model
{


    public function __construct()
    {
        parent::__construct();
        $this->load->database('default');
        $this->load->helper('url');
        $this->load->library('session');
        //$this->db->reconnect();
    }

    
    public function guardar_palete($referencia,$descricao,$formato,$cb,$calibre,$lote,$quantidade,$serie,$nome_nivel,$codigo_nivel,$setor,$obsPL,$username,$funcionario_gpac){

        $user=strtoupper($funcionario_gpac);        
        if($user==''){
            $user=strtoupper($username);
        }
        
        //cria PLS
        $DocPLS=$this->getMax_PL($serie);
        //print_r($DocSP);
        
        foreach ($DocPLS as $val) {
			$CodigoPL = $val->Documento;
            $SeriePL = $val->Serie;  
            $NumeradorPL = $val->Novo_Numerador;          
            $NumeroPL = $val->Numero;
		}
           
        $this->createPL($NumeroPL,$CodigoPL,$SeriePL,$obsPL,$user);
        $this->update_Serie_PL($NumeradorPL,$CodigoPL,$SeriePL);
        //doclog
        $this->insert_doclogPL($NumeroPL,$CodigoPL,$user);

        //insere linhas PLS
        $this->insert_linhas_PL($NumeroPL,$CodigoPL,$referencia,$descricao,$formato,$calibre,$lote,$quantidade,$setor,$codigo_nivel,$user);
        //estadolog
        $this->insert_estadologPL($NumeroPL,$user);                              
        
        $_SESSION["seriePalete"]=$CodigoPL.$SeriePL;
		$_SESSION["nPalete"]=$NumeroPL;

        //cria SP
        $DocSP=$this->getMax_SP();
        //print_r($DocSP);
        
        foreach ($DocSP as $val) {
			$CodigoSP = $val->Documento;
            $SerieSP = $val->Serie;  
            $NumeradorSP = $val->Novo_Numerador;          
            $NumeroSP = $val->Numero;
		}
           
        $_SESSION["nSP"]=$NumeroSP;

        $this->createSP($NumeroSP,$CodigoSP,$SerieSP,$user);
        $this->update_Series_SP($NumeradorSP,$CodigoSP,$SerieSP);
        
        //entrada em stock da PLS criada (Stkldocs)
        $this->insere_palete($NumeroSP,$CodigoSP,$NumeroPL,$CodigoPL,$referencia,$descricao,$formato,$cb,$calibre,$lote,$quantidade,$setor,$codigo_nivel,$user);

        //verifica se existe mais do que uma linha por DocPL
        $newPL = $this->valida_linhas_PL($NumeroPL);        

        foreach ($newPL as $val) {
			$AntigoDocPL = $val->NumeroDocumento;
            $MaxLinha = $val->Linha;
            $TotLinhas = $val->TotLinhas;
            
            $etapa='INICIO DA ALTERÇÃO';
            $this->insere_zxLogsCriacaoPL_Forcada($MaxLinha,$AntigoDocPL,$NumeroPL,$_SESSION["nSP"],$etapa,$user);   
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
                    
                    $this->createPL($NumeroPL,$CodigoPL,$SeriePL,'',$user);
                    $this->update_Serie_PL($NumeradorPL,$CodigoPL,$SeriePL);
                    //doclog
                    $this->insert_doclogPL($NumeroPL,$CodigoPL,$user);

                    //insere linhas PLS
                    $this->update_linhas_PL($AntigoDocPL,$MaxLinha,$NumeroPL);
                    //estadolog
                    $this->insert_estadologPL($NumeroPL,$user);                              

                    //entrada em stock da PLS criada (Stkldocs)
                    $this->upldate_palete_SP($_SESSION["nSP"],$AntigoDocPL,$MaxLinha,$NumeroPL);

                    $etapa='FIM DA ALTERÇÃO';
                    $this->insere_zxLogsCriacaoPL_Forcada($MaxLinha,$AntigoDocPL,$NumeroPL,$_SESSION["nSP"],$etapa,$user);   

           // }                        
		}
    }

    public function getMax_PL($serie){
        $sql="SELECT TOP 1 Documento, Serie, Numerador+1 Novo_Numerador, Documento+Serie+CAST(Numerador+1 as varchar(20)) Numero
              FROM Series
              WHERE Documento='PL' and Serie='{$serie}'";         
        
        $query = $this->db->query($sql);        
        $result = $query->result();
        
        return $result;
    }

    public function createPL($Numero,$Codigo,$Serie,$obsPL,$user){
        $sql="INSERT INTO PlDocs (Numero,Codigo, Serie, Data, Estado, Obs1, Obs2, Obs3, Terceiro, Desconto, Moeda, Cambio, TotalVolumeM3, TotalNVolumes, TotalVolumeCarga, TotalPeso, TotalMercadoria, 
                                  TotalDescontos, TotalDescFin, TotalILiquido, TotalIva, TotalLiquido, TotalMercadoriaNM, TotalDescontosNM, TotalDescFinNM, TotalILiquidoNM, TotalIvaNM, TotalLiquidoNM, 
                                  NossaRef, VossaRef, AssDig, Comissao, TotalComissao, TotalComissaoNM, OperadorMOV, Cor, EstadoOperador, CodigoIdent, TipoTerceiro, ContraMarca, NumSeqTurno, PHC,
                                  Reescolha, DHReescolha, OPRescolha)        
            VALUES ('{$Numero}', '{$Codigo}', '{$Serie}',  convert(char(8),getdate(),112), 'F', '{$obsPL}', '', '', '', '0', 'EUR', 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '', '', '', 0, 0, '{$user}', 16744448, '', '', '', 'Web', -1, 0,
                    1, getdate(), '{$user}' )";
		$this->db->query($sql); 
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
        $sql="INSERT INTO DocLog (Codigo, Documento, OperadorCriacao, DataCriacao, HoraCriacao, OperadorUltimaEdicao, DataUltimaEdicao, HoraUltimaEdicao, OperadorValidacao, MaquinaCriacao, LocalCriacaoDoc, 
                                  MaquinaUltimaEdicao, MaquinaValidacao, OperadorApagou, MaquinaApagou)         
            VALUES ('{$Codigo}','{$Numero}', '{$user}', convert(char(8),getdate(),112), convert(char(8),getdate(),108), '{$user}', convert(char(8),getdate(),112), convert(char(8),getdate(),108), '', 
                    'SERVERSQL', '18907', 'SERVERSQL', '', '', '')";
		$this->db->query($sql); 
        $this->db->close(); 
    }

    public function insert_linhas_PL($Numero,$Codigo,$referencia,$descricao,$formato,$calibre,$lote,$quantidade,$sector,$codigo_nivel,$user){

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
                "SELECT '{$Codigo}', '{$Numero}', 0, '', '00', {$quantidade}, '{$sector}', null, '', '', '{$referencia}', A.Artigo, '{$descricao}', 0, 0, 0, A.Acabamento, 
                        '', '', '', '', A.Unidade, 0, '{$descricao}', 0, 0, '','00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', '{$user}', 
                        getdate(), {$quantidade}, 0, '00001', convert(char(8),getdate(),112), 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '', A.Coleccao, '', '0', 0, 0, 0, 0, 0, 0, 
                        0, '', 0, 0, 0, '', 0, '', '', '{$formato}', A.Qual, A.TipoEmbalagem, '', '', A.RefCor, UPPER('{$lote}'), 0, 0, 1, A.TabEspessura, '', '', '', 
                        '{$referencia}', '{$calibre}', 0, '{$codigo_nivel}'
                 FROM ReferArt A 
                 WHERE A.Referencia='{$referencia}'";        

        $this->db->query($sql04);
        $this->db->close();

        $sql05 ="UPDATE PlDocs
                 SET Estado='F', Cor=16744448, Contramarca='Web', NumSeqTurno=-1
                 WHERE Numero='{$Numero}'";   

        $this->db->query($sql05);
        $this->db->close();

        $sql06 ="UPDATE PlLDocs
                 SET LinhaMae=NumeroLinha, LinhaPai=NumeroLinha, Ordena=NumeroLinha
                 where NumeroDocumento='{$Numero}'";
        $this->db->query($sql06);
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

    public function insere_palete($NumeroSP,$CodigoSP,$NumeroPL,$CodigoPL,$referencia,$descricao,$formato,$cb,$calibre,$lote,$quantidade,$setor,$codigo_nivel,$user){

        //$reluni='M2 = M2';

        $sql09="INSERT INTO StklDocs (LinhaDocumento, CodigoDocumento, NumeroDocumento, Documento, TipoMovimento, Sector, Nivel, NumeroSerieInferior, Quantidade, 
                QuantidadeUnidade, Referencia, Artigo, DescricaoArtigo, Comprimento, Largura, Espessura, Unidade, VolumeM3, Preco, PrecoNM, 
                Desconto, Iva, TaxaIva, NVolumes, VolumeCarga, Peso, FactorEmbalagem, TotalNVolumes, TotalVolumeCarga, TotalPeso, TotalMercadoria, 
                TotalDescontos, DescFin, TotalIliquido, TotalIva, TotalLiquido, TotalMercadoriaNM, TotalDescontosNM, DescFinNM, TotalIliquidoNM, 
                TotalIvaNM, TotalLiquidoNM, Status, OperadorMOV, DataHoraMOV, Cor, CompCorte, LargCorte, EspeCorte, TotalComissao, TotalComissaoNM, 
                LinhaMae, LinhaPai, Ordena, QuantidadeUnStock, UnidadeStock, RelUni, FactorRelUni, ValorStock, DataStockMOV, Changed, FCor, LinhaArtCliForn, 
                Local, KeyScript, Formato, Qual, TipoEmbalagem, Superficie, Decoracao, RefCor, Lote, TabEspessura, Palete, SectorDestino, RefP, LoteInt, 
                Script, DocumentoOrigem, LinhaDocumentoOrigem, Calibre, LinhaPL, DocPL, NivelPalete) ".
            "SELECT 0,'{$CodigoSP}', '{$NumeroSP}', '', '02', '{$setor}', 0, 'REESCOLHA PALETE', {$quantidade}, {$quantidade}, '{$referencia}', Artigo, '{$descricao}', 0, 0, 0,
            Unidade, 0, 0, 0, '0', '00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'SP','{$user}',getdate(), 0, 0, 0, 0, 0, 0, -1, 0, 0, 
            {$quantidade}, Unidade, CONCAT(Unidade,' = ',Unidade), 1, 0, getdate(), 1, 0, 0, '', 'Rescolha_Paletes#insere_palete', '{$formato}', Qual, TipoEmbalagem,
            Superficie, Decoracao, RefCor, UPPER('{$lote}'), TabEspessura, '{$NumeroPL}', '', '{$referencia}', '', -1, '', 0, '{$calibre}', NumeroLinha,
            '{$NumeroPL}', '{$codigo_nivel}'
            FROM PlLDocs
            where NumeroDocumento='{$NumeroPL}'";

    //echo $sql09;

    $this->db->query($sql09);
    $this->db->close();

    $sql05 ="UPDATE StklDocs
        SET LinhaMae=NumeroLinha
        WHERE NumeroDocumento='{$NumeroSP}'";   
    $this->db->query($sql05);
    $this->db->close();

    $sql06 ="UPDATE StkLDocs
             SET LoteInt=substring(cast(replicate('0',15-len(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(Lote,'a',''),'b',''),'c',''),'d',''),'e',''),'f',''),'g',''),'h',''),'i',''),'j',''),'k',''),'l',''),'m',''),'n',''),'o',''),'p',''),'q',''),'r',''),'s',''),'t',''),'u',''),'v',''),'w',''),'x',''),'y',''),'z',''),' ',''),',','.'),'ç',''),'?','')))+rtrim(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(Lote,'a',''),'b',''),'c',''),'d',''),'e',''),'f',''),'g',''),'h',''),'i',''),'j',''),'k',''),'l',''),'m',''),'n',''),'o',''),'p',''),'q',''),'r',''),'s',''),'t',''),'u',''),'v',''),'w',''),'x',''),'y',''),'z',''),' ',''),',','.'),'ç',''),'?','')) as varchar(15)),1,15)
             where NumeroDocumento='{$NumeroSP}' and isnull(Lote,'')<>'' and isnull(LoteInt,'')=''";
    $this->db->query($sql06);
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

    public function insere_zxLogsCriacaoPL_Forcada($MaxLinha,$AntigoDocPL,$NumeroPL,$DocSP,$etapa,$user){

        $sql08="INSERT INTO zxLogsCriacaoPL_Forcada(LinhaPL, DocPL_antigo, DocPL_novo, DocSP, Causa, Etapa, OperadorMOV, DataHoraMOV)  ".
                "VALUES ({$MaxLinha}, '{$AntigoDocPL}', '{$NumeroPL}', '{$DocSP}', 'MIGRAÇÃO DE PALETE', '{$etapa}', '{$user}',getdate())";
        $this->db->query($sql08);
        $this->db->close();                  

    }

    public function update_linhas_PL($Palete,$MaxLinha,$NumeroPL){

		$sql ="UPDATE PllDocs
			   SET NumeroDocumento='{$NumeroPL}'
			   WHERE NumeroDocumento='{$Palete}' and NumeroLinha={$MaxLinha}";   
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

}