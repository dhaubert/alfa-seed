<?php
include ("../model/dados_estacoes.php");
include ("inmet.php");

class Estacoes{
    /* lista estacoes */
    function get_estacoes($estacao_id = NULL){
        $estacoes = new dados_estacoes();
        $resultado = $estacoes->lista_estacoes_INMET($estacao_id);
        return $resultado;
    }
    /* baixa dados do Instituto Nacional de Meteorologia */
    function baixa_dados_INMET($estacao_id, $data_inicial){
        $data_final = date("d/m/Y");
        $inmet = new INMET();
        $estacoes = new dados_estacoes();
        $dados_estacao = $estacoes->busca_dados_estacao($estacao_id);
        $dados = $inmet->busca_dados_INMET($dados_estacao, $data_inicial, $data_final);
        $dados_filtrados = $inmet->filtra_dados($dados, $dados_estacao);
        $inmet->insere_dados($dados_estacao, $dados_filtrados);
        
    }
    /* busca dados de determinada estacao em determinado periodo */
    function busca_dados_por_periodo($estacao_id, $data_inicial, $data_final = NULL){
        $dados_estacoes = new dados_estacoes();
        $dados = $dados_estacoes->busca_dados_climaticos($estacao_id, $data_inicial, $data_final);
        return $dados;
    }
    /* Consulta por medias diarias de um periodo */
    function busca_medias_diarias($estacao_id, $data_inicial, $data_final = NULL){
        $dados_estacoes = new dados_estacoes();
        $dados = $dados_estacoes->busca_medias_diarias($estacao_id, $data_inicial, $data_final);
        return $dados;
    }
    /* Tarefa de rotina para atualização da base de dados de estações*/
    function baixa_dados_estacoes(){ 
        echo "Iniciando download de dados <br/>";
        $dados_estacoes = new dados_estacoes();
        $estacoes = $dados_estacoes->lista_estacoes_INMET();
        foreach($estacoes as $estacao){ //percorre estações
            for($i = 90; $i > 0; $i--){ //percorre datas
                $data_BR = date('d/m/Y', strtotime("-$i days"));
                $data = date('Y-m-d', strtotime("-$i days"));
                $estacao_id = $estacao['estacao_id'];
                $dados = $this->busca_dados_por_periodo($estacao_id, $data, $data);
                if (count($dados) < 24) {
                    echo "(".count($dados).") Baixando dados da estacao {$estacao_id} para a data $data <br/>";
                    $this->baixa_dados_INMET($estacao_id, $data_BR, $data_BR);
                }
                else{
                    echo "Estação <$estacao_id> está atualizada para o dia $data (".count($dados)." dados) <br/>";
                }
            }
            $data = date('d/m/Y');
            $this->baixa_dados_INMET($estacao_id, $data, $data); //baixa para hoje
        }
        echo "Finalizado <br/>";
    }
    
    
}
?>

