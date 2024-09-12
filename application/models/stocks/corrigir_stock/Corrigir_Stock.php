<?php

class Corrigir_Stock extends CI_Model 
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database('default');
        $this->load->helper('url');
        $this->load->library('session');
        //$this->db->reconnect();
    }

    public function create_new_DocP($user,$funcionario_gpac,$obs,$serie){
        $user=strtoupper($funcionario_gpac);        
        if($user==''){
            $user=strtoupper($user);
        }
        
        $DocSP=$this->getMax_SP($serie);
            //print_r($DocSP);
            
        foreach ($DocSP as $val) {
            $Codigo = $val->Documento;
            $Serie = $val->Serie;  
            $Numerador = $val->Novo_Numerador;          
            $Numero = $val->Numero;
        }

        $_SESSION["CodigoGS_P"]=$Codigo;
        $_SESSION["NumeroGS_P"]=$Numero;
        $_SESSION["CodigoGS_R"]='';
        $_SESSION["NumeroGS_R"]='';
        
               
        $this->createSP($Numero,$Codigo,$Serie,$user,$obs);
        $this->update_Series($Numerador,$Codigo,$Serie);
    }

    
    public function create_new_DocR($user,$funcionario_gpac,$obs,$serie){
        $user=strtoupper($funcionario_gpac);        
        if($user==''){
            $user=strtoupper($user);
        }
        
        $DocSP=$this->getMax_SP($serie);
            //print_r($DocSP);
            
        foreach ($DocSP as $val) {
            $Codigo = $val->Documento;
            $Serie = $val->Serie;  
            $Numerador = $val->Novo_Numerador;          
            $Numero = $val->Numero;
        }

        $_SESSION["CodigoGS_P"]='';
        $_SESSION["NumeroGS_P"]='';
        $_SESSION["CodigoGS_R"]=$Codigo;
        $_SESSION["NumeroGS_R"]=$Numero;
               
        $this->createSP($Numero,$Codigo,$Serie,$user,$obs);
        $this->update_Series($Numerador,$Codigo,$Serie);
    }

    public function getMax_SP($serie){
        $sql="SELECT TOP 1 Documento, Serie, Numerador+1 Novo_Numerador, Documento+Serie+CAST(Numerador+1 as varchar(20)) Numero
              FROM Series
              WHERE Documento='GS' and Serie='{$serie}'";         
        //echo $sql;
        $query = $this->db->query($sql);        
        $result = $query->result();
        
        return $result;
    }

    public function createSP($Numero,$Codigo,$Serie,$user,$obs){

        $sql="INSERT INTO StkDocs (Numero,Codigo, Serie, Data, DataHoraMOV, OperadorMOV, Moeda, Cambio, Desconto, TipoTerceiro, Referencia)
            VALUES ('{$Numero}', '{$Codigo}', '{$Serie}',convert(char(8),getdate(),112), getdate(), '{$user}', 'EUR', 1, '0', 'DO','{$obs}')";
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

    public function confirm_correction($CodigoGS,$NumeroGS,$DocPL, $LinhaPL, $Referencia, $Artigo, $DescricaoArtigo, $Quantidade, $Unidade, $Sector, $Formato,
    $Qual, $TipoEmbalagem, $Superficie, $Decoracao, $RefCor, $Lote, $TabEspessura, $Calibre, $Local, 
    $Nivel, $NovaQtd, $Reabilitado, $username, $funcionario_gpac){

    $flag=0;
      
    $user=strtoupper($funcionario_gpac);        
    if($user==''){
        $user=strtoupper($username);
    }

        $reluni= $Unidade.' = ' .$Unidade;

        $sql01="INSERT INTO StklDocs (LinhaDocumento, CodigoDocumento, NumeroDocumento, Documento, TipoMovimento, Sector, Nivel, NumeroSerieInferior, Quantidade, 
                                    QuantidadeUnidade, Referencia, Artigo, DescricaoArtigo, Comprimento, Largura, Espessura, Unidade, VolumeM3, Preco, PrecoNM, 
                                    Desconto, Iva, TaxaIva, NVolumes, VolumeCarga, Peso, FactorEmbalagem, TotalNVolumes, TotalVolumeCarga, TotalPeso, TotalMercadoria, 
                                    TotalDescontos, DescFin, TotalIliquido, TotalIva, TotalLiquido, TotalMercadoriaNM, TotalDescontosNM, DescFinNM, TotalIliquidoNM, 
                                    TotalIvaNM, TotalLiquidoNM, Status, OperadorMOV, DataHoraMOV, Cor, CompCorte, LargCorte, EspeCorte, TotalComissao, TotalComissaoNM, 
                                    LinhaMae, LinhaPai, Ordena, QuantidadeUnStock, UnidadeStock, RelUni, FactorRelUni, ValorStock, DataStockMOV, Changed, FCor, LinhaArtCliForn, 
                                    Local, KeyScript, Formato, Qual, TipoEmbalagem, Superficie, Decoracao, RefCor, Lote, TabEspessura, Palete, SectorDestino, RefP, LoteInt, 
                                    Script, DocumentoOrigem, LinhaDocumentoOrigem, Calibre, LinhaPL, DocPL, NivelPalete)".
               "SELECT 0,'{$CodigoGS}', '{$NumeroGS}', '',case when ({$NovaQtd})<({$Quantidade}) then '15' else '05' end, '{$Sector}', 0, 'ACERTO GS WEB',                
               case when ({$NovaQtd})=({$Quantidade}) then 0 when ({$NovaQtd})<({$Quantidade}) then ({$Quantidade})-({$NovaQtd}) else ({$NovaQtd})-({$Quantidade}) end,
               case when ({$NovaQtd})=({$Quantidade}) then 0 when ({$NovaQtd})<({$Quantidade}) then ({$Quantidade})-({$NovaQtd}) else ({$NovaQtd})-({$Quantidade}) end,
               '{$Referencia}', '{$Artigo}', '{$DescricaoArtigo}', 0, 0, 0,'{$Unidade}', VolumeM3, Preco, PrecoNM, Desconto, '00', 0, NVolumes, VolumeCarga, Peso, FactorEmbalagem, 
               TotalNVolumes, TotalVolumeCarga, TotalPeso,(case when ({$NovaQtd})=({$Quantidade}) then 0 when ({$NovaQtd})<({$Quantidade}) then ({$Quantidade})-({$NovaQtd}) else ({$NovaQtd})-({$Quantidade}) end)*PrecoNM, 
               0, 0,(case when ({$NovaQtd})=({$Quantidade}) then 0 when ({$NovaQtd})<({$Quantidade}) then ({$Quantidade})-({$NovaQtd}) else ({$NovaQtd})-({$Quantidade}) end)*PrecoNM, 0,                
               (case when ({$NovaQtd})=({$Quantidade}) then 0 when ({$NovaQtd})<({$Quantidade}) then ({$Quantidade})-({$NovaQtd}) else ({$NovaQtd})-({$Quantidade}) end)*PrecoNM,                
               (case when ({$NovaQtd})=({$Quantidade}) then 0 when ({$NovaQtd})<({$Quantidade}) then ({$Quantidade})-({$NovaQtd}) else ({$NovaQtd})-({$Quantidade}) end)*PrecoNM, 0, 0,                
               (case when ({$NovaQtd})=({$Quantidade}) then 0 when ({$NovaQtd})<({$Quantidade}) then ({$Quantidade})-({$NovaQtd}) else ({$NovaQtd})-({$Quantidade}) end)*PrecoNM, 0, 
               (case when ({$NovaQtd})=({$Quantidade}) then 0 when ({$NovaQtd})<({$Quantidade}) then ({$Quantidade})-({$NovaQtd}) else ({$NovaQtd})-({$Quantidade}) end)*PrecoNM, 
               'GS','{$user}',getdate(), 0, 0, 0, 0, TotalComissao, TotalComissaoNM, -1, 0, 0,(case when ({$NovaQtd})=({$Quantidade}) then 0 when ({$NovaQtd})<({$Quantidade}) then ({$Quantidade})-({$NovaQtd}) else ({$NovaQtd})-({$Quantidade}) end),
               '{$Unidade}', '{$reluni}' , 1, 0, getdate(), 1, 0, 0, '{$Local}', 'Corrigir_Stock#correct_stock', '{$Formato}', '{$Qual}', '{$TipoEmbalagem}', '{$Superficie}', '{$Decoracao}', '{$RefCor}', '{$Lote}', 
               '{$TabEspessura}', '{$DocPL}', '', '{$Referencia}', '', -1, '', 0, '{$Calibre}', {$LinhaPL}, '{$DocPL}', '{$Nivel}'
               from PlLDocs
               where NumeroDocumento='{$DocPL}' and {$Reabilitado}=0";       
                
        //echo $sql01; 
        $this->db->query($sql01);
        $this->db->close();

        $sql02 ="UPDATE StklDocs
                 SET LinhaMae=NumeroLinha, LinhaPai=NumeroLinha, Ordena=NumeroLinha
                 WHERE NumeroDocumento='{$NumeroGS}'";   
        $this->db->query($sql02);
        $this->db->close();  

        $sql03 ="UPDATE StkLDocs
                 SET LoteInt=substring(cast(replicate('0',15-len(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(Lote,'a',''),'b',''),'c',''),'d',''),'e',''),'f',''),'g',''),'h',''),'i',''),'j',''),'k',''),'l',''),'m',''),'n',''),'o',''),'p',''),'q',''),'r',''),'s',''),'t',''),'u',''),'v',''),'w',''),'x',''),'y',''),'z',''),' ',''),',','.'),'ç',''),'?','')))+rtrim(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(Lote,'a',''),'b',''),'c',''),'d',''),'e',''),'f',''),'g',''),'h',''),'i',''),'j',''),'k',''),'l',''),'m',''),'n',''),'o',''),'p',''),'q',''),'r',''),'s',''),'t',''),'u',''),'v',''),'w',''),'x',''),'y',''),'z',''),' ',''),',','.'),'ç',''),'?','')) as varchar(15)),1,15)
                 where NumeroDocumento='{$NumeroGS}' and isnull(Lote,'')<>'' and isnull(LoteInt,'')=''";
        $this->db->query($sql03);
        $this->db->close();  
      

        //REABILITADO
        $sql04="INSERT INTO StklDocs (LinhaDocumento, CodigoDocumento, NumeroDocumento, Documento, TipoMovimento, Sector, Nivel, NumeroSerieInferior, Quantidade, 
                                    QuantidadeUnidade, Referencia, Artigo, DescricaoArtigo, Comprimento, Largura, Espessura, Unidade, VolumeM3, Preco, PrecoNM, 
                                    Desconto, Iva, TaxaIva, NVolumes, VolumeCarga, Peso, FactorEmbalagem, TotalNVolumes, TotalVolumeCarga, TotalPeso, TotalMercadoria, 
                                    TotalDescontos, DescFin, TotalIliquido, TotalIva, TotalLiquido, TotalMercadoriaNM, TotalDescontosNM, DescFinNM, TotalIliquidoNM, 
                                    TotalIvaNM, TotalLiquidoNM, Status, OperadorMOV, DataHoraMOV, Cor, CompCorte, LargCorte, EspeCorte, TotalComissao, TotalComissaoNM, 
                                    LinhaMae, LinhaPai, Ordena, QuantidadeUnStock, UnidadeStock, RelUni, FactorRelUni, ValorStock, DataStockMOV, Changed, FCor, LinhaArtCliForn, 
                                    Local, KeyScript, Formato, Qual, TipoEmbalagem, Superficie, Decoracao, RefCor, Lote, TabEspessura, Palete, SectorDestino, RefP, LoteInt, 
                                    Script, DocumentoOrigem, LinhaDocumentoOrigem, Calibre, LinhaPL, DocPL, NivelPalete)".
               "SELECT 0,'{$CodigoGS}', '{$NumeroGS}', '','15', '{$Sector}', 0, 'ACERTO GS WEB', ({$Quantidade}), ({$Quantidade}),'{$Referencia}', '{$Artigo}', '{$DescricaoArtigo}', 0, 0, 0,
               '{$Unidade}', VolumeM3, Preco, PrecoNM, Desconto, '00', 0, NVolumes, VolumeCarga, Peso, FactorEmbalagem,TotalNVolumes, TotalVolumeCarga, TotalPeso,({$Quantidade})*PrecoNM, 
               0, 0,({$Quantidade})*PrecoNM, 0, ({$Quantidade})*PrecoNM,({$Quantidade})*PrecoNM, 0, 0, ({$Quantidade})*PrecoNM, 0, ({$Quantidade})*PrecoNM,'GS','{$user}',getdate(), 0, 0, 0, 0, TotalComissao, 
               TotalComissaoNM, -1, 0, 0,({$Quantidade}),'{$Unidade}', '{$reluni}' , 1, 0, getdate(), 1, 0, 0, '{$Local}', 'Corrigir_Stock#correct_stock', '{$Formato}', '{$Qual}', 
               '{$TipoEmbalagem}', '{$Superficie}', '{$Decoracao}', '{$RefCor}', '{$Lote}', '{$TabEspessura}', '{$DocPL}', '', '{$Referencia}', '', -1, '', 0, '{$Calibre}', {$LinhaPL}, '{$DocPL}', '{$Nivel}'
               from PlLDocs
               where NumeroDocumento='{$DocPL}' and {$Reabilitado}=1";       
        
       // echo $sql01; 
        $this->db->query($sql04);
        $this->db->close();

        $sql05 ="UPDATE StklDocs
                 SET LinhaMae=NumeroLinha, LinhaPai=NumeroLinha, Ordena=NumeroLinha
                 WHERE NumeroDocumento='{$NumeroGS}'";   
        $this->db->query($sql05);
        $this->db->close();  

        $sql06 ="UPDATE StkLDocs
                 SET LoteInt=substring(cast(replicate('0',15-len(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(Lote,'a',''),'b',''),'c',''),'d',''),'e',''),'f',''),'g',''),'h',''),'i',''),'j',''),'k',''),'l',''),'m',''),'n',''),'o',''),'p',''),'q',''),'r',''),'s',''),'t',''),'u',''),'v',''),'w',''),'x',''),'y',''),'z',''),' ',''),',','.'),'ç',''),'?','')))+rtrim(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(Lote,'a',''),'b',''),'c',''),'d',''),'e',''),'f',''),'g',''),'h',''),'i',''),'j',''),'k',''),'l',''),'m',''),'n',''),'o',''),'p',''),'q',''),'r',''),'s',''),'t',''),'u',''),'v',''),'w',''),'x',''),'y',''),'z',''),' ',''),',','.'),'ç',''),'?','')) as varchar(15)),1,15)
                 where NumeroDocumento='{$NumeroGS}' and isnull(Lote,'')<>'' and isnull(LoteInt,'')=''";
        $this->db->query($sql06);
        $this->db->close();  
        
    }
}