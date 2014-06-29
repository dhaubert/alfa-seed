<?php
set_time_limit(10000);

class INMET {

    private $host, $caminho;
    private $UTC;
    
    /* inicializa hora local, host e o caminho do arquivo de extração */
    function INMET($host = null, $path = null) {
        if (!isset($host)) {
            $this->host = '200.252.242.20';
        }
        if (!isset($path)) {
            $this->caminho = '/sonabra/pg_dspDadosCodigo.php?';
        }
        //horario de verao
        $this->UTC = (date("I", strtotime("now")))? 2: 3;
        
    }
    function numero_de_horas($data_inicial, $data_final){
        $data = explode('/',$data_inicial);
        $data_inicial = $data[2] . '-' . $data[1] . '-' . $data[0];
        
//        $data = explode('/',$data_final);
//        $data_final = $data[2] . '-' . $data[1] . '-' . $data[0];
        $data_final = date('Y-m-d H:i:s');
        $nhoras = (int) ceil((strtotime($data_final) - strtotime($data_inicial)) / (60 * 60 ));
        return $nhoras;
    }
    /* busca nome do arquivo e organiza os dados */
    function busca_dados_INMET($estacao, $data_inicial, $data_final) {
        $contents = $this->get_file($estacao, $data_inicial, $data_final);
        $html = explode('</tr>', $contents);
        $html = array_map('trim', $html);
        $dados = array_filter(array_map("inmet::extrai_tabela", $html));
        $nhoras = $this->numero_de_horas($data_inicial, $data_final);
        
        if(count($dados) > 0){
            $porcentagem = round(count($dados)*100 / $nhoras, 2);
            echo "OK::"._("Foram obtidos"). " " . count($dados) . " " . _("dados horários.").' '. _("Precisão de").  " $porcentagem%.\n";
        }
        else{
            echo "ERRO::" . _("Não foi possível buscar dados para este múnicipio.")."\n";
        }
        $ret = array();
        foreach ($dados as $elem) {
            $hora = $elem['hora'];
            $date = $elem['data'];
            $time = strtotime($date . " " . $hora . ":00"); //yyyy-mm-dd hh:ii:ss
            $time_diff = $this->UTC * 60 * 60; //fuso horario
            $sub = $time - $time_diff;
            $elem['data'] = date("Y-m-d", $sub);
            $elem['hora'] = date("H:i", $sub);
            $ret[] = $elem;
        }
        return $ret;
    }

    /* busca conteudo do arquivo para os parametros no host inmet.gov.br */
    function get_file($estacao, $data_inicial, $data_final) {
        $post_data = http_build_query(
                array(
                    'aleaValue' => 'MDgxNw==',
                    'dtaini' => $data_inicial,
                    'dtafim' => $data_final,
                    'aleaNum' => '0817',
                )
        );
        $context = stream_context_create(array(
            'http' => array(
                'method' => 'POST',
                'content' => $post_data,
        )));
        $uri = "http://" . $this->host . $this->caminho . $estacao['codigo'];
        $contents = file_get_contents($uri, null, $context);
        return $contents;
    }

    /* extrai apenas os valores da tabela para cada linha correspondente */
    function extrai_tabela($linha) {
        $td_ini = '<td[^>]*><span[^>]*>';
        $td_fim = '</span></td>';
        $regex_tabela = $td_ini . '([0-9][0-9]/[0-1][0-9]/[12][0-9][0-9][0-9])' . $td_fim .
                $td_ini . '([0-9]+)' . $td_fim .
                $td_ini . '(-?[0-9]+\.?([0-9]+)?)' . $td_fim .
                $td_ini . '(-?[0-9]+\.?([0-9]+)?)' . $td_fim .
                $td_ini . '(-?[0-9]+\.?([0-9]+)?)' . $td_fim .
                $td_ini . '(-?[0-9]+\.?([0-9]+)?)' . $td_fim .
                $td_ini . '(-?[0-9]+\.?([0-9]+)?)' . $td_fim .
                $td_ini . '(-?[0-9]+\.?([0-9]+)?)' . $td_fim .
                $td_ini . '(-?[0-9]+\.?([0-9]+)?)' . $td_fim .
                $td_ini . '(-?[0-9]+\.?([0-9]+)?)' . $td_fim .
                $td_ini . '(-?[0-9]+\.?([0-9]+)?)' . $td_fim .
                $td_ini . '(-?[0-9]+\.?([0-9]+)?)' . $td_fim .
                $td_ini . '(-?[0-9]+\.?([0-9]+)?)' . $td_fim .
                $td_ini . '(-?[0-9]+\.?([0-9]+)?)' . $td_fim .
                $td_ini . '(-?[0-9]+\.?([0-9]+)?)' . $td_fim .
                $td_ini . '(-?[0-9]+\.?([0-9]+)?).' . $td_fim .
                $td_ini . '(-?[0-9]+\.?([0-9]+)?)' . $td_fim .
                $td_ini . '(-?[0-9]+\.?([0-9]+)?)' . $td_fim .
                $td_ini . '(-?[0-9]+\.?([0-9]+)?)' . $td_fim;

        $resultado = array();
        if (preg_match('|' . $regex_tabela . '|', $linha, $resultado)) {
            $date = preg_replace('|([0-9]+)/([0-9]+)/([0-9]+)|', '$3-$2-$1', $resultado[1]);
            $retorno = array(
                'data' => $date,
                'hora' => $resultado[2] . ':00',
                'temperatura_ar' => $resultado[3],
                'temperatura_maxima' => $resultado[5],
                'temperatura_minima' => $resultado[7],
                'umidade' => $resultado[9],
                'umidade_maxima' => $resultado[11],
                'umidade_minima' => $resultado[13],
                'pressao' => $resultado[21],
                'velocidade_vento' => $resultado[27],
                'radiacao' => $resultado[33],
                'precipitacao' => $resultado[35],
                'insolacao' => $resultado[33] > 30 ? 1 : 0,
            );
            return $retorno;
        }
        return NULL;
    }

    /* Prepara os dados para inserção no banco de dados */
    function filtra_dados($dados, $d) {
        $i = 0;
        while ($i < count($dados[$i])) {
            if ($dados[$i]['temperatura_ar'] < -5 || $dados[$i]['temperatura_ar'] > 45) {
                $dados[$i]['temperatura_ar'] = "null";
            }
            if ($dados[$i]['temperatura_maxima'] < -5 || $dados[$i]['temperatura_maxima'] > 45) {
                $dados[$i]['temperatura_maxima'] = "null";
            }
            if ($dados[$i]['temperatura_minima'] < -5 ||
                    $dados[$i]['temperatura_minima'] > 45) {
                $dados[$i]['temperatura_minima'] = "null";
            }
            if ($dados[$i]['umidade'] < 0 || $dados[$i]['umidade'] > 115) {
                $dados[$i]['umidade'] = "null";
            } else {
                if ($dados[$i]['umidade'] > 100 && $dados[$i]['umidade'] <= 115) {
                    $dados[$i]['umidade'] = 100;
                }
            }
            if ($dados[$i]['pressao'] < 300 || $dados[$i]['pressao'] > 1900) {
                $dados[$i]['pressao'] = "null";
            } else {
                $dados[$i]['pressao'] = $dados[$i]['pressao'] / 1.33;
            }
            $dados[$i]['velocidade_vento'] = $dados[$i]['velocidade_vento'] * 0.79;
            if ($dados[$i]['velocidade_vento'] < 0 || $dados[$i]['velocidade_vento'] > 30) {
                $dados[$i]['velocidade_vento'] = "null";
            }
            if ($dados[$i]['velocidade_vento'] == 0) {
                $dados[$i]['velocidade_vento'] = "0.0";
            }
            if ($dados[$i]['precipitacao'] < 0 || $dados[$i]['precipitacao'] > 100) {
                $dados[$i]['precipitacao'] = "null";
            }
            $dados[$i]['radiacao'] = $dados[$i]['radiacao'] / 3.6;
            if ($dados[$i]['radiacao'] < 0 || $dados[$i]['radiacao'] > 2000) {
                $dados[$i]['radiacao'] = "null";
            }
            if ($dados[$i]['radiacao'] > 30) {
                $dados[$i]['insolacao'] = "1";
            } else {
                $dados[$i]['insolacao'] = "0";
            }
            $i++;
        }
        return $dados;
    }

    function insere_dados($estacao, $dados) {
        $dados_estacoes = new dados_estacoes();
        $dados_estacoes->insere_dados_estacao($estacao, $dados);
    }
    
    
}

?>
