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
                                isnull(A.NivelPalete,'') Nivel, CAST(ROW_NUMBER() OVER(ORDER BY C.Numero asc)-1 AS int) Id
                         from StkLDocs A join ReferArt B on (A.RefP=B.Referencia and A.Artigo=B.Artigo)
                                         join PlDocs   C on (C.Estado='F' and (case when isnull(A.DocPL,'')='' then A.Palete else A.DocPL end)=C.Numero)
                                         join PllDocs  D on (C.Numero=D.NumeroDocumento)
                        where A.Sector in ({$setor})
                        group by A.Sector, A.RefP, A.Artigo, isnull(A.Lote,''), isnull(A.Calibre,''), D.NumeroLinha, C.Numero,B.Formato, B.Qual, B.TipoEmbalagem, B.Superficie, B.Decoracao,
                                B.RefCor, B.TabEspessura, case when isnull(B.Unidade,'')='' then 'M2' else B.Unidade end, B.Descricao, isnull(A.NivelPalete,'')
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
                            isnull(A.NivelPalete,'') Nivel, CAST(ROW_NUMBER() OVER(ORDER BY C.Numero asc)-1 AS int) Id
                      from StkLDocs A join ReferArt B on (A.RefP=B.Referencia and A.Artigo=B.Artigo)
                                      join PlDocs   C on (C.Estado='F' and (case when isnull(A.DocPL,'')='' then A.Palete else A.DocPL end)=C.Numero)
                                      join PllDocs  D on (C.Numero=D.NumeroDocumento)
                      where A.Sector in ({$setor})
                      group by A.Sector, A.RefP, A.Artigo, isnull(A.Lote,''), isnull(A.Calibre,''), D.NumeroLinha, C.Numero,B.Formato, B.Qual, B.TipoEmbalagem, B.Superficie, B.Decoracao,
                                B.RefCor, B.TabEspessura, case when isnull(B.Unidade,'')='' then 'M2' else B.Unidade end, B.Descricao, isnull(A.NivelPalete,'')
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
                $arr['Id']=$row->Id;

                $data[]=$arr;
                unset($arr);
            }
        }        

        $data = array_unique($data, SORT_REGULAR);        
        return $data;
    }
    
    public function armazem($setor){
        //echo $setor;
        $sql01 = "SELECT count(A.id) nLinhas
                  FROM ( SELECT cast(0 as int) Sel, A.Sector, isnull(A.Local,'') Local, round(sum(case when A.TipoMovimento<='10' then A.Quantidade else -A.Quantidade end),2) Quantidade, case when isnull(B.Unidade,'')='' then 'M2' else B.Unidade end Unidade,
                            A.RefP Referencia, A.Artigo, B.Descricao DescricaoArtigo, isnull(A.Lote,'') Lote, isnull(A.Calibre,'') Calibre, D.NumeroLinha LinhaPL, C.Numero DocPL, B.Formato, B.Qual, B.TipoEmbalagem, B.Superficie, B.Decoracao,
                            B.RefCor, B.TabEspessura, isnull(A.NivelPalete,'') Nivel, CAST(ROW_NUMBER() OVER(ORDER BY C.Numero asc)-1 AS int) Id
                         from StkLDocs A join ReferArt B on (A.RefP=B.Referencia and A.Artigo=B.Artigo)
                                         join PlDocs   C on (C.Estado='F' and (case when isnull(A.DocPL,'')='' then A.Palete else A.DocPL end)=C.Numero)
                                         join PllDocs  D on (C.Numero=D.NumeroDocumento)
                        where A.Sector in ({$setor})
                         -- and isnull(A.Local,'')<>''
                        group by A.Sector, isnull(A.Local,''), A.RefP, A.Artigo, isnull(A.Lote,''), isnull(A.Calibre,''), D.NumeroLinha, C.Numero, B.Formato, B.Qual, B.TipoEmbalagem,
                                 B.Superficie, B.Decoracao, B.RefCor, B.TabEspessura, case when isnull(B.Unidade,'')='' then 'M2' else B.Unidade end, B.Descricao, isnull(A.NivelPalete,'')
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
            $sql02 = "SELECT cast(0 as int) Sel, A.Sector, isnull(A.Local,'') Local, round(sum(case when A.TipoMovimento<='10' then A.Quantidade else -A.Quantidade end),2) Quantidade, case when isnull(B.Unidade,'')='' then 'M2' else B.Unidade end Unidade,
                            A.RefP Referencia, A.Artigo, B.Descricao DescricaoArtigo, isnull(A.Lote,'') Lote, isnull(A.Calibre,'') Calibre, D.NumeroLinha LinhaPL, C.Numero DocPL, B.Formato, B.Qual, B.TipoEmbalagem, B.Superficie, B.Decoracao,
                            B.RefCor, B.TabEspessura, isnull(A.NivelPalete,'') Nivel, CAST(ROW_NUMBER() OVER(ORDER BY C.Numero asc)-1 AS int) Id
                      from StkLDocs A join ReferArt B on (A.RefP=B.Referencia and A.Artigo=B.Artigo)
                                      join PlDocs   C on (C.Estado='F' and (case when isnull(A.DocPL,'')='' then A.Palete else A.DocPL end)=C.Numero)
                                      join PllDocs  D on (C.Numero=D.NumeroDocumento)
                      where A.Sector in ({$setor}) 
                      group by A.Sector, isnull(A.Local,''), A.RefP, A.Artigo, isnull(A.Lote,''), isnull(A.Calibre,''), D.NumeroLinha, C.Numero, B.Formato, B.Qual, B.TipoEmbalagem,
                                B.Superficie, B.Decoracao, B.RefCor, B.TabEspessura, case when isnull(B.Unidade,'')='' then 'M2' else B.Unidade end, B.Descricao, isnull(A.NivelPalete,'')
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
                  FROM ( SELECT A.Sector, isnull(A.Local,'') Local, round(sum(case when A.TipoMovimento<='10' then A.Quantidade else -A.Quantidade end),2) Quantidade,
                                case when isnull(B.Unidade,'')='' then 'M2' else B.Unidade end Unidade, A.RefP Referencia, A.Artigo, B.Descricao DescricaoArtigo, isnull(A.Lote,'') Lote,
                                isnull(A.Calibre,'') Calibre, D.NumeroLinha LinhaPL, C.Numero DocPL, isnull(B.Formato,'') Formato, isnull(B.Qual,'') Qual,
                                isnull(B.TipoEmbalagem,'') TipoEmbalagem, isnull(B.Superficie,'') Superficie, isnull(B.Decoracao,'') Decoracao, isnull(B.RefCor,'') RefCor,
                                isnull(B.TabEspessura,'') TabEspessura, isnull(E.Tipo,'') Tipo, CAST(ROW_NUMBER() OVER(ORDER BY C.Numero asc)-1 AS int) Id
                         from StkLDocs A join Sectores     X on (A.Sector=X.Codigo and X.Empresa=UPPER('{$empresa}'))
                                         join ReferArt     B on (A.RefP=B.Referencia and A.Artigo=B.Artigo)
                                         join PlDocs       C on (C.Estado='F' and (case when isnull(A.DocPL,'')='' then A.Palete else A.DocPL end)=C.Numero)
                                         join PllDocs      D on (C.Numero=D.NumeroDocumento)
                                    left join NiveisPalete E on (A.NivelPalete=E.Codigo)
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
        
            $sql = "SELECT A.Sector, isnull(A.Local,'') Local, round(sum(case when A.TipoMovimento<='10' then A.Quantidade else -A.Quantidade end),2) Quantidade,
                           case when isnull(B.Unidade,'')='' then 'M2' else B.Unidade end Unidade, A.RefP Referencia, A.Artigo, B.Descricao DescricaoArtigo, isnull(A.Lote,'') Lote,
                           isnull(A.Calibre,'') Calibre, D.NumeroLinha LinhaPL, C.Numero DocPL, isnull(B.Formato,'') Formato, isnull(B.Qual,'') Qual,
                           isnull(B.TipoEmbalagem,'') TipoEmbalagem, isnull(B.Superficie,'') Superficie, isnull(B.Decoracao,'') Decoracao, isnull(B.RefCor,'') RefCor,
                           isnull(B.TabEspessura,'') TabEspessura, isnull(E.Tipo,'') Tipo, CAST(ROW_NUMBER() OVER(ORDER BY C.Numero asc)-1 AS int) Id
                    from StkLDocs A join Sectores     X on (A.Sector=X.Codigo and X.Empresa=UPPER('{$empresa}'))
                                    join ReferArt     B on (A.RefP=B.Referencia and A.Artigo=B.Artigo)
                                    join PlDocs       C on (C.Estado='F' and (case when isnull(A.DocPL,'')='' then A.Palete else A.DocPL end)=C.Numero)
                                    join PllDocs      D on (C.Numero=D.NumeroDocumento)
                               left join NiveisPalete E on (A.NivelPalete=E.Codigo)
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

}