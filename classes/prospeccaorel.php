<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of prospeccaorel
 *
 * @author Marcelo
 */
class prospeccaorel {
    //put your code here
    private $agencia;
    private $nome;
    private $contato;
    private $ramo;
    private $volume;
    private $email;
    private $fone;
    private $comercial; 
    private $cadastro;
    private $seguimento; 
    private $datacontato;
    private $datanovo;
    private $ocorrencia; 
    private $naofechado;
    private $comentario;
    private $pendencia;
    
    
    function getPendencia() {
        return $this->pendencia;
    }

    function setPendencia($pendencia) {
        $this->pendencia = $pendencia;
    }
    
    function getAgencia() {
        return $this->agencia;
    }

    function getNome() {
        return $this->nome;
    }

    function getContato() {
        return $this->contato;
    }

    function getRamo() {
        return $this->ramo;
    }

    function getVolume() {
        return $this->volume;
    }

    function getEmail() {
        return $this->email;
    }

    function getFone() {
        return $this->fone;
    }

    function getComercial() {
        return $this->comercial;
    }

    function getCadastro() {
        return $this->cadastro;
    }

    function getSeguimento() {
        return $this->seguimento;
    }

    function getDatacontato() {
        return $this->datacontato;
    }

    function getDatanovo() {
        return $this->datanovo;
    }

    function getOcorrencia() {
        return $this->ocorrencia;
    }

    function getNaofechado() {
        return $this->naofechado;
    }

    function getComentario() {
        return $this->comentario;
    }

    function setAgencia($agencia) {
        $this->agencia = $agencia;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setContato($contato) {
        $this->contato = $contato;
    }

    function setRamo($ramo) {
        $this->ramo = $ramo;
    }

    function setVolume($volume) {
        $this->volume = $volume;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setFone($fone) {
        $this->fone = $fone;
    }

    function setComercial($comercial) {
        $this->comercial = $comercial;
    }

    function setCadastro($cadastro) {
        $this->cadastro = $cadastro;
    }

    function setSeguimento($seguimento) {
        $this->seguimento = $seguimento;
    }

    function setDatacontato($datacontato) {
        $this->datacontato = $datacontato;
    }

    function setDatanovo($datanovo) {
        $this->datanovo = $datanovo;
    }

    function setOcorrencia($ocorrencia) {
        $this->ocorrencia = $ocorrencia;
    }

    function setNaofechado($naofechado) {
        $this->naofechado = $naofechado;
    }

    function setComentario($comentario) {
        $this->comentario = $comentario;
    }


    
}
