<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of agendamentorel
 *
 * @author Marcelo
 */
class agendamentorel {
    //put your code here
    private $status;
    private $agencia;
    private $cliente;
    private $datainput;
    private $datarealizar;
    private $datacoleta;
    private $statuscoleta;
    private $qtde;
    private $tipo;
    private $valor;
    private $comercial;
    private $motorista;
    private $datarotainicio;
    
    function getDatarotainicio() {
        return $this->datarotainicio;
    }

    function setDatarotainicio($datarotainicio) {
        $this->datarotainicio = $datarotainicio;
    }    
    
    function getStatus() {
        return $this->status;
    }

    function getAgencia() {
        return $this->agencia;
    }

    function getCliente() {
        return $this->cliente;
    }

    function getDatainput() {
        return $this->datainput;
    }

    function getDatarealizar() {
        return $this->datarealizar;
    }

    function getDatacoleta() {
        return $this->datacoleta;
    }

    function getStatuscoleta() {
        return $this->statuscoleta;
    }

    function getQtde() {
        return $this->qtde;
    }

    function getTipo() {
        return $this->tipo;
    }

    function getValor() {
        return $this->valor;
    }

    function getComercial() {
        return $this->comercial;
    }

    function getMotorista() {
        return $this->motorista;
    }

    function setStatus($status) {
        $this->status = $status;
    }

    function setAgencia($agencia) {
        $this->agencia = $agencia;
    }

    function setCliente($cliente) {
        $this->cliente = $cliente;
    }

    function setDatainput($datainput) {
        $this->datainput = $datainput;
    }

    function setDatarealizar($datarealizar) {
        $this->datarealizar = $datarealizar;
    }

    function setDatacoleta($datacoleta) {
        $this->datacoleta = $datacoleta;
    }

    function setStatuscoleta($statuscoleta) {
        $this->statuscoleta = $statuscoleta;
    }

    function setQtde($qtde) {
        $this->qtde = $qtde;
    }

    function setTipo($tipo) {
        $this->tipo = $tipo;
    }

    function setValor($valor) {
        $this->valor = $valor;
    }

    function setComercial($comercial) {
        $this->comercial = $comercial;
    }

    function setMotorista($motorista) {
        $this->motorista = $motorista;
    }

}
