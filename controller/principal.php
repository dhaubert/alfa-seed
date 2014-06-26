<?php
chdir('C:/xampp/htdocs/AlfaSeed/');

include ("estacoes.php");
include ("calculos.php");
include ("culturas.php");

class principal{
    /* construtor possibilita atualização do arquivo que forma mapa de estacoes */
    function principal($mapa = NULL){
        if(isset($mapa)){
            $this->get_json_estacoes();
        }
    }
    /* retorna estacoes disponiveis no sistema */
    function get_estacoes($estacao_id = NULL){
        $estacoes = new Estacoes();
        $resultado = $estacoes->get_estacoes($estacao_id);
        return $resultado;
    }
    function get_culturas($estacao_id = NULL){
        $culturas = new Culturas();
        return $culturas->busca_culturas($cultura_id);
    }
    /* busca dados para todas as estacoes */
    function baixa_dados_todas_estacoes(){
        $estacoes = new Estacoes();
        $estacoes->baixa_dados_estacoes();
    }
    /* busca estacoes disponiveis no sistema e retorna no formato json */
    function get_json_estacoes($estacao_id = NULL){
        $resultado = $this->get_estacoes($estacao_id);
        $json = json_encode($resultado);
        $arquivo = fopen('./js/mapas/pontos.json', 'w+');
        fwrite($arquivo, $json);
        fclose($arquivo);
        //return $json;
    }
    /* busca por dados das estacoes no formato json */
    function get_json_dados_estacoes($estacao_id, $data_inicial, $data_final){
        $estacoes = new Estacoes();
        $dados_climaticos = $estacoes->busca_dados_por_periodo($estacao_id, $data_inicial, $data_final);
        return json_encode($dados_climaticos);
    }
    function baixa_dados($estacao_id, $data_inicial){ //baixa dados para a estacao, a partir da data inicial até o dia atual
        $estacoes = new Estacoes();
        $estacoes->baixa_dados_INMET($estacao_id, $data_inicial);
    }
    function busca_resultados($cultura_id, $estacao_id, $data_inicial, $data_final = NULL){
        $culturas = new Culturas();
        $cultura = $culturas->busca_culturas($cultura_id);
        $estacoes = new Estacoes();
        $medias = $estacoes->busca_medias_diarias($estacao_id, $data_inicial, $data_final);
        $calculo = new Calculo();
        $dados = $calculo->calcula_resultados($medias, $cultura[0]);
        return $dados;
    }
}
