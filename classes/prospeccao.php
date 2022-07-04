<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of prospeccao
 *
 * @author marcelo
 */
class prospeccao {
    //put your code here
    private $id;
    private $nome;
    private $email;
    private $fone;
    private $comentario;
    private $ocorrencia;
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
    private $usuario;
    private $ramo;
    private $contato;
    private $volume;
    private $naofechado;
    private $datanovo;
    private $datacontato;
    private $agencia;
    private $pendencia;
    private $tipopendencia;
    
    
    
    function getPendencia() {
        return $this->pendencia;
    }

    function getTipopendencia() {
        return $this->tipopendencia;
    }

    function setPendencia($pendencia) {
        $this->pendencia = $pendencia;
    }

    function setTipopendencia($tipopendencia) {
        $this->tipopendencia = $tipopendencia;
    }

        
    function getAgencia() {
        return $this->agencia;
    }

    function setAgencia($agencia) {
        $this->agencia = $agencia;
    }     
   
    function getNaofechado() {
        return $this->naofechado;
    }

    function getDatanovo() {
        return $this->datanovo;
    }

    function getDatacontato() {
        return $this->datacontato;
    }

    function setNaofechado($naofechado) {
        $this->naofechado = $naofechado;
    }

    function setDatanovo($datanovo) {
        $this->datanovo = $datanovo;
    }

    function setDatacontato($datacontato) {
        $this->datacontato = $datacontato;
    }    
    
    function getRamo() {
        return $this->ramo;
    }

    function getContato() {
        return $this->contato;
    }

    function getVolume() {
        return $this->volume;
    }

    function setRamo($ramo) {
        $this->ramo = $ramo;
    }

    function setContato($contato) {
        $this->contato = $contato;
    }

    function setVolume($volume) {
        $this->volume = $volume;
    }
    
    function getComentario() {
        return $this->comentario;
    }

    function getOcorrencia() {
        return $this->ocorrencia;
    }

    function setComentario($comentario) {
        $this->comentario = $comentario;
    }

    function setOcorrencia($ocorrencia) {
        $this->ocorrencia = $ocorrencia;
    }
        
    function getId() {
        return $this->id;
    }

    function getNome() {
        return $this->nome;
    }

    function getEmail() {
        return $this->email;
    }

    function getFone() {
        return $this->fone;
    }

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

    function getUsuario() {
        return $this->usuario;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setNome($nome) {
        $this->nome = $nome;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setFone($fone) {
        $this->fone = $fone;
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

    function setUsuario($usuario) {
        $this->usuario = $usuario;
    }    
    
}
