<?php

class Anulacao_Carga extends CI_Model
{


    public function __construct()
    {
        parent::__construct();
        $this->load->database('default');
        $this->load->helper('url');
        $this->load->library('session');
        //$this->db->reconnect();
    }

    public function valida_movimentosPL($palete){

        $sql="SELECT cast(B.NumeroLinha as int) NumeroLinha, B.NumeroDocumento, B.DataHoraMOV, B.OperadorMOV, B.NumeroSerieInferior, B.Quantidade, 
                     B.Unidade, B.Sector, B.TipoMovimento, B.Local, 
                     case when B.TipoMovimento in ('00','01','02','03','04','05','06','07','08','09') then B.Quantidade else B.Quantidade*-1 end QtdMOV
              from PlDocs A join StkLDocs B on (A.Numero='{$palete}' and A.Numero=B.Palete)
              where isnull(B.NumeroSerieInferior,'')<>'PALETIZAÇÃO CLIENTE'";
        
        $query = $this->db->query($sql);
        $result = $query->result();
        return $result;   
    }

    public function get_valor_reverte($palete){

        $sql="SELECT case when isnull(Reverte,0)=0 then 'FALSE' else 'TRUE' end Reverte
              from PlDocs Where Numero='{$palete}'";
        
        $query = $this->db->query($sql);
        $result = $query->result();
        return $result;   
    }

    public function anula_palete($cliente,$encomenda,$linha,$palete_cliente,$palete_origem,$setor_cliente,$setor_exp,$reverte,$movimenta,$local,$username,$funcionario_gpac){

        $this->load->dbforge();
        $user=strtoupper($funcionario_gpac);        
        if($user==''){
            $user=strtoupper($username);
        }
        $tbl01 = $user.'.MS_ExeSP';
        $this->createTBL_exesp($tbl01);

        $this->reentrada_paleteorigem($tbl01,$palete_cliente,$local,$user);
        $this->anula_paletecliente($tbl01,$palete_cliente,$setor_exp,$local,$user);

        //faz movimento entre empresas 
        if($movimenta == 1){
            $this->anula_mov_entre_empresas($tbl01,$palete_cliente,$setor_cliente,$local,$user);
            $this->atualiza_reverte($palete_origem);
        }

        //faz movimento de stocks        
        //cria SP
        $DocSP=$this->getMax_SP();        
        foreach ($DocSP as $val) {
			$CodigoSP = $val->Documento;
            $SerieSP = $val->Serie;  
            $NumeradorSP = $val->Novo_Numerador;          
            $NumeroSP = $val->Numero;
		}           
        $_SESSION["nSP"]=$NumeroSP;
        $this->createSP($NumeroSP,$CodigoSP,$SerieSP,$user);
        $this->update_Series_SP($NumeradorSP,$CodigoSP,$SerieSP);
        $this->movimenta_stock($NumeroSP,$CodigoSP,$tbl01,$user);
        $this->atualiza_estadoPL($palete_cliente);    
        $this->insere_estadologPL($palete_cliente,$user);  
        
        //atualiza qtd paletizada    
        $tbl02 = $user.'.MS_QtdPL';
        $this->createTBL($tbl02);
        $tbl03 = $user.'.MS_Temp';
        $this->createTBL_temp($tbl03);        
        $this->atualiza_qtdpaletizada_vdlencs($tbl02,$tbl03);

        //QTD_ENTREGUE_VDLENCS     
        $tbl04 = $user.'.MS_QtdEN';   
        $this->createTBL_qtdEN($tbl04);
        $tbl05 = $user.'.MS_Temp1';   
        $this->createTBL($tbl05);
        $tbl06 = $user.'.MS_Temp2';   
        $this->createTBL($tbl06);
        $this->qtd_entregue_vdlencs($tbl04,$tbl05,$tbl06);
        
        /*
        $sql = "SELECT LinhaDocumento, NumeroDocumento, Documento, TipoMovimento, Sector, NumeroSerieInferior, NumeroSerieSuperior, Quantidade, QuantidadeUnidade, 
                       Referencia, Artigo, DescricaoArtigo, Formato, RefCor, Qual, TipoEmbalagem, Superficie, Lote, Calibre, Decoracao, Acabamento, Coleccao, 
                       TabEspessura, RefP, Unidade, Preco, PrecoNM, Desconto, Iva, TaxaIva, Local, KeyScript, Palete, TotalMercadoria, TotalDescontos, DescFin, 
                       TotalIliquido, TotalIva, TotalLiquido, TotalMercadoriaNM, TotalDescontosNM, DescFinNM, TotalIliquidoNM, TotalIvaNM, TotalLiquidoNM, OperadorMOV, 
                       DataHoraMOV
                FROM ". $tbl01;

        echo $sql;

        $query = $this->db->query($sql);
        $result = $query->result();
        return $result;    
        */
    }

    public function reentrada_paleteorigem($tbl,$palete_cliente,$local,$user){
        $sql = "INSERT into ". $tbl ."(LinhaDocumento, NumeroDocumento, Documento, TipoMovimento, Sector, NumeroSerieInferior, NumeroSerieSuperior, Quantidade, QuantidadeUnidade, 
                                       Referencia, Artigo, DescricaoArtigo, Formato, RefCor, Qual, TipoEmbalagem, Superficie, Lote, Calibre, Decoracao, Acabamento, Coleccao, 
                                       TabEspessura, RefP, Unidade, Preco, PrecoNM, Desconto, Iva, TaxaIva, Local, KeyScript, Palete, TotalMercadoria, TotalDescontos, DescFin, 
                                       TotalIliquido, TotalIva, TotalLiquido, TotalMercadoriaNM, TotalDescontosNM, DescFinNM, TotalIliquidoNM, TotalIvaNM, TotalLiquidoNM, OperadorMOV, 
                                       DataHoraMOV)".
               "SELECT 0, '', '', '02', Sector, 'ANULAÇÃO PLC TERMINAL', '', Quantidade, Quantidade, Referencia, Artigo, DescricaoArtigo, Formato, RefCor, Qual, TipoEmbalagem, 
                       Superficie, Lote, Calibre, Decoracao, Acabamento, Coleccao, TabEspessura, Referencia, Unidade, PrecoNM, PrecoNM, '0', '00', 0, '{$local}', 
                       'Anulacao_Carga#reentrada_paleteorigem', PaleteOrigem, Quantidade*PrecoNM, 0, 0, Quantidade*PrecoNM, 0, Quantidade*PrecoNM, Quantidade*PrecoNM, 0, 0, 
                       Quantidade*PrecoNM, 0, Quantidade*PrecoNM, '{$user}', getdate()
                from PlLDocs
                where NumeroDocumento='{$palete_cliente}'";
        $this->db->query($sql);
        $this->db->close();        
    }

    public function anula_paletecliente($tbl,$palete_cliente,$setor_exp,$local,$user){
        $sql = "INSERT into ". $tbl ."(LinhaDocumento, NumeroDocumento, Documento, TipoMovimento, Sector, NumeroSerieInferior, NumeroSerieSuperior, Quantidade, QuantidadeUnidade, 
                                       Referencia, Artigo, DescricaoArtigo, Formato, RefCor, Qual, TipoEmbalagem, Superficie, Lote, Calibre, Decoracao, Acabamento, Coleccao, 
                                       TabEspessura, RefP, Unidade, Preco, PrecoNM, Desconto, Iva, TaxaIva, Local, KeyScript, Palete, TotalMercadoria, TotalDescontos, DescFin, 
                                       TotalIliquido, TotalIva, TotalLiquido, TotalMercadoriaNM, TotalDescontosNM, DescFinNM, TotalIliquidoNM, TotalIvaNM, TotalLiquidoNM, OperadorMOV, 
                                       DataHoraMOV)".
               "SELECT 0, '', '', '12', '{$setor_exp}', 'ANULAÇÃO PLC TERMINAL', '', Quantidade, Quantidade, Referencia, Artigo, DescricaoArtigo, Formato, RefCor, Qual, TipoEmbalagem, 
                       Superficie, Lote, Calibre, Decoracao, Acabamento, Coleccao, TabEspessura, Referencia, Unidade, PrecoNM, PrecoNM, Desconto, '00', 0, '{$local}', 
                       'Anulacao_Carga#anula_paletecliente', NumeroDocumento, Quantidade*PrecoNM, 0, 0, Quantidade*PrecoNM, 0, Quantidade*PrecoNM, Quantidade*PrecoNM, 0, 0, 
                       Quantidade*PrecoNM, 0, Quantidade*PrecoNM, '{$user}', getdate()
                from PlLDocs
                where NumeroDocumento='{$palete_cliente}'";
        $this->db->query($sql);
        $this->db->close();        
    }

    public function anula_mov_entre_empresas($tbl,$palete_cliente,$setor_cliente,$local,$user){
        $sql = "INSERT into ". $tbl ."(LinhaDocumento, NumeroDocumento, Documento, TipoMovimento, Sector, NumeroSerieInferior, NumeroSerieSuperior, Quantidade, QuantidadeUnidade, 
                                       Referencia, Artigo, DescricaoArtigo, Formato, RefCor, Qual, TipoEmbalagem, Superficie, Lote, Calibre, Decoracao, Acabamento, Coleccao, 
                                       TabEspessura, RefP, Unidade, Preco, PrecoNM, Desconto, Iva, TaxaIva, Local, KeyScript, Palete, TotalMercadoria, TotalDescontos, DescFin, 
                                       TotalIliquido, TotalIva, TotalLiquido, TotalMercadoriaNM, TotalDescontosNM, DescFinNM, TotalIliquidoNM, TotalIvaNM, TotalLiquidoNM, OperadorMOV, 
                                       DataHoraMOV)".
                 "SELECT 0, '', '', '12', '{$setor_cliente}', 'ANULAÇÃO PLC TERMINAL', '', A.Quantidade, A.Quantidade, A.Referencia, A.Artigo, A.DescricaoArtigo, A.Formato, A.RefCor, A.Qual, 
                         A.TipoEmbalagem, A.Superficie, A.Lote, A.Calibre, A.Decoracao, A.Acabamento, A.Coleccao, A.TabEspessura, A.Referencia, A.Unidade, A.PrecoNM, A.PrecoNM, '0', 
                         '00', 0, '{$local}', 'Anulacao_Carga#anula_mov_entre_empresas', A.PaleteOrigem, A.Quantidade*A.PrecoNM, 0, 0, A.Quantidade*A.PrecoNM, 0, A.Quantidade*A.PrecoNM, 
                         A.Quantidade*A.PrecoNM, 0, 0, A.Quantidade*A.PrecoNM, 0, A.Quantidade*A.PrecoNM, '{$user}', getdate()
                  from PlLDocs A
                  where A.NumeroDocumento='{$palete_cliente}'";

        $this->db->query($sql);
        $this->db->close();        
    }

    public function atualiza_reverte($palete_origem) {
        $sql ="UPDATE PlDocs
               SET Reverte=0
               WHERE Numero='{$palete_origem}'";   
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

    public function update_Series_SP($Numerador,$Codigo,$Serie){

		$sql ="UPDATE Series
			   SET Numerador={$Numerador}
			   WHERE Documento='{$Codigo}' and Serie='{$Serie}'";   
		$this->db->query($sql);	
        $this->db->close();
	}

    public function movimenta_stock($NumeroSP,$CodigoSP,$tbl,$user){

        $sql01="INSERT INTO StklDocs (LinhaDocumento, CodigoDocumento, NumeroDocumento, Documento, TipoMovimento, Sector, Nivel, NumeroSerieInferior, Quantidade, 
                                      QuantidadeUnidade, Referencia, Artigo, DescricaoArtigo, Comprimento, Largura, Espessura, Unidade, VolumeM3, Preco, PrecoNM, 
                                      Desconto, Iva, TaxaIva, NVolumes, VolumeCarga, Peso, FactorEmbalagem, TotalNVolumes, TotalVolumeCarga, TotalPeso, TotalMercadoria, 
                                      TotalDescontos, DescFin, TotalIliquido, TotalIva, TotalLiquido, TotalMercadoriaNM, TotalDescontosNM, DescFinNM, TotalIliquidoNM, 
                                      TotalIvaNM, TotalLiquidoNM, Status, OperadorMOV, DataHoraMOV, Cor, CompCorte, LargCorte, EspeCorte, TotalComissao, TotalComissaoNM, 
                                      LinhaMae, LinhaPai, Ordena, QuantidadeUnStock, UnidadeStock, RelUni, FactorRelUni, ValorStock, DataStockMOV, Changed, FCor, LinhaArtCliForn, 
                                      Local, KeyScript, Formato, Qual, TipoEmbalagem, Superficie, Decoracao, RefCor, Lote, TabEspessura, Palete, SectorDestino, RefP, LoteInt, 
                                      Script, DocumentoOrigem, LinhaDocumentoOrigem, Calibre, LinhaPL, DocPL, NivelPalete) ".
               "SELECT 0,'{$CodigoSP}', '{$NumeroSP}', '',  TipoMovimento, Sector, 0, NumeroSerieInferior, Quantidade, QuantidadeUnidade, Referencia, Artigo, DescricaoArtigo, 0, 0, 0,
                       Unidade, 0, Preco, PrecoNM, Desconto, Iva, TaxaIva, 0, 0, 0, 0, 0, 0, 0, TotalMercadoria, TotalDescontos, DescFin, TotalIliquido, TotalIva, TotalLiquido, 
                       TotalMercadoriaNM, TotalDescontosNM, DescFinNM, TotalIliquidoNM, TotalIvaNM, TotalLiquidoNM, 'SP','{$user}',getdate(), 0, 0, 0, 0, 0, 0, -1, 0, 0, 
                       Quantidade, Unidade, CONCAT(Unidade,' = ',Unidade), 1, 0, getdate(), 1, 0, 0, Local, 'Anulacao_Carga#movimenta_stock', Formato, Qual, TipoEmbalagem,
                       Superficie, Decoracao, RefCor, Lote, TabEspessura, Palete, '', RefP, '', -1, '', 0, Calibre, 0, Palete, ''
                FROM ". $tbl;
    //echo $sql01;
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

    public function atualiza_estadoPL($palete_cliente){
        $sql = "UPDATE PlDocs
                SET Estado='A', Cor=8421631
                WHERE Numero='{$palete_cliente}'";   
        $this->db->query($sql);
        $this->db->close();
    }

    public function insere_estadologPL($palete_cliente,$user){
        $sql = "INSERT INTO EstadoLog (Documento, Accao, Maquina, DataHoraMov, OperadorMOV, Estado, Sistema, OperSistH)
                SELECT Numero, 'BLOQUEOU', '', getdate(), '{$user}', 'F', 0, ''
                FROM PlDocs
                WHERE Numero='{$palete_cliente}'";                
        $this->db->query($sql);
        $this->db->close();  

    }

    public function atualiza_qtdpaletizada_vdlencs($tbl02,$tbl03){

        $sql01 = "UPDATE VdLEncs set QtdPaletizada=0 where QtdPaletizada is null";
        $this->db->query($sql01);
        $this->db->close();  

        $sql02 = "INSERT into ". $tbl02 ."(LinhaDocumento, Documento, Quantidade)".
                 "SELECT A.LinhaDocumento, A.Documento, round(sum(A.Quantidade),4) Quantidade
                  from PlLDocs A join PlDocs B on (A.NumeroDocumento=B.Numero)
                  where B.Serie in ('C','PC') and B.Estado<>'A'
                  group by A.LinhaDocumento, A.Documento";
        $this->db->query($sql02);
        $this->db->close(); 

        $sql03 = "INSERT into ". $tbl03 ."(NumeroLinha, QtdPaletizada, Quantidade)".
                 "SELECT A.NumeroLinha, isnull(A.QtdPaletizada,0) QtdPaletizada, isnull(B.Quantidade,0) Quantidade
                  from VdLEncs A left join MS_QtdPL B on (A.NumeroLinha=B.LinhaDocumento and A.NumeroDocumento=B.Documento)
                  where isnull(A.QtdPaletizada,0)<>isnull(B.Quantidade,0)";
        $this->db->query($sql03);
        $this->db->close(); 

        $sql04 = "UPDATE A
                  set A.QtdPaletizada=isnull(X.Quantidade,0)
                  from ". $tbl03 ." X join VdLEncs A WITH (NOLOCK) on (X.NumeroLinha=A.NumeroLinha)";
        $this->db->query($sql04);
        $this->db->close();  
    }

    public function qtd_entregue_vdlencs($tbl04,$tbl05,$tbl06){

        $sql01 = "INSERT into ". $tbl04 ."(NumeroLinha, NumeroDocumento, Quantidade, QuantidadeSatisfeita, Concluido, Estado, QtdFA, QtdGT, QtdEntregue)".
                 "SELECT B.NumeroLinha, B.NumeroDocumento, B.Quantidade, B.QuantidadeSatisfeita, B.Concluido, A.Estado, 0, 0, 0
                  from VdEncs A join VdLEncs B on (A.Numero=B.NumeroDocumento and A.Estado<>'A')";
        $this->db->query($sql01);
        $this->db->close(); 
        
        $sql02 = "INSERT into ". $tbl05 ."(LinhaDocumento, Documento, Quantidade)".
                 "SELECT B.LinhaDocumento, B.Documento, sum(B.Quantidade) Quantidade
                  from VdLDocs B join VdDocs C on (B.DocumentoFacturacao=C.Numero)
                  where C.Codigo='FA' and C.Estado<>'A'
                  group by B.LinhaDocumento, B.Documento";
        $this->db->query($sql02);
        $this->db->close(); 

        $sql03 = "INSERT into ". $tbl06 ."(LinhaDocumento, Documento, Quantidade)".
                 "SELECT B.LinhaDocumento, B.Documento, sum(B.Quantidade) Quantidade
                 from VdLDocs B join VdDocs C on (B.DocumentoTransporte=C.Numero)
                 where C.Codigo='GT' and C.Estado<>'A'
                 group by B.LinhaDocumento, B.Documento";
        $this->db->query($sql03);
        $this->db->close(); 

        $sql04 = "UPDATE A 
                  set A.QtdFA=isnull(B.Quantidade,0) 
                  from ". $tbl04 ." A join ".$tbl05 ." B on (A.NumeroLinha=B.LinhaDocumento and A.NumeroDocumento=B.Documento)";
        $this->db->query($sql04);
        $this->db->close();  

        $sql05 = "UPDATE A 
                  set A.QtdGT=isnull(B.Quantidade,0) 
                  from ". $tbl04 ." A join ".$tbl06 ." B on (A.NumeroLinha=B.LinhaDocumento and A.NumeroDocumento=B.Documento)";
        $this->db->query($sql05);
        $this->db->close();  

        $sql06 = "UPDATE ". $tbl04 ." set QtdEntregue=case when (Concluido=1 or Estado='E') then Quantidade when (Concluido=1 or Estado='E') and QtdFA>=QtdGT then QtdFA else QtdGT end";        
        $this->db->query($sql06);
        $this->db->close();  
        
        $sql07 = "delete from ". $tbl04 ." where QuantidadeSatisfeita=QtdEntregue";
        $this->db->query($sql07);
        $this->db->close();       

        $sql08 = "UPDATE update A
                  set A.QuantidadeSatisfeita=B.QtdEntregue, A.QuantidadeFalta=A.Quantidade-B.QtdEntregue
                  from ". $tbl04 ." B join VdLEncs A WITH (NOLOCK) on (A.NumeroLinha=B.NumeroLinha and A.NumeroDocumento=B.NumeroDocumento)";
        $this->db->query($sql08);
        $this->db->close();         
    }

    /*
    * CREATE TABLEs
    */
    public function createTBL_exesp($tbl){
                        
        $this->dbforge->drop_table($tbl,TRUE);
        
        $fields = array(
            'LinhaDocumento' => array(
                'type' => 'INT',
                'null' => TRUE
            ),
            'NumeroDocumento' => array(
                'type' => 'VARCHAR',
                'constraint' => '17',
                'null' => TRUE
            ),
            'Documento' => array(
                'type' => 'VARCHAR',
                'constraint' => '17',
                'null' => TRUE
            ),
            'TipoMovimento' => array(
                'type' => 'VARCHAR',
                'constraint' => '2',
                'null' => TRUE
            ),
            'Sector' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE
            ),
            'NumeroSerieInferior' => array(
                'type' => 'VARCHAR',
                'constraint' => '30',
                'null' => TRUE
            ),
            'NumeroSerieSuperior' => array(
                'type' => 'VARCHAR',
                'constraint' => '30',
                'null' => TRUE
            ),
            'Quantidade' => array(
                'type' => 'FLOAT',
                'null' => TRUE
            ),
            'QuantidadeUnidade' => array(
                'type' => 'FLOAT',
                'null' => TRUE
            ),
            'Referencia' => array(
                'type' => 'VARCHAR',
                'constraint' => '30',
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
            'TabEspessura' => array(
                'type' => 'VARCHAR',
                'constraint' => '10',
                'null' => TRUE
            ),
            'RefP' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE
            ),
            'Unidade' => array(
                'type' => 'VARCHAR',
                'constraint' => '5',
                'null' => TRUE
            ),
            'Preco' => array(
                'type' => 'FLOAT',
                'null' => TRUE
            ),
            'PrecoNM' => array(
                'type' => 'FLOAT',
                'null' => TRUE
            ),
            'Desconto' => array(
                'type' => 'VARCHAR',
                'constraint' => '40',
                'null' => TRUE
            ),
            'Iva' => array(
                'type' => 'VARCHAR',
                'constraint' => '2',
                'null' => TRUE
            ),
            'TaxaIva' => array(
                'type' => 'FLOAT',
                'null' => TRUE
            ),
            'Local' => array(
                'type' => 'VARCHAR',
                'constraint' => '10',
                'null' => TRUE
            ),
            'KeyScript' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ),
            'Palete' => array(
                'type' => 'VARCHAR',
                'constraint' => '17',
                'null' => TRUE
            ),
            'TotalMercadoria' => array(
                'type' => 'FLOAT',
                'null' => TRUE
            ),
            'TotalDescontos' => array(
                'type' => 'FLOAT',
                'null' => TRUE
            ),
            'DescFin' => array(
                'type' => 'FLOAT',
                'null' => TRUE
            ),
            'TotalIliquido' => array(
                'type' => 'FLOAT',
                'null' => TRUE
            ),
            'TotalIva' => array(
                'type' => 'FLOAT',
                'null' => TRUE
            ),
            'TotalLiquido' => array(
                'type' => 'FLOAT',
                'null' => TRUE
            ),
            'TotalMercadoriaNM' => array(
                'type' => 'FLOAT',
                'null' => TRUE
            ),
            'TotalDescontosNM' => array(
                'type' => 'FLOAT',
                'null' => TRUE
            ),
            'DescFinNM' => array(
                'type' => 'FLOAT',
                'null' => TRUE
            ),
            'TotalIliquidoNM' => array(
                'type' => 'FLOAT',
                'null' => TRUE
            ),
            'TotalIvaNM' => array(
                'type' => 'FLOAT',
                'null' => TRUE
            ),
            'TotalLiquidoNM' => array(
                'type' => 'FLOAT',
                'null' => TRUE
            ),
            'OperadorMOV' => array(
                'type' => 'VARCHAR',
                'constraint' => '10',
                'null' => TRUE
            ),
            'DataHoraMOV' => array(
                'type' => 'DATETIME',
                'null' => TRUE
            )
        );
        
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table($tbl,TRUE);
    }

    public function createTBL($tbl){
                        
        $this->dbforge->drop_table($tbl,TRUE);
        
        $fields = array(
            'LinhaDocumento' => array(
                'type' => 'INT',
                'null' => TRUE
            ),            
            'Documento' => array(
                'type' => 'VARCHAR',
                'constraint' => '17',
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

    public function createTBL_temp($tbl){
                        
        $this->dbforge->drop_table($tbl,TRUE);
        
        $fields = array(
            'NumeroLinha' => array(
                'type' => 'INT',
                'null' => TRUE
            ),            
            'QtdPaletizada' => array(
                'type' => 'FLOAT',
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

    public function createTBL_qtdEN($tbl){
                                
        $this->dbforge->drop_table($tbl,TRUE);
        
        $fields = array(
            'NumeroLinha' => array(
                'type' => 'INT',
                'null' => TRUE
            ),            
            'NumeroDocumento' => array(
                'type' => 'VARCHAR',
                'constraint' => '17',
                'null' => TRUE
            ),
            'Quantidade' => array(
                'type' => 'FLOAT',
                'null' => TRUE
            ),
            'QuantidadeSatisfeita' => array(
                'type' => 'FLOAT',
                'null' => TRUE
            ),
            'Concluido' => array(
                'type' => 'INT',
                'null' => TRUE
            ),            
            'Estado' => array(
                'type' => 'VARCHAR',
                'constraint' => '3',
                'null' => TRUE
            ),
            'QtdFA' => array(
                'type' => 'FLOAT',
                'null' => TRUE
            ),
            'QtdGT' => array(
                'type' => 'FLOAT',
                'null' => TRUE
            ),
            'QtdEntregue' => array(
                'type' => 'FLOAT',
                'null' => TRUE
            )
        );
        
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table($tbl,TRUE);
    }

}