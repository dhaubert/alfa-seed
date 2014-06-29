<?php

class dados_culturas {

    function dados_culturas() {
        require_once ("database.php");
    }

    function salvar_cultura($dados_cultura) {
        $sql = "INSERT INTO 
                      culturas
                        (cultura,
                        kc_ini,
                        kc_mid,
                        kc_end,
                        gda_ini,
                        gda_mid,
                        gda_dev,
                        gda_late,
                        temperatura_base,
                        temperatura_superior,
                        externa
                         )
                 VALUES
                        (
                        '{$dados_cultura['nome_cultura']}',
                        '{$dados_cultura['kc_ini']}',
                        '{$dados_cultura['kc_mid']}',
                        '{$dados_cultura['kc_end']}',
                        '{$dados_cultura['gda_ini']}',
                        '{$dados_cultura['gda_mid']}',
                        '{$dados_cultura['gda_dev']}',
                        '{$dados_cultura['gda_late']}',
                        '{$dados_cultura['temp_base']}',
                        '{$dados_cultura['temp_superior']}',
                        '1'    
                          )
            ";
        $result = mysql_query($sql);
        return mysql_insert_id();
    }

    function busca_culturas($cultura_id = NULL) {
        $WHERE = "AND externa <> 1";
        if (isset($cultura_id)) {
            $WHERE = "AND id = '$cultura_id'";
        }
        $query = " SELECT
                    id,
                    cultura,
                    kc_ini,
                    kc_mid,
                    kc_end,
                    altura,
                    gda_ini,
                    gda_mid,
                    gda_dev,
                    gda_late,
                    temperatura_base,
                    temperatura_superior
               FROM
                    culturas
               WHERE
                    gda_ini is not null
                    $WHERE
               ORDER BY
                    cultura ASC
                 ";
        $result = mysql_query($query);
        echo mysql_error();
        while ($l = mysql_fetch_assoc($result)) {
            $dados[] = $l;
        }
        return $dados;
    }

}
?>

