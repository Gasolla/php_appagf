<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of requisicao
 *
 * @author marcelo
 */
class requisicao {
    //put your code here
    private $Imagem;
    private $Cliente;
    private $agencia;
    private $DataColeta;
    private $coleta;
    
    function getAgencia() {
        return $this->agencia;
    }

    function setAgencia($agencia) {
        $this->agencia = $agencia;
    }
    
    function getColeta() {
        return $this->coleta;
    }

    function setColeta($coleta) {
        $this->coleta = $coleta;
    }
    
    function getImagem() {
        return $this->Imagem;
    }

    function getCliente() {
        return $this->Cliente;
    }

    function getDataColeta() {
        return $this->DataColeta;
    }

    function setImagem($Imagem) {
        $this->Imagem = $Imagem;
    }

    function setCliente($Cliente) {
        $this->Cliente = $Cliente;
    }

    function setDataColeta($DataColeta) {
        $this->DataColeta = $DataColeta;
    }
    
}
