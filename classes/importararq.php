<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of importararq
 *
 * @author Marcelo
 */
class importararq {
    //put your code here
    private $id;
    private $data;
    private $usuario;
    private $arquivo;
    private $qtde;
    private $arqnome;
    
    
    function getArqnome() {
        return $this->arqnome;
    }

    function setArqnome($arqnome) {
        $this->arqnome = $arqnome;
    }
    
    function getId() {
        return $this->id;
    }

    function getData() {
        return $this->data;
    }

    function getUsuario() {
        return $this->usuario;
    }

    function getArquivo() {
        return $this->arquivo;
    }

    function getQtde() {
        return $this->qtde;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setData($data) {
        $this->data = $data;
    }

    function setUsuario($usuario) {
        $this->usuario = $usuario;
    }

    function setArquivo($arquivo) {
        $this->arquivo = $arquivo;
    }

    function setQtde($qtde) {
        $this->qtde = $qtde;
    }
    
}
