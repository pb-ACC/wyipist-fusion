<?php

class Anular_Paletes extends CI_Model 
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database('default');
        $this->load->helper('url');
        $this->load->library('session');
        //$this->db->reconnect();
    }

    public function save_cancellation($DocPL, $LinhaPL, $Referencia, $Artigo, $DescricaoArtigo, $Quantidade, $Unidade, $Sector, $Formato,
                                     $Qual, $TipoEmbalagem, $Superficie, $Decoracao, $RefCor, $Lote, $TabEspessura, $Calibre, $Local, 
                                     $Nivel, $motivoAnula, $obs, $username,$funcionario_gpac){

        $flag=0;
        $user=strtoupper($funcionario_gpac);        
        if($user==''){
            $user=strtoupper($username);
        }
        

        $resultado=$this->validaMov($DocPL);

        foreach ($resultado as $val) {
			$Linha = $val->NumeroLinha;
            $Doc = $val->NumeroDocumento;  
            $DataHora = $val->DataHoraMOV;          
            $Operador = $val->OperadorMOV;
            $SerieInferior = $val->NumeroSerieInferior;
            $Qtd = $val->Quantidade;
            $Uni = $val->Unidade;
            $Sec = $val->Sector;
            $TpMov = $val->TipoMovimento;
            $QtdMov = $val->QtdMOV;
            $Loc = $val->Local;

           // if($Doc != ''){                
                $this->insert_zxCrtAnulaPalete($Linha, $Doc, $DataHora, $Operador, $SerieInferior, $Qtd, $Uni, $Sec, $TpMov, $QtdMov, $Loc, $user);
            //}
		}
                   
        $this->update_zxPaletesProd($DocPL);

        $DocSP=$this->getMax_SP();
            //print_r($DocSP);
            
        foreach ($DocSP as $val) {
            $Codigo = $val->Documento;
            $Serie = $val->Serie;  
            $Numerador = $val->Novo_Numerador;          
            $Numero = $val->Numero;
        }
               
        $this->createSP($Numero,$Codigo,$Serie,$user);
        $this->update_Series($Numerador,$Codigo,$Serie);
          
        $table_name='#MS_ExeSP_anula'; 
          
        $this->remove_palete_stock($Codigo, $Numero, $table_name, $DocPL, $LinhaPL, $Referencia, $Artigo, $DescricaoArtigo, $Quantidade, $Unidade, 
                                   $Sector, $Formato, $Qual, $TipoEmbalagem, $Superficie, $Decoracao, $RefCor, $Lote, $TabEspessura, $Calibre, 
                                   $Local, $Nivel, $user);

        $this->updatePLDocs($DocPL, $motivoAnula, $obs);                                             
    }

    public function validaMov($DocPL){

        $sql="SELECT cast(B.NumeroLinha as int) NumeroLinha, B.NumeroDocumento, B.DataHoraMOV, B.OperadorMOV, B.NumeroSerieInferior, B.Quantidade, B.Unidade, B.Sector,
                     B.TipoMovimento, case when B.TipoMovimento<='10' then B.Quantidade else B.Quantidade*-1 end QtdMOV, B.Local
              from PlDocs A join StkLDocs B on (A.Numero='{$DocPL}' and A.Numero=B.Palete)
                            join StkDocs  C on (B.NumeroDocumento=C.Numero)
              where C.Serie<>'P' and C.Codigo<>'SP'";         
        
        $query = $this->db->query($sql);        
        $result = $query->result();
        
        return $result;

    }

    public function insert_zxCrtAnulaPalete($Linha, $Doc, $DataHora, $Operador, $SerieInferior, $Qtd, $Uni, $Sec, $TpMov, $QtdMov, $Loc, $user){

        $sql01="INSERT INTO zx_CrtAnulaPalete (NumeroLinha, NumeroDocumento, DataHoraMOV, OperadorMOV, NumeroSerieInferior, Quantidade, Unidade, Sector, TipoMovimento, 
                                               QtdMOV, Local, DataHora, Operador, Computador)".
               "VALUES({$Linha}, '{$Doc}', '{$DataHora}', '{$Operador}', '{$SerieInferior}', {$Qtd}, '{$Uni}', '{$Sec}', '{$TpMov}', {$QtdMov}, '{$Loc}', 
                       getdate(), '{$user}', '')";       
        
       // echo $sql01; 

        $this->db->query($sql01);
        $this->db->close();

    }

    
    public function update_zxPaletesProd($DocPL){

		$sql ="UPDATE zx_PaletesProd
               set Anulada=1
               where Palete='{$DocPL}'";   

		$this->db->query($sql);	
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

    public function update_Series($Numerador,$Codigo,$Serie){

		$sql ="UPDATE Series
			   SET Numerador={$Numerador}
			   WHERE Documento='{$Codigo}' and Serie='{$Serie}'";   
		$this->db->query($sql);	
        $this->db->close();
	}

        public function remove_palete_stock($Codigo, $Numero, $table_name, $DocPL, $LinhaPL, $Referencia, $Artigo, $DescricaoArtigo, $Quantidade, $Unidade, 
                                              $Sector, $Formato, $Qual, $TipoEmbalagem, $Superficie, $Decoracao, $RefCor, $Lote, $TabEspessura, $Calibre, 
                                              $Local, $Nivel, $user){

        $reluni= $Unidade.' = ' .$Unidade;

        $sql01="INSERT INTO StklDocs (LinhaDocumento, CodigoDocumento, NumeroDocumento, Documento, TipoMovimento, Sector, Nivel, NumeroSerieInferior, Quantidade, 
                                    QuantidadeUnidade, Referencia, Artigo, DescricaoArtigo, Comprimento, Largura, Espessura, Unidade, VolumeM3, Preco, PrecoNM, 
                                    Desconto, Iva, TaxaIva, NVolumes, VolumeCarga, Peso, FactorEmbalagem, TotalNVolumes, TotalVolumeCarga, TotalPeso, TotalMercadoria, 
                                    TotalDescontos, DescFin, TotalIliquido, TotalIva, TotalLiquido, TotalMercadoriaNM, TotalDescontosNM, DescFinNM, TotalIliquidoNM, 
                                    TotalIvaNM, TotalLiquidoNM, Status, OperadorMOV, DataHoraMOV, Cor, CompCorte, LargCorte, EspeCorte, TotalComissao, TotalComissaoNM, 
                                    LinhaMae, LinhaPai, Ordena, QuantidadeUnStock, UnidadeStock, RelUni, FactorRelUni, ValorStock, DataStockMOV, Changed, FCor, LinhaArtCliForn, 
                                    Local, KeyScript, Formato, Qual, TipoEmbalagem, Superficie, Decoracao, RefCor, Lote, TabEspessura, Palete, SectorDestino, RefP, LoteInt, 
                                    Script, DocumentoOrigem, LinhaDocumentoOrigem, Calibre, LinhaPL, DocPL, NivelPalete)".
               "SELECT 0,'{$Codigo}', '{$Numero}', '', '12', '{$Sector}', 0, 'ANULAÇÃO PL', {$Quantidade}, {$Quantidade}, '{$Referencia}', '{$Artigo}', '{$DescricaoArtigo}', 0, 0, 0,
               '{$Unidade}', VolumeM3, Preco, PrecoNM, Desconto, '00', 0, NVolumes, VolumeCarga, Peso, FactorEmbalagem, TotalNVolumes, TotalVolumeCarga, TotalPeso, 
               {$Quantidade}*PrecoNM, 0, 0, {$Quantidade}*PrecoNM, 0, {$Quantidade}*PrecoNM, {$Quantidade}*PrecoNM, 0, 0, {$Quantidade}*PrecoNM, 0, {$Quantidade}*PrecoNM, 
               'SP','{$user}',getdate(), 0, 0, 0, 0, TotalComissao, TotalComissaoNM, -1, 0, 0, {$Quantidade},'{$Unidade}', '{$reluni}' , 1, 0, getdate(), 1, 0, 0, 
               '{$Local}', 'Anular_Paletes#remove_palete_stock', '{$Formato}', '{$Qual}', '{$TipoEmbalagem}', '{$Superficie}', '{$Decoracao}', '{$RefCor}', '{$Lote}', '{$TabEspessura}', '{$DocPL}', '', 
               '{$Referencia}', '', -1, '', 0, '{$Calibre}', {$LinhaPL}, '{$DocPL}', '{$Nivel}'
               from PlLDocs
               where NumeroDocumento='{$DocPL}'";       
        
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

    public function updatePLDocs($DocPL, $motivoAnula, $obs){

        $sql01 ="UPDATE A
                 set A.NossaRef='{$motivoAnula}', A.Obs1='{$obs}', A.Estado='A'
                 from PlDocs A 
                 where A.Numero='{$DocPL}'";   

		$this->db->query($sql01);	
        $this->db->close();

		$sql02 ="UPDATE A
               set A.Cor=B.Cor
               from PlDocs A join Estado B on (A.Codigo=B.TipoDoc and B.Codigo='A')
               where A.Numero='{$DocPL}'";   

		$this->db->query($sql02);	
        $this->db->close();
	}
}