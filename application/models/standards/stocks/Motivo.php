<?php

class Motivo extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database('default');
        $this->load->helper('url');
        $this->load->library('session');
        //$this->db->reconnect();
    }

    public function save_motivo_and_movstock($palete,$sectorDestino,$codigomotivo,$motivo,$obs,$username,$funcionario_gpac,$user_type){

        $user=strtoupper($funcionario_gpac);        
        if($user==''){
            $user=strtoupper($username);
        }


        //cria SP
        $DocSP=$this->getMax_SP();
        //print_r($DocSP);
        
        foreach ($DocSP as $val) {
			$CodigoSP = $val->Documento;
            $SerieSP = $val->Serie;  
            $NumeradorSP = $val->Novo_Numerador;          
            $NumeroSP = $val->Numero;
		}
           
        $this->createSP($NumeroSP,$CodigoSP,$SerieSP,$user);
        $this->update_Series_SP($NumeradorSP,$CodigoSP,$SerieSP);
        
        $this->remove_palete_sem_stock($NumeroSP,$CodigoSP,$palete,$user);
        
        //entrada em stock da  criada (Stkldocs)
        $this->insere_palete_sem_stock($NumeroSP,$CodigoSP,$palete,$sectorDestino,$user);

        //zxErrosPicagemPalete
        $this->save_motivo($palete,$codigomotivo,$motivo,$obs,$username,$funcionario_gpac,$user_type);
    }

    public function save_motivo($palete,$codigomotivo,$motivo,$obs,$username,$funcionario_gpac,$user_type){

        $user=strtoupper($funcionario_gpac);        
        if($user==''){
            $user=strtoupper($username);
        }

        $sector = $this->convert_type_to_sector02($user_type);
        
        $sql="INSERT INTO zxErrosPicagemPalete (Palete, Motivo, Observacoes, CodigoMotivo, SectorErro, OperadorMOV, DataHoraMOV)
              VALUES ('{$palete}', '{$motivo}', '{$obs}', '{$codigomotivo}', '{$sector}', '{$user}',getdate())";       
       
        $this->db->query($sql);
        $this->db->close();
    }

    public function getMax_SP(){
        $sql="SELECT TOP 1 Documento, Serie, Numerador+1 Novo_Numerador, Documento+Serie+CAST(Numerador+1 as varchar(20)) Numero
              FROM Series
              WHERE Documento='SP' and Serie='P'";         
        
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


    public function remove_palete_sem_stock($NumeroSP,$CodigoSP,$palete,$user){

        $reluni= 'M2 = M2';

        $sql01="INSERT INTO StklDocs (LinhaDocumento, CodigoDocumento, NumeroDocumento, Documento, TipoMovimento, Sector, Nivel, NumeroSerieInferior, Quantidade, 
                                        QuantidadeUnidade, Referencia, Artigo, DescricaoArtigo, Comprimento, Largura, Espessura, Unidade, VolumeM3, Preco, PrecoNM, 
                                        Desconto, Iva, TaxaIva, NVolumes, VolumeCarga, Peso, FactorEmbalagem, TotalNVolumes, TotalVolumeCarga, TotalPeso, TotalMercadoria, 
                                        TotalDescontos, DescFin, TotalIliquido, TotalIva, TotalLiquido, TotalMercadoriaNM, TotalDescontosNM, DescFinNM, TotalIliquidoNM, 
                                        TotalIvaNM, TotalLiquidoNM, Status, OperadorMOV, DataHoraMOV, Cor, CompCorte, LargCorte, EspeCorte, TotalComissao, TotalComissaoNM, 
                                        LinhaMae, LinhaPai, Ordena, QuantidadeUnStock, UnidadeStock, RelUni, FactorRelUni, ValorStock, DataStockMOV, Changed, FCor, LinhaArtCliForn, 
                                        Local, KeyScript, Formato, Qual, TipoEmbalagem, Superficie, Decoracao, RefCor, Lote, TabEspessura, Palete, SectorDestino, RefP, LoteInt, 
                                        Script, DocumentoOrigem, LinhaDocumentoOrigem, Calibre, LinhaPL, DocPL, NivelPalete)".
                "SELECT 0,'{$CodigoSP}', '{$NumeroSP}', '', '12', Sector, 0, 'REMOÇÃO DA PALETE', Quantidade, Quantidade, Referencia, Artigo, DescricaoArtigo, 0, 0, 0,
                        Unidade, VolumeM3, Preco, PrecoNM, Desconto, '00', 0, NVolumes, VolumeCarga, Peso, FactorEmbalagem, TotalNVolumes, TotalVolumeCarga, TotalPeso, 
                        Quantidade*PrecoNM, 0, 0, Quantidade*PrecoNM, 0, Quantidade*PrecoNM, Quantidade*PrecoNM, 0, 0, Quantidade*PrecoNM, 0, Quantidade*PrecoNM, 
                        'SP','{$user}',getdate(), 0, 0, 0, 0, TotalComissao, TotalComissaoNM, -1, 0, 0, Quantidade, Unidade, '{$reluni}' , 1, 0, getdate(), 1, 0, 0, 
                        Local, '', Formato, Qual, TipoEmbalagem, Superficie, Decoracao, RefCor, Lote, TabEspessura, NumeroDocumento, '', 
                        Referencia, '', -1, '', 0, Calibre, NumeroLinha, NumeroDocumento, NivelPalete
                from PlLDocs
                where NumeroDocumento='{$palete}'";       
        
       // echo $sql01; 

        $this->db->query($sql01);
        $this->db->close();

        $sql02 ="UPDATE StklDocs
                 SET LinhaMae=NumeroLinha
                 WHERE NumeroDocumento='{$NumeroSP}'";   
        $this->db->query($sql02);
        $this->db->close();  

        $sql03 ="UPDATE StkLDocs
                 SET LoteInt=substring(cast(replicate('0',15-len(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(Lote,'a',''),'b',''),'c',''),'d',''),'e',''),'f',''),'g',''),'h',''),'i',''),'j',''),'k',''),'l',''),'m',''),'n',''),'o',''),'p',''),'q',''),'r',''),'s',''),'t',''),'u',''),'v',''),'w',''),'x',''),'y',''),'z',''),' ',''),',','.'),'ç',''),'?','')))+rtrim(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(replace(Lote,'a',''),'b',''),'c',''),'d',''),'e',''),'f',''),'g',''),'h',''),'i',''),'j',''),'k',''),'l',''),'m',''),'n',''),'o',''),'p',''),'q',''),'r',''),'s',''),'t',''),'u',''),'v',''),'w',''),'x',''),'y',''),'z',''),' ',''),',','.'),'ç',''),'?','')) as varchar(15)),1,15)
                 where NumeroDocumento='{$NumeroSP}' and isnull(Lote,'')<>'' and isnull(LoteInt,'')=''";
        $this->db->query($sql03);
        $this->db->close(); 
    }
        
    public function insere_palete_sem_stock($NumeroSP,$CodigoSP,$palete,$sectorDestino,$user){

        $reluni= 'M2 = M2';

        $sql04="INSERT INTO StklDocs (LinhaDocumento, CodigoDocumento, NumeroDocumento, Documento, TipoMovimento, Sector, Nivel, NumeroSerieInferior, Quantidade, 
                                        QuantidadeUnidade, Referencia, Artigo, DescricaoArtigo, Comprimento, Largura, Espessura, Unidade, VolumeM3, Preco, PrecoNM, 
                                        Desconto, Iva, TaxaIva, NVolumes, VolumeCarga, Peso, FactorEmbalagem, TotalNVolumes, TotalVolumeCarga, TotalPeso, TotalMercadoria, 
                                        TotalDescontos, DescFin, TotalIliquido, TotalIva, TotalLiquido, TotalMercadoriaNM, TotalDescontosNM, DescFinNM, TotalIliquidoNM, 
                                        TotalIvaNM, TotalLiquidoNM, Status, OperadorMOV, DataHoraMOV, Cor, CompCorte, LargCorte, EspeCorte, TotalComissao, TotalComissaoNM, 
                                        LinhaMae, LinhaPai, Ordena, QuantidadeUnStock, UnidadeStock, RelUni, FactorRelUni, ValorStock, DataStockMOV, Changed, FCor, LinhaArtCliForn, 
                                        Local, KeyScript, Formato, Qual, TipoEmbalagem, Superficie, Decoracao, RefCor, Lote, TabEspessura, Palete, SectorDestino, RefP, LoteInt, 
                                        Script, DocumentoOrigem, LinhaDocumentoOrigem, Calibre, LinhaPL, DocPL, NivelPalete)".
                   "SELECT 0,'{$CodigoSP}', '{$NumeroSP}', '', '02', '{$sectorDestino}', 0, 'INSERÇÃO DA PALETE', Quantidade, Quantidade, Referencia, Artigo, DescricaoArtigo, 0, 0, 0,
                           Unidade, VolumeM3, Preco, PrecoNM, Desconto, '00', 0, NVolumes, VolumeCarga, Peso, FactorEmbalagem, TotalNVolumes, TotalVolumeCarga, TotalPeso, 
                           Quantidade*PrecoNM, 0, 0, Quantidade*PrecoNM, 0, Quantidade*PrecoNM, Quantidade*PrecoNM, 0, 0, Quantidade*PrecoNM, 0, Quantidade*PrecoNM, 
                           'SP','{$user}',getdate(), 0, 0, 0, 0, TotalComissao, TotalComissaoNM, -1, 0, 0, Quantidade, Unidade, '{$reluni}' , 1, 0, getdate(), 1, 0, 0, 
                           Local, '', Formato, Qual, TipoEmbalagem, Superficie, Decoracao, RefCor, Lote, TabEspessura, NumeroDocumento, '', 
                           Referencia, '', -1, '', 0, Calibre, NumeroLinha, NumeroDocumento, NivelPalete
                   from PlLDocs
                   where NumeroDocumento='{$palete}'";       
            
           // echo $sql01; 
    
            $this->db->query($sql04);
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
}