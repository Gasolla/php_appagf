<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of cliente
 *
 * @author marcelo
 */
class cliente {
    //put your code here
    
    private $nome;
    private $id;
    private $documento;
    private $email;
    private $fone;
    private $motoqueiro;
    private $apelido;
    private $agencia;
    private $comercial;
    private $microvisual;
    
    function getMicrovisual() {
        return $this->microvisual;
    }

    function setMicrovisual($microvisual) {
        $this->microvisual = $microvisual;
    }
    
    function getComercial() {
        return $this->comercial;
    }

    function setComercial($comercial) {
        $this->comercial = $comercial;
    }

    function getAgencia() {
        return $this->agencia;
    }

    function setAgencia($agencia) {
        $this->agencia = $agencia;
    }
    
    function getApelido() {
        return $this->apelido;
    }

    function setApelido($apelido) {
        $this->apelido = $apelido;
    }

    function getMotoqueiro() {
        return $this->motoqueiro;
    }

    function setMotoqueiro($motoqueiro) {
        $this->motoqueiro = $motoqueiro;
    }
    
    function getEmail() {
        return $this->email;
    }

    function getFone() {
        return $this->fone;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setFone($fone) {
        $this->fone = $fone;
    }    
    
    function getNome() {
        return $this->nome;
    }

    function getId() {
        return $this->id;
    }

    function getDocumento() {
        return $this->documento;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setDocumento($documento) {
        $this->documento = $documento;
    }

    
}
