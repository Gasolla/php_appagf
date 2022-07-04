<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of suprimentoclienterelController
 *
 * @author marcelo
 */
require 'classes/item.php';
class suprimentoclienterelController {
    //put your code here
    private $params;
    private $conexao;
    private $filtro;
    private $suprimentocliente = array();
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
    private $suprimento = array();
    
    function getSuprimento(){
        return $this->suprimento;
    }
    
    function getAgencia(){
        return $this->agencia;
    }
    
        
    function getCliente(){
        return $this->cliente;
    }
    
    public function __construct($params, $conexao, $usuarioacesso) {
        $this->params = $params;
        $this->conexao = $conexao;
        $this->usuarioacesso = $usuarioacesso;
    }
    
    function getSuprimentocliente() {
        return $this->suprimentocliente;
    }


    private function preparafiltro() {
        if ($this->usuarioacesso->Cliente > 0) {
            $this->descricaofiltro[] = "(EstoqueClientes.Cliente = {$this->usuarioacesso->Cliente})";
            $this->fArray[] = "(EstoqueClientes.Cliente = ?)";
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
            $this->fDescricaoArray[] = "(EstoqueClientes.cliente = '".crypto::decrypt($this->params['cliente_id'])."')";
            $this->fArray[] = "(EstoqueClientes.cliente = ?)";
            $this->fParmsArray[] = crypto::decrypt($this->params['cliente_id']);
            $this->fParmsNameArray[] = "cliente_id";
        }
        if (isset($this->params['suprimento_id']) && ($this->params['suprimento_id'] !== "")) {
            $this->fDescricaoArray[] = "(EstoqueClientes.suprimento= '".crypto::decrypt($this->params['suprimento_id'])."')";
            $this->fArray[] = "(EstoqueClientes.suprimento = ?)";
            $this->fParmsArray[] = crypto::decrypt($this->params['suprimento_id']);
            $this->fParmsNameArray[] = "suprimento_id";
        }
        
        $this->filtro = implode(' and ', $this->fArray);
        if (count($this->fDescricaoArray)>0){
            $this->descricaofiltro = crypto::encrypt(implode(' and ', $this->fDescricaoArray)); 
        }
    }
    
    public function lista(){
        $this->conexao->setRequisicao(true);
        $this->preparafiltro();
        $SQL = "Select count(*) total from EstoqueClientes "
                . "Inner join Clientes on EstoqueClientes.Cliente = clientes.id "
                ." inner join agencia on agencia.id = clientes.agencia_id "
                . "Inner join suprimentos on suprimentos.ID = EstoqueClientes.Suprimento "
                . "Left join VW_REMESSAITENS_SUPRIMENTO_QTDE remessa on remessa.Cliente = EstoqueClientes.cliente and remessa.Sigla = suprimentos.Sigla " 
                .($this->filtro<>""?"Where {$this->filtro}":"");
        $retorno = $this->conexao->consultar($SQL, $this->fParmsArray, $this->fParmsNameArray, $this->usuarioacesso->Codigo, false);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            return false;
        }
        $this->count = $retorno[0]['total'];
        $SQL = "Select  Clientes.nome cliente, Suprimentos.nome suprimento,
                        Suprimentos.sigla, EstoqueClientes.Qtde disponibilizado, 
                        ISNULL(remessa.total, 0) utilizado, agencia.nome agencia,
                        (EstoqueClientes.Qtde-ISNULL(remessa.total, 0)) disponivel "
                . "From EstoqueClientes "
                . "Inner join Clientes on EstoqueClientes.Cliente = clientes.id "
                ." inner join agencia on agencia.id = clientes.agencia_id "
                . "Inner join suprimentos on suprimentos.ID = EstoqueClientes.Suprimento "
                . "Left join VW_REMESSAITENS_SUPRIMENTO_QTDE remessa on remessa.Cliente = EstoqueClientes.cliente and remessa.Sigla = suprimentos.Sigla " 
                .($this->filtro<>""?"Where {$this->filtro}":"");
                "ORDER BY estoqueclientes.DtHr desc OFFSET (100 * {$this->params['pag']}) - 100 ROWS FETCH NEXT 100 ROWS ONLY";
        $retorno = $this->conexao->consultar($SQL, $this->fParmsArray, $this->fParmsNameArray, $this->usuarioacesso->Codigo, false);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            return false;
        }if ($this->conexao->contagem === 0) {
            $this->msg = "Nenhum registro encontrado";
            return false;
        }
        foreach ($retorno as $key => $value) {
            $suprimentocliente = new suprimentocliente();
            $suprimentocliente->setCliente($value['cliente']);
            $suprimentocliente->setAgencia($value['agencia']);
            $suprimentocliente->setSuprimento($value['suprimento']);
            $suprimentocliente->setSigla($value['sigla']);
            $suprimentocliente->setDisponibilizado($value['disponibilizado']);
            $suprimentocliente->setDisponivel($value['disponivel']);
            $suprimentocliente->setUtilizado($value['utilizado']);
            $this->suprimentocliente[$key] = $suprimentocliente;
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
        $SQL = "Select  Clientes.nome cliente, Suprimentos.nome suprimento,
                        Suprimentos.sigla, EstoqueClientes.Qtde disponibilizado, 
                        ISNULL(remessa.total, 0) utilizado, agencia.nome agencia,
                        (EstoqueClientes.Qtde-ISNULL(remessa.total, 0)) disponivel "
                . "From EstoqueClientes "
                . "Inner join Clientes on EstoqueClientes.Cliente = clientes.id "
                ." inner join agencia on agencia.id = clientes.agencia_id "
                . "Inner join suprimentos on suprimentos.ID = EstoqueClientes.Suprimento "
                . "Left join VW_REMESSAITENS_SUPRIMENTO_QTDE remessa on remessa.Cliente = EstoqueClientes.cliente and remessa.Sigla = suprimentos.Sigla " 
            .($this->filtro<>""?"Where {$this->filtro}":"").
                " Order by Clientes.nome ";
        $retorno = $this->conexao->consultar($SQL, $this->fParmsArray, $this->fParmsNameArray, $this->usuarioacesso->Codigo, false);
        if ($retorno === false) {
            exit("ajax_htm\nn0\nn{$this->conexao->mensagem}");
        }if ($this->conexao->contagem === 0) {
            exit("ajax_htm\nn0\nnNenhum registro encontrado");
        }
        $wHeader = array(); $wRegistros = array();
        $wHeader[] = 'Agencia'; $wRegistros[] = 'agencia';
        $wHeader[] = 'Cliente'; $wRegistros[] = 'cliente';
        $wHeader[] = 'Suprimento'; $wRegistros[] = 'suprimento';
        $wHeader[] = 'Sigla'; $wRegistros[] = 'sigla';
        $wHeader[] = 'Disponibilizado'; $wRegistros[] = 'disponibilizado';
        $wHeader[] = 'Utilizado'; $wRegistros[] = 'utilizado';
        $wHeader[] = 'Disponivel'; $wRegistros[] = 'disponivel';
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
    
    
    public function addSuprimento(){
        $SQL = "SELECT id, nome FROM Suprimentos Order by nome ";
        $retorno = $this->conexao->consultar($SQL, array(), array(), $this->usuarioacesso->Codigo, false);
        if ($retorno===false){
            return false;
        }
        if (is_array($retorno)&&(count($retorno)>0)){
           
            foreach ($retorno as $value) {
                $suprimento = new item();
                $suprimento->setId($value['id']);
                $suprimento->setNome(utf8_encode($value['nome']));
                $this->suprimento[] = $suprimento;
            }
        }   
    }
    
    
}
