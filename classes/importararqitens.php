<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of iimportararqitens
 *
 * @author Marcelo
 */
class importararqitens {
    //put your code here
    private $objeto;
    private $valor;
    private $peso;
    
    function getObjeto() {
        return $this->objeto;
    }

    function getValor() {
        return $this->valor;
    }

    function getPeso() {
        return $this->peso;
    }

    function setObjeto($objeto) {
        $this->objeto = $objeto;
    }

    function setValor($valor) {
        $this->valor = $valor;
    }

    function setPeso($peso) {
        $this->peso = $peso;
    }


}
