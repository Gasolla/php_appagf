<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of homeController
 *
 * @author Marcelo
 */

require 'classes/item.php';
class homeController {
    //put your code here
    //put your code here
    
    private $params;
    private $conexao;
    private $usuarioacesso;
    public $msg;
    public $count = 0;
    public $descricaofiltro;
    private $alertaatraso = array();
    private $alertacontato = array();
    private $alertaprimeiro = array();
    
    function getAlertaprimeiro() {
        return $this->alertaprimeiro;
    }
    
    function getAlertacontato() {
        return $this->alertacontato;
    }

    function getAlertaatraso() {
        return $this->alertaatraso;
    }

    public function __construct($params, $conexao, $usuarioacesso) {
        $this->params = $params;
        $this->conexao = $conexao;
        $this->usuarioacesso = $usuarioacesso;
    }
    
    public function addAlertaContato(){
        $this->count = 0;
        $SQL = "Select 
                    CONVERT(nvarchar(10), datanovo, 103) data, 
                    nome, fone
                From Prospeccao
                Where   (datanovo = cast(CURRENT_TIMESTAMP as date))  and 
                        comercial = {$this->usuarioacesso->Codigo} ";
        $retorno = $this->conexao->consultar($SQL, array(), array(), $this->usuarioacesso->Codigo, false);
        if ($retorno===false){
            return false;
        }
        $this->count = $this->conexao->contagem;
        if (is_array($retorno)&&(count($retorno)>0)){
            foreach ($retorno as $value) {
                $alertacontato = new item();
                $alertacontato->setData($value['data']);
                $alertacontato->setNome(utf8_encode($value['nome']));
                $alertacontato->setFone($value['fone']);
                $this->alertacontato[] = $alertacontato;
            }
        }   
        
    }
    
    public function addAlertaAtraso(){
        $this->count = 0;
        $SQL = "Select 
                    CONVERT(nvarchar(10), datanovo, 103) data, 
                    nome, fone
                From Prospeccao
                Where   (isnull(pendencia, 'F')='F') and
                        (datanovo < cast(CURRENT_TIMESTAMP as date))  and 
                        comercial = {$this->usuarioacesso->Codigo} ";
        $retorno = $this->conexao->consultar($SQL, array(), array(), $this->usuarioacesso->Codigo, false);
        if ($retorno===false){
            return false;
        }
        $this->count = $this->conexao->contagem;
        if (is_array($retorno)&&(count($retorno)>0)){
            foreach ($retorno as $value) {
                $alertaatraso = new item();
                $alertaatraso->setData($value['data']);
                $alertaatraso->setNome(utf8_encode($value['nome']));
                $alertaatraso->setFone($value['fone']);
                $this->alertaatraso[] = $alertaatraso;
            }
        }   
        
    }
    
    public function addAlertaPrimeiro(){
        $this->count = 0;
        $SQL = "Select 
                    CONVERT(nvarchar(10), datanovo, 103) data, 
                    nome, fone
                From Prospeccao
                Where   (isnull(pendencia, 'F')='F') and
                        ((dataselecionado is not null) and (datacontato is null))  and 
                        (comercial = {$this->usuarioacesso->Codigo} )";
        $retorno = $this->conexao->consultar($SQL, array(), array(), $this->usuarioacesso->Codigo, false);
        if ($retorno===false){
            return false;
        }
        $this->count = $this->conexao->contagem;
        if (is_array($retorno)&&(count($retorno)>0)){
            foreach ($retorno as $value) {
                $alertaprimeiro = new item();
                $alertaprimeiro->setData($value['data']);
                $alertaprimeiro->setNome(utf8_encode($value['nome']));
                $alertaprimeiro->setFone($value['fone']);
                $this->alertaprimeiro[] = $alertaprimeiro;
            }
        }   
        
    }
    
}
