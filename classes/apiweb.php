<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of apiweb
 *
 * @author Marcelo
 */
class apiweb {

    //put your code here
    private $id;
    private $cliente;
    private $agencia;
    private $objeto;
    private $nomedestino;
    private $ufdestino;
    private $cidadedestino;
    private $bairrodestino;
    private $enderecodestino;
    private $cepdestino;
    private $nomeremetente;
    private $ufremetente;
    private $cidaderemetente;
    private $bairroremetente;
    private $enderecoremetente;
    private $numeroremetente;
    private $cepremetente;
    private $datapostagem;
    private $datacoleta;
    private $dataentrega;
    private $statussro;
    private $descricao;
    private $datahora;
    private $status;
    private $servico;
    private $statusagendamento;
    private $numerodestino;
    private $peso;
    private $valor;
    private $cartao;
    private $impressao;
    
    
    function getImpressao() {
        return $this->impressao;
    }

    function setImpressao($impressao) {
        $this->impressao = $impressao;
    }    
    
    function getCartao() {
        return $this->cartao;
    }

    function setCartao($cartao) {
        $this->cartao = $cartao;
    }
    
    function getPeso() {
        return $this->peso;
    }

    function getValor() {
        return $this->valor;
    }

    function setPeso($peso) {
        $this->peso = $peso;
    }

    function setValor($valor) {
        $this->valor = $valor;
    }

        
    function getDatacoleta() {
        return $this->datacoleta;
    }

    function setDatacoleta($datacoleta) {
        $this->datacoleta = $datacoleta;
    }

    function getNumerodestino() {
        return $this->numerodestino;
    }

    function setNumerodestino($numerodestino) {
        $this->numerodestino = $numerodestino;
    }
    
    function getStatusagendamento() {
        return $this->statusagendamento;
    }

    function setStatusagendamento($statusagendamento) {
        $this->statusagendamento = $statusagendamento;
    }
    
    function getId() {
        return $this->id;
    }

    function setId($id) {
        $this->id = $id;
    }

    function getAgencia() {
        return $this->agencia;
    }

    function setAgencia($agencia) {
        $this->agencia = $agencia;
    }

    function getCliente() {
        return $this->cliente;
    }

    function getObjeto() {
        return $this->objeto;
    }

    function getNomedestino() {
        return $this->nomedestino;
    }

    function getUfdestino() {
        return $this->ufdestino;
    }

    function getCidadedestino() {
        return $this->cidadedestino;
    }

    function getBairrodestino() {
        return $this->bairrodestino;
    }

    function getEnderecodestino() {
        return $this->enderecodestino;
    }

    function getCepdestino() {
        return $this->cepdestino;
    }

    function getNomeremetente() {
        return $this->nomeremetente;
    }

    function getUfremetente() {
        return $this->ufremetente;
    }

    function getCidaderemetente() {
        return $this->cidaderemetente;
    }

    function getBairroremetente() {
        return $this->bairroremetente;
    }

    function getEnderecoremetente() {
        return $this->enderecoremetente;
    }

    function getNumeroremetente() {
        return $this->numeroremetente;
    }

    function getCepremetente() {
        return $this->cepremetente;
    }

    function getDatapostagem() {
        return $this->datapostagem;
    }

    function getDataentrega() {
        return $this->dataentrega;
    }

    function getStatussro() {
        return $this->statussro;
    }

    function getDescricao() {
        return $this->descricao;
    }

    function getDatahora() {
        return $this->datahora;
    }

    function getStatus() {
        return $this->status;
    }

    function getServico() {
        return $this->servico;
    }

    function setCliente($cliente) {
        $this->cliente = $cliente;
    }

    function setObjeto($objeto) {
        $this->objeto = $objeto;
    }

    function setNomedestino($nomedestino) {
        $this->nomedestino = $nomedestino;
    }

    function setUfdestino($ufdestino) {
        $this->ufdestino = $ufdestino;
    }

    function setCidadedestino($cidadedestino) {
        $this->cidadedestino = $cidadedestino;
    }

    function setBairrodestino($bairrodestino) {
        $this->bairrodestino = $bairrodestino;
    }

    function setEnderecodestino($enderecodestino) {
        $this->enderecodestino = $enderecodestino;
    }

    function setCepdestino($cepdestino) {
        $this->cepdestino = $cepdestino;
    }

    function setNomeremetente($nomeremetente) {
        $this->nomeremetente = $nomeremetente;
    }

    function setUfremetente($ufremetente) {
        $this->ufremetente = $ufremetente;
    }

    function setCidaderemetente($cidaderemetente) {
        $this->cidaderemetente = $cidaderemetente;
    }

    function setBairroremetente($bairroremetente) {
        $this->bairroremetente = $bairroremetente;
    }

    function setEnderecoremetente($enderecoremetente) {
        $this->enderecoremetente = $enderecoremetente;
    }

    function setNumeroremetente($numeroremetente) {
        $this->numeroremetente = $numeroremetente;
    }

    function setCepremetente($cepremetente) {
        $this->cepremetente = $cepremetente;
    }

    function setDatapostagem($datapostagem) {
        $this->datapostagem = $datapostagem;
    }

    function setDataentrega($dataentrega) {
        $this->dataentrega = $dataentrega;
    }

    function setStatussro($statussro) {
        $this->statussro = $statussro;
    }

    function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    function setDatahora($datahora) {
        $this->datahora = $datahora;
    }

    function setStatus($status) {
        $this->status = $status;
    }

    function setServico($servico) {
        $this->servico = $servico;
    }

}
