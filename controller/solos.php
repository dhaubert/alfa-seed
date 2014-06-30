<?php
include ("../model/dados_solos.php");
class Solos {
    function busca_solos($solo_id = NULL){
        $solos = new dados_solos();
        return $solos->get_solos($solo_id);
    }
}
