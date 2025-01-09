<?php

use LDAP\Result;

class SaidaProducao extends CI_Model
{


    public function __construct()
    {
        parent::__construct();
        $this->load->database('default');
        $this->load->helper('url');
        $this->load->library('session');
        //$this->db->reconnect();
    }

    public function save_production ($DocPL,$LinhaPL,$Referencia,$Artigo,$DescricaoArtigo,$Quantidade,$Unidade,$Sector,$Formato,$Qual,$TipoEmbalagem,$Superficie,$Decoracao,$RefCor,$Lote,
                                     $TabEspessura,$Calibre,$Nivel,$novo_sector,$local,$username,$funcionario_gpac)
    {
        $user=strtoupper($funcionario_gpac);        
        if($user==''){
            $user=strtoupper($username);
        }            
        $DocSP=$this->getMax_SP();
       // print_r($DocSP);        
        foreach ($DocSP as $val) {
			$Codigo = $val->Documento;
            $Serie = $val->Serie;  
            $Numerador = $val->Novo_Numerador;          
            $Numero = $val->Num;
		}           
        $this->createSP($Numero,$Codigo,$Serie,$user);
        $this->update_Series($Numerador,$Codigo,$Serie);
        $table_name='#MS_ExeSP_remove';         
        $this->remove_palete_producao($Codigo, $Numero, $table_name, $DocPL, $LinhaPL, $Referencia, $Artigo, $DescricaoArtigo, $Quantidade, $Unidade, $Sector, $Formato, $Qual, 
                                      $TipoEmbalagem, $Superficie, $Decoracao, $RefCor, $Lote, $TabEspessura, $Calibre, $Nivel, $user);
        $table_name='#MS_ExeSP_remove'; 
        $this->insere_palete_novosector($Codigo, $Numero, $table_name, $DocPL, $LinhaPL, $Referencia, $Artigo, $DescricaoArtigo, $Quantidade, $Unidade, $Formato, $Qual, 
                                        $TipoEmbalagem, $Superficie, $Decoracao, $RefCor, $Lote, $TabEspessura, $Calibre, $Nivel, $novo_sector, $local, $user);   
    }

    public function getMax_SP(){
        $sql="SELECT TOP 1 Documento, Serie, Numerador+1 Novo_Numerador, (Documento+Serie+CAST(Numerador+1 as varchar(20))) Num
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

    public function update_Series($Numerador,$Codigo,$Serie){
		$sql ="UPDATE Series
			   SET Numerador={$Numerador}
			   WHERE Documento='{$Codigo}' and Serie='{$Serie}'";   
		$this->db->query($sql);	
        $this->db->close();
	}

    public function remove_palete_producao($Codigo, $Numero, $table_name, $DocPL, $LinhaPL, $Referencia, $Artigo, $DescricaoArtigo, $Quantidade, $Unidade, $Sector, $Formato, $Qual, 
                                           $TipoEmbalagem, $Superficie, $Decoracao, $RefCor, $Lote, $TabEspessura, $Calibre, $Nivel, $user)
    {
        $reluni= $Unidade.' = ' .$Unidade;

        $sql01="INSERT INTO StklDocs (LinhaDocumento, CodigoDocumento, NumeroDocumento, Documento, TipoMovimento, Sector, Nivel, NumeroSerieInferior, Quantidade, 
                                      QuantidadeUnidade, Referencia, Artigo, DescricaoArtigo, Comprimento, Largura, Espessura, Unidade, VolumeM3, Preco, PrecoNM, 
                                      Desconto, Iva, TaxaIva, NVolumes, VolumeCarga, Peso, FactorEmbalagem, TotalNVolumes, TotalVolumeCarga, TotalPeso, TotalMercadoria, 
                                      TotalDescontos, DescFin, TotalIliquido, TotalIva, TotalLiquido, TotalMercadoriaNM, TotalDescontosNM, DescFinNM, TotalIliquidoNM, 
                                      TotalIvaNM, TotalLiquidoNM, Status, OperadorMOV, DataHoraMOV, Cor, CompCorte, LargCorte, EspeCorte, TotalComissao, TotalComissaoNM, 
                                      LinhaMae, LinhaPai, Ordena, QuantidadeUnStock, UnidadeStock, RelUni, FactorRelUni, ValorStock, DataStockMOV, Changed, FCor, LinhaArtCliForn, 
                                      Local, KeyScript, Formato, Qual, TipoEmbalagem, Superficie, Decoracao, RefCor, Lote, TabEspessura, Palete, SectorDestino, RefP, LoteInt, 
                                      Script, DocumentoOrigem, LinhaDocumentoOrigem, Calibre, LinhaPL, DocPL, NivelPalete)".
                "VALUES(0,'{$Codigo}', '{$Numero}', '', '12', '{$Sector}', 0, 'REMOÇÃO DA PALETE', {$Quantidade}, {$Quantidade}, '{$Referencia}', '{$Artigo}', '{$DescricaoArtigo}', 0, 0, 0,
                       '{$Unidade}', 0, 0, 0, '0', '00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'SP','{$user}',getdate(), 0, 0, 0, 0, 0, 0, -1, 0, 0, 
                       {$Quantidade},'{$Unidade}', '{$reluni}' , 1, 0, getdate(), 1, 0, 0, '', 'SaidaProducao#remove_palete_producao', '{$Formato}', '{$Qual}', '{$TipoEmbalagem}',
                       '{$Superficie}', '{$Decoracao}', '{$RefCor}', '{$Lote}', '{$TabEspessura}', '{$DocPL}', '', '{$Referencia}', '', -1, '', 0, '{$Calibre}', {$LinhaPL},
                       '{$DocPL}','{$Nivel}')";       

        // echo $sql01; 

        $this->db->query($sql01);
        $this->db->close();

        $sql02 ="UPDATE StklDocs
        SET LinhaMae=NumeroLinha
        WHERE NumeroDocumento='{$Numero}'";   
        $this->db->query($sql02);
        $this->db->close();  

        $sql03 ="UPDATE StkLDocs
        SET LoteInt=substring(cast(replicate('0',15-len(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(Lote,'a',''),'b',''),'c',''),'d',''),'e',''),'f',''),'g',''),'h',''),'i',''),'j',''),'k',''),'l',''),'m',''),'n',''),'o',''),'p',''),'q',''),'r',''),'s',''),'t',''),'u',''),'v',''),'w',''),'x',''),'y',''),'z',''),' ',''),',','.'),'ç',''),'?','')))+rtrim(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(Lote,'a',''),'b',''),'c',''),'d',''),'e',''),'f',''),'g',''),'h',''),'i',''),'j',''),'k',''),'l',''),'m',''),'n',''),'o',''),'p',''),'q',''),'r',''),'s',''),'t',''),'u',''),'v',''),'w',''),'x',''),'y',''),'z',''),' ',''),',','.'),'ç',''),'?','')) as varchar(15)),1,15)
        where NumeroDocumento='{$Numero}' and isnull(Lote,'')<>'' and isnull(LoteInt,'')=''";
        $this->db->query($sql03);
        $this->db->close();  
    }

    public function insere_palete_novosector($Codigo, $Numero, $table_name, $DocPL, $LinhaPL, $Referencia, $Artigo, $DescricaoArtigo, $Quantidade, $Unidade, $Formato, $Qual, 
                                             $TipoEmbalagem, $Superficie, $Decoracao, $RefCor, $Lote, $TabEspessura, $Calibre, $Nivel, $novo_sector, $local, $user)
    {
        $reluni= $Unidade.' = ' .$Unidade;

        $sql04="INSERT INTO StklDocs (LinhaDocumento, CodigoDocumento, NumeroDocumento, Documento, TipoMovimento, Sector, Nivel, NumeroSerieInferior, Quantidade, 
                                      QuantidadeUnidade, Referencia, Artigo, DescricaoArtigo, Comprimento, Largura, Espessura, Unidade, VolumeM3, Preco, PrecoNM, 
                                      Desconto, Iva, TaxaIva, NVolumes, VolumeCarga, Peso, FactorEmbalagem, TotalNVolumes, TotalVolumeCarga, TotalPeso, TotalMercadoria, 
                                      TotalDescontos, DescFin, TotalIliquido, TotalIva, TotalLiquido, TotalMercadoriaNM, TotalDescontosNM, DescFinNM, TotalIliquidoNM, 
                                      TotalIvaNM, TotalLiquidoNM, Status, OperadorMOV, DataHoraMOV, Cor, CompCorte, LargCorte, EspeCorte, TotalComissao, TotalComissaoNM, 
                                      LinhaMae, LinhaPai, Ordena, QuantidadeUnStock, UnidadeStock, RelUni, FactorRelUni, ValorStock, DataStockMOV, Changed, FCor, LinhaArtCliForn, 
                                      Local, KeyScript, Formato, Qual, TipoEmbalagem, Superficie, Decoracao, RefCor, Lote, TabEspessura, Palete, SectorDestino, RefP, LoteInt, 
                                      Script, DocumentoOrigem, LinhaDocumentoOrigem, Calibre, LinhaPL, DocPL, NivelPalete)".
                "VALUES(0,'{$Codigo}', '{$Numero}', '', '02', '{$novo_sector}', 0, 'INSERÇÃO DA PALETE', {$Quantidade}, {$Quantidade}, '{$Referencia}', '{$Artigo}', '{$DescricaoArtigo}', 0, 0, 0,
                '{$Unidade}', 0, 0, 0, '0', '00', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 'SP','{$user}',getdate(), 0, 0, 0, 0, 0, 0, -1, 0, 0, 
                {$Quantidade},'{$Unidade}', '{$reluni}' , 1, 0, getdate(), 1, 0, 0, '{$local}', 'SaidaProducao#insere_palete_novosector', '{$Formato}', '{$Qual}', '{$TipoEmbalagem}',
                '{$Superficie}', '{$Decoracao}', '{$RefCor}', '{$Lote}', '{$TabEspessura}', '{$DocPL}', '', '{$Referencia}', '', -1, '', 0, '{$Calibre}', {$LinhaPL},
                '{$DocPL}', '{$Nivel}')";        

        $this->db->query($sql04);
        $this->db->close();

        $sql05 ="UPDATE StklDocs
        SET LinhaMae=NumeroLinha
        WHERE NumeroDocumento='{$Numero}'";   
        $this->db->query($sql05);
        $this->db->close();

        $sql06 ="UPDATE StkLDocs
        SET LoteInt=substring(cast(replicate('0',15-len(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(Lote,'a',''),'b',''),'c',''),'d',''),'e',''),'f',''),'g',''),'h',''),'i',''),'j',''),'k',''),'l',''),'m',''),'n',''),'o',''),'p',''),'q',''),'r',''),'s',''),'t',''),'u',''),'v',''),'w',''),'x',''),'y',''),'z',''),' ',''),',','.'),'ç',''),'?','')))+rtrim(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(Lote,'a',''),'b',''),'c',''),'d',''),'e',''),'f',''),'g',''),'h',''),'i',''),'j',''),'k',''),'l',''),'m',''),'n',''),'o',''),'p',''),'q',''),'r',''),'s',''),'t',''),'u',''),'v',''),'w',''),'x',''),'y',''),'z',''),' ',''),',','.'),'ç',''),'?','')) as varchar(15)),1,15)
        where NumeroDocumento='{$Numero}' and isnull(Lote,'')<>'' and isnull(LoteInt,'')=''";
        $this->db->query($sql06);
        $this->db->close();  
    }
}