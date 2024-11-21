<?php
ini_set('max_input_time', 0);
ini_set('memory_limit',-1);
ini_set('max_execution_time', 0);

class Lista_Stock extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database('default');
        $this->load->helper('url');
        $this->load->library('session');
        //$this->db->reconnect();
    }

    public function filter_by_sectors($sectors){
        //echo $sectors;
        set_time_limit(0);
        $sql01 = "SELECT count(F.LinhaPL) nLinhas
                  FROM ( SELECT A.Sector, isnull(A.Local,'') Local, round(sum(case when A.TipoMovimento<='10' then A.Quantidade else -A.Quantidade end),8) Quantidade,
                                case when isnull(B.Unidade,'')='' then 'M2' else B.Unidade end Unidade, A.RefP Referencia, A.Artigo, B.Descricao DescricaoArtigo, isnull(A.Lote,'') Lote,
                                isnull(A.Calibre,'') Calibre, D.NumeroLinha LinhaPL, C.Numero DocPL, isnull(B.Formato,'') Formato, isnull(B.Qual,'') Qual,
                                isnull(B.TipoEmbalagem,'') TipoEmbalagem, isnull(B.Superficie,'') Superficie, isnull(B.Decoracao,'') Decoracao, isnull(B.RefCor,'') RefCor,
                                isnull(B.TabEspessura,'') TabEspessura, isnull(E.Tipo,'') Tipo, CAST(ROW_NUMBER() OVER(ORDER BY C.Numero asc)-1 AS int) Id
                         from StkLDocs A join ReferArt     B on (A.RefP=B.Referencia and A.Artigo=B.Artigo)
                                         join PlDocs       C on (C.Estado='F' and (case when isnull(A.DocPL,'')='' then A.Palete else A.DocPL end)=C.Numero)
                                         join PllDocs      D on (C.Numero=D.NumeroDocumento)
                                    left join NiveisPalete E on (D.NivelPalete=E.Codigo)
                         where A.Sector in ({$sectors})
                         group by A.Sector, isnull(A.Local,''), A.RefP, A.Artigo, isnull(A.Lote,''), isnull(A.Calibre,''), D.NumeroLinha, C.Numero, B.Formato, B.Qual, B.TipoEmbalagem,
                                B.Superficie, B.Decoracao, B.RefCor, B.TabEspessura, case when isnull(B.Unidade,'')='' then 'M2' else B.Unidade end, B.Descricao, E.Descricao, E.Tipo
                         having round(sum(case when A.TipoMovimento<='10' then A.Quantidade else -A.Quantidade end),8)>0
                     ) F";          
        
        $query01 = $this->db->query($sql01);        
        $tot  = $query01->result();
        $this->db->close();

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
            $sql = "SELECT A.Sector, isnull(A.Local,'') Local, round(sum(case when A.TipoMovimento<='10' then A.Quantidade else -A.Quantidade end),8) Quantidade,
                                case when isnull(B.Unidade,'')='' then 'M2' else B.Unidade end Unidade, A.RefP Referencia, A.Artigo, B.Descricao DescricaoArtigo, isnull(A.Lote,'') Lote,
                                isnull(A.Calibre,'') Calibre, D.NumeroLinha LinhaPL, C.Numero DocPL, isnull(B.Formato,'') Formato, isnull(B.Qual,'') Qual,
                                isnull(B.TipoEmbalagem,'') TipoEmbalagem, isnull(B.Superficie,'') Superficie, isnull(B.Decoracao,'') Decoracao, isnull(B.RefCor,'') RefCor,
                                isnull(B.TabEspessura,'') TabEspessura, isnull(E.Tipo,'') Tipo, CAST(ROW_NUMBER() OVER(ORDER BY C.Numero asc)-1 AS int) Id
                    from StkLDocs A join ReferArt     B on (A.RefP=B.Referencia and A.Artigo=B.Artigo)
                                         join PlDocs       C on (C.Estado='F' and (case when isnull(A.DocPL,'')='' then A.Palete else A.DocPL end)=C.Numero)
                                         join PllDocs      D on (C.Numero=D.NumeroDocumento)
                                    left join NiveisPalete E on (D.NivelPalete=E.Codigo)
                    where A.Sector in ({$sectors})
                    group by A.Sector, isnull(A.Local,''), A.RefP, A.Artigo, isnull(A.Lote,''), isnull(A.Calibre,''), D.NumeroLinha, C.Numero, B.Formato, B.Qual, B.TipoEmbalagem,
                               B.Superficie, B.Decoracao, B.RefCor, B.TabEspessura, case when isnull(B.Unidade,'')='' then 'M2' else B.Unidade end, B.Descricao, E.Descricao, E.Tipo
                    having round(sum(case when A.TipoMovimento<='10' then A.Quantidade else -A.Quantidade end),8)>0
                    Order by C.Numero ASC 
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

    public function  filter_by_pallet($palete){

        $sql="SELECT A.Sector, isnull(A.Local,'') Local, case when A.TipoMovimento<='10' then round(A.Quantidade,2) else round(-A.Quantidade,2) end Quantidade, A.RefP Referencia, B.Descricao, A.Lote, A.Calibre,
                    A.DataHoraMOV, A.OperadorMOV, A.NumeroDocumento, A.NumeroLinha, D.Descricao DescricaoNivel, D.Tipo Nivel,
                    round(sum(case when A.TipoMovimento<='10' then A.Quantidade else -A.Quantidade end) over (order by A.DataHoraMOV, A.NumeroLInha ROWS between unbounded preceding and current row),2) Acumulado,
                    row_number() over (order by A.Palete, A.LinhaPL, A.DataHoraMOV, A.NumeroLinha) Ordem
              from StkLDocs A join ReferArt     B on (A.RefP=B.Referencia)
                              join PlDocs       C on (C.Estado='F' and (case when isnull(A.DocPL,'')='' then A.Palete else A.DocPL end)=C.Numero)
                              join PllDocs      E on (C.Numero=E.NumeroDocumento)
                         left join NiveisPalete D on (E.NivelPalete=D.Codigo)
              where C.Numero='{$palete}'";  
        //echo $sql; 
        $query = $this->db->query($sql);
        $result = $query->result();
        $this->db->close();
        return $result;
    }

}