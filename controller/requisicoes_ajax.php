<?php

chdir('../');
switch ($_POST['request']) {
    case 'salvar_clima_inmet': {
            include_once ('controller/principal.php');
            $main = new principal();
            $main->baixa_dados($_POST['estacao_id'], $_POST['data_inicial']);
            die;
        }
    case 'atualizar_mapa':{
        include_once ('controller/principal.php');
        $main = new principal();
        $main->get_json_estacoes($_POST['estacao_id']);
        die;
    }
}



