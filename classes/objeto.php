<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of objeto
 *
 * @author marcelo
 */
class objeto {
    //put your code here
    private $Objeto;
    private $Cliente;
    private $agencia;
    private $DataColeta;
    private $DataPostagem;
    private $DataEntrega;
    private $Apelido;
    private $Descricao;
    
    function getAgencia() {
        return $this->agencia;
    }

    function setAgencia($agencia) {
        $this->agencia = $agencia;
    }

    function getObjeto() {
        return $this->Objeto;
    }

    function getCliente() {
        return $this->Cliente;
    }

    function getDataColeta() {
        return $this->DataColeta;
    }

    function getDataPostagem() {
        return $this->DataPostagem;
    }

    function getDataEntrega() {
        return $this->DataEntrega;
    }

    function getApelido() {
        return $this->Apelido;
    }

    function getDescricao() {
        return $this->Descricao;
    }

    function setObjeto($Objeto) {
        $this->Objeto = $Objeto;
    }

    function setCliente($Cliente) {
        $this->Cliente = $Cliente;
    }

    function setDataColeta($DataColeta) {
        $this->DataColeta = $DataColeta;
    }

    function setDataPostagem($DataPostagem) {
        $this->DataPostagem = $DataPostagem;
    }

    function setDataEntrega($DataEntrega) {
        $this->DataEntrega = $DataEntrega;
    }

    function setApelido($Apelido) {
        $this->Apelido = $Apelido;
    }

    function setDescricao($Descricao) {
        $this->Descricao = $Descricao;
    }
    
}
