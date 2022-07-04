<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of agendamentorelController
 *
 * @author Marcelo
 */
require 'classes/item.php';
class agendamentorelController {
    //put your code here
    //put your code here
    private $params;
    private $conexao;
    private $filtro;
    private $agendamentorel = array();
    private $fArray = array();
    private $fDescricaoArray = array();
    private $comercial = array();
    private $fParmsArray = array();
    private $fParmsNameArray = array();
    private $motorista = array();
    private $usuarioacesso;
    public $msg;
    public $count = 0;
    public $descricaofiltro;
    private $agencia = array();
    private $cliente = array();
    
        
    function getAgencia(){
        return $this->agencia;
    }
    
    function getComercial(){
        return $this->comercial;
    }
    
    function getMotorista() {
        return $this->motorista;
    }
        
    function getCliente(){
        return $this->cliente;
    }
    
    
    function getAgendamentorel() {
        return $this->agendamentorel;
    }


     public function __construct($params, $conexao, $usuarioacesso) {
        $this->params = $params;
        $this->conexao = $conexao;
        $this->usuarioacesso = $usuarioacesso;
    }
    
    private function preparafiltro() {
        if ($this->usuarioacesso->Cliente > 0) {
            $this->fDescricaoArray[] = "(cliente_id = {$this->usuarioacesso->Cliente})";
            $this->fArray[] = "(cliente_id = ?)";
            $this->fParmsArray[] = $this->usuarioacesso->Cliente;
            $this->fParmsNameArray[] = "cliente";
        }
        if ($this->usuarioacesso->Agencia > 0) {
            $this->fDescricaoArray[] = "(agencia_Id = {$this->usuarioacesso->Agencia})";
            $this->fArray[] = "(agencia_Id = ?)";
            $this->fParmsArray[] = $this->usuarioacesso->Agencia;
            $this->fParmsNameArray[] = "agencia";
        }
        if (isset($this->params['agencia_id']) && ($this->params['agencia_id'] !== "")) {
            $this->fDescricaoArray[] = "(agencia_Id = ".crypto::decrypt($this->params['agencia_id']).")";
            $this->fArray[] = "(agencia_Id = ?)";
            $this->fParmsArray[] = crypto::decrypt($this->params['agencia_id']);
            $this->fParmsNameArray[] = "agencia2";
        }
        
        if (isset($this->params['cliente_id']) && ($this->params['cliente_id'] !== "")) {
            $this->fDescricaoArray[] = "(cliente_id = '".crypto::decrypt($this->params['cliente_id'])."')";
            $this->fArray[] = "(cliente_id = ?)";
            $this->fParmsArray[] = crypto::decrypt($this->params['cliente_id']);
            $this->fParmsNameArray[] = "cliente_id";
        }
        if (isset($this->params['cidata']) && ($this->params['cidata'] !== "")) {
            $this->fDescricaoArray[] = "(Data >= '{$this->params['cidata']}')";
            $this->fArray[] = "(Data >= ?)";
            $this->fParmsArray[] = "{$this->params['cidata']}";
            $this->fParmsNameArray[] = "cidata";
        }
        if (isset($this->params['cfdata']) && ($this->params['cfdata'] !== "")) {
            $this->fDescricaoArray[] = "(Data <= '{$this->params['cfdata']}')";
            $this->fArray[] = "(Data <= ?)";
            $this->fParmsArray[] = "{$this->params['cfdata']} 23:59:59";
            $this->fParmsNameArray[] = "cfdata";
        }
        if (isset($this->params['status']) && ($this->params['status'] !== "")) {
            $this->fDescricaoArray[] = "(status= '" . ($this->params['status']) . "')";
            $this->fArray[] = "(status = ?)";
            $this->fParmsArray[] = ($this->params['status']);
            $this->fParmsNameArray[] = "status";
        }
        if (isset($this->params['usuario_id']) && ($this->params['usuario_id'] !== "")) {
            $this->fDescricaoArray[] = "(usuario_id = '" . crypto::decrypt($this->params['usuario_id']) . "')";
            $this->fArray[] = "(usuario_id = ?)";
            $this->fParmsArray[] = crypto::decrypt($this->params['usuario_id']);
            $this->fParmsNameArray[] = "usuario_id";
        }
        
        if (isset($this->params['comercial_id']) && ($this->params['comercial_id'] !== "")) {
            $this->fDescricaoArray[] = "(comercial_id = '" . crypto::decrypt($this->params['comercial_id']) . "')";
            $this->fArray[] = "(comercial_id = ?)";
            $this->fParmsArray[] = crypto::decrypt($this->params['comercial_id']);
            $this->fParmsNameArray[] = "comercial_id";
        }
        
        if (isset($this->params['statuscoleta']) && ($this->params['statuscoleta'] !== "")) {
            $this->fDescricaoArray[] = "(statuscoleta= '" . ($this->params['statuscoleta']) . "')";
            $this->fArray[] = "(statuscoleta = ?)";
            $this->fParmsArray[] = ($this->params['statuscoleta']);
            $this->fParmsNameArray[] = "statuscoleta";
        }
        
        $this->filtro = implode(' and ', $this->fArray);
        if (count($this->fDescricaoArray)>0){
            $this->descricaofiltro = crypto::encrypt(implode(' and ', $this->fDescricaoArray)); 
        }
    }
    
    public function lista(){
        $this->conexao->setRequisicao(true);
        $this->preparafiltro();
        $SQL = "Select count(*) total from VW_AGENDAMENTORELATORIO "
                .($this->filtro<>""?"Where {$this->filtro}":"");
        $retorno = $this->conexao->consultar($SQL, $this->fParmsArray, $this->fParmsNameArray, $this->usuarioacesso->Codigo, false);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            return false;
        }
        $this->count = $retorno[0]['total'];
        $SQL = "Select  statusdescricao status, agencia, 
                        cliente, datainput, datarealizar,
                        datacoleta, statuscoletadescricao statuscoleta,
                        qtde, tipo, valor,   
                        comercial, motorista, datarotainicio	  
                From VW_AGENDAMENTORELATORIO " 
                .($this->filtro<>""?"Where {$this->filtro}":"")
                ."ORDER BY cliente OFFSET (100 * {$this->params['pag']}) - 100 ROWS FETCH NEXT 100 ROWS ONLY";
        $retorno = $this->conexao->consultar($SQL, $this->fParmsArray, $this->fParmsNameArray, $this->usuarioacesso->Codigo, false);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            return false;
        }if ($this->conexao->contagem === 0) {
            $this->msg = "Nenhum registro encontrado";
            return false;
        }
        foreach ($retorno as $key => $value) {
            $agendamentorel = new agendamentorel();
            $agendamentorel->setCliente(utf8_encode($value['cliente']));
            $agendamentorel->setAgencia($value['agencia']);
            $agendamentorel->setComercial($value['comercial']);
            $agendamentorel->setDatacoleta($value['datacoleta']);
            $agendamentorel->setDatainput($value['datainput']);
            $agendamentorel->setDatarealizar($value['datarealizar']);
            $agendamentorel->setMotorista($value['motorista']);
            $agendamentorel->setQtde($value['qtde']);
            $agendamentorel->setStatus($value['status']);
            $agendamentorel->setStatuscoleta($value['statuscoleta']);
            $agendamentorel->setTipo($value['tipo']);
            $agendamentorel->setDatarotainicio($value['datarotainicio']);
            $agendamentorel->setValor(number_format(doubleval($value['valor']), 2, ",", "."));
            $this->agendamentorel[$key] = $agendamentorel;
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
        $SQL = "Select  statusdescricao status, agencia, 
                        cliente, datainput, datarealizar,
                        datacoleta, statuscoletadescricao statuscoleta,
                        qtde, tipo, valor,   
                        comercial, motorista, fixo, datarotainicio	  
                From VW_AGENDAMENTORELATORIO " 
                .($this->filtro<>""?"Where {$this->filtro}":"").
                " Order by cliente ";
        $retorno = $this->conexao->consultar($SQL, $this->fParmsArray, $this->fParmsNameArray, $this->usuarioacesso->Codigo, false);
        if ($retorno === false) {
            exit("ajax_htm\nn0\nn{$this->conexao->mensagem}");
        }if ($this->conexao->contagem === 0) {
            exit("ajax_htm\nn0\nnNenhum registro encontrado");
        }
        $wHeader = array(); $wRegistros = array();
        $wHeader[] = 'Status'; $wRegistros[] = 'status';
        $wHeader[] = 'Agencia'; $wRegistros[] = 'agencia';
        $wHeader[] = 'Cliente'; $wRegistros[] = 'cliente';
        $wHeader[] = 'Data input sistema'; $wRegistros[] = 'datainput';
        $wHeader[] = 'Data ser realizado'; $wRegistros[] = 'datarealizar';
        $wHeader[] = 'Data inicio rota'; $wRegistros[] = 'datarotainicio';
        $wHeader[] = 'Data efetuada coleta'; $wRegistros[] = 'datacoleta';
        $wHeader[] = 'Status Coleta'; $wRegistros[] = 'statuscoleta';
        $wHeader[] = 'Qtde objetos'; $wRegistros[] = 'qtde';
        $wHeader[] = 'Tipo objetos'; $wRegistros[] = 'tipo';
        $wHeader[] = 'Valor'; $wRegistros[] = 'valor';
        $wHeader[] = 'Comercial'; $wRegistros[] = 'comercial';
        $wHeader[] = 'Motorista'; $wRegistros[] = 'motorista';
        $wHeader[] = 'Coleta Fixa'; $wRegistros[] = 'fixo';
        
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
    
    public function addMotorista(){
        $SQL = "SELECT id, nome FROM usuarios where motoqueiro = 1 "
                .($this->usuarioacesso->Agencia>0?" and agencia_id = {$this->usuarioacesso->Agencia}":"")
                . " Order by nome ";
        $retorno = $this->conexao->consultar($SQL, array(), array(), $this->usuarioacesso->Codigo, false);
        if ($retorno===false){
            return false;
        }
        if (is_array($retorno)&&(count($retorno)>0)){
           
            foreach ($retorno as $value) {
                $motorista = new item();
                $motorista->setId($value['id']);
                $motorista->setNome(utf8_encode($value['nome']));
                $this->motorista[] = $motorista;
            }
        }   
    }
    
    public function addComercial(){
        $SQL = "Select id, nome from comercial where (id > 0) ".
                ($this->usuarioacesso->Agencia>0?" and agencia_id = {$this->usuarioacesso->Agencia}":"").
                " Order by nome ";
        $retorno = $this->conexao->consultar($SQL, array(), array(), $this->usuarioacesso->Codigo, false);
        if ($retorno===false){
            return false;
        }
        if (is_array($retorno)&&(count($retorno)>0)){
            foreach ($retorno as $value) {
                $comercial = new item();
                $comercial->setId($value['id']);
                $comercial->setNome(utf8_encode($value['nome']));
                $this->comercial[] = $comercial;
            }
        }   
    }
    
    
}
