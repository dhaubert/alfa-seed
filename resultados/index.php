<?php
//include_once('../controller/calculos.php');
include_once('C:/xampp/htdocs/Alfaseed/controller/principal.php');
//FAZER GRAFICO DE BARRAS E PONTOS (X = TEMPERATURA E Y É UMIDADE)
//FAZER GRAFICO DE CHUVA PARA AS DATAS
//FAZER GRAFICO PARA ETC E GRAUS DIA
//Exibir tabela com todos os dados
$main = new principal();
//$main->baixa_dados_todas_estacoes();
$estacao_id = 'A427';

$cultura_id = '28';//tomate
$data_inicial = '2014-05-01';
$data_final = '2014-06-10';
echo $main->baixa_dados_todas_estacoes();
//$resultados = $main->busca_resultados($cultura_id, $estacao_id, $data_inicial, $data_final);

echo "<pre>";
print_r($resultados);
echo "</pre>";

