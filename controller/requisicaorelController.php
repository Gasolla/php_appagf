<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of requisicaoController
 *
 * @author marcelo
 */
require 'classes/item.php';
class requisicaorelController {
    //put your code here
    private $params;
    private $conexao;
    private $filtro;
    private $requisicao = array();
    private $fArray = array();
    private $fDescricaoArray = array();
    private $fParmsArray = array();
    private $fParmsNameArray = array();
    private $usuarioacesso;
    public $msg;
    public $count = 0;
    public $descricaofiltro;
    private $agencia = array();
    private $cliente = array();
    
        
    function getAgencia(){
        return $this->agencia;
    }
    
        
    function getCliente(){
        return $this->cliente;
    }
    
    
    function getRequisicao() {
        return $this->requisicao;
    }


     public function __construct($params, $conexao, $usuarioacesso) {
        $this->params = $params;
        $this->conexao = $conexao;
        $this->usuarioacesso = $usuarioacesso;
    }
    
    private function preparafiltro() {
        if ($this->usuarioacesso->Cliente > 0) {
            $this->fDescricaoArray[] = "(Requisicao.Cliente = {$this->usuarioacesso->Cliente})";
            $this->fArray[] = "(Requisicao.Cliente = ?)";
            $this->fParmsArray[] = $this->usuarioacesso->Cliente;
            $this->fParmsNameArray[] = "cliente";
        }
        if ($this->usuarioacesso->Agencia > 0) {
            $this->fDescricaoArray[] = "(clientes.Agencia_Id = {$this->usuarioacesso->Agencia})";
            $this->fArray[] = "(clientes.Agencia_Id = ?)";
            $this->fParmsArray[] = $this->usuarioacesso->Agencia;
            $this->fParmsNameArray[] = "agencia";
        }
        if (isset($this->params['agencia_id']) && ($this->params['agencia_id'] !== "")) {
            $this->fDescricaoArray[] = "(ISNULL(clientes.Agencia_Id, 0) = ".crypto::decrypt($this->params['agencia_id']).")";
            $this->fArray[] = "(ISNULL(clientes.Agencia_Id, 0) = ?)";
            $this->fParmsArray[] = crypto::decrypt($this->params['agencia_id']);
            $this->fParmsNameArray[] = "agencia2";
        }
        
        if (isset($this->params['cliente_id']) && ($this->params['cliente_id'] !== "")) {
            $this->fDescricaoArray[] = "(Requisicao.cliente = '".crypto::decrypt($this->params['cliente_id'])."')";
            $this->fArray[] = "(Requisicao.cliente = ?)";
            $this->fParmsArray[] = crypto::decrypt($this->params['cliente_id']);
            $this->fParmsNameArray[] = "cliente_id";
        }
        if (isset($this->params['cidata']) && ($this->params['cidata'] !== "")) {
            $this->fDescricaoArray[] = "(Requisicao.DtHr >= '{$this->params['cidata']} 00:00:00')";
            $this->fArray[] = "(Requisicao.DtHr >= ?)";
            $this->fParmsArray[] = "{$this->params['cidata']} 00:00:00";
            $this->fParmsNameArray[] = "cidata";
        }
        if (isset($this->params['cfdata']) && ($this->params['cfdata'] !== "")) {
            $this->fDescricaoArray[] = "(Requisicao.DtHr <= '{$this->params['cfdata']} 23:59:59')";
            $this->fArray[] = "(Requisicao.DtHr <= ?)";
            $this->fParmsArray[] = "{$this->params['cfdata']} 23:59:59";
            $this->fParmsNameArray[] = "cfdata";
        }
        $this->filtro = implode(' and ', $this->fArray);
        if (count($this->fDescricaoArray)>0){
            $this->descricaofiltro = crypto::encrypt(implode(' and ', $this->fDescricaoArray)); 
        }
    }
    
    public function lista(){
        $this->conexao->setRequisicao(true);
        $this->preparafiltro();
        $SQL = "Select count(*) total from requisicao "
                . "inner join clientes on clientes.id = requisicao.cliente "
                . "inner join agencia on agencia.id = clientes.agencia_id "
                . "inner join requisicaoitens on requisicao.id = requisicaoitens.requisicao "
                .($this->filtro<>""?"Where {$this->filtro}":"");
        $retorno = $this->conexao->consultar($SQL, $this->fParmsArray, $this->fParmsNameArray, $this->usuarioacesso->Codigo, false);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            return false;
        }
        $this->count = $retorno[0]['total'];
        $SQL = "Select  clientes.nome cliente, base64, case when coleta = 'T' then 'SIM' else 'NAO' end coleta, 
                        agencia.nome agencia, 
                        Convert(nvarchar(10), requisicao.DtHr, 103) datacoleta
                from requisicao " 
                . "inner join clientes on clientes.id = requisicao.cliente "
                . "inner join agencia on agencia.id = clientes.agencia_id "
                . "inner join requisicaoitens on requisicao.id = requisicaoitens.requisicao "
                .($this->filtro<>""?"Where {$this->filtro}":"")
                ."ORDER BY clientes.nome OFFSET (50 * {$this->params['pag']}) - 50 ROWS FETCH NEXT 50 ROWS ONLY";
        $retorno = $this->conexao->consultar($SQL, $this->fParmsArray, $this->fParmsNameArray, $this->usuarioacesso->Codigo, false);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            return false;
        }if ($this->conexao->contagem === 0) {
            $this->msg = "Nenhum registro encontrado";
            return false;
        }
        foreach ($retorno as $key => $value) {
            $requisicao = new requisicao();
            $requisicao->setCliente(utf8_encode($value['cliente']));
            $requisicao->setAgencia($value['agencia']);
            $requisicao->setDataColeta($value['datacoleta']);
            $requisicao->setImagem($value['base64']);
            $requisicao->setColeta($value['coleta']);
            $this->requisicao[$key] = $requisicao;
        }
        return true;
    }
    
    
    public function excel(){
        if (!$this->usuarioacesso->Gerar){
            exit("ajax_txt\nn0\nnUsuário não possui permissão de geração.");    
        }
        $this->conexao->setRequisicao(true);
        if (trim($this->params['filtro'])!==""){ 
            $this->filtro = crypto::decrypt($this->params['filtro']);
        }
        $SQL = "Select  clientes.nome cliente, 
                        Convert(nvarchar(10), requisicao.DtHr, 103) datacoleta,
                        agencia.nome agencia, 
                        case when coleta = 'T' then 'SIM' else 'NAO' end coleta
                from requisicao " 
                . "inner join clientes on clientes.id = requisicao.cliente "
                . "inner join agencia on agencia.id = clientes.agencia_id "
                . "inner join requisicaoitens on requisicao.id = requisicaoitens.requisicao "
                .($this->filtro<>""?"Where {$this->filtro}":"").
                " Order by clientes.nome ";
        $retorno = $this->conexao->consultar($SQL, $this->fParmsArray, $this->fParmsNameArray, $this->usuarioacesso->Codigo, false);
        if ($retorno === false) {
            exit("ajax_htm\nn0\nn{$this->conexao->mensagem}");
        }if ($this->conexao->contagem === 0) {
            exit("ajax_htm\nn0\nnNenhum registro encontrado");
        }
        $wHeader = array(); $wRegistros = array();
        $wHeader[] = 'Cliente'; $wRegistros[] = 'cliente';
        $wHeader[] = 'Agencia'; $wRegistros[] = 'agencia';
        $wHeader[] = 'Data Coleta'; $wRegistros[] = 'datacoleta';
        $wHeader[] = 'Possui Coleta'; $wRegistros[] = 'coleta';
        $caminho = ajusta_temporario_excel($this->usuarioacesso->Codigo)."excel.xls";
        $excel = new excel($wHeader, $wRegistros, $retorno);
        $excel->gerar($caminho);        
    }
    
    public function addAgencia(){
        $SQL = "Select id, nome from agencia ".
                ($this->usuarioacesso->Agencia>0?"Where id = {$this->usuarioacesso->Agencia}":"").
                " Order by nome ";
        $retorno = $this->conexao->consultar($SQL, array(), array(), $this->usuarioacesso->Codigo, false);
        if ($retorno===false){
            return false;
        }
        if (is_array($retorno)&&(count($retorno)>0)){
            foreach ($retorno as $value) {
                $agencia = new item();
                $agencia->setId($value['id']);
                $agencia->setNome($value['nome']);
                $this->agencia[] = $agencia;
            }
        }   
    }
    
    public function addCliente(){
        $SQL = "SELECT id, Ltrim(Concat(apelido, ' ', nome)) nome FROM CLIENTES where (id > 0) "
                .($this->usuarioacesso->Cliente>0?" and id = {$this->usuarioacesso->Cliente} " : "")
                .($this->usuarioacesso->Agencia>0?" and agencia_id = {$this->usuarioacesso->Agencia}":"")
                . " Order by nome ";
                //echo $SQL;
        $retorno = $this->conexao->consultar($SQL, array(), array(), $this->usuarioacesso->Codigo, false);
        if ($retorno===false){
            return false;
        }
        if (is_array($retorno)&&(count($retorno)>0)){
           
            foreach ($retorno as $value) {
                $cliente = new item();
                $cliente->setId($value['id']);
                $cliente->setNome(utf8_encode($value['nome']));
                $this->cliente[] = $cliente;
            }
        }   
    }
    
    
}
