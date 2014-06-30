<?php

//chdir('C:/xampp/htdocs/AlfaSeed/');

include ("estacoes.php");
include ("calculos.php");
include ("culturas.php");
include ("solos.php");

class principal {
    /* construtor possibilita atualização do arquivo que forma mapa de estacoes */

    function principal($mapa = NULL) {
        if (isset($mapa)) {
            $this->get_json_estacoes();
        }
    }

    /* retorna estacoes disponiveis no sistema */

    function get_estacoes($estacao_id = NULL) {
        $estacoes = new Estacoes();
        $resultado = $estacoes->get_estacoes($estacao_id);
        return $resultado;
    }

    function get_culturas($cultura_id = NULL) {
        $culturas = new Culturas();
        return $culturas->busca_culturas($cultura_id);
    }

    function get_solos($solo_id = NULL) {
        $solos = new Solos();
        return $solos->busca_solos($solo_id);
    }

    function serialize($resultados, $chave, $is_string) {

        foreach ($resultados as $resultado) {
            $data = $resultado['data'];
            if ($chave == 'kc') {
                $values[] = $resultado['etc'] / $resultado['eto'];
                $serializado[] = "[Date.UTC(" . date('Y', strtotime($data)) . "," . (date('n', strtotime($data)) - 1) . "," . date('j', strtotime($data)) . "), " . $resultado['etc'] / $resultado['eto'] . "]";
            } else {
                if ($is_string) {
                    $values[] = $resultado["$chave"];
                    $serializado[] = '\'' . $resultado["$chave"] . '\'';
                } else {
                    $values[] = $resultado["$chave"];
                    $serializado[] = $serializado[] = "[Date.UTC(" . date('Y', strtotime($data)) . "," . (date('n', strtotime($data)) - 1) . "," . date('j', strtotime($data)) . "), " . round($resultado["$chave"], 2) . "]";
                }
            }
        }
        return array(implode(',', $serializado), min($values), max($values));
    }

    function serialize_gda($resultados) {
        foreach ($resultados as $resultado) {
            $data = $resultado['data'];
            $values[] = $resultado['GD_acumulado'];
            $serializado[] = "[Date.UTC(" . date('Y', strtotime($data)) . "," . (date('n', strtotime($data)) - 1) . "," . date('j', strtotime($data)) . "), " . $resultado["GD_acumulado"] . "]";
        }
        return array(implode(',', $serializado), min($values), max($values));
    }

    function serialize_kc($resultados) {
        foreach ($resultados as $resultado) {
            $data = $resultado['data'];
            $values[] = $resultado["etc"] / $resultado['eto'];
            $serializado[] = "[Date.UTC(" . date('Y', strtotime($data)) . "," . (date('n', strtotime($data)) - 1) . "," . date('j', strtotime($data)) . "), " . $resultado["etc"] / $resultado['eto'] . "]";
        }
        return array(implode(',', $serializado), min($values), max($values));
    }

    /* busca dados para todas as estacoes */

    function baixa_dados_todas_estacoes() {
        $estacoes = new Estacoes();
        $estacoes->baixa_dados_estacoes();
    }

    /* busca estacoes disponiveis no sistema e retorna no formato json */

    function get_json_estacoes($estacao_id = NULL) {
        $resultado = $this->get_estacoes($estacao_id);
        $json = json_encode($resultado);
        $arquivo = fopen('../js/mapas/pontos.json', 'w+');
        fwrite($arquivo, $json);
        fclose($arquivo);
        //return $json;
    }

    function busca_estagio($estagio_alias) {
        switch ($estagio_alias) {
            case 'inicial' : return _('Inicial');
            case 'development' : return _('Desenvolvimento');
            case 'mid-season' : return _('Intermediário');
            case 'late-season' : return _('Final');
            case 'mature' : return _('Encerrado');
        }
    }

    function busca_cor_resultado($resultado) {
        $color = $resultado['etc'] < 1.1 ? '404040' : 'FFFFFF';
        $etc = ($resultado['etc']) * 100;

        $fator = ceil(255 - $etc);
        if ($fator > 175) {
            $fator = 175;
        }
        if ($fator <= 0) {
            $fator = 0;
        }
        return "style='color: #$color; background-color: rgb(" . ($fator + 14) . "," . ($fator + 80) . "," . ($fator + 35) . ")'";
    }

    function salvar_nova_cultura($dados_cultura) {
        $culturas = new Culturas();
        return $culturas->salva_nova_cultura($dados_cultura);
    }

    /* busca por dados das estacoes no formato json */

    function get_json_dados_estacoes($estacao_id, $data_inicial, $data_final) {
        $estacoes = new Estacoes();
        $dados_climaticos = $estacoes->busca_dados_por_periodo($estacao_id, $data_inicial, $data_final);
        return json_encode($dados_climaticos);
    }

    function baixa_dados($estacao_id, $data_inicial) { //baixa dados para a estacao, a partir da data inicial até o dia atual
        $estacoes = new Estacoes();
        $estacoes->baixa_dados_INMET($estacao_id, $data_inicial);
    }

    function busca_resultados($cultura_id, $estacao_id, $data_inicial, $data_final = NULL) {
        $culturas = new Culturas();
        $cultura = $culturas->busca_culturas($cultura_id);
        $estacoes = new Estacoes();
        $medias = $estacoes->busca_medias_diarias($estacao_id, $data_inicial, $data_final);
        $calculo = new Calculo();
        $dados = $calculo->calcula_resultados($medias, $cultura[0]);
        return $dados;
    }

}
