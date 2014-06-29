<?php

switch ($_POST['request']) {
    case 'salvar_clima_inmet': {
            include_once ('principal.php');
            $main = new principal();
            $main->baixa_dados($_POST['estacao_id'], $_POST['data_inicial']);
            die;
        }
    case 'atualizar_mapa': {
            include_once ('principal.php');
            $main = new principal();
            $main->get_json_estacoes($_POST['estacao_id']);
            die;
        }
    case 'salvar_nova_cultura' : {
            include_once ('principal.php');
            $main = new principal();
            $cultura_id = $main->salvar_nova_cultura($_POST);
            if($cultura_id){
                echo "OK::A cultura ". $_POST['nome_cultura']. " foi salva com sucesso.::$cultura_id";
            }
            else{
                echo "ERRO::Houve um erro ao salvar a cultura.::0";
            }
            die;
        }
}



