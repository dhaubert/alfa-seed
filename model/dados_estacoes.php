<?php

class dados_estacoes {

    function dados_estacoes() {
        require_once ("database.php");
    }
    function busca_medias_diarias($estacao_id, $data_inicial, $data_final){
        if(empty($data_final)){
            $data_final = date('Y-m-d');
        }
        $sql = 	
                "SELECT
                    de.estacao_id,
                    e.nome estacao,
                    e.latitude,
                    (e.latitude * 3.141592654 / 180) latitude_radianos,
                    e.altitude,
                    de.data,
                    COUNT(*) numero_registros,
                    COUNT(DISTINCT de.data) datas,
                    AVG(de.temperatura_ar) temperatura_ar,
                    MIN(de.temperatura_minima) temperatura_minima,
                    MAX(de.temperatura_maxima) temperatura_maxima,
                    MAX(de.umidade_maxima) umidade_maxima,
                    MIN(IF(de.umidade_minima <> 0, de.umidade_minima, NULL)) umidade_minima,
                    AVG(IF(de.umidade <> 0, de.umidade, NULL)) umidade,
                    AVG(de.pressao * 0.1333224) pressao,
                    AVG(de.velocidade_vento) vento,
                    (SUM(de.precipitacao) + 0) chuva,
                    AVG(IF(de.radiacao >= 30, de.radiacao, NULL)) radiacao,
                    SUM(IF(de.radiacao >= 30, de.radiacao, NULL)) radiacao_soma,
                    SUM(IF(de.radiacao >= 30, 1, 0)) insolacao
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
