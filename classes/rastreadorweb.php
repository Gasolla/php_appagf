<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of rastreadorweb
 *
 * @author Marcelo
 */
class rastreadorweb {
    //put your code here
    private $id;
    private $cliente;
    private $agencia;
    private $objeto;
    private $nomedestino;
    private $cepdestino;
    private $datapostagem;
    private $dataentrega;
    private $statussro;
    private $descricao;
    private $datahora;
    private $status;
    
    function getId() {
        return $this->id;
    }

    function getCliente() {
        return $this->cliente;
    }

    function getAgencia() {
        return $this->agencia;
    }

    function getObjeto() {
        return $this->objeto;
    }

    function getNomedestino() {
        return $this->nomedestino;
    }

    function getCepdestino() {
        return $this->cepdestino;
    }

    function getDatapostagem() {
        return $this->datapostagem;
    }

    function getDataentrega() {
        return $this->dataentrega;
    }

    function getStatussro() {
        return $this->statussro;
    }

    function getDescricao() {
        return $this->descricao;
    }

    function getDatahora() {
        return $this->datahora;
    }

    function getStatus() {
        return $this->status;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setCliente($cliente) {
        $this->cliente = $cliente;
    }

    function setAgencia($agencia) {
        $this->agencia = $agencia;
    }

    function setObjeto($objeto) {
        $this->objeto = $objeto;
    }

    function setNomedestino($nomedestino) {
        $this->nomedestino = $nomedestino;
    }

    function setCepdestino($cepdestino) {
        $this->cepdestino = $cepdestino;
    }

    function setDatapostagem($datapostagem) {
        $this->datapostagem = $datapostagem;
    }

    function setDataentrega($dataentrega) {
        $this->dataentrega = $dataentrega;
    }

    function setStatussro($statussro) {
        $this->statussro = $statussro;
    }

    function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    function setDatahora($datahora) {
        $this->datahora = $datahora;
    }

    function setStatus($status) {
        $this->status = $status;
    }
    
}
