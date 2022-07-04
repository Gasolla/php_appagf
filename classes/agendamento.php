<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of agendamento
 *
 * @author marcelo
 */
class agendamento {
    //put your code here
    private $id;
    private $cliente;
    private $cadastro;
    private $status;
    private $finalizacao;
    private $data;
    private $usuario;
    private $imediata;
    private $agencia; 
    
    
    function getAgencia() {
        return $this->agencia;
    }

    function setAgencia($agencia) {
        $this->agencia = $agencia;
    }

         
    function getImediata() {
        return $this->imediata;
    }

    function setImediata($imediata) {
        $this->imediata = $imediata;
    }
    
    function getData() {
        return $this->data;
    }

    function getUsuario() {
        return $this->usuario;
    }

    function setData($data) {
        $this->data = $data;
    }

    function setUsuario($usuario) {
        $this->usuario = $usuario;
    }
    
    function getId() {
        return $this->id;
    }

    function getCliente() {
        return $this->cliente;
    }

    function getCadastro() {
        return $this->cadastro;
    }

    function getStatus() {
        return $this->status;
    }

    function getFinalizacao() {
        return $this->finalizacao;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setCliente($cliente) {
        $this->cliente = $cliente;
    }

    function setCadastro($cadastro) {
        $this->cadastro = $cadastro;
    }

    function setStatus($status) {
        $this->status = $status;
    }

    function setFinalizacao($finalizacao) {
        $this->finalizacao = $finalizacao;
    }
    
}
