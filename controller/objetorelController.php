<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of objetorelController
 *
 * @author marcelo
 */
require 'classes/item.php';
class objetorelController {
    //put your code here
    
    private $params;
    private $conexao;
    private $filtro;
    private $objeto = array();
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
    
    function getObjeto() {
        return $this->objeto;
    }
    
    public function __construct($params, $conexao, $usuarioacesso) {
        $this->params = $params;
        $this->conexao = $conexao;
        $this->usuarioacesso = $usuarioacesso;
    }
    
    private function preparafiltro() {
        if ($this->usuarioacesso->Cliente > 0) {
            $this->fDescricaoArray[] = "(Remessa.Cliente = {$this->usuarioacesso->Cliente})";
            $this->fArray[] = "(Remessa.Cliente = ?)";
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
            $this->fDescricaoArray[] = "(Remessa.cliente = '".crypto::decrypt($this->params['cliente_id'])."')";
            $this->fArray[] = "(Remessa.cliente = ?)";
            $this->fParmsArray[] = crypto::decrypt($this->params['cliente_id']);
            $this->fParmsNameArray[] = "cliente_id";
        }
        if (isset($this->params['objeto']) && ($this->params['objeto'] !== "")) {
            $this->fDescricaoArray[] = "(RemessaItens.objeto = '{$this->params['objeto']}')";
            $this->fArray[] = "(RemessaItens.objeto = ?)";
            $this->fParmsArray[] = paramstostring($this->params['objeto']);
            $this->fParmsNameArray[] = "objeto";
        }
        if (isset($this->params['cidata']) && ($this->params['cidata'] !== "")) {
            $this->fDescricaoArray[] = "(Remessa.DtHr >= '{$this->params['cidata']} 00:00:00')";
            $this->fArray[] = "(Remessa.DtHr >= ?)";
            $this->fParmsArray[] = "{$this->params['cidata']} 00:00:00";
            $this->fParmsNameArray[] = "cidata";
        }
        if (isset($this->params['cfdata']) && ($this->params['cfdata'] !== "")) {
            $this->fDescricaoArray[] = "(Remessa.DtHr <= '{$this->params['cfdata']} 23:59:59')";
            $this->fArray[] = "(Remessa.DtHr <= ?)";
            $this->fParmsArray[] = "{$this->params['cfdata']} 23:59:59";
            $this->fParmsNameArray[] = "cfdata";
        }
        if (isset($this->params['pidata']) && ($this->params['pidata'] !== "")) {
            $this->fDescricaoArray[] = "(RemessaItens.DataPostagem >= '{$this->params['pidata']} 00:00:00')";
            $this->fArray[] = "(RemessaItens.DataPostagem >= ?)";
            $this->fParmsArray[] = "{$this->params['pidata']} 00:00:00";
            $this->fParmsNameArray[] = "pidata";
        }
        if (isset($this->params['pfdata']) && ($this->params['pfdata'] !== "")) {
            $this->fArray[] = "(RemessaItens.DataPostagem <= '{$this->params['pfdata']} 23:59:59')";
            $this->fArray[] = "(RemessaItens.DataPostagem <= ?)";
            $this->fParmsArray[] = "{$this->params['pfdata']} 23:59:59";
            $this->fParmsNameArray[] = "pfdata";
        }
        $this->filtro = implode(' and ', $this->fArray);
        if (count($this->fDescricaoArray)>0){
            $this->descricaofiltro = crypto::encrypt(implode(' and ', $this->fDescricaoArray)); 
        }
    }
    
    public function lista(){
        $this->conexao->setRequisicao(true);
        $this->preparafiltro();
        $SQL = "Select count(*) total from remessa "
                . "inner join clientes on clientes.id = remessa.cliente "
                ." inner join agencia on agencia.id = clientes.agencia_id "
                . "inner join remessaitens on remessa.id = remessaitens.remessa "
                .($this->filtro<>""?"Where {$this->filtro}":"");
        $retorno = $this->conexao->consultar($SQL, $this->fParmsArray, $this->fParmsNameArray, $this->usuarioacesso->Codigo, false);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            return false;
        }
        $this->count = $retorno[0]['total'];
        $SQL = "Select  objeto, clientes.nome cliente, remessaitens.apelido, descricao, 
                        Convert(nvarchar(10), remessa.DtHr, 103) datacoleta,
                        agencia.nome agencia,
                        case when (remessaitens.datapostagem > '01/01/1900') then Convert(nvarchar(10), remessaitens.datapostagem, 103) else '' end datapostagem, 
                        case when (remessaitens.dataentrega > '01/01/1900') then Convert(nvarchar(10), remessaitens.dataentrega, 103) else '' end dataentrega
                from remessa " 
                . "inner join clientes on clientes.id = remessa.cliente "
                ." inner join agencia on agencia.id = clientes.agencia_id "
                . "inner join remessaitens on remessa.id = remessaitens.remessa "
                .($this->filtro<>""?"Where {$this->filtro}":"");
                "ORDER BY remessa.DtHr desc OFFSET (100 * {$this->params['pag']}) - 100 ROWS FETCH NEXT 100 ROWS ONLY";
        $retorno = $this->conexao->consultar($SQL, $this->fParmsArray, $this->fParmsNameArray, $this->usuarioacesso->Codigo, false);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            return false;
        }if ($this->conexao->contagem === 0) {
            $this->msg = "Nenhum registro encontrado";
            return false;
        }
        foreach ($retorno as $key => $value) {
            $objeto = new objeto();
            $objeto->setCliente(utf8_encode($value['cliente']));
            $objeto->setAgencia($value['agencia']);
            $objeto->setObjeto($value['objeto']);
            $objeto->setDataColeta($value['datacoleta']);
            $objeto->setDataPostagem($value['datapostagem']);
            $objeto->setDataEntrega($value['dataentrega']);
            $objeto->setApelido($value['apelido']);
            $objeto->setDescricao($value['descricao']);
            $this->objeto[$key] = $objeto;
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
        $SQL = "Select  objeto, clientes.nome cliente, remessaitens.apelido, descricao, 
                        Convert(nvarchar(10), remessa.DtHr, 103) datacoleta,
                        agencia.nome agencia,
                        case when (remessaitens.datapostagem > '01/01/1900') then Convert(nvarchar(10), remessaitens.datapostagem, 103) else '' end datapostagem, 
                        case when (remessaitens.dataentrega > '01/01/1900') then Convert(nvarchar(10), remessaitens.dataentrega, 103) else '' end dataentrega
                from remessa " 
                . "inner join clientes on clientes.id = remessa.cliente "
                ." inner join agencia on agencia.id = clientes.agencia_id "
                . "inner join remessaitens on remessa.id = remessaitens.remessa "
                .($this->filtro<>""?"Where {$this->filtro}":"").
                " Order by remessa.DtHr";
        $retorno = $this->conexao->consultar($SQL, $this->fParmsArray, $this->fParmsNameArray, $this->usuarioacesso->Codigo, false);
        if ($retorno === false) {
            exit("ajax_htm\nn0\nn{$this->conexao->mensagem}");
        }if ($this->conexao->contagem === 0) {
            exit("ajax_htm\nn0\nnNenhum registro encontrado");
        }
        $wHeader = array(); $wRegistros = array();
        $wHeader[] = 'Objeto'; $wRegistros[] = 'objeto';
        $wHeader[] = 'Agencia'; $wRegistros[] = 'agencia';
        $wHeader[] = 'Cliente'; $wRegistros[] = 'cliente';
        $wHeader[] = 'Data Coleta'; $wRegistros[] = 'datacoleta';
        $wHeader[] = 'Data Postagem'; $wRegistros[] = 'datapostagem';
        $wHeader[] = 'Data Entrega'; $wRegistros[] = 'dataentrega';
        $wHeader[] = 'Status'; $wRegistros[] = 'apelido';
        $wHeader[] = 'Motivo'; $wRegistros[] = 'descricao';
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
