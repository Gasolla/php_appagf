<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of rastreamento
 *
 * @author Marcelo
 */
class rastreamento {
    //put your code here
    private $objeto;
    private $cidade;
    private $uf;
    private $local;
    private $data;
    private $descricao;
    
    
    function getObjeto() {
        return $this->objeto;
    }

    function getCidade() {
        return $this->cidade;
    }

    function getUf() {
        return $this->uf;
    }

    function getLocal() {
        return $this->local;
    }

    function getData() {
        return $this->data;
    }

    function getDescricao() {
        return $this->descricao;
    }

    function setObjeto($objeto) {
        $this->objeto = $objeto;
    }

    function setCidade($cidade) {
        $this->cidade = $cidade;
    }

    function setUf($uf) {
        $this->uf = $uf;
    }

    function setLocal($local) {
        $this->local = $local;
    }

    function setData($data) {
        $this->data = $data;
    }

    function setDescricao($descricao) {
        $this->descricao = $descricao;
    }


}
