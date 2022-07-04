<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of usuario
 *
 * @author marcelo
 */
class usuario {

    //put your code here

    private $id;
    private $usuario;
    private $nome;
    private $sobrenome;
    private $senha;
    private $email;
    private $cliente;
    private $acesso;
    private $agencia;
    private $cpfcpnj;
    private $tpacesso;

    
    
    function getSobrenome() {
        return $this->sobrenome;
    }

    function getCpfcpnj() {
        return $this->cpfcpnj;
    }

    function getTpacesso() {
        return $this->tpacesso;
    }

    function setSobrenome($sobrenome) {
        $this->sobrenome = $sobrenome;
    }

    function setCpfcpnj($cpfcpnj) {
        $this->cpfcpnj = $cpfcpnj;
    }

    function setTpacesso($tpacesso) {
        $this->tpacesso = $tpacesso;
    }

        function getAgencia() {
        return $this->agencia;
    }

    function setAgencia($agencia) {
        $this->agencia = $agencia;
    }

    function getAcesso() {
        return $this->acesso;
    }

    function setAcesso($acesso) {
        $this->acesso = $acesso;
    }

    function getCliente() {
        return $this->cliente;
    }

    function setCliente($cliente) {
        $this->cliente = $cliente;
    }

    function getId() {
        return $this->id;
    }

    function getUsuario() {
        return $this->usuario;
    }

    function getNome() {
        return $this->nome;
    }

    function getSenha() {
        return $this->senha;
    }

    function getEmail() {
        return $this->email;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setUsuario($usuario) {
        $this->usuario = $usuario;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setSenha($senha) {
        $this->senha = $senha;
    }

    function setEmail($email) {
        $this->email = $email;
    }

}
