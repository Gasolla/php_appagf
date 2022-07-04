<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of endereco
 *
 * @author marcelo
 */
class endereco {
    //put your code here
    private $latitude;
    private $longitude;
    private $enderecoExtenso;
    private $rua;
    private $bairro;
    private $cep;
    private $cidade;
    private $numero;
    private $complemento;
    private $uf;
    
    function getLatitude() {
        return $this->latitude;
    }

    function getLongitude() {
        return $this->longitude;
    }

    function getEnderecoExtenso() {
        return $this->enderecoExtenso;
    }

    function getRua() {
        return $this->rua;
    }

    function getBairro() {
        return $this->bairro;
    }

    function getCep() {
        return $this->cep;
    }

    function getCidade() {
        return $this->cidade;
    }

    function getNumero() {
        return $this->numero;
    }

    function getComplemento() {
        return $this->complemento;
    }

    function getUf() {
        return $this->uf;
    }

    function setLatitude($latitude) {
        $this->latitude = $latitude;
    }

    function setLongitude($longitude) {
        $this->longitude = $longitude;
    }

    function setEnderecoExtenso($enderecoExtenso) {
        $this->enderecoExtenso = $enderecoExtenso;
    }

    function setRua($rua) {
        $this->rua = $rua;
    }

    function setBairro($bairro) {
        $this->bairro = $bairro;
    }

    function setCep($cep) {
        $this->cep = $cep;
    }

    function setCidade($cidade) {
        $this->cidade = $cidade;
    }

    function setNumero($numero) {
        $this->numero = $numero;
    }

    function setComplemento($complemento) {
        $this->complemento = $complemento;
    }

    function setUf($uf) {
        $this->uf = $uf;
    }    

}
