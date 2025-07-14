<?php
ini_set('max_input_time', 0);
ini_set('memory_limit',-1);
ini_set('max_execution_time', 0);
class Recolhe_Dados extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database('default');
        $this->load->helper('url');
        $this->load->library('session');
        //$this->db->reconnect();
    }

    public function recolhe_dados($st_arm, $st_prod, $st_ver, $st_exp, $cond01, $cond02, $serie, $username, $funcionario_gpac){
  
        $user=strtoupper($funcionario_gpac);        
        if($user==''){
            $user=strtoupper($username);
        }    
        $result = $this->agrupa_tudo($st_arm, $st_prod, $st_ver, $st_exp, $cond01, $cond02, $serie, $user);
        return $result;   

    }

    public function agrupa_tudo($st_arm, $st_prod, $st_ver, $st_exp, $cond01, $cond02, $serie, $user){
        $this->load->dbforge();

        //TABELA REFERECNIAS
        $tbl01 = $user.'.MS_RefP';
        $this->createTBL_refP($tbl01);
        $this->tabela_refs01($tbl01,$cond01);

        //TABELA PRINCIPAL 
        $tbl02 = $user.'.MS_TabX1';
        $this->createTBL_tabx1($tbl02);
        $this->tabela_principal01($tbl01,$tbl02,$user);

        //TABELA DATAHORA 
        $tbl03 = $user.'.MS_DF';
        $this->createTBL_df($tbl03);
        $this->tabela_datahora01($tbl03);
        $datahora = $this->tabela_datahora02($tbl03);

        foreach ($datahora as $val) {
            $df = $val->DataFinal;
        }

        //TABELA SETORES 
        $tbl04 = $user.'.MS_StProcura';
        $this->createTBL_stprocura($tbl04);
        $this->tabela_stprocura01($tbl04,$st_arm,$st_prod,$st_exp);


        //TABELA RECOLHE STOCK    
        $tbl05 = $user.'.MS_StkSectA';
        $this->createTBL_stksect($tbl05);
        $this->tabela_stksecta01($tbl01,$tbl04,$tbl05,$df,$cond02);
        $this->tabela_stksecta02($tbl05);
        $tbl06 = $user.'.MS_StkSect';
        $this->createTBL_stksect($tbl06);
        $this->tabela_stksecta03($tbl05,$tbl06);
        $tbl07 = $user.'.MS_NumPLs';
        $this->createTBL_numpls($tbl07);
        $this->tabela_stksecta04($tbl06,$tbl07);

        //MAIN        
        $this->tabela_main01($tbl02,$tbl06,$st_arm,$st_prod,$st_ver);

       //TABELAS ENCOMENDAS EM CURSO
        $tbl08 = $user.'.MS_InfoAfetacaoX1';
        $this->createTBL_infoafetacaoX1($tbl08);
        $this->tabela_infoafetacao01($tbl01,$tbl08,$serie);
        $tbl09 = $user.'.MS_ENsX1';
        $this->createTBL_ensX1($tbl09);
        $this->tabela_ensX01($tbl01,$tbl08,$tbl09,$serie);
        
        //TABELAS PRÉ-ENNCOMENDAS
        $tbl10 = $user.'.MS_WEX1';
        $this->createTBL_wex1($tbl10);
        $this->tabela_wex01($tbl10,$serie);


        //MAIN        
       $this->tabela_main02($tbl02,$tbl09,$tbl10);

        $sql001 = "SELECT count(A.id) nLinhas
                   FROM ( SELECT Id, CAST(Seleccionado AS INT) AS Seleccionado, Referencia, Descricao, ISNULL(Formato, '') AS Formato, ISNULL(Qualidade, '') AS Qualidade,
                                 ISNULL(ColeccaoCer, '') AS ColeccaoCer, ISNULL(Acabamento, '') AS Acabamento, ISNULL(Decoracao, '') AS Decoracao, ISNULL(Coleccao, '') AS Coleccao,  
                                 ISNULL(QtdStkArm, 0) AS QtdStkArm, ISNULL(QtdStkProd, 0) AS QtdStkProd, ISNULL(QtdStkAfetado, 0) AS QtdStkAfetado,
                                 ISNULL(QtdStkEncSemAfe, 0) AS QtdStkEncSemAfe, ISNULL(QtdStkPreEN, 0) AS QtdStkPreEN, ISNULL(QtdStkPaletizado, 0) AS QtdStkPaletizado,
                                 ISNULL(QtdStkReservado, 0) AS QtdStkReservado, ISNULL(StkDisponivel, 0) AS StkDisponivel, ISNULL(StkEmVer, 0) AS StkEmVer, ISNULL(Ordem, 0) AS Ordem,
                                 ISNULL(Nivel, 0) AS Nivel, ISNULL(NumeroLinha, 0) AS NumeroLinha, ISNULL(LinhaDocumento, 0) AS LinhaDocumento, ISNULL(Documento, '') AS Documento,
                                 ISNULL(Cor, 0) AS Cor, CAST(RefCeragni AS INT) AS RefCeragni, CAST(RefCerteca AS INT) AS RefCerteca
                          FROM ". $tbl02 ." 
                        ) A";
        
        $query01 = $this->db->query($sql001);        
        $tot  = $query01->result();
        foreach ($tot as $val) {
            $itemcount = $val->nLinhas;
        }

        $data = array();
        $arr = [];
        set_time_limit(0);
        $this->db->db_pconnect();
        $batches = $itemcount / 3500; // Number of while-loop calls - around 120.
        
        for ($i = 0; $i <= $batches; $i++) {
            $offset = $i * 3500; // MySQL Limit offset number
            $fetch = $offset + 3500;
            set_time_limit(0);
            $this->db->db_pconnect();
            $sql002 = "SELECT Id, CAST(Seleccionado AS INT) AS Seleccionado, Referencia, Descricao, ISNULL(Formato, '') AS Formato, ISNULL(Qualidade, '') AS Qualidade,
                              ISNULL(ColeccaoCer, '') AS ColeccaoCer, ISNULL(Acabamento, '') AS Acabamento, ISNULL(Decoracao, '') AS Decoracao, ISNULL(Coleccao, '') AS Coleccao,  
                              ISNULL(QtdStkArm, 0) AS QtdStkArm, ISNULL(QtdStkProd, 0) AS QtdStkProd, ISNULL(QtdStkAfetado, 0) AS QtdStkAfetado,
                              ISNULL(QtdStkEncSemAfe, 0) AS QtdStkEncSemAfe, ISNULL(QtdStkPreEN, 0) AS QtdStkPreEN, ISNULL(QtdStkPaletizado, 0) AS QtdStkPaletizado,
                              ISNULL(QtdStkReservado, 0) AS QtdStkReservado, ISNULL(StkDisponivel, 0) AS StkDisponivel, ISNULL(StkEmVer, 0) AS StkEmVer, ISNULL(Ordem, 0) AS Ordem,
                              ISNULL(Nivel, 0) AS Nivel, ISNULL(NumeroLinha, 0) AS NumeroLinha, ISNULL(LinhaDocumento, 0) AS LinhaDocumento, ISNULL(Documento, '') AS Documento,
                              ISNULL(Cor, 0) AS Cor, CAST(RefCeragni AS INT) AS RefCeragni, CAST(RefCerteca AS INT) AS RefCerteca
                       FROM ". $tbl02." 
                       ORDER BY Id
                       OFFSET ".$offset." ROWS
                       FETCH NEXT ".$fetch ." ROWS ONLY";  
            //and isnull(A.Local,'')<>'' 
            //echo $sql002;
            set_time_limit(0);
            $this->db->db_pconnect();                                          
            $query02 = $this->db->query($sql002);
        
                // Verifique se a consulta foi bem-sucedida
            if ($query02) {
                $result = $query02->result();                        
                // Processa os resultados conforme necessário
                foreach ($result as $row) {

                    $arr['Id'] = $row->Id;
                    $arr['Seleccionado'] = $row->Seleccionado;
                    $arr['Referencia'] = $row->Referencia;
                    $arr['Descricao'] = $row->Descricao;
                    $arr['Formato'] = $row->Formato;
                    $arr['Qualidade'] = $row->Qualidade;
                    $arr['Acabamento'] = $row->Acabamento;
                    $arr['Decoracao'] = $row->Decoracao;
                    $arr['Coleccao'] = $row->Coleccao;
                    $arr['ColeccaoCer'] = $row->ColeccaoCer;
                    $arr['QtdStkArm'] = $row->QtdStkArm;
                    $arr['QtdStkProd'] = $row->QtdStkProd;
                    $arr['QtdStkAfetado'] = $row->QtdStkAfetado;
                    $arr['QtdStkEncSemAfe'] = $row->QtdStkEncSemAfe;
                    $arr['QtdStkPreEN'] = $row->QtdStkPreEN;
                    $arr['QtdStkPaletizado'] = $row->QtdStkPaletizado;
                    $arr['QtdStkReservado'] = $row->QtdStkReservado;
                    $arr['StkDisponivel'] = $row->StkDisponivel;
                    $arr['StkEmVer'] = $row->StkEmVer;
                    $arr['Ordem'] = $row->Ordem;                    
                    $arr['Nivel'] = $row->Nivel;
                    $arr['NumeroLinha'] = $row->NumeroLinha;
                    $arr['LinhaDocumento'] = $row->LinhaDocumento;
                    $arr['Documento'] = $row->Documento;
                    $arr['Cor'] = $row->Cor;
                    $arr['RefCeragni'] = $row->RefCeragni;
                    $arr['RefCerteca'] = $row->RefCerteca;

                    $data[]=$arr;
                    unset($arr);
                }
        } else {
            // Lida com o erro de consulta
            $erro = $this->db->error();
            log_message('error', 'Erro na consulta SQL: ' . $erro['message']);
        }
    
        $this->db->close();
    } 
        $data = array_unique($data, SORT_REGULAR);        
        return $data;
    }

    /* TABELA REFERECNIAS */
    public function tabela_refs01($tbl01,$cond){
        $sql01 = "INSERT into ". $tbl01 ." (Referencia) ".
                 "SELECT Referencia from ReferArt 
                  where isnull(Referencia,'')<>'' ". $cond ." group by Referencia";
        $this->db->query($sql01);
        $this->db->close();

        $sql02 = "UPDATE ".$tbl01 ." set Referencia=replace(Referencia,' ','')";
        $this->db->query($sql02);
        $this->db->close();
    }
        
    /* TABELA PRINCIPAL */
    public function tabela_principal01($tbl01,$tbl02,$user){
        $sql03 = "INSERT into ". $tbl02 . " (Seleccionado, Referencia, Descricao, Formato, Qualidade, Acabamento, Decoracao, Coleccao, ColeccaoCer, QtdStkArm, QtdStkProd, 
                                            QtdStkAfetado, QtdStkEncSemAfe, QtdStkPaletizado, QtdStkReservado, StkDisponivel, Ordem, CampoF, FSize, FName, Nivel, NumeroLinha, 
                                            LinhaDocumento, Documento, Cor, RefCeragni, RefCerteca, StkEmVer, QtdStkPreEN)".
                 "SELECT 0, A.Referencia, A.Descricao, isnull(A.Formato,''), A.Qual, isnull(A.Acabamento,''), isnull(A.Decoracao,''), isnull(A.Coleccao,''), A.ColeccaoCer, 0, 0, 0, 0, 0, 0, 0, 
                         row_number() over(order by A.Referencia asc), substring(A.Referencia+A.Descricao,1,255), '10', 'Calibri', 0, 0, 0, A.Referencia+'{$user}', 0, isnull(A.RefCeragni,0), isnull(A.RefCerteca,0), 0, 0
                  FROM ". $tbl01 ." X join ReferArt A on (X.Referencia=replace(A.Referencia,' ',''))
                  where isnull(A.Inactivo,0)=0 and isnull(A.Referencia,'') not in ('','00')";
        $this->db->query($sql03);
        $this->db->close();

      

        $sql04 = "UPDATE A set A.Acabamento=isnull(substring(B.Descricao,1,100),'N/Preenchido') from ". $tbl02 ." A left join Acabam B on (A.Acabamento=B.Codigo);
                  UPDATE A set A.Decoracao=isnull(substring(B.Descricao,1,100),'N/Preenchido') from ". $tbl02 ." A left join Decoracao B on (A.Decoracao=B.Codigo);
                  UPDATE A set A.Coleccao=isnull(substring(B.Descricao,1,100),'N/Preenchido') from ". $tbl02 ." A left join Coleccao B on (A.Coleccao=B.Codigo);
                  UPDATE A set A.Formato=isnull(substring(B.Descricao,1,100),'N/Preenchido') from ". $tbl02 ." A left join Formato B on (A.Formato=B.Codigo);
                  UPDATE ". $tbl02 ." set NumeroLinha=Id, LinhaDocumento=Id;
                  ";
        $this->db->query($sql04);
        $this->db->close();
    }

    /* TABELA DATAHORA  */
    public function tabela_datahora01($tbl03){

        $sql05 = "INSERT into ". $tbl03 . " (DataFinal)".
                 "VALUES (getdate())";
        $this->db->query($sql05);
        $this->db->close();
    }
    
    public function tabela_datahora02($tbl03){
        $sql06 = "SELECT TOP 1 DataFinal
                  FROM ". $tbl03;         
        //echo $sql;
        $query = $this->db->query($sql06);        
        $result = $query->result();
        
        return $result;
    }

    /* TABELA SETORES  */
    public function tabela_stprocura01($tbl04,$st_arm,$st_prod,$st_exp){

        $sql05 = "INSERT into ". $tbl04 . " (Codigo, Descricao)".
                 "SELECT Codigo, Descricao
                  FROM Sectores
                  where Codigo in ({$st_arm}) or Codigo in ({$st_prod}) or Codigo in ({$st_exp}) ";
                  
        $this->db->query($sql05);
        $this->db->close();
    }

    /* TABELA RECOLHE STOCK  */
    public function tabela_stksecta01($tbl01,$tbl04,$tbl05,$datahora,$cond02){

        $sql06 = "INSERT into ". $tbl05 . " (DataDe, Artigo, Descricao, Sector, NomeSector, Quantidade, Formato, RefCor, Qual, TipoEmbalagem, Superficie, TabEspessura, RefP, 
                                             Lote, Calibre, Decoracao, Acabamento, Coleccao, Custo, Chave, Familia, NomeFamilia, Local, Categoria, NomeCategoria, Unidade, 
                                             Palete, SectorDestino, LoteInt, Fornecedor, Material, CorMat, NumTina, LoteVR, KSTina)".
                 "SELECT '{$datahora}', B.Artigo, '', isnull(B.Sector,''), '', sum(case when B.TipoMovimento in ('00','01','02','03','04','05','06','07','08','09') then B.QuantidadeUnStock else B.QuantidadeUnStock*-1 end),
                         '', '', '', '', '', '', isnull(B.RefP,'') RefP, isnull(B.Lote,''), isnull(B.Calibre,''), '', '', '',  0, '', '', '', isnull(B.Local,''), '', '', '', 
                         isnull(B.Palete,''), isnull(B.Artigo,''), min(B.LoteInt), '' Fornecedor, '' Material, '' CorMat, '' NumTina, '' LoteVR, '' KSTina 
                  FROM ". $tbl01 . " R join StkLDocs     B on (B.RefP=R.Referencia)
                                       join ". $tbl04 ." C on (C.Codigo=B.Sector)
                                       join Artigos      X on (B.Artigo=X.Codigo)
                  where convert(char(8),B.DataHoraMOV,112)<=convert(char(8),'{$datahora}',112)
                  group by B.Artigo, isnull(B.RefP,''), isnull(B.Lote,''), isnull(B.Calibre,''), isnull(B.Sector,''), isnull(B.Local,''), isnull(B.Palete,''), isnull(B.Artigo,'')
                  having sum(case when B.TipoMovimento in ('00','01','02','03','04','05','06','07','08','09') then B.QuantidadeUnStock else B.QuantidadeUnStock*-1 end){$cond02}";
       
        $this->db->query($sql06);
        $this->db->close();

        //echo $sql06;

        $sql07 = "INSERT into ". $tbl05 . " (DataDe, Artigo, Descricao, Sector, NomeSector, Quantidade, Formato, RefCor, Qual, TipoEmbalagem, Superficie, TabEspessura, RefP, 
                                             Lote, Calibre, Decoracao, Acabamento, Coleccao, Custo, Chave, Familia, NomeFamilia, Local, Categoria, NomeCategoria, Unidade, 
                                             Palete, SectorDestino, LoteInt, Fornecedor, Material, CorMat, NumTina, LoteVR, KSTina)".
                 "SELECT '{$datahora}', B.Artigo, '', isnull(B.Sector,''), '', sum(case when B.TipoMovimento in ('00','01','02','03','04','05','06','07','08','09') then B.QuantidadeUnStock else B.QuantidadeUnStock*-1 end),
                         '', '', '', '', '', '', isnull(B.RefP,'') RefP, isnull(B.Lote,''), isnull(B.Calibre,''), '', '', '',  0, '', '', '', isnull(B.Local,''), '', '', '', 
                         isnull(B.Palete,''), isnull(B.Artigo,''), min(B.LoteInt), '' Fornecedor, '' Material, '' CorMat, '' NumTina, '' LoteVR, '' KSTina 
                  FROM ". $tbl01 . " R join StkLDocs     B on (B.RefP=R.Referencia)
                                       join ". $tbl04 ." C on (C.Codigo=B.Sector)
                                       join Artigos      X on (B.Artigo=X.Codigo)
                  where convert(char(8),B.DataHoraMOV,112)<=convert(char(8),'{$datahora}',112) and B.Sector='ST007'
                  group by B.Artigo, isnull(B.RefP,''), isnull(B.Lote,''), isnull(B.Calibre,''), isnull(B.Sector,''), isnull(B.Local,''), isnull(B.Palete,''), isnull(B.Artigo,'')
                  having sum(case when B.TipoMovimento in ('00','01','02','03','04','05','06','07','08','09') then B.QuantidadeUnStock else B.QuantidadeUnStock*-1 end){$cond02}";

        $this->db->query($sql07);
        $this->db->close();

        $sql08 = "INSERT into ". $tbl05 . " (DataDe, Artigo, Descricao, Sector, NomeSector, Quantidade, Formato, RefCor, Qual, TipoEmbalagem, Superficie, TabEspessura, RefP, 
                                             Lote, Calibre, Decoracao, Acabamento, Coleccao, Custo, Chave, Familia, NomeFamilia, Local, Categoria, NomeCategoria, Unidade, 
                                             Palete, SectorDestino, LoteInt, Fornecedor, Material, CorMat, NumTina, LoteVR, KSTina)".
                 "SELECT '{$datahora}', B.Artigo, '', isnull(B.Sector,''), '', sum(case when B.TipoMovimento in ('00','01','02','03','04','05','06','07','08','09') then B.QuantidadeUnStock else B.QuantidadeUnStock*-1 end),
                         '', '', '', '', '', '', isnull(B.RefP,'') RefP, isnull(B.Lote,''), isnull(B.Calibre,''), '', '', '',  0, '', '', '', isnull(B.Local,''), '', '', '', 
                         isnull(B.Palete,''), isnull(B.Artigo,''), min(B.LoteInt), '' Fornecedor, '' Material, '' CorMat, '' NumTina, '' LoteVR, '' KSTina 
                  FROM ". $tbl01 . " R join StkLDocs     B on (B.RefP=R.Referencia)
                                       join ". $tbl04 ." C on (C.Codigo=B.Sector)
                                       join Artigos      X on (B.Artigo=X.Codigo)
                  where convert(char(8),B.DataHoraMOV,112)<=convert(char(8),'{$datahora}',112) and B.Sector like 'ST2%'
                  group by B.Artigo, isnull(B.RefP,''), isnull(B.Lote,''), isnull(B.Calibre,''), isnull(B.Sector,''), isnull(B.Local,''), isnull(B.Palete,''), isnull(B.Artigo,''),
                           isnull(B.Fornecedor,''), isnull(B.Material,''), isnull(B.CorMat,''), isnull(B.NumTina,''), isnull(B.LoteVR,''), isnull(B.KSTina,'')    
                  having sum(case when B.TipoMovimento in ('00','01','02','03','04','05','06','07','08','09') then B.QuantidadeUnStock else B.QuantidadeUnStock*-1 end){$cond02}";

        $this->db->query($sql08);
        $this->db->close();        
    }

    public function tabela_stksecta02($tbl05){

        $sql09 = "UPDATE A set A.Descricao=B.Descricao, A.Unidade=B.Unidade from ". $tbl05 ." A join Artigos B on (A.Artigo=B.Codigo);
                  UPDATE A set A.DescMaterial=B.Descricao from ". $tbl05 ." A join Artigos B on (A.Material=B.Codigo);
                  UPDATE A set A.NomeFornecedor=B.Nome from ". $tbl05 ." A join Forneced B on (A.Fornecedor=B.Codigo);
                  UPDATE ". $tbl05 ." set Quantidade=round(Quantidade,4);
                  UPDATE ". $tbl05 ." set SectorDestino='' where SectorDestino=Artigo;
                  UPDATE ". $tbl05 ." set Palete='' where Palete=Artigo;
                  ";
        $this->db->query($sql09);
        $this->db->close();

        $sql10 = "UPDATE A
                  set A.Descricao=B.Descricao, A.Qual=B.Qual, A.Superficie=B.Superficie, A.Formato=B.Formato, A.TabEspessura=B.TabEspessura, 
                      A.Unidade=case when isnull(B.Unidade,'')='' then A.Unidade else B.Unidade end, A.ColeccaoCer=B.ColeccaoCer
                  from ". $tbl05 ." A join ReferArt B on (A.RefP=replace(B.Referencia,' ',''))
                  where isnull(A.RefP,'')<>'' ";
        $this->db->query($sql10);
        $this->db->close();

        $sql11 = "UPDATE A set A.NomeSector=B.Descricao from ". $tbl05 ." A join Sectores B on (A.Sector=B.Codigo)";
        $this->db->query($sql11);
        $this->db->close();

        $sql11 = "UPDATE ". $tbl05 ."
                  set Chave=Artigo+RefP+Lote+Calibre+Sector+Local+Palete+Fornecedor+Material+CorMat+NumTina+LoteVR+KSTina, Chave2=Artigo+RefP+Lote+Calibre+Sector, 
                      Chave3=Artigo+RefP+Sector, Chave4=Artigo+RefP, Chave5=Artigo+Lote+Calibre+Sector+Local, Chave6=Artigo+RefP+Lote+Calibre, Chave7=Artigo+RefP";
        $this->db->query($sql11);
        $this->db->close();

        $sql12 = "UPDATE A set A.Ordem2=case when isnull(B.Ordem,0)=0 then 99 else B.Ordem end from ". $tbl05 ." A join Formato B on (A.Formato=B.Codigo);
                  UPDATE ". $tbl05 ." set Ordem2=99 where Ordem2 is null";
        $this->db->query($sql12);
        $this->db->close();

        $sql13 = "UPDATE A
                 set A.Ordem2=A.Ordem2+case when B.Codigo is null then 0.99 else case when isnull(B.Ordem,0)=0 then 0.99 else (cast(B.Ordem as float)/100) end end
                 from ". $tbl05 ." A left join Superficie B on (A.Superficie=B.Codigo)";
        $this->db->query($sql13);
        $this->db->close();
        
        $sql14 = "UPDATE A set A.Data=B.Data from ". $tbl05 ." A join PlDocs B on (A.Palete=B.Numero)";
        $this->db->query($sql14);
        $this->db->close();
    }

    public function tabela_stksecta03($tbl05,$tbl06){
        
        $sql15 = "INSERT into ". $tbl06 . " (DataDe, Artigo, Descricao, Sector, NomeSector, Quantidade, Formato, RefCor, Qual, TipoEmbalagem, Superficie, TabEspessura, RefP, Lote, 
                                             Calibre, Decoracao, Acabamento, Coleccao, Custo, Chave, Familia, NomeFamilia, Local, Categoria, NomeCategoria, Unidade, Palete, 
                                             SectorDestino, Ordem, Chave2, Chave3, Chave4, Chave5, Chave6, Chave7, Data, LoteInt, ColeccaoCer, Fornecedor, NomeFornecedor, Material, 
                                             DescMaterial, CorMat, NumTina, LoteVR, KSTina)".
                 "SELECT  DataDe, Artigo, Descricao, Sector, NomeSector, Quantidade, Formato, RefCor, Qual, TipoEmbalagem, Superficie, TabEspessura, RefP, Lote, Calibre,
                          Decoracao, Acabamento, Coleccao, Custo, Chave, Familia, NomeFamilia, Local, Categoria, NomeCategoria, Unidade, Palete, SectorDestino,
                          row_number() over(order by Ordem2, Qual, Artigo, upper(RefP), Descricao, Quantidade, Unidade), Chave2, Chave3, Chave4, Chave5, Chave6, Chave7, Data, 
                          LoteInt, ColeccaoCer, Fornecedor, NomeFornecedor, Material, DescMaterial, CorMat, NumTina, LoteVR, KSTina
                  FROM ". $tbl05 . "
                  order by Ordem2, Qual, Artigo, RefP, Descricao, Quantidade, Unidade";

        $this->db->query($sql15);
        $this->db->close();
    }

    public function tabela_stksecta04($tbl06,$tbl07){
        $sql16 = "INSERT into ". $tbl07 . " (Palete, Id)".
                 "SELECT  Palete, min(Id) Id
                  FROM ". $tbl06 ."
                  where isnull(Palete,'')<>'' 
                  group by Palete";       
        $this->db->query($sql16);
        $this->db->close();
        
        $sql17 = "UPDATE A set A.NumPLs=1 from ". $tbl07 . " B join ". $tbl06 . " A on (B.Palete=A.Palete and B.Id=A.Id) ";
        $this->db->query($sql17);
        $this->db->close();
        
        $sql18 = "DELETE from ". $tbl06 . " where Quantidade=0";
        $this->db->query($sql18);
        $this->db->close();
    }

    /* TABELAS ENCOMENDAS EM CURSO */
    public function tabela_infoafetacao01($tbl01,$tbl08,$serie){

        $sql20 = "INSERT into ". $tbl08 . " (x,Numero,NumeroLinha,Terceiro,Nome,Data,VossaRef,DataEntrega,Referencia,Artigo,DescricaoArtigo,Quantidade,QuantidadeAfetada,Unidade,Formato,RefCor,Qual,TipoEmbalagem,
                                             Superficie,TabEspessura,Lote,Calibre,QtdPaletizada,QtdFalta,Decoracao,Acabamento,Coleccao,Ordem,Tratado,LinhaMae)".
                 "SELECT cast('.' as varchar(1)) x, A.Numero, cast(B.NumeroLinha as int) NumeroLinha, A.Terceiro, D.Nome, A.Data, A.VossaRef, B.DataEntrega, B.Referencia, B.Artigo, B.DescricaoArtigo, B.Quantidade, 
                         C.Quantidade QuantidadeAfetada, B.Unidade, B.Formato, B.RefCor, B.Qual, B.TipoEmbalagem, B.Superficie, B.TabEspessura, C.Lote, C.Calibre, round(B.QtdPaletizada,4) QtdPaletizada, 
                         round(B.Quantidade,4)-round(B.QtdPaletizada,4) QtdFalta, B.Decoracao, B.Acabamento, B.Coleccao, cast(row_number() over(order by A.Numero, B.NumeroLinha, C.NumSeq) as int) Ordem,
                         C.Tratado, B.LinhaMae
                  from VdEncs A join VdLEncs       B on (A.Estado not in ('A','E') and A.Numero=B.NumeroDocumento and isnull(B.Concluido,0)=0)
                                join zx_StkAfetado C on (B.NumeroLinha=C.LinhaDocumento and B.NumeroDocumento=C.Documento and isnull(C.Tratado,0)=0)
                                join Clientes      D on (A.Terceiro=D.Codigo)
                                join ". $tbl01 ." X on (replace(B.Referencia,' ','')=X.Referencia)
                  where A.Serie in ({$serie})"; 

        $this->db->query($sql20);
        $this->db->close();
    }

    
    /* TABELAS ENCOMENDAS EM CURSO */
    public function tabela_ensX01($tbl01,$tbl08,$tbl09,$serie){

        $sql21 = "INSERT into ". $tbl09 . " (Numero,NumeroLinha,Terceiro,Nome,Data,DataEntrega,VossaRef,Quantidade,QtdAfetada,Unidade,DocumentoCarga,QtdPaletizada,QtdFalta,Ordem,Referencia)".
                 "SELECT C.Numero, cast(B.NumeroLinha as int) NumeroLinha, C.Terceiro, D.Nome, C.Data, B.DataEntrega, C.VossaRef, round(B.Quantidade,4) Quantidade, cast(0 as float) QtdAfetada, B.Unidade, 
                         B.DocumentoCarga, round(B.QtdPaletizada,4) QtdPaletizada, round(B.Quantidade,4)-round(B.QtdPaletizada,4) QtdFalta, row_number() over(order by B.DataEntrega) Ordem, B.Referencia
                  from VdEncs C join VdLEncs  B on (C.Numero=B.NumeroDocumento)
                                join Clientes D on (C.Terceiro=D.Codigo)              
                                join ". $tbl01 ." X on (replace(B.Referencia,' ','')=X.Referencia)
                  where isnull(B.Concluido,0)=0 and C.Estado not in ('A','E') and C.Serie in ({$serie})";       
        $this->db->query($sql21);
        $this->db->close(); 

        $sql22 = "UPDATE A set A.QtdAfetada=isnull((select sum(B.QuantidadeAfetada) from ". $tbl08 ." B where A.NumeroLinha=B.NumeroLinha),0) from ". $tbl09 ." A";       
        $this->db->query($sql22);
        $this->db->close();
                
    }


    /* TABELAS PRÉ-ENNCOMENDAS */ 
    public function tabela_wex01($tbl10,$serie){
        $sql23 = "INSERT into ". $tbl10 . " (Documento,Data,Cliente,VossaRef,Quantidade,Unidade,Artigo,Referencia,DescricaoArtigo,Descricao,PrecoNM,Desconto,QtdEN,QtdFaltaImportar,LinhaDocumento,Estado,Cor_Estado,Ordem)".
                 "SELECT A.Numero Documento, A.Data, C.Nome Cliente, A.VossaRef, B.Quantidade, B.Unidade, B.Artigo, B.Referencia, B.DescricaoArtigo, B.Descricao, B.PrecoNM, B.Desconto, cast(0 as float) QtdEN, 
                         cast(0 as float) QtdFaltaImportar, cast(B.NumeroLinha as int) LinhaDocumento, D.Descricao Estado, D.Cor Cor_Estado, row_number() over(order by A.Data, A.Numero, B.NumeroLinha) Ordem
                  from VdGuias A join VdLGuias B on (A.Numero=B.NumeroDocumento)
                                 join Clientes C on (A.Terceiro=C.Codigo)
                            left join Estado   D on (A.Codigo=D.TipoDoc and A.Estado=D.Codigo)
                  where A.Codigo='WE' and A.Estado<>'A' and A.Serie in ({$serie})";       
        $this->db->query($sql23);
        $this->db->close();

        $sql24 = "UPDATE A set A.QtdEN=isnull((select sum(B.Quantidade) from VdLEncs B join VdEncs C on (B.NumeroDocumento=C.Numero) where B.LinhaDocumento=A.LinhaDocumento and B.Documento=A.Documento and C.Codigo='EN' and C.Estado<>'A' ),0) from ". $tbl10 ." A;
                  UPDATE ". $tbl10 ." set QtdFaltaImportar=Quantidade-QtdEN;
                  DELETE from ". $tbl10 ." where QtdFaltaImportar<=0;";
        $this->db->query($sql24);
        $this->db->close();
    }


    /* TABELA MAIN  */
    public function tabela_main01($tbl02,$tbl06,$st_arm,$st_prod,$st_ver){
        $sql19 = "UPDATE A
                  set A.QtdStkArm=isnull((select sum(B.Quantidade) from ". $tbl06 . " B where replace(A.Referencia,' ','')=B.RefP and B.Sector in ({$st_arm})),0),
                      A.QtdStkProd=isnull((select sum(B.Quantidade) from ". $tbl06 . " B where replace(A.Referencia,' ','')=B.RefP and B.Sector in ({$st_prod})),0),
                      A.StkEmVer=isnull((select sum(B.Quantidade) from ". $tbl06 . " B where replace(A.Referencia,' ','')=B.RefP and B.Sector in ({$st_ver})),0),
                      A.QtdStkReservado=round(isnull((select sum(B.Quantidade) from zx_ReservaAntes B where replace(A.Referencia,' ','')=replace(B.Referencia,' ','')),0),4)
                  from ". $tbl02 . " A";
        $this->db->query($sql19);
        $this->db->close();
    }

    public function tabela_main02($tbl02,$tbl09,$tbl10){

        $sql25 = "UPDATE A
                  set A.QtdStkAfetado=isnull((select round(sum(case when B.QtdPaletizada>B.QtdAfetada then 0 else B.QtdAfetada-B.QtdPaletizada end),4) from ". $tbl09 ." B where B.Quantidade>B.QtdPaletizada and A.Referencia=B.Referencia),0),
                      A.QtdStkPaletizado=isnull((select round(sum(B.QtdPaletizada),4) from ". $tbl09 ." B where A.Referencia=B.Referencia),0),
                      A.QtdStkEncSemAfe=isnull((select round(sum(B.Quantidade-case when B.QtdPaletizada>B.QtdAfetada then B.QtdPaletizada else B.QtdAfetada end),4) from ". $tbl09 ." B where B.Quantidade>B.QtdPaletizada and B.Quantidade>B.QtdAfetada and A.Referencia=B.Referencia),0),
                      A.QtdStkPreEN=isnull((select round(sum(B.QtdFaltaImportar),4) from ". $tbl10 ." B where A.Referencia=B.Referencia),0)
                  from ". $tbl02 . " A";
        $this->db->query($sql25);
        $this->db->close();

        $sql26 = "UPDATE ". $tbl02 ." set StkDisponivel=round((QtdStkArm+QtdStkProd)-QtdStkReservado-QtdStkAfetado-QtdStkEncSemAfe-QtdStkPreEN,4);
                  DELETE from ". $tbl02 ." where round(QtdStkArm+QtdStkProd+QtdStkReservado+QtdStkAfetado+QtdStkEncSemAfe+QtdStkPreEN,4)=0;
                  UPDATE ". $tbl02 ." set Cor=case when StkDisponivel<0 then 8421631 else 0 end";
        $this->db->query($sql26);
        $this->db->close();
        
    }


    public function filtra_dados_lote($iLinha, $iDocL, $refp, $st01, $st02, $st03, $tbl01, $tbl02, $tbl03, $user){  
        
        $tbl04 = $user.'.MS_StkLoX1';
        $this->filtra_agrupa_tudo_lote($iLinha, $iDocL, $refp, $st01, $st02, $st03, $tbl01, $tbl02, $tbl03, $tbl04, $user);

        $sql001 = "SELECT count(A.id) nLinhas
                   FROM ( SELECT LinhaDocumento,Documento,Referencia,Lote,Calibre,QtdStkArm,QtdStkProd,QtdStkAfetado,QtdStkPaletizado,QtdStkReservado,StkDisponivel,
                                 Inicio,Fim,Observacoes,Cor,Id
                          FROM ". $tbl04 ." 
                        ) A";
        
        $query01 = $this->db->query($sql001);        
        $tot  = $query01->result();
        foreach ($tot as $val) {
            $itemcount = $val->nLinhas;
        }

        $data = array();
        $arr = [];
        set_time_limit(0);
        $this->db->db_pconnect();
        $batches = $itemcount / 3500; // Number of while-loop calls - around 120.
        
        for ($i = 0; $i <= $batches; $i++) {
            $offset = $i * 3500; // MySQL Limit offset number
            $fetch = $offset + 3500;
            set_time_limit(0);
            $this->db->db_pconnect();
            $sql002 = "SELECT LinhaDocumento,Documento,Referencia,Lote,Calibre,QtdStkArm,QtdStkProd,QtdStkAfetado,QtdStkPaletizado,QtdStkReservado,StkDisponivel,
                              Inicio,Fim,Observacoes,Cor,Id
                       FROM ". $tbl04." 
                       ORDER BY Id
                       OFFSET ".$offset." ROWS
                       FETCH NEXT ".$fetch ." ROWS ONLY";  
            //and isnull(A.Local,'')<>'' 
            //echo $sql002;
            set_time_limit(0);
            $this->db->db_pconnect();                                          
            $query02 = $this->db->query($sql002);
        
                // Verifique se a consulta foi bem-sucedida
            if ($query02) {
                $result = $query02->result();                        
                // Processa os resultados conforme necessário
                foreach ($result as $row) {

                    $arr['Lote'] = $row->Lote;
                    $arr['Calibre'] = $row->Calibre;
                    $arr['QtdStkArm'] = $row->QtdStkArm;
                    $arr['QtdStkProd'] = $row->QtdStkProd;
                    $arr['QtdStkAfetado'] = $row->QtdStkAfetado;
                    $arr['QtdStkReservado'] = $row->QtdStkReservado;
                    $arr['QtdStkPaletizado'] = $row->QtdStkPaletizado;
                    $arr['StkDisponivel'] = $row->StkDisponivel;                    
                    $arr['Inicio'] = $row->Inicio;
                    $arr['Fim'] = $row->Fim;

                    $data[]=$arr;
                    unset($arr);
                }
        } else {
            // Lida com o erro de consulta
            $erro = $this->db->error();
            log_message('error', 'Erro na consulta SQL: ' . $erro['message']);
        }
    
        $this->db->close();
    } 
        $data = array_unique($data, SORT_REGULAR);        
        return $data;
    }

    public function filtra_agrupa_tudo_lote($iLinha, $iDocL, $refp, $st01, $st02, $st03, $tbl01, $tbl02, $tbl03, $tbl04, $user){
        $this->load->dbforge();

        $this->createTBL_stklox1($tbl04);
        $this->tabela_stklox01($iLinha, $iDocL, $refp, $tbl01, $tbl02, $tbl03, $tbl04, $st01, $st02, $st03);

        $tbl05 = $user.'.MS_MinD';
        $this->createTBL_mind($tbl05);
        $this->tabela_mind01($tbl04, $tbl05);
        $this->tabela_stkl001($tbl04, $tbl05);
    }


    public function filtra_dados_palete($iLinha, $iDocL, $refp, $st01, $st02, $st03, $tbl01, $tbl02, $tbl03, $user){  
        
        $tbl06 = $user.'.MS_StkPlX1';
        $this->filtra_agrupa_tudo_palete($iLinha, $iDocL, $refp, $st01, $st02, $st03, $tbl01, $tbl02, $tbl03, $tbl06, $user);

        $sql001 = "SELECT count(A.id) nLinhas
                   FROM ( SELECT LinhaDocumento,Documento,Referencia,Palete,Lote,Calibre,Quantidade,Sector,Local,Inicio,Fim,Id
                          FROM ". $tbl06 ." 
                        ) A";
        
        $query01 = $this->db->query($sql001);        
        $tot  = $query01->result();
        foreach ($tot as $val) {
            $itemcount = $val->nLinhas;
        }

        $data = array();
        $arr = [];
        set_time_limit(0);
        $this->db->db_pconnect();
        $batches = $itemcount / 3500; // Number of while-loop calls - around 120.
        
        for ($i = 0; $i <= $batches; $i++) {
            $offset = $i * 3500; // MySQL Limit offset number
            $fetch = $offset + 3500;
            set_time_limit(0);
            $this->db->db_pconnect();
            $sql002 = "SELECT LinhaDocumento,Documento,Referencia,Palete,Lote,Calibre,Quantidade,Sector,Local,Inicio,Fim,Id
                       FROM ". $tbl06." 
                       ORDER BY Id
                       OFFSET ".$offset." ROWS
                       FETCH NEXT ".$fetch ." ROWS ONLY";  
            //and isnull(A.Local,'')<>'' 
            //echo $sql002;
            set_time_limit(0);
            $this->db->db_pconnect();                                          
            $query02 = $this->db->query($sql002);
        
                // Verifique se a consulta foi bem-sucedida
            if ($query02) {
                $result = $query02->result();                        
                // Processa os resultados conforme necessário
                foreach ($result as $row) {

                    $arr['Palete'] = $row->Palete;
                    $arr['Sector'] = $row->Sector;
                    $arr['Local'] = $row->Local;
                    $arr['Lote'] = $row->Lote;
                    $arr['Calibre'] = $row->Calibre;
                    $arr['Quantidade'] = $row->Quantidade;
                    $arr['Inicio'] = $row->Inicio;
                    $arr['Fim'] = $row->Fim;

                    $data[]=$arr;
                    unset($arr);
                }
        } else {
            // Lida com o erro de consulta
            $erro = $this->db->error();
            log_message('error', 'Erro na consulta SQL: ' . $erro['message']);
        }
    
        $this->db->close();
    } 
        $data = array_unique($data, SORT_REGULAR);        
        return $data;
    }

    public function filtra_agrupa_tudo_palete($iLinha, $iDocL, $refp, $st01, $st02, $st03, $tbl01, $tbl02, $tbl03, $tbl06, $user){
        $this->load->dbforge();

        $this->createTBL_stkPlx1($tbl06);
        $this->tabela_stkPlx01($iLinha, $iDocL, $refp, $tbl01, $tbl02, $tbl03, $tbl06, $st01, $st02, $st03);

        $tbl07 = $user.'.MS_MinD';
        $this->createTBL_mind($tbl07);
        $this->tabela_mind01($tbl06, $tbl07);
        $this->tabela_stkl001($tbl06, $tbl07);
    }


    public function tabela_stklox01($iLinha, $iDocL, $refp, $tbl01, $tbl02, $tbl03, $tbl04, $st01, $st02, $st03){
        $sql01 = "INSERT INTO ". $tbl04 ." (LinhaDocumento,Documento,Referencia,Lote,Calibre,QtdStkArm,QtdStkProd,QtdStkAfetado,QtdStkPaletizado,QtdStkReservado,StkDisponivel,Inicio,Fim,Observacoes,Cor)".
                 "SELECT A.LinhaDocumento, A.Documento, A.Referencia, B.Lote, B.Calibre, cast(0 as float) QtdStkArm, cast(0 as float) QtdStkProd, cast(0 as float) QtdStkAfetado, cast(0 as float) QtdStkPaletizado,
                         cast(0 as float) QtdStkReservado, cast(0 as float) StkDisponivel, cast(null as datetime) Inicio, cast(null as datetime) Fim, cast('' as varchar(max)) Observacoes, cast(0 as int) Cor
                  FROM ". $tbl01 ." A join ". $tbl02 ." B on (replace(A.Referencia,' ','')=B.RefP)
                  where A.NumeroLinha={$iLinha} and A.LinhaDocumento={$iDocL} and A.Referencia='{$refp}' and B.Sector not in ({$st03})
                  group by A.LinhaDocumento, A.Documento, A.Referencia, B.Lote, B.Calibre";

        $this->db->query($sql01);
        $this->db->close();

        $sql02 = "UPDATE A
                  set A.QtdStkArm=isnull((select sum(B.Quantidade) from ". $tbl02 ." B where replace(A.Referencia,' ','')=B.RefP and A.Lote=B.Lote and isnull(A.Calibre,'')=isnull(B.Calibre,'') and B.Sector in ({$st01})),0),
                      A.QtdStkProd=isnull((select sum(B.Quantidade) from ". $tbl02 ." B where replace(A.Referencia,' ','')=B.RefP and A.Lote=B.Lote and isnull(A.Calibre,'')=isnull(B.Calibre,'') and B.Sector in ({$st02})),0),
                      A.QtdStkAfetado=isnull((select sum(B.QuantidadeAfetada) from ". $tbl03 ." B where replace(A.Referencia,' ','')=replace(B.Referencia,' ','') and A.Lote=B.Lote and isnull(A.Calibre,'')=isnull(B.Calibre,'')),0),
                      A.QtdStkPaletizado=isnull((select sum(B.Quantidade) from ". $tbl02 ." B where replace(A.Referencia,' ','')=B.RefP and A.Lote=B.Lote and isnull(A.Calibre,'')=isnull(B.Calibre,'') and B.Sector in ({$st03})),0),
                      A.QtdStkReservado=round(isnull((select sum(B.Quantidade) from zx_ReservaAntes B where replace(A.Referencia,' ','')=replace(B.Referencia,' ','') and A.Lote=B.Lote and isnull(A.Calibre,'')=isnull(B.Calibre,'')),0),4)
                  from ". $tbl04 . " A";
        $this->db->query($sql02);
        $this->db->close();

        $sql03 = "UPDATE ". $tbl04 ." set StkDisponivel=round((QtdStkArm+QtdStkProd)-QtdStkAfetado-QtdStkReservado,4)";
        $this->db->query($sql03);
        $this->db->close();

        $sql04 = "UPDATE A
                  set A.Observacoes=B.Observacao, A.Cor=case when isnull(B.Observacao,'')='' then 0 else 255 end
                  from ". $tbl04 ." A join zx_Obs_Lotes B on (A.Referencia=B.Artigo and A.Lote=B.Lote and isnull(A.Calibre,'')=isnull(B.Calibre,''))";
        $this->db->query($sql04);
        $this->db->close();
    }

    public function tabela_mind01($tbl04, $tbl05){

        $sql05 = "TRUNCATE TABLE ". $tbl05;        
        $this->db->query($sql05);
        $this->db->close();

        $sql06 = "INSERT INTO ". $tbl05 ." (Id, Inicio, Fim)".
                 "SELECT A.Id, min(B.DataHoraMov) Inicio, max(B.DataHoraMov) Fim
                  from ". $tbl04 ." A join StkLDocs B on (replace(A.Referencia,' ','')=B.RefP and A.Lote=B.Lote and isnull(A.Calibre,'')=isnull(B.Calibre,''))
                  where B.TipoMovimento in ('01','02','03','04','05','06','07','08','09')
                  group by A.Id";
        
        $this->db->query($sql06);
        $this->db->close();
    }

    public function tabela_stkl001($tbl04, $tbl05){
        $sql07 = "UPDATE A
                  set A.Inicio=B.Inicio, A.Fim=B.Fim
                  from ". $tbl04 ." A join ". $tbl05 ." B on (A.Id=B.Id)";
        $this->db->query($sql07);
        $this->db->close();
    }

    public function tabela_stkPlx01($iLinha, $iDocL, $refp, $tbl01, $tbl02, $tbl03, $tbl06, $st01, $st02, $st03){
        $sql08 = "INSERT INTO ". $tbl06 ." (LinhaDocumento,Documento,Referencia,Palete,Lote,Calibre,Quantidade,Sector,Local,Inicio,Fim)".
                 "SELECT A.LinhaDocumento, A.Documento, A.Referencia, B.Palete, isnull(B.Lote,'') Lote, isnull(B.Calibre,'') Calibre, sum(B.Quantidade) Quantidade, B.Sector, B.Local, cast(null as datetime) Inicio, cast(null as datetime) Fim
                  FROM ". $tbl01 ." A join ". $tbl02 ." B on (replace(A.Referencia,' ','')=B.RefP)
                  where A.NumeroLinha={$iLinha} and A.LinhaDocumento={$iDocL} and A.Referencia='{$refp}' and B.Sector not in ({$st03})
                  group by A.LinhaDocumento, A.Documento, A.Referencia, B.Palete, isnull(B.Lote,''), isnull(B.Calibre,''), B.Sector, B.Local";

        $this->db->query($sql08);
        $this->db->close();
    }
    

    public function filtra_dados_enc($iLinha, $iDocL, $refp, $tbl01, $tbl09, $user){

        $this->load->dbforge();

        //TABELA REFERECNIAS
        $tbl11 = $user.'.MS_Anx01X1';
        $this->createTBL_anx($tbl11);
        $this->tabela_anx01($iLinha,$iDocL,$refp,$tbl01,$tbl09,$tbl11,$user);    
        
        $sql001 = "SELECT count(A.ordem) nLinhas
                   FROM ( SELECT LinhaDocumento, Documento, Numero, NumeroLinha, Terceiro, Nome, Data, DataEntrega, VossaRef, Quantidade, QtdAfetada, Unidade, DocumentoCarga, QtdPaletizada, 
                                 QtdFalta, Ordem, Referencia, Operador, Estado, DescricaoEstado
                          from ". $tbl11 .") A";
        
        $query01 = $this->db->query($sql001);        
        $tot  = $query01->result();
        foreach ($tot as $val) {
            $itemcount = $val->nLinhas;
        }

        $data = array();
        $arr = [];
        set_time_limit(0);
        $this->db->db_pconnect();
        $batches = $itemcount / 3500; // Number of while-loop calls - around 120.
        
        for ($i = 0; $i <= $batches; $i++) {
            $offset = $i * 3500; // MySQL Limit offset number
            $fetch = $offset + 3500;
            set_time_limit(0);
            $this->db->db_pconnect();
            $sql002 = "SELECT LinhaDocumento, Documento, Numero, NumeroLinha, Terceiro, Nome, Data, DataEntrega, VossaRef, Quantidade, QtdAfetada, Unidade, DocumentoCarga, QtdPaletizada, 
                              QtdFalta, Ordem, Referencia, Operador, Estado, DescricaoEstado
                       FROM ". $tbl11;  
            //and isnull(A.Local,'')<>'' 
            set_time_limit(0);
            $this->db->db_pconnect();                                          
            $query02 = $this->db->query($sql002);
        
                // Verifique se a consulta foi bem-sucedida
            if ($query02) {
                $result = $query02->result();                        
                // Processa os resultados conforme necessário
                foreach ($result as $row) {

                    $arr['Numero'] = $row->Numero;
                    $arr['Nome'] = $row->Nome;
                    $arr['Data'] = $row->Data;
                    $arr['DocumentoCarga'] = $row->DocumentoCarga;
                    $arr['Quantidade'] = $row->Quantidade;
                    $arr['QtdAfetada'] = $row->QtdAfetada;
                    $arr['QtdPaletizada'] = $row->QtdPaletizada;
                    $arr['QtdFalta'] = $row->QtdFalta;
                    $arr['Unidade'] = $row->Unidade;
                    $arr['Quantidade'] = $row->Quantidade;
                    $arr['DataEntrega'] = $row->DataEntrega;
                    $arr['VossaRef'] = $row->VossaRef;
                    $arr['Estado'] = $row->Estado;
                    $arr['DescricaoEstado'] = $row->DescricaoEstado;

                    $data[]=$arr;
                    unset($arr);
                }
        } else {
            // Lida com o erro de consulta
            $erro = $this->db->error();
            log_message('error', 'Erro na consulta SQL: ' . $erro['message']);
        }
    
        $this->db->close();
    } 
        $data = array_unique($data, SORT_REGULAR);        
        return $data;
        

    }       
    
    public function tabela_anx01($iLinha,$iDocL,$refp,$tbl01,$tbl09,$tbl11,$user){

        $sql001 = "INSERT INTO ". $tbl11 ."(LinhaDocumento, Documento, Numero, NumeroLinha, Terceiro, Nome, Data, DataEntrega, VossaRef, Quantidade, QtdAfetada, Unidade, DocumentoCarga, QtdPaletizada, QtdFalta, Ordem, Referencia, Operador)".
                  "SELECT A.LinhaDocumento, A.Documento, B.Numero, B.NumeroLinha, B.Terceiro, B.Nome, B.Data, B.DataEntrega, B.VossaRef, B.Quantidade, B.QtdAfetada, B.Unidade, B.DocumentoCarga, B.QtdPaletizada, B.QtdFalta, B.Ordem,
                          B.Referencia, '{$user}'
                   FROM ". $tbl01 ." A join ". $tbl09 ." B on (A.Referencia=B.Referencia)
                   where A.NumeroLinha={$iLinha} and A.LinhaDocumento={$iDocL} and A.Referencia='{$refp}'";
        $this->db->query($sql001);
        $this->db->close();

        $sql002 = "UPDATE A
                   set A.Estado=B.Estado, A.Cor=B.Cor
                   from ". $tbl11 ." A join PrGuias B on (A.DocumentoCarga=B.Numero)";
        $this->db->query($sql002);
        $this->db->close();

        $sql003 = "UPDATE A
                   set A.DescricaoEstado=B.Descricao
                   from ". $tbl11 ." A join Estado B on (B.TipoDoc='GG' and A.Estado=B.Codigo)";
        $this->db->query($sql003);
        $this->db->close();
    }

    public function filtra_dados_preenc($iLinha, $iDocL, $refp, $tbl01, $tbl10){

        $sql001 = "SELECT count(A.ordem) nLinhas
                   FROM ( SELECT A.LinhaDocumento, A.Documento, B.Documento Numero, B.Data, B.Cliente, B.VossaRef, B.Quantidade, B.Unidade, B.Artigo, B.Referencia, B.DescricaoArtigo, B.Descricao, B.PrecoNM, B.Desconto, B.QtdEN, B.QtdFaltaImportar,
                                 B.LinhaDocumento NumeroLinha, B.Estado, B.Cor_Estado, row_number() over(order by B.Ordem) Ordem
                          from ". $tbl01 ." A join ". $tbl10 ." B on (A.Referencia=B.Referencia)
                          where A.NumeroLinha={$iLinha} and A.LinhaDocumento={$iDocL} and A.Referencia='{$refp}'
                        ) A";
        
        $query01 = $this->db->query($sql001);        
        $tot  = $query01->result();
        foreach ($tot as $val) {
            $itemcount = $val->nLinhas;
        }

        $data = array();
        $arr = [];
        set_time_limit(0);
        $this->db->db_pconnect();
        $batches = $itemcount / 3500; // Number of while-loop calls - around 120.
        
        for ($i = 0; $i <= $batches; $i++) {
            $offset = $i * 3500; // MySQL Limit offset number
            $fetch = $offset + 3500;
            set_time_limit(0);
            $this->db->db_pconnect();
            $sql002 = "SELECT A.LinhaDocumento, A.Documento, B.Documento Numero, B.Data, B.Cliente, B.VossaRef, B.Quantidade, B.Unidade, B.Artigo, B.Referencia, B.DescricaoArtigo, B.Descricao, B.PrecoNM, B.Desconto, B.QtdEN, B.QtdFaltaImportar,
                              B.LinhaDocumento NumeroLinha, B.Estado, B.Cor_Estado, row_number() over(order by B.Ordem) Ordem
                       from ". $tbl01 ." A join ". $tbl10 ." B on (A.Referencia=B.Referencia)
                       where A.NumeroLinha={$iLinha} and A.LinhaDocumento={$iDocL} and A.Referencia='{$refp}'
                       ORDER BY B.Ordem
                       OFFSET ".$offset." ROWS
                       FETCH NEXT ".$fetch ." ROWS ONLY";  
            //and isnull(A.Local,'')<>'' 
            //echo $sql002;
            set_time_limit(0);
            $this->db->db_pconnect();                                          
            $query02 = $this->db->query($sql002);
        
                // Verifique se a consulta foi bem-sucedida
            if ($query02) {
                $result = $query02->result();                        
                // Processa os resultados conforme necessário
                foreach ($result as $row) {

                    $arr['Numero'] = $row->Numero;
                    $arr['Data'] = $row->Data;
                    $arr['Cliente'] = $row->Cliente;
                    $arr['VossaRef'] = $row->VossaRef;
                    $arr['Unidade'] = $row->Unidade;
                    $arr['Quantidade'] = $row->Quantidade;
                    $arr['QtdFaltaImportar'] = $row->QtdFaltaImportar;
                    $arr['Estado'] = $row->Estado;

                    $data[]=$arr;
                    unset($arr);
                }
        } else {
            // Lida com o erro de consulta
            $erro = $this->db->error();
            log_message('error', 'Erro na consulta SQL: ' . $erro['message']);
        }
    
        $this->db->close();
    } 
        $data = array_unique($data, SORT_REGULAR);        
        return $data;
    }


    /** TABLES */
    public function createTBL_refP($tbl){
                        
        $this->dbforge->drop_table($tbl,TRUE);
        
        $fields = array(
            'Referencia' => array(
                'type' => 'VARCHAR',
                'constraint' => '50'
            )
        );
        
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table($tbl,TRUE);
    }

    public function createTBL_tabx1($tbl){
                        
        $this->dbforge->drop_table($tbl,TRUE);
        
        $fields = array(
            'Id' => array(
                'type' => 'INT',
                'auto_increment' => TRUE
            ),
            'Seleccionado' => array(
                'type' => 'INT'
            ),
            'Referencia' => array(
                'type' => 'VARCHAR',
                'constraint' => '50'
            ),
            'Descricao' => array(
                'type' => 'VARCHAR',
                'constraint' => '255'
            ),
            'Formato' => array(
                'type' => 'VARCHAR',
                'constraint' => '20'
            ),
            'Qualidade' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE
            ),
            'Acabamento' => array(
                'type' => 'VARCHAR',
                'constraint' => '20'
            ),
            'Decoracao' => array(
                'type' => 'VARCHAR',
                'constraint' => '20'
            ),
            'Coleccao' => array(
                'type' => 'VARCHAR',
                'constraint' => '20'
            ),
            'ColeccaoCer' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE
            ),
            'QtdStkArm' => array(
                'type' => 'FLOAT'
            ),
            'QtdStkProd' => array(
                'type' => 'FLOAT'
            ),
            'QtdStkAfetado' => array(
                'type' => 'FLOAT'
            ),
            'QtdStkEncSemAfe' => array(
                'type' => 'FLOAT'
            ),
            'QtdStkPreEN' => array(
                'type' => 'FLOAT'
            ),
            'QtdStkPaletizado' => array(
                'type' => 'FLOAT'
            ),
            'QtdStkReservado' => array(
                'type' => 'FLOAT'
            ),
            'StkDisponivel' => array(
                'type' => 'FLOAT'
            ),
            'StkEmVer' => array(
                'type' => 'FLOAT'
            ),
            'Ordem' => array(
                'type' => 'INT'
            ),
            'CampoF' => array(
                'type' => 'VARCHAR',
                'constraint' => '255'
            ),
            'FSize' => array(
                'type' => 'INT'
            ),
            'FName' => array(
                'type' => 'VARCHAR',
                'constraint' => '12'
            ),
            'Nivel' => array(
                'type' => 'INT'
            ),
            'NumeroLinha' => array(
                'type' => 'INT'
            ),
            'LinhaDocumento' => array(
                'type' => 'INT'
            ),
            'Documento' => array(
                'type' => 'VARCHAR',
                'constraint' => '50'
            ),
            'Cor' => array(
                'type' => 'INT'
            ),
            'RefCeragni' => array(
                'type' => 'INT'
            ),
            'RefCerteca' => array(
                'type' => 'INT'
            )
        );
                
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table($tbl,TRUE);
    }

    public function createTBL_df($tbl){
                        
        $this->dbforge->drop_table($tbl,TRUE);
        
        $fields = array(
            'DataFinal' => array(
                'type' => 'datetime'
            )
        );
        
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table($tbl,TRUE);
    }

    public function createTBL_stprocura($tbl){
                        
        $this->dbforge->drop_table($tbl,TRUE);
        
        $fields = array(
            'Codigo' => array(
                'type' => 'VARCHAR',
                'constraint' => '50'
            ),
            'Descricao' => array(
                'type' => 'VARCHAR',
                'constraint' => '255'
            ),
        );
        
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table($tbl,TRUE);
    }

    public function createTBL_stksect($tbl){
                        
        $this->dbforge->drop_table($tbl,TRUE);
        
        $fields = array(
            'Id' => array(
                'type' => 'INT',
                'auto_increment' => TRUE
            ),
            'DataDe' => array(
                'type' => 'DATETIME'
            ),
            'Artigo' => array(
                'type' => 'VARCHAR',
                'constraint' => '50'
            ),
            'Descricao' => array(
                'type' => 'VARCHAR',
                'constraint' => '255'
            ),
            'Sector' => array(
                'type' => 'VARCHAR',
                'constraint' => '50'
            ),
            'NomeSector' => array(
                'type' => 'VARCHAR',
                'constraint' => '255'
            ),
            'Quantidade' => array(
                'type' => 'FLOAT'
            ),
            'Formato' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE
            ),
            'RefCor' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE
            ),
            'Qual' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE
            ),
            'TipoEmbalagem' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE
            ),
            'Superficie' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE
            ),
            'TabEspessura' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE
            ),
            'Lote' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE
            ),
            'Calibre' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE
            ),
            'Decoracao' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE
            ),
            'Acabamento' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE
            ),
            'Coleccao' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE
                
            ),
            'Custo' => array(
                'type' => 'FLOAT'
            ),
            'Chave' => array(
                'type' => 'VARCHAR',
                'constraint' => '255'
            ),
            'Familia' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE
            ),
            'NomeFamilia' => array(
                'type' => 'VARCHAR',
                'constraint' => '255'
            ),
            'Local' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE
            ),
            'Categoria' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE
            ),
            'NomeCategoria' => array(
                'type' => 'VARCHAR',
                'constraint' => '255'
            ),
            'Unidade' => array(
                'type' => 'VARCHAR',
                'constraint' => '3'
            ),
            'Chave2' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ),
            'Chave3' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ),
            'Chave4' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ),
            'Chave5' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ),
            'Chave6' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ),
            'Chave7' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ),
            'Palete' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE
            ),
            'SectorDestino' => array(
                'type' => 'VARCHAR',
                'constraint' => '20'
            ),
            'RefP' => array(
                'type' => 'VARCHAR',
                'constraint' => '50'
            ),
            'Ordem' => array(
                'type' => 'INT',
                'null' => TRUE
            ),
            'Ordem2' => array(
                'type' => 'FLOAT',
                'null' => TRUE
            ),
            'Data' => array(
                'type' => 'DATETIME',
                'null' => TRUE
            ),
            'Cor' => array(
                'type' => 'INT',
                'null' => TRUE
            ),
            'LoteInt' => array(
                'type' => 'VARCHAR',
                'constraint' => '15',
                'null' => TRUE
            ),
            'ColeccaoCer' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE
            ),
            'Fornecedor' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE
            ),
            'NomeFornecedor' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ),
            'Material' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE
            ),
            'DescMaterial' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ),
            'CorMat' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE
            ),
            'NumTina' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE
            ),
            'LoteVR' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE
            ),
            'KSTina' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE
            ),
            'LinhaDocumento' => array(
                'type' => 'INT',
                'null' => TRUE
            ),
            'NumeroLinha' => array(
                'type' => 'INT',
                'null' => TRUE
            ),
            'Documento' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE
            ),
            'FSize' => array(
                'type' => 'INT',
                'null' => TRUE
            ),
            'FName' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE
            ),
            'Nivel' => array(
                'type' => 'INT',
                'null' => TRUE
            ),
            'CampoF' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ),
            'TotalCusto' => array(
                'type' => 'FLOAT',
                'null' => TRUE
            ),
            'NumPLs' => array(
                'type' => 'FLOAT',
                'null' => TRUE
            )
        );
        
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table($tbl,TRUE);
    }

    public function createTBL_numpls($tbl){
                        
        $this->dbforge->drop_table($tbl,TRUE);
        
        $fields = array(
            'Palete' => array(
                'type' => 'VARCHAR',
                'constraint' => '50'
            ),
            'Id' => array(
                'type' => 'INT'
            ),
        );
        
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table($tbl,TRUE);
    }

    public function createTBL_infoafetacaoX1($tbl){

        $this->dbforge->drop_table($tbl,TRUE);
        
        $fields = array(
            'x' => array('type' => 'VARCHAR', 'constraint' => '1'),
            'Numero' => array('type' => 'VARCHAR', 'constraint' => '50'),
            'NumeroLinha' => array('type' => 'INT'),
            'Terceiro' => array('type' => 'VARCHAR', 'constraint' => '50'),
            'Nome' => array('type' => 'VARCHAR', 'constraint' => '255'),
            'Data' => array('type' => 'DATETIME'),
            'VossaRef' => array('type' => 'VARCHAR', 'constraint' => '255','null' => TRUE),
            'DataEntrega' => array('type' => 'DATETIME'),
            'Referencia' => array('type' => 'VARCHAR', 'constraint' => '100'),
            'Artigo' => array('type' => 'VARCHAR', 'constraint' => '100'),
            'DescricaoArtigo' => array('type' => 'VARCHAR', 'constraint' => '255'),
            'Quantidade' => array('type' => 'FLOAT'),
            'QuantidadeAfetada' => array('type' => 'FLOAT'),
            'Unidade' => array('type' => 'VARCHAR', 'constraint' => '5'),
            'Formato' => array('type' => 'VARCHAR', 'constraint' => '50', 'null' => TRUE),
            'RefCor' => array('type' => 'VARCHAR', 'constraint' => '50', 'null' => TRUE),
            'Qual' => array('type' => 'VARCHAR', 'constraint' => '20', 'null' => TRUE),
            'TipoEmbalagem' => array('type' => 'VARCHAR', 'constraint' => '50', 'null' => TRUE),
            'Superficie' => array('type' => 'VARCHAR', 'constraint' => '50', 'null' => TRUE),
            'TabEspessura' => array('type' => 'VARCHAR', 'constraint' => '50', 'null' => TRUE),
            'Lote' => array('type' => 'VARCHAR', 'constraint' => '50', 'null' => TRUE),
            'Calibre' => array('type' => 'VARCHAR', 'constraint' => '50', 'null' => TRUE),
            'QtdPaletizada' => array('type' => 'FLOAT'),
            'QtdFalta' => array('type' => 'FLOAT'),
            'Decoracao' => array('type' => 'VARCHAR', 'constraint' => '100', 'null' => TRUE),
            'Acabamento' => array('type' => 'VARCHAR', 'constraint' => '100', 'null' => TRUE),
            'Coleccao' => array('type' => 'VARCHAR', 'constraint' => '100', 'null' => TRUE),
            'Ordem' => array('type' => 'INT'),
            'Tratado' => array('type' => 'INT', 'null' => TRUE),
            'LinhaMae' => array('type' => 'INT'),
        );
        
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table($tbl,TRUE);

    }
    
    public function createTBL_ensX1($tbl){
            
        $this->dbforge->drop_table($tbl,TRUE);
        
        $fields = array(
            'Numero' => array('type' => 'VARCHAR', 'constraint' => '50'),
            'NumeroLinha' => array('type' => 'INT'),
            'Terceiro' => array('type' => 'VARCHAR', 'constraint' => '50'),
            'Nome' => array('type' => 'VARCHAR', 'constraint' => '255'),
            'Data' => array('type' => 'DATETIME'),
            'DataEntrega' => array('type' => 'DATETIME','null' => TRUE),
            'VossaRef' => array('type' => 'VARCHAR', 'constraint' => '255','null' => TRUE),
            'Quantidade' => array('type' => 'FLOAT'),
            'QtdAfetada' => array('type' => 'FLOAT'),
            'Unidade' => array('type' => 'VARCHAR', 'constraint' => '5'),
            'DocumentoCarga' => array('type' => 'VARCHAR', 'constraint' => '100', 'null' => TRUE),
            'QtdPaletizada' => array('type' => 'FLOAT'),
            'QtdFalta' => array('type' => 'FLOAT'),
            'Ordem' => array('type' => 'INT'),
            'Referencia' => array('type' => 'VARCHAR', 'constraint' => '100'),
        );
        
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table($tbl,TRUE);

    }
    
    public function createTBL_wex1($tbl)
    {
        $this->dbforge->drop_table($tbl,TRUE);
        
        $fields = array(
            'Id' => array('type' => 'INT', 'auto_increment' => TRUE),
            'Documento' => array('type' => 'VARCHAR', 'constraint' => '50'),
            'Data' => array('type' => 'DATETIME'),
            'Cliente' => array('type' => 'VARCHAR', 'constraint' => '255'),
            'VossaRef' => array('type' => 'VARCHAR', 'constraint' => '255'),
            'Quantidade' => array('type' => 'FLOAT'),
            'Unidade' => array('type' => 'VARCHAR', 'constraint' => '5'),
            'Artigo' => array('type' => 'VARCHAR', 'constraint' => '100'),
            'Referencia' => array('type' => 'VARCHAR', 'constraint' => '100'),
            'DescricaoArtigo' => array('type' => 'VARCHAR', 'constraint' => '255'),
            'Descricao' => array('type' => 'VARCHAR', 'constraint' => '255'),
            'PrecoNM' => array('type' => 'FLOAT'),
            'Desconto' => array('type' => 'FLOAT'),
            'QtdEN' => array('type' => 'FLOAT'),
            'QtdFaltaImportar' => array('type' => 'FLOAT'),
            'LinhaDocumento' => array('type' => 'INT'),
            'Estado' => array('type' => 'VARCHAR', 'constraint' => '100'),
            'Cor_Estado' => array('type' => 'VARCHAR', 'constraint' => '50'),
            'Ordem' => array('type' => 'INT'),
        );
        
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table($tbl,TRUE);

    }

    public function createTBL_stklox1($tbl){
                        
        $this->dbforge->drop_table($tbl,TRUE);
        
        $fields = array(
            'LinhaDocumento' => array(
                'type' => 'INT'
            ),
            'Documento' => array(
                'type' => 'VARCHAR',
                'constraint' => 50
            ),
            'Referencia' => array(
                'type' => 'VARCHAR',
                'constraint' => 50
            ),
            'Lote' => array(
                'type' => 'VARCHAR',
                'constraint' => 50
            ),
            'Calibre' => array(
                'type' => 'VARCHAR',
                'constraint' => 50
            ),
            'QtdStkArm' => array(
                'type' => 'FLOAT',
                'default' => 0
            ),
            'QtdStkProd' => array(
                'type' => 'FLOAT',
                'default' => 0
            ),
            'QtdStkAfetado' => array(
                'type' => 'FLOAT',
                'default' => 0
            ),
            'QtdStkPaletizado' => array(
                'type' => 'FLOAT',
                'default' => 0
            ),
            'QtdStkReservado' => array(
                'type' => 'FLOAT',
                'default' => 0
            ),
            'StkDisponivel' => array(
                'type' => 'FLOAT',
                'default' => 0
            ),
            'Inicio' => array(
                'type' => 'DATETIME',
                'null' => TRUE
            ),
            'Fim' => array(
                'type' => 'DATETIME',
                'null' => TRUE
            ),
            'Observacoes' => array(
                'type' => 'TEXT',
                'null' => TRUE
            ),
            'Cor' => array(
                'type' => 'INT',
                'default' => 0
            ),
            'Id' => array(
                'type' => 'INT',
                'auto_increment' => TRUE
            )
        );
        
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table($tbl,TRUE);
    }

    public function createTBL_stkPlx1($tbl){
                        
        $this->dbforge->drop_table($tbl,TRUE);
        
        $fields = array(
            'LinhaDocumento' => array(
                'type' => 'INT'
            ),
            'Documento' => array(
                'type' => 'VARCHAR',
                'constraint' => '50'
            ),
            'Referencia' => array(
                'type' => 'VARCHAR',
                'constraint' => '50'
            ),
            'Palete' => array(
                'type' => 'VARCHAR',
                'constraint' => '50'
            ),
            'Lote' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE
            ),
            'Calibre' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE
            ),
            'Quantidade' => array(
                'type' => 'FLOAT'
            ),
            'Sector' => array(
                'type' => 'VARCHAR',
                'constraint' => '50'
            ),
            'Local' => array(
                'type' => 'VARCHAR',
                'constraint' => '50'
            ),
            'Inicio' => array(
                'type' => 'DATETIME',
                'null' => TRUE
            ),
            'Fim' => array(
                'type' => 'DATETIME',
                'null' => TRUE
            ),
            'Id' => array(
                'type' => 'INT',
                'auto_increment' => TRUE
            ),
        );
        
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table($tbl,TRUE);
    }

    public function createTBL_mind($tbl)
    {
        $this->dbforge->drop_table($tbl,TRUE);
        
        $fields = array(
            'Id' => array('type' => 'INT'),            
            'Inicio' => array('type' => 'DATETIME'),
            'Fim' => array('type' => 'DATETIME')
        );
        
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table($tbl,TRUE);

    }

    public function createTBL_anx($tbl){
        $this->dbforge->drop_table($tbl,TRUE);
                
        $fields = array(
            'NumSeq' => array(
                'type' => 'INT',
                'auto_increment' => TRUE
            ),
            'LinhaDocumento' => array(
                'type' => 'INT',
                'null' => TRUE
            ),
            'Documento' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => TRUE
            ),
            'Numero' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE
            ),
            'NumeroLinha' => array(
                'type' => 'INT',
                'null' => TRUE
            ),
            'Terceiro' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE
            ),
            'Nome' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE
            ),
            'Data' => array(
                'type' => 'DATETIME',
                'null' => TRUE
            ),
            'DataEntrega' => array(
                'type' => 'DATETIME',
                'null' => TRUE
            ),
            'VossaRef' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => TRUE
            ),
            'Quantidade' => array(
                'type' => 'FLOAT',
                'null' => TRUE
            ),
            'QtdAfetada' => array(
                'type' => 'FLOAT',
                'null' => TRUE
            ),
            'Unidade' => array(
                'type' => 'VARCHAR',
                'constraint' => '5',
                'null' => TRUE
            ),
            'DocumentoCarga' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
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
            'Referencia' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE
            ),
            'Operador' => array(
                'type' => 'VARCHAR',
                'constraint' => '20',
                'null' => TRUE
            ),
            'Estado' => array(
                'type' => 'VARCHAR',
                'constraint' => '5',
                'null' => TRUE
            ),
            'DescricaoEstado' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'Cor' => array(
                'type' => 'INT',
                'null' => TRUE
            ),
        );
                
        $this->dbforge->add_field($fields);
        $this->dbforge->create_table($tbl,TRUE);
    }
}