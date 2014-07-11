<?php

class dados_estacoes {

    function dados_estacoes() {
        require_once ("database.php");
    }
    function busca_ultimo_dado($estacao_id){
        $sql = 
                "SELECT
                    MAX(data) as data, hora
                 FROM 
                    dados_estacoes
                 WHERE
                    estacao_id = '$estacao_id';
                 ";
        $res = mysql_query($sql);
        return mysql_fetch_array($res);
    }
    function busca_dados_recentes($estacao_id, $ultimo_tempo) {
        $sql = "
                SELECT
                    *
                FROM
                    dados_estacoes de
                    JOIN estacoes USING (estacao_id)
                WHERE
                    data = '{$ultimo_tempo['data']}'
                    AND hora = '{$ultimo_tempo['hora']}'
                    AND de.estacao_id = '$estacao_id'
                ";
        echo "<pre>";
        print_r($sql);
        echo "</pre>";
        $result = mysql_query($sql);
        $dados = mysql_fetch_assoc($result);
        echo "<pre>";
        print_r($dados);
        echo "</pre>";
        return $dados;
    }
    function busca_medias_diarias($estacao_id, $data_inicial, $data_final){
        if(empty($data_final)){
            $data_final = date('Y-m-d');
        }
        $sql = 	
                "SELECT
                    de.estacao_id,
                    e.nome AS estacao,
                    e.latitude,
                    e.altitude,
                    de.data,
                    COUNT(*) AS numero_registros,
                    COUNT(DISTINCT de.data) AS datas,
                    AVG(de.temperatura_ar) AS temperatura_ar,
                    MIN(de.temperatura_minima) AS temperatura_minima,
                    MAX(de.temperatura_maxima) AS temperatura_maxima,
                    MAX(de.umidade_maxima) AS umidade_maxima,
                    MIN(IF(de.umidade_minima <> 0, de.umidade_minima, NULL)) AS umidade_minima,
                    AVG(IF(de.umidade <> 0, de.umidade, NULL)) AS umidade,
                    AVG(de.pressao * 0.1333224) AS pressao,
                    AVG(de.velocidade_vento) AS vento,
                    (SUM(de.precipitacao) + 0) AS chuva,
                    (SUM(de.precipitacao) * 0.8) AS chuva_efetiva,
                    AVG(IF(de.radiacao >= 30, de.radiacao, NULL)) AS radiacao,
                    SUM(IF(de.radiacao >= 30, de.radiacao, NULL)) AS radiacao_soma,
                    SUM(IF(de.radiacao >= 30, 1, 0)) AS  insolacao
            FROM  
                dados_estacoes  de
                JOIN estacoes e USING (estacao_id)
            WHERE 
                (de.data BETWEEN '$data_inicial' AND '$data_final') 
                AND de.estacao_id = '$estacao_id'
            GROUP BY 
                de.data
            HAVING 
                (de.estacao_id = '$estacao_id')
            ORDER BY 
                de.data, de.hora";  
        $result = mysql_query($sql);
        while($estacao = mysql_fetch_assoc($result)){
            $dados[] = $estacao;
        } 
        return $dados;
    }
    
    function lista_estacoes_INMET($estacao_id = NULL) {
        $where = "";
        if (isset($estacao_id)) {
            $where = "AND estacao_id = '$estacao_id'";
        }
        $lista = "SELECT
                        estacao_id,
                        nome,
                        municipio,
                        codigo,
                        latitude,
                        longitude,
                        altitude
                 FROM
                        estacoes
                 WHERE
                        codigo IS NOT NULL
                        $where
                 ORDER BY
                        municipio";
        $res = mysql_query($lista);
        $estacoes = array();
        while ($d = mysql_fetch_assoc($res)) {
            $d['municipio'] = utf8_encode($d['municipio']);
            $d['nome'] = utf8_encode($d['nome']);
            $estacoes[] = $d;
        }
        return $estacoes;
    }

    function busca_dados_estacao($estacao_id) {
        $sql = "SELECT 
                    estacao_id,
                    nome,
                    municipio,
                    codigo,
                    latitude,
                    longitude, 
                    altitude
                FROM 
                    estacoes
                WHERE
                    estacao_id = '$estacao_id' ";
        $res = mysql_query($sql);
        while ($estacao = mysql_fetch_assoc($res)) {
            $dados[] = $estacao;
        }
        return $dados[0];
    }

    function busca_dados_climaticos($estacao_id, $data_inicial, $data_final = NULL) {
        $estacao = $this->busca_dados_estacao($estacao_id);
        if (empty($data_final)) {
            $data_final = date('Y-m-d');
        }
        $sql = "SELECT
                        e.altitude,
                        e.latitude,
                        e.longitude,
                        de.data,
                        de.hora,
                        de.velocidade_vento,
                        de.temperatura_ar,
                        de.temperatura_minima,
                        de.temperatura_maxima,
                        de.umidade_minima,
                        de.umidade_maxima,
                        de.umidade, 
                        de.pressao,
                        de.radiacao,
                        de.precipitacao,
                        de.insolacao
                FROM
                        dados_estacoes de
                        JOIN estacoes e USING (estacao_id) 
                WHERE
                        de.estacao_id = '{$estacao_id}'
                        AND de.data BETWEEN '$data_inicial' AND '$data_final'
                 ";
        echo "<pre>";
        print_r($sql);
        echo "</pre>------------";
        $res = mysql_query($sql);
        while ($estacao = mysql_fetch_assoc($res)) {
            $dados[] = $estacao;
        }
        
        return $dados;
    }
    function insere_dados_estacao($estacao, $dados) {
        for ($i = 0; $i < count($dados); $i++) {
            $insere_estacao = "
            INSERT INTO dados_estacoes 
                (
                estacao_id,
                data,
                hora,
                temperatura_ar,
                temperatura_maxima,
                temperatura_minima,
                umidade,
                umidade_maxima,
                umidade_minima,
                pressao,
                velocidade_vento,
                radiacao,
                precipitacao,
                insolacao
                )
            VALUES
                (
                '{$estacao['estacao_id']}',
                '{$dados[$i]['data']}',
                '{$dados[$i]['hora']}',
                '{$dados[$i]['temperatura_ar']}',
                '{$dados[$i]['temperatura_maxima']}',
                '{$dados[$i]['temperatura_minima']}',
                '{$dados[$i]['umidade']}',
                '{$dados[$i]['umidade_maxima']}',
                '{$dados[$i]['umidade_minima']}',
                '{$dados[$i]['pressao']}',
                '{$dados[$i]['velocidade_vento']}',
                '{$dados[$i]['radiacao']}',
                '{$dados[$i]['precipitacao']}',
                '{$dados[$i]['insolacao']}'
                )
                ";
            $res = mysql_query($insere_estacao);
        }
    }

}
