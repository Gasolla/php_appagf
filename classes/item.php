<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of agencia
 *
 * @author marcelo
 */
class item {

    //put your code here
    private $id;
    private $nome;
    private $data;
    private $descricao;
    private $fone;
    
    function getFone() {
        return $this->fone;
    }

    function setFone($fone) {
        $this->fone = $fone;
    }

    
    function getData() {
        return $this->data;
    }

    function getDescricao() {
        return $this->descricao;
    }

    function setData($data) {
        $this->data = $data;
    }

    function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    function getId() {
        return $this->id;
    }

    function getNome() {
        return $this->nome;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

}
