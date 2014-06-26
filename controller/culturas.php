<?php
include ("model/dados_culturas.php");
class Culturas {
    private $culturas;
    function Culturas(){
        $this->culturas = new dados_culturas();
    }
    function busca_culturas($cultura_id = NULL){
        $culturas = $this->culturas->busca_culturas($cultura_id);
        return $culturas;
    }
    
}

