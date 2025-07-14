<?php
ini_set('max_input_time', 0);
ini_set('memory_limit',-1);
ini_set('max_execution_time', 0);
class GetPalets extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database('default');
        $this->load->helper('url');
        $this->load->library('session');
        //$this->db->reconnect();
    }


    public function producao($setor){
        //echo $setor;
        $sql01 = "SELECT count(A.id) nLinhas
                  FROM ( SELECT cast(0 as int) Sel, A.Sector, round(sum(case when A.TipoMovimento<='10' then A.Quantidade else -A.Quantidade end),8) Quantidade, case when isnull(B.Unidade,'')='' then 'M2' else B.Unidade end Unidade, A.RefP Referencia,
                                A.Artigo, B.Descricao DescricaoArtigo, isnull(A.Lote,'') Lote, isnull(A.Calibre,'') Calibre, D.NumeroLinha LinhaPL, C.Numero DocPL, B.Formato, B.Qual, B.TipoEmbalagem, B.Superficie, B.Decoracao, B.RefCor, B.TabEspessura,
                                round(sum(case when A.TipoMovimento<='10' then A.Quantidade else -A.Quantidade end),8) NovaQtd, isnull(D.NivelPalete,'') Nivel, cast(0 as float) Qtd_OK, cast(0 as float) Qtd_NOK, cast(0 as float) Qtd_Caco,
                                cast('' as varchar(50)) RefSeg, cast('' as varchar(255)) DescRefSeg, CAST(ROW_NUMBER() OVER(ORDER BY C.Numero asc)-1 AS int) Id
                         from StkLDocs A join ReferArt B on (A.RefP=B.Referencia and A.Artigo=B.Artigo)
                                         join PlDocs   C on ((case when isnull(A.DocPL,'')='' then A.Palete else A.DocPL end)=C.Numero)
                                         join PllDocs  D on (C.Numero=D.NumeroDocumento)
                        where A.Sector in ({$setor}) and C.Serie not in ('C','PC')
                        group by A.Sector, A.RefP, A.Artigo, isnull(A.Lote,''), isnull(A.Calibre,''), D.NumeroLinha, C.Numero,B.Formato, B.Qual, B.TipoEmbalagem, B.Superficie, B.Decoracao,
                                B.RefCor, B.TabEspessura, case when isnull(B.Unidade,'')='' then 'M2' else B.Unidade end, B.Descricao, isnull(D.NivelPalete,'')
                        having round(sum(case when A.TipoMovimento<='10' then A.Quantidade else -A.Quantidade end),8)>0
                     ) A";        
        $query01 = $this->db->query($sql01);        
        $tot  = $query01->result();
        foreach ($tot as $val) {
			$itemcount = $val->nLinhas;
		}

        $data = array();
		$arr = [];
        $batches = $itemcount / 3500; // Number of while-loop calls - around 120.

        for ($i = 0; $i <= $batches; $i++) {
            $offset = $i * 3500; // MySQL Limit offset number
            $fetch = $offset + 3500;
            $this->db->db_pconnect(); 
            $sql02 = "SELECT cast(0 as int) Sel, A.Sector, round(sum(case when A.TipoMovimento<='10' then A.Quantidade else -A.Quantidade end),8) Quantidade, case when isnull(B.Unidade,'')='' then 'M2' else B.Unidade end Unidade, A.RefP Referencia,
                            A.Artigo, B.Descricao DescricaoArtigo, isnull(A.Lote,'') Lote, isnull(A.Calibre,'') Calibre, D.NumeroLinha LinhaPL, C.Numero DocPL, B.Formato, B.Qual, B.TipoEmbalagem, B.Superficie, B.Decoracao, B.RefCor, B.TabEspessura,
                            isnull(D.NivelPalete,'') Nivel, round(sum(case when A.TipoMovimento<='10' then A.Quantidade else -A.Quantidade end),8) NovaQtd, cast(0 as float) Qtd_OK, cast(0 as float) Qtd_NOK, cast(0 as float) Qtd_Caco,
                            cast('' as varchar(50)) RefSeg, cast('' as varchar(255)) DescRefSeg, CAST(ROW_NUMBER() OVER(ORDER BY C.Numero asc)-1 AS int) Id
                      from StkLDocs A join ReferArt B on (A.RefP=B.Referencia and A.Artigo=B.Artigo)
                                      join PlDocs   C on ((case when isnull(A.DocPL,'')='' then A.Palete else A.DocPL end)=C.Numero)
                                      join PllDocs  D on (C.Numero=D.NumeroDocumento)
                      where A.Sector in ({$setor}) and C.Serie not in ('C','PC')
                      group by A.Sector, A.RefP, A.Artigo, isnull(A.Lote,''), isnull(A.Calibre,''), D.NumeroLinha, C.Numero,B.Formato, B.Qual, B.TipoEmbalagem, B.Superficie, B.Decoracao,
                                B.RefCor, B.TabEspessura, case when isnull(B.Unidade,'')='' then 'M2' else B.Unidade end, B.Descricao, isnull(D.NivelPalete,'')
                      having round(sum(case when A.TipoMovimento<='10' then A.Quantidade else -A.Quantidade end),8)>0
                      Order by C.Numero ASC 
                      OFFSET ".$offset." ROWS
                      FETCH NEXT ".$fetch ." ROWS ONLY";  
            //echo $sql02;
            $query02 = $this->db->query($sql02);
            $result = $query02->result();
            $this->db->close();

            foreach ($result as $row) {

                $arr['Sel']=$row->Sel;
                $arr['Sector']=$row->Sector;
                $arr['Quantidade']=$row->Quantidade;
                $arr['Unidade']=$row->Unidade;
                $arr['Referencia']=$row->Referencia;
                $arr['Artigo']=$row->Artigo;
                $arr['DescricaoArtigo']=$row->DescricaoArtigo;
                $arr['Lote']=$row->Lote;
                $arr['Calibre']=$row->Calibre;
                $arr['LinhaPL']=$row->LinhaPL;
                $arr['DocPL']=$row->DocPL;
                $arr['Formato']=$row->Formato;
                $arr['Qual']=$row->Qual;
                $arr['TipoEmbalagem']=$row->TipoEmbalagem;
                $arr['Superficie']=$row->Superficie;
                $arr['Decoracao']=$row->Decoracao;
                $arr['RefCor']=$row->RefCor;
                $arr['TabEspessura']=$row->TabEspessura;
                $arr['Nivel']=$row->Nivel;
                $arr['NovaQtd']=$row->NovaQtd;   
                $arr['Qtd_OK']=$row->Qtd_OK;   
                $arr['Qtd_NOK']=$row->Qtd_NOK; 
                $arr['Qtd_Caco']=$row->Qtd_Caco; 
                $arr['RefSeg']=$row->RefSeg; 
                $arr['DescRefSeg']=$row->DescRefSeg; 
                $arr['Id']=$row->Id;

                $data[]=$arr;
                unset($arr);
            }
        }        

        $data = array_unique($data, SORT_REGULAR);        
        return $data;
    }
    
    public function armazem($setor,$condicao){
        //echo $setor;
        $sql01 = "SELECT count(A.id) nLinhas
                  FROM ( SELECT cast(0 as int) Sel, A.Sector, isnull(A.Local,'') Local, round(sum(case when A.TipoMovimento<='10' then A.Quantidade else -A.Quantidade end),8) Quantidade, case when isnull(B.Unidade,'')='' then 'M2' else B.Unidade end Unidade,
                            A.RefP Referencia, A.Artigo, B.Descricao DescricaoArtigo, isnull(A.Lote,'') Lote, isnull(A.Calibre,'') Calibre, D.NumeroLinha LinhaPL, C.Numero DocPL, B.Formato, B.Qual, B.TipoEmbalagem, B.Superficie, B.Decoracao,
                            B.RefCor, B.TabEspessura, isnull(D.NivelPalete,'') Nivel, cast(0 as float) NovaQtd, CAST(ROW_NUMBER() OVER(ORDER BY C.Numero asc)-1 AS int) Id
                         from StkLDocs A join ReferArt B on (A.RefP=B.Referencia and A.Artigo=B.Artigo)
                                         join PlDocs   C on ((case when isnull(A.DocPL,'')='' then A.Palete else A.DocPL end)=C.Numero)
                                         join PllDocs  D on (C.Numero=D.NumeroDocumento)
                        where A.Sector in ({$setor}) and C.Serie not in ('C','PC')
                         -- and isnull(A.Local,'')<>''
                        group by A.Sector, isnull(A.Local,''), A.RefP, A.Artigo, isnull(A.Lote,''), isnull(A.Calibre,''), D.NumeroLinha, C.Numero, B.Formato, B.Qual, B.TipoEmbalagem,
                                 B.Superficie, B.Decoracao, B.RefCor, B.TabEspessura, case when isnull(B.Unidade,'')='' then 'M2' else B.Unidade end, B.Descricao, isnull(D.NivelPalete,'')
                        having round(sum(case when A.TipoMovimento<='10' then A.Quantidade else -A.Quantidade end),8){$condicao}
                     ) A";        
        $query01 = $this->db->query($sql01);        
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
            $sql02 = "SELECT cast(0 as int) Sel, A.Sector, isnull(A.Local,'') Local, round(sum(case when A.TipoMovimento<='10' then A.Quantidade else -A.Quantidade end),8) Quantidade, case when isnull(B.Unidade,'')='' then 'M2' else B.Unidade end Unidade,
                            A.RefP Referencia, A.Artigo, B.Descricao DescricaoArtigo, isnull(A.Lote,'') Lote, isnull(A.Calibre,'') Calibre, D.NumeroLinha LinhaPL, C.Numero DocPL, B.Formato, B.Qual, B.TipoEmbalagem, B.Superficie, B.Decoracao,
                            B.RefCor, B.TabEspessura, isnull(D.NivelPalete,'') Nivel, cast(0 as float) NovaQtd, cast(0 as int) Reabilitado, CAST(ROW_NUMBER() OVER(ORDER BY C.Numero asc)-1 AS int) Id
                      from StkLDocs A join ReferArt B on (A.RefP=B.Referencia and A.Artigo=B.Artigo)
                                      join PlDocs   C on ((case when isnull(A.DocPL,'')='' then A.Palete else A.DocPL end)=C.Numero)
                                      join PllDocs  D on (C.Numero=D.NumeroDocumento)
                      where A.Sector in ({$setor}) and C.Serie not in ('C','PC')
                      group by A.Sector, isnull(A.Local,''), A.RefP, A.Artigo, isnull(A.Lote,''), isnull(A.Calibre,''), D.NumeroLinha, C.Numero, B.Formato, B.Qual, B.TipoEmbalagem,
                                B.Superficie, B.Decoracao, B.RefCor, B.TabEspessura, case when isnull(B.Unidade,'')='' then 'M2' else B.Unidade end, B.Descricao, isnull(D.NivelPalete,'')
                      having round(sum(case when A.TipoMovimento<='10' then A.Quantidade else -A.Quantidade end),8){$condicao}
                      Order by C.Numero ASC
                      OFFSET ".$offset." ROWS
                      FETCH NEXT ".$fetch ." ROWS ONLY";  
            //and isnull(A.Local,'')<>'' 
           // echo $sql02;
            set_time_limit(0);
            $this->db->db_pconnect();                                          
            $query = $this->db->query($sql02);
        
                    // Verifique se a consulta foi bem-sucedida
            if ($query) {
                $result = $query->result();
                        
                        // Processa os resultados conforme necessário
                foreach ($result as $row) {
                    $arr['Sel']=$row->Sel;
                    $arr['Sector']=$row->Sector;
                    $arr['Local']=$row->Local;
                    $arr['Quantidade']=$row->Quantidade;
                    $arr['Unidade']=$row->Unidade;
                    $arr['Referencia']=$row->Referencia;
                    $arr['Artigo']=$row->Artigo;
                    $arr['DescricaoArtigo']=$row->DescricaoArtigo;
                    $arr['Lote']=$row->Lote;
                    $arr['Calibre']=$row->Calibre;
                    $arr['LinhaPL']=$row->LinhaPL;
                    $arr['DocPL']=$row->DocPL;
                    $arr['Formato']=$row->Formato;
                    $arr['Qual']=$row->Qual;
                    $arr['TipoEmbalagem']=$row->TipoEmbalagem;
                    $arr['Superficie']=$row->Superficie;
                    $arr['Decoracao']=$row->Decoracao;
                    $arr['RefCor']=$row->RefCor;
                    $arr['TabEspessura']=$row->TabEspessura;
                    $arr['Nivel']=$row->Nivel;
                    $arr['NovaQtd']=$row->NovaQtd;
                    $arr['Reabilitado']=$row->Reabilitado;
                    $arr['Id']=$row->Id;

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

    public function cargas($refp,$setor){
        //echo $setor;
        $sql01 = "SELECT count(A.id) nLinhas
                  FROM ( SELECT cast(0 as int) Sel, A.Sector, isnull(A.Local,'') Local, round(sum(case when A.TipoMovimento<='10' then A.Quantidade else -A.Quantidade end),8) Quantidade, case when isnull(B.Unidade,'')='' then 'M2' else B.Unidade end Unidade,
                            A.RefP Referencia, A.Artigo, B.Descricao DescricaoArtigo, isnull(A.Lote,'') Lote, isnull(A.Calibre,'') Calibre, D.NumeroLinha LinhaPL, C.Numero DocPL, B.Formato, B.Qual, B.TipoEmbalagem, B.Superficie, B.Decoracao,
                            B.RefCor, B.TabEspessura, isnull(D.NivelPalete,'') Nivel, round(sum(case when A.TipoMovimento<='10' then A.Quantidade else -A.Quantidade end),8) NovaQtd, CAST(ROW_NUMBER() OVER(ORDER BY C.Numero asc)-1 AS int) Id
                         from StkLDocs A join ReferArt B on (A.RefP=B.Referencia and A.Artigo=B.Artigo)
                                         join PlDocs   C on ((case when isnull(A.DocPL,'')='' then A.Palete else A.DocPL end)=C.Numero)
                                         join PllDocs  D on (C.Numero=D.NumeroDocumento)
                        where A.Refp='{$refp}' and A.Sector in ({$setor}) and C.Serie not in ('C','PC')
                         -- and isnull(A.Local,'')<>''
                        group by A.Sector, isnull(A.Local,''), A.RefP, A.Artigo, isnull(A.Lote,''), isnull(A.Calibre,''), D.NumeroLinha, C.Numero, B.Formato, B.Qual, B.TipoEmbalagem,
                                 B.Superficie, B.Decoracao, B.RefCor, B.TabEspessura, case when isnull(B.Unidade,'')='' then 'M2' else B.Unidade end, B.Descricao, isnull(D.NivelPalete,'')
                        having round(sum(case when A.TipoMovimento<='10' then A.Quantidade else -A.Quantidade end),8)>0
                     ) A";        
        $query01 = $this->db->query($sql01);        
        $tot  = $query01->result();
        foreach ($tot as $val) {
			$itemcount = $val->nLinhas;
		}

        $data = array();
		$arr = [];
        $batches = $itemcount / 3500; // Number of while-loop calls - around 120.

        for ($i = 0; $i <= $batches; $i++) {
            $offset = $i * 3500; // MySQL Limit offset number
            $fetch = $offset + 3500;
            $this->db->db_pconnect(); 
            $sql02 = "SELECT cast(0 as int) Sel, A.Sector, isnull(A.Local,'') Local, round(sum(case when A.TipoMovimento<='10' then A.Quantidade else -A.Quantidade end),8) Quantidade, case when isnull(B.Unidade,'')='' then 'M2' else B.Unidade end Unidade,
                            A.RefP Referencia, A.Artigo, B.Descricao DescricaoArtigo, isnull(A.Lote,'') Lote, isnull(A.Calibre,'') Calibre, D.NumeroLinha LinhaPL, C.Numero DocPL, B.Formato, B.Qual, B.TipoEmbalagem, B.Superficie, B.Decoracao,
                            B.RefCor, B.TabEspessura, isnull(D.NivelPalete,'') Nivel, round(sum(case when A.TipoMovimento<='10' then A.Quantidade else -A.Quantidade end),8) NovaQtd, cast(0 as int) Reabilitado, CAST(ROW_NUMBER() OVER(ORDER BY C.Numero asc)-1 AS int) Id
                      from StkLDocs A join ReferArt B on (A.RefP=B.Referencia and A.Artigo=B.Artigo)
                                      join PlDocs   C on ((case when isnull(A.DocPL,'')='' then A.Palete else A.DocPL end)=C.Numero)
                                      join PllDocs  D on (C.Numero=D.NumeroDocumento)
                      where A.Refp='{$refp}' and A.Sector in ({$setor}) and C.Serie not in ('C','PC')
                      group by A.Sector, isnull(A.Local,''), A.RefP, A.Artigo, isnull(A.Lote,''), isnull(A.Calibre,''), D.NumeroLinha, C.Numero, B.Formato, B.Qual, B.TipoEmbalagem,
                                B.Superficie, B.Decoracao, B.RefCor, B.TabEspessura, case when isnull(B.Unidade,'')='' then 'M2' else B.Unidade end, B.Descricao, isnull(D.NivelPalete,'')
                      having round(sum(case when A.TipoMovimento<='10' then A.Quantidade else -A.Quantidade end),8)>0
                      Order by C.Numero ASC
                      OFFSET ".$offset." ROWS
                      FETCH NEXT ".$fetch ." ROWS ONLY";  
            //and isnull(A.Local,'')<>'' 
            //echo $sql02;
            $query02 = $this->db->query($sql02);
            $result = $query02->result();
            $this->db->close();

            foreach ($result as $row) {

                $arr['Sel']=$row->Sel;
                $arr['Sector']=$row->Sector;
                $arr['Local']=$row->Local;
                $arr['Quantidade']=$row->Quantidade;
                $arr['Unidade']=$row->Unidade;
                $arr['Referencia']=$row->Referencia;
                $arr['Artigo']=$row->Artigo;
                $arr['DescricaoArtigo']=$row->DescricaoArtigo;
                $arr['Lote']=$row->Lote;
                $arr['Calibre']=$row->Calibre;
                $arr['LinhaPL']=$row->LinhaPL;
                $arr['DocPL']=$row->DocPL;
                $arr['Formato']=$row->Formato;
                $arr['Qual']=$row->Qual;
                $arr['TipoEmbalagem']=$row->TipoEmbalagem;
                $arr['Superficie']=$row->Superficie;
                $arr['Decoracao']=$row->Decoracao;
                $arr['RefCor']=$row->RefCor;
                $arr['TabEspessura']=$row->TabEspessura;
                $arr['Nivel']=$row->Nivel;
                $arr['NovaQtd']=$row->NovaQtd;
                $arr['Reabilitado']=$row->Reabilitado;
                $arr['Id']=$row->Id;

                $data[]=$arr;
                unset($arr);
            }
        }        

        $data = array_unique($data, SORT_REGULAR);        
        return $data;
    }

    public function stock($empresa){

        set_time_limit(0);
        $sql01 = "SELECT count(F.LinhaPL) nLinhas
                  FROM ( SELECT A.Sector, isnull(A.Local,'') Local, round(sum(case when A.TipoMovimento<='10' then A.Quantidade else -A.Quantidade end),8) Quantidade,
                                case when isnull(B.Unidade,'')='' then 'M2' else B.Unidade end Unidade, A.RefP Referencia, A.Artigo, B.Descricao DescricaoArtigo, isnull(A.Lote,'') Lote,
                                isnull(A.Calibre,'') Calibre, D.NumeroLinha LinhaPL, C.Numero DocPL, isnull(B.Formato,'') Formato, isnull(B.Qual,'') Qual,
                                isnull(B.TipoEmbalagem,'') TipoEmbalagem, isnull(B.Superficie,'') Superficie, isnull(B.Decoracao,'') Decoracao, isnull(B.RefCor,'') RefCor,
                                isnull(B.TabEspessura,'') TabEspessura, isnull(E.Tipo,'') Tipo, CAST(ROW_NUMBER() OVER(ORDER BY C.Numero asc)-1 AS int) Id
                         from StkLDocs A join Sectores     X on (A.Sector=X.Codigo and X.Empresa=UPPER('{$empresa}'))
                                         join ReferArt     B on (A.RefP=B.Referencia and A.Artigo=B.Artigo)
                                         join PlDocs       C on ((case when isnull(A.DocPL,'')='' then A.Palete else A.DocPL end)=C.Numero)
                                         join PllDocs      D on (C.Numero=D.NumeroDocumento)
                                    left join NiveisPalete E on (D.NivelPalete=E.Codigo)
                         group by A.Sector, isnull(A.Local,''), A.RefP, A.Artigo, isnull(A.Lote,''), isnull(A.Calibre,''), D.NumeroLinha, C.Numero, B.Formato, B.Qual, B.TipoEmbalagem,
                                B.Superficie, B.Decoracao, B.RefCor, B.TabEspessura, case when isnull(B.Unidade,'')='' then 'M2' else B.Unidade end, B.Descricao, E.Descricao, E.Tipo
                         having round(sum(case when A.TipoMovimento<='10' then A.Quantidade else -A.Quantidade end),8)>0
                     ) F";         
        //echo $sql01;
        $query01 = $this->db->query($sql01);        
        $tot  = $query01->result();
        $this->db->close();

        foreach ($tot as $val) {
			$itemcount = $val->nLinhas;
		}

        $data = array();
		$arr = [];
        
        //echo '$itemcount: '. $itemcount;
        $batches = round($itemcount / 3500); // Number of while-loop calls - around 120.
        //echo '$batches: '. $batches;
        set_time_limit(0);
        $this->db->db_pconnect();
        for ($i = 0; $i <= $batches; $i++) {            
            $offset = $i * 3500; // MySQL Limit offset number
            $fetch = $offset + 3500;
            set_time_limit(0);
            $this->db->db_pconnect();    
        
            $sql = "SELECT A.Sector, isnull(A.Local,'') Local, round(sum(case when A.TipoMovimento<='10' then A.Quantidade else -A.Quantidade end),8) Quantidade,
                           case when isnull(B.Unidade,'')='' then 'M2' else B.Unidade end Unidade, A.RefP Referencia, A.Artigo, B.Descricao DescricaoArtigo, isnull(A.Lote,'') Lote,
                           isnull(A.Calibre,'') Calibre, D.NumeroLinha LinhaPL, C.Numero DocPL, isnull(B.Formato,'') Formato, isnull(B.Qual,'') Qual,
                           isnull(B.TipoEmbalagem,'') TipoEmbalagem, isnull(B.Superficie,'') Superficie, isnull(B.Decoracao,'') Decoracao, isnull(B.RefCor,'') RefCor,
                           isnull(B.TabEspessura,'') TabEspessura, isnull(E.Tipo,'') Tipo, CAST(ROW_NUMBER() OVER(ORDER BY C.Numero asc)-1 AS int) Id
                    from StkLDocs A join Sectores     X on (A.Sector=X.Codigo and X.Empresa=UPPER('{$empresa}'))
                                    join ReferArt     B on (A.RefP=B.Referencia and A.Artigo=B.Artigo)
                                    join PlDocs       C on ((case when isnull(A.DocPL,'')='' then A.Palete else A.DocPL end)=C.Numero)
                                    join PllDocs      D on (C.Numero=D.NumeroDocumento)
                               left join NiveisPalete E on (D.NivelPalete=E.Codigo)
                    group by A.Sector, isnull(A.Local,''), A.RefP, A.Artigo, isnull(A.Lote,''), isnull(A.Calibre,''), D.NumeroLinha, C.Numero, B.Formato, B.Qual, B.TipoEmbalagem,
                                B.Superficie, B.Decoracao, B.RefCor, B.TabEspessura, case when isnull(B.Unidade,'')='' then 'M2' else B.Unidade end, B.Descricao, E.Descricao, E.Tipo
                    having round(sum(case when A.TipoMovimento<='10' then A.Quantidade else -A.Quantidade end),8)>0
                    Order by C.Numero ASC
                    OFFSET ".$offset." ROWS
                    FETCH NEXT ".$fetch ." ROWS ONLY";
        
            set_time_limit(0);
            $this->db->db_pconnect();                                          
            $query = $this->db->query($sql);
        
            // Verifique se a consulta foi bem-sucedida
            if ($query) {
                $result = $query->result();
                
                // Processa os resultados conforme necessário
                foreach ($result as $row) {
                    $arr['Sector'] = $row->Sector;
                    $arr['Local'] = $row->Local;
                    $arr['Quantidade'] = $row->Quantidade;
                    $arr['Unidade'] = $row->Unidade;
                    $arr['Referencia'] = $row->Referencia;
                    $arr['Artigo'] = $row->Artigo;
                    $arr['DescricaoArtigo'] = $row->DescricaoArtigo;
                    $arr['Lote'] = $row->Lote;
                    $arr['Calibre'] = $row->Calibre;                
                    $arr['DocPL'] = $row->DocPL;
                    $arr['Formato'] = $row->Formato;
                    $arr['Tipo'] = $row->Tipo;
                    $arr['Id'] = $row->Id;
                    
                    $data[] = $arr;
                    unset($arr);
                }
            } else {
                // Lida com o erro de consulta
                $erro = $this->db->error();
                log_message('error', 'Erro na consulta SQL: ' . $erro['message']);
            }
        
            $this->db->close();
        }
        
        // Remove duplicatas dos dados
        $data = array_unique($data, SORT_REGULAR);
        
        return $data;
        
    }

    public function stock_datas($empresa,$idata,$fdata){

        set_time_limit(0);
        $sql01 = "SELECT count(F.LinhaPL) nLinhas
                  FROM ( SELECT A.Sector, isnull(A.Local,'') Local, round(sum(case when A.TipoMovimento<='10' then A.Quantidade else -A.Quantidade end),8) Quantidade,
                                case when isnull(B.Unidade,'')='' then 'M2' else B.Unidade end Unidade, A.RefP Referencia, A.Artigo, B.Descricao DescricaoArtigo, isnull(A.Lote,'') Lote,
                                isnull(A.Calibre,'') Calibre, D.NumeroLinha LinhaPL, C.Numero DocPL, isnull(B.Formato,'') Formato, isnull(B.Qual,'') Qual,
                                isnull(B.TipoEmbalagem,'') TipoEmbalagem, isnull(B.Superficie,'') Superficie, isnull(B.Decoracao,'') Decoracao, isnull(B.RefCor,'') RefCor,
                                isnull(B.TabEspessura,'') TabEspessura, isnull(E.Tipo,'') Tipo, CAST(ROW_NUMBER() OVER(ORDER BY C.Numero asc)-1 AS int) Id
                         from StkLDocs A join Sectores     X on (A.Sector=X.Codigo and X.Empresa=UPPER('{$empresa}'))
                                         join ReferArt     B on (A.RefP=B.Referencia and A.Artigo=B.Artigo)
                                         join PlDocs       C on ((case when isnull(A.DocPL,'')='' then A.Palete else A.DocPL end)=C.Numero)
                                         join PllDocs      D on (C.Numero=D.NumeroDocumento)
                                    left join NiveisPalete E on (D.NivelPalete=E.Codigo)
                        where convert(char,A.DataHoraMOV,112)>='{$idata}' AND convert(char,A.DataHoraMOV,112)<='{$fdata}'
                        group by A.Sector, isnull(A.Local,''), A.RefP, A.Artigo, isnull(A.Lote,''), isnull(A.Calibre,''), D.NumeroLinha, C.Numero, B.Formato, B.Qual, B.TipoEmbalagem,
                                B.Superficie, B.Decoracao, B.RefCor, B.TabEspessura, case when isnull(B.Unidade,'')='' then 'M2' else B.Unidade end, B.Descricao, E.Descricao, E.Tipo
                         having round(sum(case when A.TipoMovimento<='10' then A.Quantidade else -A.Quantidade end),8)>0
                     ) F";         
        //echo $sql01;
        $query01 = $this->db->query($sql01);        
        $tot  = $query01->result();
        $this->db->close();

        foreach ($tot as $val) {
			$itemcount = $val->nLinhas;
		}

        $data = array();
		$arr = [];
        
        //echo '$itemcount: '. $itemcount;
        $batches = round($itemcount / 3500); // Number of while-loop calls - around 120.
        //echo '$batches: '. $batches;
        set_time_limit(0);
        $this->db->db_pconnect();
        for ($i = 0; $i <= $batches; $i++) {            
            $offset = $i * 3500; // MySQL Limit offset number
            $fetch = $offset + 3500;
            set_time_limit(0);
            $this->db->db_pconnect();    
        
            $sql = "SELECT A.Sector, isnull(A.Local,'') Local, round(sum(case when A.TipoMovimento<='10' then A.Quantidade else -A.Quantidade end),8) Quantidade,
                           case when isnull(B.Unidade,'')='' then 'M2' else B.Unidade end Unidade, A.RefP Referencia, A.Artigo, B.Descricao DescricaoArtigo, isnull(A.Lote,'') Lote,
                           isnull(A.Calibre,'') Calibre, D.NumeroLinha LinhaPL, C.Numero DocPL, isnull(B.Formato,'') Formato, isnull(B.Qual,'') Qual,
                           isnull(B.TipoEmbalagem,'') TipoEmbalagem, isnull(B.Superficie,'') Superficie, isnull(B.Decoracao,'') Decoracao, isnull(B.RefCor,'') RefCor,
                           isnull(B.TabEspessura,'') TabEspessura, isnull(E.Tipo,'') Tipo, CAST(ROW_NUMBER() OVER(ORDER BY C.Numero asc)-1 AS int) Id
                    from StkLDocs A join Sectores     X on (A.Sector=X.Codigo and X.Empresa=UPPER('{$empresa}'))
                                    join ReferArt     B on (A.RefP=B.Referencia and A.Artigo=B.Artigo)
                                    join PlDocs       C on ((case when isnull(A.DocPL,'')='' then A.Palete else A.DocPL end)=C.Numero)
                                    join PllDocs      D on (C.Numero=D.NumeroDocumento)
                               left join NiveisPalete E on (D.NivelPalete=E.Codigo)
                    where convert(char,A.DataHoraMOV,112)>='{$idata}' AND convert(char,A.DataHoraMOV,112)<='{$fdata}'
                    group by A.Sector, isnull(A.Local,''), A.RefP, A.Artigo, isnull(A.Lote,''), isnull(A.Calibre,''), D.NumeroLinha, C.Numero, B.Formato, B.Qual, B.TipoEmbalagem,
                                B.Superficie, B.Decoracao, B.RefCor, B.TabEspessura, case when isnull(B.Unidade,'')='' then 'M2' else B.Unidade end, B.Descricao, E.Descricao, E.Tipo
                    having round(sum(case when A.TipoMovimento<='10' then A.Quantidade else -A.Quantidade end),8)>0
                    Order by C.Numero ASC
                    OFFSET ".$offset." ROWS
                    FETCH NEXT ".$fetch ." ROWS ONLY";
        
            set_time_limit(0);
            $this->db->db_pconnect();                                          
            $query = $this->db->query($sql);
        
            // Verifique se a consulta foi bem-sucedida
            if ($query) {
                $result = $query->result();
                
                // Processa os resultados conforme necessário
                foreach ($result as $row) {
                    $arr['Sector'] = $row->Sector;
                    $arr['Local'] = $row->Local;
                    $arr['Quantidade'] = $row->Quantidade;
                    $arr['Unidade'] = $row->Unidade;
                    $arr['Referencia'] = $row->Referencia;
                    $arr['Artigo'] = $row->Artigo;
                    $arr['DescricaoArtigo'] = $row->DescricaoArtigo;
                    $arr['Lote'] = $row->Lote;
                    $arr['Calibre'] = $row->Calibre;                
                    $arr['DocPL'] = $row->DocPL;
                    $arr['Formato'] = $row->Formato;
                    $arr['Tipo'] = $row->Tipo;
                    $arr['Id'] = $row->Id;
                    
                    $data[] = $arr;
                    unset($arr);
                }
            } else {
                // Lida com o erro de consulta
                $erro = $this->db->error();
                log_message('error', 'Erro na consulta SQL: ' . $erro['message']);
            }
        
            $this->db->close();
        }
        
        // Remove duplicatas dos dados
        $data = array_unique($data, SORT_REGULAR);
        
        return $data;
        
    }

    public function recolhe_dados_palete($serie,$palete){
        $sql="SELECT A.Numero, A.VossaRef, A.NossaRef, '{$serie}' Serie, CONCAT(B.Referencia,' - ',B.DescricaoArtigo) Artigo, CONCAT(B.Quantidade,' ',B.Unidade) QtdUni, 
                     case when isnull(B.Lote,'')='' then 'XXXXX' else CONCAT('LOTE ',B.Lote) end Lote, 
                     case when isnull(B.Calibre,'')='' then 'XXXXX' else CONCAT('CALIBRE ',B.Calibre) end Calibre, CONCAT(convert(char(10),B.DataHoraMOV,105),' ',convert(char(10),B.DataHoraMOV,108)) DataHoraMOV
              FROM PlDocs A join PlLDocs B on (A.Numero=B.NumeroDocumento)
              WHERE A.Numero='{$palete}'";         
  
        $query = $this->db->query($sql);        
        $result = $query->result();
        
        return $result;
    }

    public function anula_palete_gg($seriePL,$refp,$setor,$plano,$username,$funcionario_gpac){

        set_time_limit(0);
        $sql01 = "SELECT count(A.id) nLinhas
                  FROM ( SELECT cast(0 as bit) Sel, D.NumeroDocumento, D.Quantidade, D.Referencia, B.Artigo, B.Descricao DescricaoArtigo, isnull(D.Lote,'') Lote, isnull(D.Calibre,'') Calibre, D.PaleteOrigem, D.DataHoraMOV, 
                                D.LinhaDocumento, D.Documento, cast('{$plano}' as varchar(20)) Carga, max(a.Local) Local, CAST(ROW_NUMBER() OVER(ORDER BY D.NumeroDocumento desc)-1 AS int) Id
                         from StkLDocs A join ReferArt B on (A.RefP=B.Referencia and A.Artigo=B.Artigo)
                                         join PlDocs   C on (C.Estado not in ('A') and (case when isnull(A.DocPL,'')='' then A.Palete else A.DocPL end)=C.Numero)
                                         join PllDocs  D on (C.Numero=D.NumeroDocumento and D.NumeroSerieInferior='{$plano}')
                        where A.Refp='{$refp}' and A.Sector in ({$setor})
                         -- and isnull(A.Local,'')<>''
                        group by D.NumeroDocumento, D.Quantidade, D.Referencia, B.Artigo, B.Descricao, isnull(D.Lote,''), isnull(D.Calibre,''), D.PaleteOrigem, D.DataHoraMOV, D.LinhaDocumento, D.Documento  
                     ) A";        
        //echo $sql01;
        $query01 = $this->db->query($sql01);        
        $tot  = $query01->result();
        $this->db->close();

        foreach ($tot as $val) {
			$itemcount = $val->nLinhas;
		}

        $data = array();
		$arr = [];
        
        //echo '$itemcount: '. $itemcount;
        $batches = round($itemcount / 3500); // Number of while-loop calls - around 120.
        //echo '$batches: '. $batches;
        set_time_limit(0);
        $this->db->db_pconnect();
        for ($i = 0; $i <= $batches; $i++) {            
            $offset = $i * 3500; // MySQL Limit offset number
            $fetch = $offset + 3500;
            set_time_limit(0);
            $this->db->db_pconnect();    
        
            $sql = "SELECT cast(0 as bit) Sel, D.NumeroDocumento, D.Quantidade, D.Referencia, B.Artigo, B.Descricao DescricaoArtigo, isnull(D.Lote,'') Lote, isnull(D.Calibre,'') Calibre, D.PaleteOrigem, D.DataHoraMOV, 
                                D.LinhaDocumento, D.Documento, cast('{$plano}' as varchar(20)) Carga, max(a.Local) Local, CAST(ROW_NUMBER() OVER(ORDER BY D.NumeroDocumento desc)-1 AS int) Id
                    from StkLDocs A join ReferArt B on (A.RefP=B.Referencia and A.Artigo=B.Artigo)
                                    join PlDocs   C on (C.Estado not in ('A') and (case when isnull(A.DocPL,'')='' then A.Palete else A.DocPL end)=C.Numero)
                                    join PllDocs  D on (C.Numero=D.NumeroDocumento and D.NumeroSerieInferior='{$plano}')
                    where A.Refp='{$refp}' and A.Sector in ({$setor})
                         -- and isnull(A.Local,'')<>''
                    group by D.NumeroDocumento, D.Quantidade, D.Referencia, B.Artigo, B.Descricao, isnull(D.Lote,''), isnull(D.Calibre,''), D.PaleteOrigem, D.DataHoraMOV, D.LinhaDocumento, D.Documento  
                    Order by D.NumeroDocumento desc
                    OFFSET ".$offset." ROWS
                    FETCH NEXT ".$fetch ." ROWS ONLY"; 
            //echo $sql;
            set_time_limit(0);
            $this->db->db_pconnect();                                          
            $query = $this->db->query($sql);
        
            // Verifique se a consulta foi bem-sucedida
            if ($query) {
                $result = $query->result();
                
                // Processa os resultados conforme necessário
                foreach ($result as $row) {
                    $arr['Sel'] = $row->Sel;
                    $arr['NumeroDocumento'] = $row->NumeroDocumento;
                    $arr['Quantidade'] = $row->Quantidade;
                    $arr['Referencia'] = $row->Referencia;
                    $arr['Artigo'] = $row->Artigo;
                    $arr['DescricaoArtigo'] = $row->DescricaoArtigo;
                    $arr['Lote'] = $row->Lote;
                    $arr['Calibre'] = $row->Calibre;                
                    $arr['PaleteOrigem'] = $row->PaleteOrigem;
                    $arr['DataHoraMOV'] = $row->DataHoraMOV;
                    $arr['LinhaDocumento'] = $row->LinhaDocumento;
                    $arr['Documento'] = $row->Documento;
                    $arr['Carga'] = $row->Carga;
                    $arr['Local'] = $row->Local;
                    $arr['Id'] = $row->Id;
                    
                    $data[] = $arr;
                    unset($arr);
                }
            } else {
                // Lida com o erro de consulta
                $erro = $this->db->error();
                log_message('error', 'Erro na consulta SQL: ' . $erro['message']);
            }
        
            $this->db->close();
        }
        
        // Remove duplicatas dos dados
        $data = array_unique($data, SORT_REGULAR);
        
        return $data;
        
    }

    public function palete_plano_carga($serie,$linha,$docEN,$plano,$refp,$lote,$calibre){

        set_time_limit(0);
        $sql01 = "SELECT count(A.id) nLinhas
                  FROM (SELECT A.Sector, isnull(d.Local,'') Local, round(sum(case when A.TipoMovimento<='10' then A.Quantidade else -A.Quantidade end),8) Quantidade,
                                case when isnull(B.Unidade,'')='' then 'M2' else B.Unidade end Unidade, A.RefP Referencia, A.Artigo, B.Descricao DescricaoArtigo, isnull(A.Lote,'') Lote,
                                isnull(A.Calibre,'') Calibre, D.NumeroLinha LinhaPL, D.PaleteOrigem DocPL, isnull(B.Formato,'') Formato, isnull(B.Qual,'') Qual,
                                isnull(B.TipoEmbalagem,'') TipoEmbalagem, isnull(B.Superficie,'') Superficie, isnull(B.Decoracao,'') Decoracao, isnull(B.RefCor,'') RefCor,
                                isnull(B.TabEspessura,'') TabEspessura, isnull(E.Tipo,'') Tipo, CAST(ROW_NUMBER() OVER(ORDER BY C.Numero asc)-1 AS int) Id
                        from StkLDocs A join ReferArt     B on (A.RefP='{$refp}' and isnull(A.Lote,'')='{$lote}' and isnull(A.Calibre,'')='{$calibre}' and A.RefP=B.Referencia and A.Artigo=B.Artigo)
                                        join PlDocs       C on (C.Estado='F' and (case when isnull(A.DocPL,'')='' then A.Palete else A.DocPL end)=C.Numero)
                                        join PllDocs      D on (isnull(D.NumeroSerieInferior,'')='{$plano}' and C.Numero=D.NumeroDocumento)
                                   left join NiveisPalete E on (D.NivelPalete=E.Codigo)
                        group by A.Sector, isnull(D.Local,''), A.RefP, A.Artigo, isnull(A.Lote,''), isnull(A.Calibre,''), D.NumeroLinha, C.Numero, D.PaleteOrigem, B.Formato, B.Qual, B.TipoEmbalagem,
                                B.Superficie, B.Decoracao, B.RefCor, B.TabEspessura, case when isnull(B.Unidade,'')='' then 'M2' else B.Unidade end, B.Descricao, E.Descricao, E.Tipo
                        having round(sum(case when A.TipoMovimento<='10' then A.Quantidade else -A.Quantidade end),8)>0
                     ) A";        
        //echo $sql01;
        $query01 = $this->db->query($sql01);        
        $tot  = $query01->result();
        $this->db->close();

        foreach ($tot as $val) {
			$itemcount = $val->nLinhas;
		}

        $data = array();
		$arr = [];
        
        //echo '$itemcount: '. $itemcount;
        $batches = round($itemcount / 3500); // Number of while-loop calls - around 120.
        //echo '$batches: '. $batches;
        set_time_limit(0);
        $this->db->db_pconnect();
        for ($i = 0; $i <= $batches; $i++) {            
            $offset = $i * 3500; // MySQL Limit offset number
            $fetch = $offset + 3500;
            set_time_limit(0);
            $this->db->db_pconnect();    
        
            $sql = "SELECT A.Sector, isnull(D.Local,'') Local, round(sum(case when A.TipoMovimento<='10' then A.Quantidade else -A.Quantidade end),8) Quantidade,
                                case when isnull(B.Unidade,'')='' then 'M2' else B.Unidade end Unidade, A.RefP Referencia, A.Artigo, B.Descricao DescricaoArtigo, isnull(A.Lote,'') Lote,
                                isnull(A.Calibre,'') Calibre, D.NumeroLinha LinhaPL, D.PaleteOrigem DocPL, isnull(B.Formato,'') Formato, isnull(B.Qual,'') Qual,
                                isnull(B.TipoEmbalagem,'') TipoEmbalagem, isnull(B.Superficie,'') Superficie, isnull(B.Decoracao,'') Decoracao, isnull(B.RefCor,'') RefCor,
                                isnull(B.TabEspessura,'') TabEspessura, isnull(E.Tipo,'') Tipo, CAST(ROW_NUMBER() OVER(ORDER BY C.Numero asc)-1 AS int) Id
                    from StkLDocs A join ReferArt     B on (A.RefP='{$refp}' and isnull(A.Lote,'')='{$lote}' and isnull(A.Calibre,'')='{$calibre}' and A.RefP=B.Referencia and A.Artigo=B.Artigo)
                                    join PlDocs       C on (C.Estado='F' and (case when isnull(A.DocPL,'')='' then A.Palete else A.DocPL end)=C.Numero)
                                    join PllDocs      D on (isnull(D.NumeroSerieInferior,'')='{$plano}' and C.Numero=D.NumeroDocumento)
                               left join NiveisPalete E on (D.NivelPalete=E.Codigo)
                    group by A.Sector, isnull(D.Local,''), A.RefP, A.Artigo, isnull(A.Lote,''), isnull(A.Calibre,''), D.NumeroLinha, C.Numero, D.PaleteOrigem, B.Formato, B.Qual, B.TipoEmbalagem,
                            B.Superficie, B.Decoracao, B.RefCor, B.TabEspessura, case when isnull(B.Unidade,'')='' then 'M2' else B.Unidade end, B.Descricao, E.Descricao, E.Tipo
                    having round(sum(case when A.TipoMovimento<='10' then A.Quantidade else -A.Quantidade end),8)>0
                    Order by D.PaleteOrigem desc
                    OFFSET ".$offset." ROWS
                    FETCH NEXT ".$fetch ." ROWS ONLY"; 
            //echo $sql;
            set_time_limit(0);
            $this->db->db_pconnect();                                          
            $query = $this->db->query($sql);
        
            // Verifique se a consulta foi bem-sucedida
            if ($query) {
                $result = $query->result();
                
                // Processa os resultados conforme necessário
                foreach ($result as $row) {
                    $arr['Sector'] = $row->Sector;
                    $arr['Local'] = $row->Local;
                    $arr['Quantidade'] = $row->Quantidade;
                    $arr['Referencia'] = $row->Referencia;
                    $arr['Artigo'] = $row->Artigo;
                    $arr['DescricaoArtigo'] = $row->DescricaoArtigo;
                    $arr['Lote'] = $row->Lote;
                    $arr['Calibre'] = $row->Calibre;                
                    $arr['PaleteOrigem'] = $row->DocPL;
                    $arr['Unidade'] = $row->Unidade;
                    $arr['Id'] = $row->Id;
                    
                    $data[] = $arr;
                    unset($arr);
                }
            } else {
                // Lida com o erro de consulta
                $erro = $this->db->error();
                log_message('error', 'Erro na consulta SQL: ' . $erro['message']);
            }
        
            $this->db->close();
        }
        
        // Remove duplicatas dos dados
        $data = array_unique($data, SORT_REGULAR);
        
        return $data;
        
    }

    public function armazem02($empresa,$condicao){
        //echo $setor;
        $sql01 = "SELECT count(A.id) nLinhas
                  FROM ( SELECT cast(0 as int) Sel, A.Sector, isnull(A.Local,'') Local, round(sum(case when A.TipoMovimento<='10' then A.Quantidade else -A.Quantidade end),8) Quantidade, case when isnull(B.Unidade,'')='' then 'M2' else B.Unidade end Unidade,
                            A.RefP Referencia, A.Artigo, B.Descricao DescricaoArtigo, isnull(A.Lote,'') Lote, isnull(A.Calibre,'') Calibre, D.NumeroLinha LinhaPL, C.Numero DocPL, B.Formato, B.Qual, B.TipoEmbalagem, B.Superficie, B.Decoracao,
                            B.RefCor, B.TabEspessura, isnull(D.NivelPalete,'') Nivel, isnull(C.Serie,'') Serie, D.DataHoraMOV, C.NossaRef, C.VossaRef, CAST(ROW_NUMBER() OVER(ORDER BY C.Numero asc)-1 AS int) Id
                         from StkLDocs A join Sectores X on (A.Sector=X.Codigo and X.Empresa=UPPER('{$empresa}'))
                                         join ReferArt B on (A.RefP=B.Referencia and A.Artigo=B.Artigo)
                                         join PlDocs   C on ((case when isnull(A.DocPL,'')='' then A.Palete else A.DocPL end)=C.Numero)
                                         join PllDocs  D on (C.Numero=D.NumeroDocumento)
                        where C.Serie not in ('C','PC')
                        group by A.Sector, isnull(A.Local,''), A.RefP, A.Artigo, isnull(A.Lote,''), isnull(A.Calibre,''), D.NumeroLinha, C.Numero, B.Formato, B.Qual, B.TipoEmbalagem,
                                 B.Superficie, B.Decoracao, B.RefCor, B.TabEspessura, case when isnull(B.Unidade,'')='' then 'M2' else B.Unidade end, B.Descricao, isnull(D.NivelPalete,''),
                                 isnull(C.Serie,''), D.DataHoraMOV, C.NossaRef, C.VossaRef
                        having round(sum(case when A.TipoMovimento<='10' then A.Quantidade else -A.Quantidade end),8){$condicao}
                     ) A";        
        $query01 = $this->db->query($sql01);        
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
            $sql02 = "SELECT cast(0 as int) Sel, A.Sector, isnull(A.Local,'') Local, round(sum(case when A.TipoMovimento<='10' then A.Quantidade else -A.Quantidade end),8) Quantidade, case when isnull(B.Unidade,'')='' then 'M2' else B.Unidade end Unidade,
                            A.RefP Referencia, A.Artigo, B.Descricao DescricaoArtigo, isnull(A.Lote,'') Lote, isnull(A.Calibre,'') Calibre, D.NumeroLinha LinhaPL, C.Numero DocPL, B.Formato, B.Qual, B.TipoEmbalagem, B.Superficie, B.Decoracao,
                            B.RefCor, B.TabEspessura, isnull(D.NivelPalete,'') Nivel, isnull(C.Serie,'') Serie, D.DataHoraMOV, C.NossaRef, C.VossaRef, CAST(ROW_NUMBER() OVER(ORDER BY C.Numero asc)-1 AS int) Id
                         from StkLDocs A join Sectores X on (A.Sector=X.Codigo and X.Empresa=UPPER('{$empresa}'))
                                         join ReferArt B on (A.RefP=B.Referencia and A.Artigo=B.Artigo)
                                         join PlDocs   C on ((case when isnull(A.DocPL,'')='' then A.Palete else A.DocPL end)=C.Numero)
                                         join PllDocs  D on (C.Numero=D.NumeroDocumento)                      
                      where C.Serie not in ('C','PC')
                      group by A.Sector, isnull(A.Local,''), A.RefP, A.Artigo, isnull(A.Lote,''), isnull(A.Calibre,''), D.NumeroLinha, C.Numero, B.Formato, B.Qual, B.TipoEmbalagem,
                                B.Superficie, B.Decoracao, B.RefCor, B.TabEspessura, case when isnull(B.Unidade,'')='' then 'M2' else B.Unidade end, B.Descricao, isnull(D.NivelPalete,''),
                                isnull(C.Serie,''), D.DataHoraMOV, C.NossaRef, C.VossaRef
                      having round(sum(case when A.TipoMovimento<='10' then A.Quantidade else -A.Quantidade end),8){$condicao}
                      Order by C.Numero ASC
                      OFFSET ".$offset." ROWS
                      FETCH NEXT ".$fetch ." ROWS ONLY";  
            //and isnull(A.Local,'')<>'' 
           // echo $sql02;
            set_time_limit(0);
            $this->db->db_pconnect();                                          
            $query = $this->db->query($sql02);
        
                    // Verifique se a consulta foi bem-sucedida
            if ($query) {
                $result = $query->result();
                        
                        // Processa os resultados conforme necessário
                foreach ($result as $row) {                    
                    $arr['Sector']=$row->Sector;
                    $arr['Local']=$row->Local;
                    $arr['Quantidade']=$row->Quantidade;
                    $arr['Unidade']=$row->Unidade;
                    $arr['Referencia']=$row->Referencia;
                    $arr['Artigo']=$row->Artigo;
                    $arr['DescricaoArtigo']=$row->DescricaoArtigo;
                    $arr['Lote']=$row->Lote;
                    $arr['Calibre']=$row->Calibre;
                    $arr['LinhaPL']=$row->LinhaPL;
                    $arr['DocPL']=$row->DocPL;
                    $arr['Formato']=$row->Formato;
                    $arr['Serie']=$row->Serie;
                    $arr['DataHoraMOV']=$row->DataHoraMOV;
                    $arr['NossaRef']=$row->NossaRef;
                    $arr['VossaRef']=$row->VossaRef;
                    $arr['Id']=$row->Id;

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

    public function reabilitados($setor){
        //echo $setor;
        $sql01 = "SELECT count(A.id) nLinhas
                  FROM ( SELECT cast(0 as int) Sel, A.Sector, isnull(A.Local,'') Local, round(sum(case when A.TipoMovimento<='10' then A.Quantidade else -A.Quantidade end),8) Quantidade, case when isnull(B.Unidade,'')='' then 'M2' else B.Unidade end Unidade, A.RefP Referencia,
                                A.Artigo, B.Descricao DescricaoArtigo, isnull(A.Lote,'') Lote, isnull(A.Calibre,'') Calibre, D.NumeroLinha LinhaPL, C.Numero DocPL, B.Formato, B.Qual, B.TipoEmbalagem, B.Superficie, B.Decoracao, B.RefCor, B.TabEspessura,
                                round(sum(case when A.TipoMovimento<='10' then A.Quantidade else -A.Quantidade end),8) NovaQtd, isnull(D.NivelPalete,'') Nivel, cast(0 as float) Qtd_OK, cast(0 as float) Qtd_NOK, cast(0 as float) Qtd_Caco,
                                cast('' as varchar(50)) RefSeg, cast('' as varchar(255)) DescRefSeg, C.Serie, CAST(ROW_NUMBER() OVER(ORDER BY C.Numero asc)-1 AS int) Id
                         from StkLDocs A join ReferArt B on (A.RefP=B.Referencia and A.Artigo=B.Artigo)
                                         join PlDocs   C on (C.Estado='F' and (case when isnull(A.DocPL,'')='' then A.Palete else A.DocPL end)=C.Numero)
                                         join PllDocs  D on (C.Numero=D.NumeroDocumento)
                        where A.Sector in ({$setor}) and C.Serie not in ('C','PC')
                        group by A.Sector, isnull(A.Local,''), A.RefP, A.Artigo, isnull(A.Lote,''), isnull(A.Calibre,''), D.NumeroLinha, C.Numero,B.Formato, B.Qual, B.TipoEmbalagem, B.Superficie, B.Decoracao,
                                B.RefCor, B.TabEspessura, case when isnull(B.Unidade,'')='' then 'M2' else B.Unidade end, B.Descricao, isnull(D.NivelPalete,''), C.Serie
                        having round(sum(case when A.TipoMovimento<='10' then A.Quantidade else -A.Quantidade end),8)>0
                     ) A";        
        $query01 = $this->db->query($sql01);        
        $tot  = $query01->result();
        foreach ($tot as $val) {
			$itemcount = $val->nLinhas;
		}

        $data = array();
		$arr = [];
        $batches = $itemcount / 3500; // Number of while-loop calls - around 120.

        for ($i = 0; $i <= $batches; $i++) {
            $offset = $i * 3500; // MySQL Limit offset number
            $fetch = $offset + 3500;
            $this->db->db_pconnect(); 
            $sql02 = "SELECT cast(0 as int) Sel, A.Sector, isnull(A.Local,'') Local, round(sum(case when A.TipoMovimento<='10' then A.Quantidade else -A.Quantidade end),8) Quantidade, case when isnull(B.Unidade,'')='' then 'M2' else B.Unidade end Unidade, A.RefP Referencia,
                            A.Artigo, B.Descricao DescricaoArtigo, isnull(A.Lote,'') Lote, isnull(A.Calibre,'') Calibre, D.NumeroLinha LinhaPL, C.Numero DocPL, B.Formato, B.Qual, B.TipoEmbalagem, B.Superficie, B.Decoracao, B.RefCor, B.TabEspessura,
                            isnull(D.NivelPalete,'') Nivel, round(sum(case when A.TipoMovimento<='10' then A.Quantidade else -A.Quantidade end),8) NovaQtd, cast(0 as float) Qtd_OK, cast(0 as float) Qtd_NOK, cast(0 as float) Qtd_Caco,
                            cast('' as varchar(50)) RefSeg, cast('' as varchar(255)) DescRefSeg, C.Serie, CAST(ROW_NUMBER() OVER(ORDER BY C.Numero asc)-1 AS int) Id
                      from StkLDocs A join ReferArt B on (A.RefP=B.Referencia and A.Artigo=B.Artigo)
                                      join PlDocs   C on (C.Estado='F' and (case when isnull(A.DocPL,'')='' then A.Palete else A.DocPL end)=C.Numero)
                                      join PllDocs  D on (C.Numero=D.NumeroDocumento)
                      where A.Sector in ({$setor}) and C.Serie not in ('C','PC')
                      group by A.Sector, isnull(A.Local,''), A.RefP, A.Artigo, isnull(A.Lote,''), isnull(A.Calibre,''), D.NumeroLinha, C.Numero,B.Formato, B.Qual, B.TipoEmbalagem, B.Superficie, B.Decoracao,
                                B.RefCor, B.TabEspessura, case when isnull(B.Unidade,'')='' then 'M2' else B.Unidade end, B.Descricao, isnull(D.NivelPalete,''), C.Serie
                      having round(sum(case when A.TipoMovimento<='10' then A.Quantidade else -A.Quantidade end),8)>0
                      Order by C.Numero ASC 
                      OFFSET ".$offset." ROWS
                      FETCH NEXT ".$fetch ." ROWS ONLY";  
            //echo $sql02;
            $query02 = $this->db->query($sql02);
            $result = $query02->result();
            $this->db->close();

            foreach ($result as $row) {

                $arr['Sel']=$row->Sel;
                $arr['Sector']=$row->Sector;
                $arr['Local']=$row->Local;
                $arr['Quantidade']=$row->Quantidade;
                $arr['Unidade']=$row->Unidade;
                $arr['Referencia']=$row->Referencia;
                $arr['Artigo']=$row->Artigo;
                $arr['DescricaoArtigo']=$row->DescricaoArtigo;
                $arr['Lote']=$row->Lote;
                $arr['Calibre']=$row->Calibre;
                $arr['LinhaPL']=$row->LinhaPL;
                $arr['DocPL']=$row->DocPL;
                $arr['Formato']=$row->Formato;
                $arr['Qual']=$row->Qual;
                $arr['TipoEmbalagem']=$row->TipoEmbalagem;
                $arr['Superficie']=$row->Superficie;
                $arr['Decoracao']=$row->Decoracao;
                $arr['RefCor']=$row->RefCor;
                $arr['TabEspessura']=$row->TabEspessura;
                $arr['Nivel']=$row->Nivel;
                $arr['NovaQtd']=$row->NovaQtd;   
                $arr['Qtd_OK']=$row->Qtd_OK;   
                $arr['Qtd_NOK']=$row->Qtd_NOK; 
                $arr['Qtd_Caco']=$row->Qtd_Caco; 
                $arr['RefSeg']=$row->RefSeg; 
                $arr['DescRefSeg']=$row->DescRefSeg; 
                $arr['Serie']=$row->Serie; 
                $arr['Id']=$row->Id;

                $data[]=$arr;
                unset($arr);
            }
        }        

        $data = array_unique($data, SORT_REGULAR);        
        return $data;
    }

    public function correcao_stocks($setor, $cond){
        //echo $setor;
        $sql01 = "SELECT count(A.id) nLinhas
                  FROM ( SELECT cast(0 as int) Sel, A.Sector, isnull(A.Local,'') Local, round(sum(case when A.TipoMovimento<='10' then A.Quantidade else -A.Quantidade end),8) Quantidade, case when isnull(B.Unidade,'')='' then 'M2' else B.Unidade end Unidade,
                            A.RefP Referencia, A.Artigo, B.Descricao DescricaoArtigo, isnull(A.Lote,'') Lote, isnull(A.Calibre,'') Calibre, 0 LinhaPL, isnull(C.Numero,'') DocPL, B.Formato, B.Qual, B.TipoEmbalagem, B.Superficie, B.Decoracao,
                            B.RefCor, B.TabEspessura, isnull(D.NivelPalete,'') Nivel, cast(0 as float) NovaQtd, CAST(ROW_NUMBER() OVER(ORDER BY isnull(C.Numero,'') asc)-1 AS int) Id
                         from StkLDocs A join ReferArt B on (A.RefP=B.Referencia and A.Artigo=B.Artigo)
                                         join PlDocs   C on ((case when isnull(A.DocPL,'')='' then A.Palete else A.DocPL end)=C.Numero)
                                         join PllDocs  D on (C.Numero=D.NumeroDocumento)
                        where A.Sector in ({$setor}) and C.Serie not in ('C','PC') $cond
                         -- and isnull(A.Local,'')<>''
                        group by A.Sector, isnull(A.Local,''), A.RefP, A.Artigo, isnull(A.Lote,''), isnull(A.Calibre,''), isnull(C.Numero,''), B.Formato, B.Qual, B.TipoEmbalagem,
                                 B.Superficie, B.Decoracao, B.RefCor, B.TabEspessura, case when isnull(B.Unidade,'')='' then 'M2' else B.Unidade end, B.Descricao, isnull(D.NivelPalete,'')                        
                     ) A";        
        $query01 = $this->db->query($sql01);        
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
            $sql02 = "SELECT cast(0 as int) Sel, A.Sector, isnull(A.Local,'') Local, round(sum(case when A.TipoMovimento<='10' then A.Quantidade else -A.Quantidade end),8) Quantidade, case when isnull(B.Unidade,'')='' then 'M2' else B.Unidade end Unidade,
                            A.RefP Referencia, A.Artigo, B.Descricao DescricaoArtigo, isnull(A.Lote,'') Lote, isnull(A.Calibre,'') Calibre, 0 LinhaPL, isnull(C.Numero,'') DocPL, B.Formato, B.Qual, B.TipoEmbalagem, B.Superficie, B.Decoracao,
                            B.RefCor, B.TabEspessura, isnull(D.NivelPalete,'') Nivel, cast(0 as float) NovaQtd, cast(0 as int) Reabilitado, CAST(ROW_NUMBER() OVER(ORDER BY isnull(C.Numero,'') asc)-1 AS int) Id
                      from StkLDocs A join ReferArt B on (A.RefP=B.Referencia and A.Artigo=B.Artigo)
                                      join PlDocs   C on ((case when isnull(A.DocPL,'')='' then A.Palete else A.DocPL end)=C.Numero)
                                      join PllDocs  D on (C.Numero=D.NumeroDocumento)
                      where A.Sector in ({$setor}) and C.Serie not in ('C','PC') $cond
                      group by A.Sector, isnull(A.Local,''), A.RefP, A.Artigo, isnull(A.Lote,''), isnull(A.Calibre,''), isnull(C.Numero,''), B.Formato, B.Qual, B.TipoEmbalagem,
                                B.Superficie, B.Decoracao, B.RefCor, B.TabEspessura, case when isnull(B.Unidade,'')='' then 'M2' else B.Unidade end, B.Descricao, isnull(D.NivelPalete,'')                      
                      Order by isnull(C.Numero,'') ASC
                      OFFSET ".$offset." ROWS
                      FETCH NEXT ".$fetch ." ROWS ONLY";  
            //and isnull(A.Local,'')<>'' 
            //echo $sql02;
            set_time_limit(0);
            $this->db->db_pconnect();                                          
            $query = $this->db->query($sql02);
        
                    // Verifique se a consulta foi bem-sucedida
            if ($query) {
                $result = $query->result();
                        
                        // Processa os resultados conforme necessário
                foreach ($result as $row) {
                    $arr['Sel']=$row->Sel;
                    $arr['Sector']=$row->Sector;
                    $arr['Local']=$row->Local;
                    $arr['Quantidade']=$row->Quantidade;
                    $arr['Unidade']=$row->Unidade;
                    $arr['Referencia']=$row->Referencia;
                    $arr['Artigo']=$row->Artigo;
                    $arr['DescricaoArtigo']=$row->DescricaoArtigo;
                    $arr['Lote']=$row->Lote;
                    $arr['Calibre']=$row->Calibre;
                    $arr['LinhaPL']=$row->LinhaPL;
                    $arr['DocPL']=$row->DocPL;
                    $arr['Formato']=$row->Formato;
                    $arr['Qual']=$row->Qual;
                    $arr['TipoEmbalagem']=$row->TipoEmbalagem;
                    $arr['Superficie']=$row->Superficie;
                    $arr['Decoracao']=$row->Decoracao;
                    $arr['RefCor']=$row->RefCor;
                    $arr['TabEspessura']=$row->TabEspessura;
                    $arr['Nivel']=$row->Nivel;
                    $arr['NovaQtd']=$row->NovaQtd;
                    $arr['Reabilitado']=$row->Reabilitado;
                    $arr['Id']=$row->Id;

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
}