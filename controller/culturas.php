<?php
include ("model/dados_culturas.php");
class Culturas {
    private $culturas;
    function Culturas(){
        $this->culturas = new dados_culturas();
    }
    function salva_nova_cultura($dados_cultura){
        return $this->culturas->salvar_cultura($dados_cultura);
    }
    function busca_culturas($cultura_id = NULL){
        $culturas = $this->culturas->busca_culturas($cultura_id);
        return $culturas;
    }
    
}

