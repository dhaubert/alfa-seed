<?php
class dados_solos {
    function dados_sols(){
        require('database.php');
    }
    function get_solos($solo_id = NULL){
        if(isset($solo_id)){
            $WHERE = 'WHERE id = \'$solo_id\'';
        }
        $query = " 
            SELECT
                *
            FROM
                solos
            $WHERE
            ORDER BY
                tipo ASC
                 ";
        $result = mysql_query($query);
        while($l = mysql_fetch_assoc($result)){
            $dados[] = $l;
        }
        return $dados;
    }
    
}
