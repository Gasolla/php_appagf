<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of estoquecliente
 *
 * @author marcelo
 */
class estoquecliente {
    //put your code here
    private $id;
    private $suprimento;
    private $cliente;
    private $agencia;
    private $qtde;
    private $data;
    
    function getAgencia() {
        return $this->agencia;
    }

    function setAgencia($agencia) {
        $this->agencia = $agencia;
    }

    function getData() {
        return $this->data;
    }

    function setData($data) {
        $this->data = $data;
    }

    function getId() {
        return $this->id;
    }

    function getSuprimento() {
        return $this->suprimento;
    }

    function getCliente() {
        return $this->cliente;
    }

    function getQtde() {
        return $this->qtde;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setSuprimento($suprimento) {
        $this->suprimento = $suprimento;
    }

    function setCliente($cliente) {
        $this->cliente = $cliente;
    }

    function setQtde($qtde) {
        $this->qtde = $qtde;
    }    
}
