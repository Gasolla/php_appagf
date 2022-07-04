<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of estoqueclienteController
 *
 * @author marcelo
 */
require 'classes/item.php';
class estoqueclienteController {
    //put your code here
    
    private $params;
    private $conexao;
    private $filtro;
    private $agencia = array();
    private $cliente = array();
    private $estoquecliente = array();
    private $fArray = array();
    private $fDescricaoArray = array();
    private $fParmsArray = array();
    private $fParmsNameArray = array();
    private $usuarioacesso;
    public $msg;
    public $count = 0;
    public $descricaofiltro;
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

    
    function getEstoquecliente() {
        return $this->estoquecliente;
    }

    public function __construct($params, $conexao, $usuarioacesso) {
        $this->params = $params;
        $this->conexao = $conexao;
        $this->usuarioacesso = $usuarioacesso;
    }
    
    
    private function preparafiltro() {
        if ($this->usuarioacesso->Cliente > 0) {
            $this->fDescricaoArray[] = "(EstoqueClientes.Cliente = {$this->usuarioacesso->Cliente})";
            $this->fArray[] = "(EstoqueClientes.Cliente = ?)";
            $this->fParmsArray[] = $this->usuarioacesso->Cliente;
            $this->fParmsNameArray[] = "cliente"; 
        }
        
         if ($this->usuarioacesso->Agencia > 0) {
            $this->fDescricaoArray[] = "(Clientes.Agencia_Id = {$this->usuarioacesso->Agencia})";
            $this->fArray[] = "(Clientes.Agencia_Id = ?)";
            $this->fParmsArray[] = $this->usuarioacesso->Agencia;
            $this->fParmsNameArray[] = "agencia";
        }
        if (isset($this->params['agencia_id']) && ($this->params['agencia_id'] !== "")) {
            $this->fDescricaoArray[] = "(ISNULL(Clientes.Agencia_Id, 0) = ".crypto::decrypt($this->params['agencia_id']).")";
            $this->fArray[] = "(ISNULL(Clientes.Agencia_Id, 0) = ?)";
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
            $this->fDescricaoArray[] = "(EstoqueClientes.suprimento = '".crypto::decrypt($this->params['suprimento_id'])."')";
            $this->fArray[] = "(EstoqueClientes.suprimento = ?)";
            $this->fParmsArray[] = crypto::decrypt($this->params['suprimento_id']);
            $this->fParmsNameArray[] = "suprimento_id";
        }
        if (isset($this->params['cidata']) && ($this->params['cidata'] !== "")) {
            $this->fDescricaoArray[] = "(EstoqueClientes.DtHr >= '{$this->params['cidata']} 00:00:00')";
            $this->fArray[] = "(EstoqueClientes.DtHr >= ?)";
            $this->fParmsArray[] = "{$this->params['cidata']} 00:00:00";
            $this->fParmsNameArray[] = "cidata";
        }
        if (isset($this->params['cfdata']) && ($this->params['cfdata'] !== "")) {
            $this->fDescricaoArray[] = "(EstoqueClientes.DtHr <= '{$this->params['cfdata']} 23:59:59')";
            $this->fArray[] = "(EstoqueClientes.DtHr <= ?)";
            $this->fParmsArray[] = "{$this->params['cfdata']} 23:59:59";
            $this->fParmsNameArray[] = "cfdata";
        }
        $this->filtro = implode(' and ', $this->fArray);
        if (count($this->fDescricaoArray)>0){
            $this->descricaofiltro = crypto::encrypt(implode(' and ', $this->fDescricaoArray)); 
        }
    }
    
    public function lista(){
        $this->preparafiltro();
        $this->conexao->setRequisicao(true);
        $SQL = "Select count(*) total from estoqueclientes "
                . "inner join clientes on clientes.id = estoqueclientes.cliente "
                . "inner join agencia on agencia.id = clientes.agencia_id "
                . "inner join suprimentos on suprimentos.id = estoqueclientes.suprimento "
                .($this->filtro<>""?"Where {$this->filtro}":"");
        $retorno = $this->conexao->consultar($SQL, $this->fParmsArray, $this->fParmsNameArray, $this->usuarioacesso->Codigo, false);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            return false;
        }
        $this->count = $retorno[0]['total'];
        $SQL = "Select  clientes.nome cliente, agencia.nome agencia, Suprimentos.nome suprimento, 
                        qtde, estoqueclientes.id, 
                        Convert(nvarchar(10), estoqueclientes.DtHr, 103) data
                from estoqueclientes " 
                . "inner join clientes on clientes.id = estoqueclientes.cliente "
                . "inner join agencia on agencia.id = clientes.agencia_id "
                . "inner join suprimentos on suprimentos.id = estoqueclientes.suprimento "
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
            $estoquecliente = new estoquecliente();
            $estoquecliente->setId($value['id']);
            $estoquecliente->setCliente($value['cliente']);
            $estoquecliente->setSuprimento($value['suprimento']);
            $estoquecliente->setData($value['data']);
            $estoquecliente->setQtde($value['qtde']);
            $estoquecliente->setAgencia($value['agencia']);
            $this->estoquecliente[$key] = $estoquecliente;
        }
        return true;
    }
    
    public function index($codigo) {
        $estoquecliente = new estoquecliente();
        $codigo = ((crypto::decrypt($codigo)===false)?0:crypto::decrypt($codigo));
        if ($codigo === 0) {
            $estoquecliente->setAgencia((isset($this->params['agencia'])? crypto::decrypt($this->params['agencia']):$this->usuarioacesso->Agencia));
            $this->params['agencia'] = (isset($this->params['agencia'])?$this->params['agencia']: crypto::encrypt($this->usuarioacesso->Agencia));
            $this->estoquecliente = $estoquecliente;
            return true;
        }

        $this->preparafiltro();
        $this->conexao->setRequisicao(true);
        $SQL = "Select estoqueclientes.id, estoqueclientes.cliente, "
                . "estoqueclientes.suprimento, estoqueclientes.qtde, "
                . "clientes.agencia_id agencia "
                . "from estoqueclientes "
                . "inner join clientes on clientes.id = estoqueclientes.cliente "
                . "inner join agencia on agencia.id = clientes.agencia_id "
                . ($this->filtro==""?"Where":"Where {$this->filtro} and ")
                . " estoqueclientes.id = ? ";
        $this->fParmsArray[] = $codigo;
        $this->fParmsNameArray[] = 'codigo';
        $retorno = $this->conexao->consultar($SQL, $this->fParmsArray, $this->fParmsNameArray, $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            return false;
        }if ($this->conexao->contagem === 0) {
            $this->msg = "Nenhum registro encontrado";
            return false;
        }
        $this->count = $this->conexao->contagem;
        
        $estoquecliente->setCliente($retorno[0]['cliente']);
        $estoquecliente->setSuprimento($retorno[0]['suprimento']);
        $estoquecliente->setId($retorno[0]['id']);
        $estoquecliente->setQtde($retorno[0]['qtde']);
        $estoquecliente->setAgencia((isset($this->params['agencia'])? crypto::decrypt($this->params['agencia']):$retorno[0]['agencia']));
        $this->estoquecliente = $estoquecliente;
        $this->params['agencia'] = (isset($this->params['agencia'])?$this->params['agencia']: crypto::encrypt($estoquecliente->getAgencia()));
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
        $SQL = "Select  clientes.nome cliente, Suprimentos.nome suprimento, 
                        qtde, estoqueclientes.id,
                        Convert(nvarchar(10), estoqueclientes.DtHr, 103) data, 
                        agencia.nome agencia
                from estoqueclientes " 
                . "inner join clientes on clientes.id = estoqueclientes.cliente "
                . "inner join agencia on agencia.id = clientes.agencia_id "
                . "inner join suprimentos on suprimentos.id = estoqueclientes.suprimento "
                .($this->filtro<>""?"Where {$this->filtro}":"").
                " Order by estoqueclientes.DtHr";
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
        $wHeader[] = 'Quantidade'; $wRegistros[] = 'qtde';
        $wHeader[] = 'Data'; $wRegistros[] = 'data';
        $caminho = ajusta_temporario_excel($this->usuarioacesso->Codigo)."excel.xls";
        $excel = new excel($wHeader, $wRegistros, $retorno);
        $excel->gerar($caminho);        
    }
    
    public function incluir() {
        if (!$this->usuarioacesso->Incluir){
            exit("ajax_txt\nn0\nnUsuário não possui permissão de inclusão.");    
        }
        $this->conexao->setRequisicao(true);
        $retorno = $this->conexao->inicializar_transacao();
        if ($retorno === false) {
            exit("ajax_txt\nn0\nn" . $this->conexao->mensagem);
        }
        $wC = array(
                        'cliente', 
                        'suprimento', 
                        'qtde', 
                        'usuario'
        );
        $wV = array(
                        crypto::decrypt($this->params['cliente']), 
                        crypto::decrypt($this->params['suprimento']), 
                        $this->params['qtde'],
                        $this->usuarioacesso->Codigo
        );
        $retorno = $this->conexao->inserir("estoqueclientes", $wC, $wV, $wC, $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            $this->conexao->cancelar_transacao();
            exit("ajax_htm\nn0\nn{$this->msg}");
        }
        $retorno = $this->conexao->efetivar_transacao();
        if ($retorno === false) {
            exit("ajax_htm\nn0\nn{$this->conexao->mensagem}");
        }

        exit("ajax_htm\nn1\nnEstoque cliente salvo com sucesso!\nn{$this->params['url']}&acao=incluir");
    }
    
    public function alterar() {
        if (!$this->usuarioacesso->Alterar){
            exit("ajax_txt\nn0\nnUsuário não possui permissão de alteração.");    
        }
        $codigo = crypto::decrypt($this->params['codigo']);
        if ($codigo===false){
            exit("ajax_txt\nn0\nnCódigo invalido.");    
        }
        
        $this->conexao->setRequisicao(true);
        $retorno = $this->conexao->inicializar_transacao();
        if ($retorno === false) {
            exit("ajax_txt\nn0\nn" . $this->conexao->mensagem);
        }
        $wC = array(
                        'cliente', 
                        'suprimento', 
                        'qtde', 
                        'usuario'
        );
        $wV = array(
                        crypto::decrypt($this->params['cliente']), 
                        crypto::decrypt($this->params['suprimento']), 
                        $this->params['qtde'],
                        $this->usuarioacesso->Codigo, 
                        $codigo
        );
        $wP = $wV;
        $wP[] = 'codigo';
        $retorno = $this->conexao->alterar("estoqueclientes", $wC, $wV, "Where id = ? ", $wP, $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            $this->conexao->cancelar_transacao();
            exit("ajax_htm\nn0\nn{$this->msg}");
        }
        
        $retorno = $this->conexao->efetivar_transacao();
        if ($retorno === false) {
            exit("ajax_htm\nn0\nn{$this->conexao->mensagem}");
        }

        exit("ajax_htm\nn1\nnEstoque cliente salvo com sucesso!\nn{$this->params['url']}&acao=index");
    }
    
    public function excluir(){
        if (!$this->usuarioacesso->Excluir){
            exit("ajax_txt\nn0\nnUsuário não possui permissão de exclusão.");    
        }
        $codigo = crypto::decrypt($this->params['codigo']);
        if ($codigo===false){
            exit("ajax_txt\nn0\nnCódigo invalido.");    
        }
        $this->conexao->setRequisicao(true);
        $SQL = "Delete from estoqueclientes Where id = ? ";
        $retorno = $this->conexao->deletar($SQL, array($codigo), array('codigo'), $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            exit("ajax_htm\nn0\nn{$this->msg}");
        }
        exit("ajax_htm\nn1\nnEstoque cliente removido com sucesso!\nn{$this->params['url']}&acao=index");
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
    
    public function addCliente($incluir){
        $SQL = "SELECT id, Ltrim(Concat(apelido, ' ', nome)) nome FROM CLIENTES where (id > 0) "
                .($this->usuarioacesso->Cliente>0?" and id = {$this->usuarioacesso->Cliente} " : "")
                .($this->usuarioacesso->Agencia>0?" and agencia_id = {$this->usuarioacesso->Agencia}":"")
                .($incluir?(isset($this->params['agencia'])?" and agencia_id = ".crypto::decrypt($this->params['agencia']):" and agencia_id = 0"):"")
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
