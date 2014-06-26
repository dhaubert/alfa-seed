<?php
class dados_culturas{
    function dados_culturas() {
        require_once ("database.php");
    }
    function busca_culturas($cultura_id = NULL){
        if(isset($cultura_id)){
            $WHERE = "AND id = '$cultura_id'";
        }
        $query = 
             " SELECT
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
        while($l = mysql_fetch_assoc($result)){
            $dados[] = $l;
        }
        return $dados;
    }
    
    
}

?>

