<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of suprimento
 *
 * @author marcelo
 */
class suprimento {
    //put your code here
    private $id;
    private $nome;
    private $sigla;
    private $inativo;
    
    function getId() {
        return $this->id;
    }

    function getNome() {
        return $this->nome;
    }

    function getSigla() {
        return $this->sigla;
    }

    function getInativo() {
        return $this->inativo;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setSigla($sigla) {
        $this->sigla = $sigla;
    }

    function setInativo($inativo) {
        $this->inativo = $inativo;
    }


    
}
