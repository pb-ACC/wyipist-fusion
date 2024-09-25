<?php
ini_set('memory_limit','256M'); // This also needs to be increased in some cases. Can be changed to a higher value as per need)
ini_set('sqlsrv.ClientBufferMaxKBSize','524288'); // Setting to 512M
ini_set('pdo_sqlsrv.client_buffer_max_kb_size','524288'); // Setting to 512M - for pdo_sqlsrv
class GetZonas extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database('default');
        $this->load->helper('url');
        $this->load->library('session');
    }

    public function zonaCelula($empresa,$emp,$setor,$sigla){

        if($emp == 'CERTECA'){
            $sql = "SELECT Codigo,Sector,Zona,Celula,Fila,Posicao,case when Empresa='CERAGNI' then Codigo else CONCAT(Zona,Celula) end CodigoBarras, cast(0 as int) Sel,
                       Identificador, (row_number() over(order by Codigo asc)-1) id              
                FROM zx_Locais 
                WHERE Empresa in ({$empresa}) and Sector in ({$setor}) and isnull(Zona,'')<>'' and isnull(Celula,'') not in ('','*')";
        }
        else{
            $sql = "SELECT A.Codigo,B.Codigo Sector,A.Zona,A.Celula,A.Fila,A.Posicao,case when A.Empresa='CERAGNI' then A.Codigo else CONCAT(A.Zona,A.Celula) end CodigoBarras, 
                        cast(0 as int) Sel,case when A.Empresa='CERAGNI' then 'CT' else substring(A.Sector,1,2) end Identificador, (row_number() over(order by A.Codigo asc)-1) id              
                    FROM zx_Locais A join Sectores B on (A.Empresa=B.Empresa and A.Empresa in ({$empresa}) and B.Empresa in ({$empresa}) )
                    where B.Codigo in ({$setor})";
        }
            //         --case when Empresa='CERAGNI' then substring(Codigo,1,2) else substring(Sector,1,2) end Identificador          case when Empresa='CERAGNI' then Zona else isnull(Zona,'')<>'' end,
                      //case when Empresa='CERAGNI' then Celula else isnull(Celula,'') not in ('','*') end";
                
       // echo $sql;                
        // Executa a consulta
        $query = $this->db->query($sql);

        // Verifica se houve erro na consulta
        if (!$query) {
            // Exibe o erro do banco de dados para depuração
            $error = $this->db->error();
            echo "Erro na consulta SQL: " . $error['message'];
            return false; // Retorna falso ou outro valor apropriado
        }

        // Se não houve erro, retorna os resultados
        $result = $query->result();
        return $result;
    }

}
