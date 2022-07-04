<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of usuariocliController
 *
 * @author Marcelo
 */
require 'classes/item.php';
class usuariocliController {
    //put your code here
    private $params;
    private $conexao;
    private $filtro;
    private $usuario = array();
    private $fArray = array();
    private $agencia = array();
    private $cliente = array();
    private $fDescricaoArray = array();
    private $fParmsArray = array();
    private $fParmsNameArray = array();
    private $usuarioacesso;
    public $msg;
    public $count = 0;
    public $descricaofiltro;

    function getUsuario() {
        return $this->usuario;
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

    private function preparafiltro() {
        if ($this->usuarioacesso->Agencia > 0) {
            $this->fDescricaoArray[] = "(clientes.Agencia_Id = {$this->usuarioacesso->Agencia})";
            $this->fArray[] = "(clientes.Agencia_Id = ?)";
            $this->fParmsArray[] = $this->usuarioacesso->Agencia;
            $this->fParmsNameArray[] = "agencia";
        }
        if ($this->usuarioacesso->Cliente > 0) {
            $this->fDescricaoArray[] = "(clientes.Cliente_Id = {$this->usuarioacesso->Cliente})";
            $this->fArray[] = "(clientes.Cliente_Id = ?)";
            $this->fParmsArray[] = $this->usuarioacesso->Cliente;
            $this->fParmsNameArray[] = "cliente1";
        }
        $this->fDescricaoArray[] = "(ISNULL(usercli.inativo, 'F') = 'F')";
        $this->fArray[] = "(ISNULL(usercli.inativo, 'F') = ?)";
        $this->fParmsArray[] = 'F';
        $this->fParmsNameArray[] = "inativo"; 
        
        if (isset($this->params['agencia_id']) && ($this->params['agencia_id'] !== "")) {
            $this->fDescricaoArray[] = "(ISNULL(clientes.Agencia_Id, 0) = ".crypto::decrypt($this->params['agencia_id']).")";
            $this->fArray[] = "(ISNULL(clientes.Agencia_Id, 0) = ?)";
            $this->fParmsArray[] = crypto::decrypt($this->params['agencia_id']);
            $this->fParmsNameArray[] = "agencia2";
        }
        
        if (isset($this->params['nome']) && ($this->params['nome'] !== "")) {
            $this->fDescricaoArray[] = "(usercli.nome like '%{$this->params['nome']}%')";
            $this->fArray[] = "(usercli.nome like ?)";
            $this->fParmsArray[] = "%". paramstostring($this->params['nome'])."%";
            $this->fParmsNameArray[] = "nome";    
        }
        if (isset($this->params['usuario']) && ($this->params['usuario'] !== "")) {
            $this->fArray[] = "(usercli.usuario like '%{$this->params['usuario']}%')";
            $this->fArray[] = "(usercli.usuario like ?)";
            $this->fParmsArray[] = "%". paramstostring($this->params['usuario'])."%";
            $this->fParmsNameArray[] = "usuario";    
            
        }
        $this->filtro = implode(' and ', $this->fArray);
        $this->descricaofiltro = crypto::encrypt(implode(' and ', $this->fDescricaoArray)); 
    }

    public function lista() {
        $this->conexao->setRequisicao(true);
        $this->preparafiltro();
        $SQL = "Select count(*) total from usercli "
                . "left join clientes on clientes.id = usercli.cliente_id "
                . "left join agencia on agencia.id = clientes.agencia_id "
                . "where " . $this->filtro;
        $retorno = $this->conexao->consultar($SQL, $this->fParmsArray, $this->fParmsNameArray, $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            return false;
        }
        $this->count = $retorno[0]['total'];
        $SQL = "Select usercli.id, usercli.nome, usercli.sobrenome, usercli.usuario, "
                . " usercli.senha, usercli.cpf, usercli.acesso,  "
                . " ISNULL(agencia.nome, 'ADMINISTRADOR') agencia, "
                . " ISNULL(clientes.nome, 'ADMINISTRADOR') cliente "
                . "from usercli "
                . "left join clientes on clientes.id = usercli.cliente_id "
                . "left join agencia on agencia.id = clientes.agencia_id "
                . "where " . $this->filtro .
                " ORDER BY usercli.nome OFFSET (100 * {$this->params['pag']}) - 100 ROWS FETCH NEXT 100 ROWS ONLY";
        $retorno = $this->conexao->consultar($SQL, $this->fParmsArray, $this->fParmsNameArray, $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            return false;
        }if ($this->conexao->contagem === 0) {
            $this->msg = "Nenhum registro encontrado";
            return false;
        }
        foreach ($retorno as $key => $value) {
            $usuario = new usuario();
            $usuario->setNome($value['nome']);
            $usuario->setUsuario($value['usuario']);
            $usuario->setId($value['id']);
            $usuario->setCliente($value['cliente']);
            $usuario->setCpfcpnj($value['cpf']);
            $usuario->setSenha($value['senha']);
            $usuario->setAgencia($value['agencia']);
            $usuario->setAcesso($value['acesso']);
            $usuario->setSobrenome($value['sobrenome']);
            
            $this->usuario[$key] = $usuario;
        }
        return true;
    }

    public function index($codigo) {
        $usuario = new usuario();
        $codigo = ((crypto::decrypt($codigo)===false)?0:crypto::decrypt($codigo));
        if ($codigo === 0) {
            $usuario->setAgencia((isset($this->params['agencia'])?$this->params['agencia']:""));
            $this->usuario = $usuario;
            return true;
        }

        $this->conexao->setRequisicao(true);
        $this->preparafiltro();
        $SQL = "Select usercli.id, usercli.nome, usercli.sobrenome, usercli.usuario, "
                . " usercli.senha, usercli.cpf, usercli.acesso,  "
                . " ISNULL(agencia_id, 0) agencia, ISNULL(cliente_id, 0) cliente "
                . "from usercli "
                . "left join clientes on clientes.id = usercli.cliente_id "
                . "left join agencia on agencia.id = clientes.agencia_id "
                . "where " . $this->filtro .
                " and usercli.id = ? ";
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
        $usuario->setNome($retorno[0]['nome']);
        $usuario->setUsuario($retorno[0]['usuario']);
        $usuario->setId($retorno[0]['id']);
        $usuario->setCliente($retorno[0]['cliente']);
        $usuario->setSobrenome($retorno[0]['sobrenome']);
        $usuario->setSenha($retorno[0]['senha']);
        $usuario->setAcesso($retorno[0]['acesso']);
        $usuario->setAgencia((isset($this->params['agencia'])?$this->params['agencia']:crypto::encrypt($retorno[0]['agencia'])));
        $this->usuario = $usuario;
        $this->params['agencia'] = $usuario->getAgencia();
        return true;
    }

    private function getPassword($senha){
        $json_file = file_get_contents("http://localhost:3333/usuario/crypt/".$senha);   
        $json_str = json_decode($json_file, true);
        return $json_str['senha'];
    }
    
    public function incluir() {
        if (!$this->usuarioacesso->Incluir){
            exit("ajax_txt\nn0\nnUsuário não possui permissão de inclusão.");    
        }
        if ($this->checaUsuario(paramstostring($this->params['usuario']), 0)){
            exit("ajax_txt\nn0\nn".$this->msg); 
        }
        $password = $this->getPassword($this->params['senha']);
        $this->conexao->setRequisicao(true);
        $retorno = $this->conexao->inicializar_transacao();
        if ($retorno === false) {
            exit("ajax_txt\nn0\nn" . $this->conexao->mensagem);
        }
        
        $wC = array(
                        'nome',
                        'sobrenome',
                        'cliente_id',
                        'usuario',
                        'acesso', 
                        'inativo', 
                        'senha'
        );
        $wV = array(
                        paramstostring($this->params['nome']), 
                        paramstostring($this->params['sobrenome']), 
                        crypto::decrypt($this->params['cliente_id']), 
                        paramstostring($this->params['usuario']), 
                        (crypto::decrypt($this->params['cliente_id'])>0?'U':'A'), 
                        'F',
                        $password
        );
        
        $retorno = $this->conexao->inserir("usercli", $wC, $wV, $wC, $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            $this->conexao->cancelar_transacao();
            exit("ajax_htm\nn0\nn{$this->msg}");
        }
        
        $retorno = $this->conexao->efetivar_transacao();
        if ($retorno === false) {
            exit("ajax_htm\nn0\nn{$this->conexao->mensagem}");
        }

        exit("ajax_htm\nn1\nnUsuario salvo com sucesso!\nn{$this->params['url']}&acao=incluir");
    }

    private function checaUsuario($usernome, $codigo){
        $SQL = "select count(*) total from usercli "
                . "where id <> ? and usuario = ? ";
        $P = array('codigo', 'usuario');
        $V = array($codigo, $usernome);
        $retorno = $this->conexao->consultar($SQL, $V, $P, $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            return true;
        }if ($retorno[0]['total'] > 0) {
            $this->msg = "Nome de usuario já existente!";
            return true;
        }
        
        return false;
        
    }
    
    public function alterar() {
        if (!$this->usuarioacesso->Alterar){
            exit("ajax_txt\nn0\nnUsuário não possui permissão de alteração.");    
        }
        $codigo = crypto::decrypt($this->params['codigo']);
        if ($codigo===false){
            exit("ajax_txt\nn0\nnCódigo invalido.");    
        }
        
        if ($this->checaUsuario(paramstostring($this->params['usuario']), $codigo)){
            exit("ajax_txt\nn0\nn".$this->msg); 
        }
        
        $password = $this->params['senha'];
        $SQL = "SELECT senha from usercli where id = ?";
        $retorno = $this->conexao->consultar($SQL, array($codigo), array('codigo'), $this->usuarioacesso->Codigo);
        if ($retorno === False) {
            exit("ajax_htm\nn0\nn{$this->conexao->mensagem}");
        }else if ($this->conexao->contagem===0){
            exit("ajax_htm\nn0\nnFalha na alteração. Usuário não encontrado. ");    
        }
        if ($this->params['senha'] !== $retorno[0]['senha']) {
            $password = $this->getPassword($this->params['senha']);
        }
        
        $this->conexao->setRequisicao(true);
        $retorno = $this->conexao->inicializar_transacao();
        if ($retorno === false) {
            exit("ajax_txt\nn0\nn" . $this->conexao->mensagem);
        }
        $wC = array(
                        'nome',
                        'sobrenome',
                        'cliente_id',
                        'usuario',
                        'acesso', 
                        'inativo', 
                        'senha', 
                        
        );
        $wV = array(
                        paramstostring($this->params['nome']), 
                        paramstostring($this->params['sobrenome']), 
                        crypto::decrypt($this->params['cliente_id']), 
                        paramstostring($this->params['usuario']), 
                        (crypto::decrypt($this->params['cliente_id'])>0?'U':'A'), 
                        'F',
                        $password, 
                        $codigo
                        
        );
        $wP = $wC;
        $wP[] = 'codigo_id';
        $retorno = $this->conexao->alterar("usercli", $wC, $wV, "Where id = ?", $wP, $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            $this->conexao->cancelar_transacao();
            exit("ajax_htm\nn0\nn{$this->msg}");
        }

        $retorno = $this->conexao->efetivar_transacao();
        if ($retorno === false) {
            exit("ajax_htm\nn0\nn{$this->conexao->mensagem}");
        }

        exit("ajax_htm\nn1\nnUsuario salvo com sucesso!\nn{$this->params['url']}&acao=index");
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
        $wC = array('Inativo');
        $wV = array('T');
        $wP = $wC;
        $wP[] = 'codigo_id';
        $retorno = $this->conexao->alterar("usercli", $wC, $wV, "Where id = ?", $wP, $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            exit("ajax_htm\nn0\nn{$this->msg}");
        }
        exit("ajax_htm\nn1\nnUsuario removido com sucesso!\nn{$this->params['url']}&acao=index");
    }
    
     public function excel(){
        if (!$this->usuarioacesso->Gerar){
            exit("ajax_txt\nn0\nnUsuário não possui permissão de geração.");    
        }
        $this->conexao->setRequisicao(true);
        $this->filtro = crypto::decrypt($this->params['filtro']);
        $SQL = "Select usercli.nome, usercli.usuario, "
                . " ISNULL(agencia_id, 0) agencia, ISNULL(cliente_id, 0) cliente "
                . "from usercli "
                . "left join clientes on clientes.id = usercli.cliente_id "
                . "left join agencia on agencia.id = clientes.agencia_id "         .($this->filtro<>""?"Where {$this->filtro}":"").
                " Order by usercli.nome ";
        $retorno = $this->conexao->consultar($SQL, array(), array(), $this->usuarioacesso->Codigo, false);
        if ($retorno === false) {
            exit("ajax_htm\nn0\nn{$this->conexao->mensagem}");
        }if ($this->conexao->contagem === 0) {
            exit("ajax_htm\nn0\nnNenhum registro encontrado");
        }
        $wHeader = array(); $wRegistros = array();
        $wHeader[] = 'Agencia'; $wRegistros[] = 'agencia';
        $wHeader[] = 'Nome'; $wRegistros[] = 'nome';
        $wHeader[] = 'Usuario'; $wRegistros[] = 'usuario';
        $wHeader[] = 'Cliente'; $wRegistros[] = 'cliente';
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
         if ($this->usuarioacesso->Agencia===0){
                $agencia = new item();
                $agencia->setId(0);
                $agencia->setNome("ADMINISTRADOR");
                $this->agencia[] = $agencia;
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
                .(isset($this->params['agencia'])?" and agencia_id = ".crypto::decrypt($this->params['agencia']):" and agencia_id = ".$this->usuarioacesso->Agencia)
                .($this->usuarioacesso->Agencia>0?" and agencia_id = {$this->usuarioacesso->Agencia}":"")
                . " Order by nome ";
        $retorno = $this->conexao->consultar($SQL, array(), array(), $this->usuarioacesso->Codigo, false);
        if ($retorno===false){
            return false;
        }
         if ($this->usuarioacesso->Cliente===0){
                $cliente = new item();
                $cliente->setId(0);
                $cliente->setNome("ADMINISTRADOR");
                $this->cliente[] = $cliente;
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
