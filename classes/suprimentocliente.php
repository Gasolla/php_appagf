<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of suprimentoclienterel
 *
 * @author marcelo
 */
class suprimentocliente {
    //put your code here
    private $cliente;
    private $agencia;
    private $suprimento;
    private $sigla;
    private $utilizado;
    private $disponibilizado;
    private $disponivel;
    
    function getAgencia() {
        return $this->agencia;
    }

    function setAgencia($agencia) {
        $this->agencia = $agencia;
    }
    
    function getCliente() {
        return $this->cliente;
    }

    function getSuprimento() {
        return $this->suprimento;
    }

    function getSigla() {
        return $this->sigla;
    }

    function getUtilizado() {
        return $this->utilizado;
    }

    function getDisponibilizado() {
        return $this->disponibilizado;
    }

    function getDisponivel() {
        return $this->disponivel;
    }

    function setCliente($cliente) {
        $this->cliente = $cliente;
    }

    function setSuprimento($suprimento) {
        $this->suprimento = $suprimento;
    }

    function setSigla($sigla) {
        $this->sigla = $sigla;
    }

    function setUtilizado($utilizado) {
        $this->utilizado = $utilizado;
    }

    function setDisponibilizado($disponibilizado) {
        $this->disponibilizado = $disponibilizado;
    }

    function setDisponivel($disponivel) {
        $this->disponivel = $disponivel;
    }


}
