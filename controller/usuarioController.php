<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of usuarioController
 *
 * @author marcelo
 */
require 'classes/item.php';
class usuarioController {

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
            $this->fDescricaoArray[] = "(UsuariosWeb.Agencia_Id = {$this->usuarioacesso->Agencia})";
            $this->fArray[] = "(UsuariosWeb.Agencia_Id = ?)";
            $this->fParmsArray[] = $this->usuarioacesso->Agencia;
            $this->fParmsNameArray[] = "agencia";
        }
        if ($this->usuarioacesso->Cliente > 0) {
            $this->fDescricaoArray[] = "(UsuariosWeb.Cliente_Id = {$this->usuarioacesso->Cliente})";
            $this->fArray[] = "(UsuariosWeb.Cliente_Id = ?)";
            $this->fParmsArray[] = $this->usuarioacesso->Cliente;
            $this->fParmsNameArray[] = "cliente1";
        }
        $this->fDescricaoArray[] = "(UsuariosWeb.inativo = 'F')";
        $this->fArray[] = "(UsuariosWeb.inativo = ?)";
        $this->fParmsArray[] = 'F';
        $this->fParmsNameArray[] = "inativo"; 
        
        if (isset($this->params['agencia_id']) && ($this->params['agencia_id'] !== "")) {
            $this->fDescricaoArray[] = "(ISNULL(UsuariosWeb.Agencia_Id, 0) = ".crypto::decrypt($this->params['agencia_id']).")";
            $this->fArray[] = "(ISNULL(UsuariosWeb.Agencia_Id, 0) = ?)";
            $this->fParmsArray[] = crypto::decrypt($this->params['agencia_id']);
            $this->fParmsNameArray[] = "agencia2";
        }
        
        if (isset($this->params['nome']) && ($this->params['nome'] !== "")) {
            $this->fDescricaoArray[] = "(UsuariosWeb.nome like '%{$this->params['nome']}%')";
            $this->fArray[] = "(UsuariosWeb.nome like ?)";
            $this->fParmsArray[] = "%". paramstostring($this->params['nome'])."%";
            $this->fParmsNameArray[] = "nome";    
        }
        if (isset($this->params['usuario']) && ($this->params['usuario'] !== "")) {
            $this->fArray[] = "(UsuariosWeb.usuario like '%{$this->params['usuario']}%')";
            $this->fArray[] = "(UsuariosWeb.usuario like ?)";
            $this->fParmsArray[] = "%". paramstostring($this->params['usuario'])."%";
            $this->fParmsNameArray[] = "usuario";    
            
        }
        $this->filtro = implode(' and ', $this->fArray);
        $this->descricaofiltro = crypto::encrypt(implode(' and ', $this->fDescricaoArray)); 
    }

    public function lista() {
        $this->conexao->setRequisicao(true);
        $this->preparafiltro();
        $SQL = "Select count(*) total from UsuariosWeb "
                . "left join agencia on agencia.id = usuariosweb.agencia_id "
                . "where " . $this->filtro;
        $retorno = $this->conexao->consultar($SQL, $this->fParmsArray, $this->fParmsNameArray, $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            return false;
        }
        $this->count = $retorno[0]['total'];
        $SQL = "Select usuariosweb.nome, codigo id, usuario, UserEmail email, "
                . "senha, cliente_id cliente, acesso, ISNULL(agencia.nome, 'ADMINISTRADOR') agencia from usuariosweb "
                . "left join agencia on agencia.id = usuariosweb.agencia_id "
                . "where " . $this->filtro .
                " ORDER BY codigo desc OFFSET (100 * {$this->params['pag']}) - 100 ROWS FETCH NEXT 100 ROWS ONLY";
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
            $usuario->setEmail($value['email']);
            $usuario->setSenha($value['senha']);
            $usuario->setAgencia($value['agencia']);
            $usuario->setAcesso($value['acesso']);
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
        $SQL = "Select usuariosweb.nome, codigo id, usuario, UserEmail "
                . "email, senha, cliente_id cliente, acesso, ISNULL(agencia_id, 0) agencia "
                . "from usuariosweb "
                . "where " . $this->filtro .
                " and codigo = ? ";
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
        $usuario->setEmail($retorno[0]['email']);
        $usuario->setSenha($retorno[0]['senha']);
        $usuario->setAcesso($retorno[0]['acesso']);
        $usuario->setAgencia((isset($this->params['agencia'])?$this->params['agencia']:crypto::encrypt($retorno[0]['agencia'])));
        $this->usuario = $usuario;
        $this->params['agencia'] = $usuario->getAgencia();
        return true;
    }

    public function incluir() {
        if (!$this->usuarioacesso->Incluir){
            exit("ajax_txt\nn0\nnUsuário não possui permissão de inclusão.");    
        }
        
        $password = strtoupper(sha1('M1' . htmlentities(stripslashes($this->params['senha'])) . 'D45'));
        $codigo = CodigoTab("UsuariosWeb", "", $this->conexao);
        $this->conexao->setRequisicao(true);
        $retorno = $this->conexao->inicializar_transacao();
        if ($retorno === false) {
            exit("ajax_txt\nn0\nn" . $this->conexao->mensagem);
        }
        
        $wC = array(
                        'codigo', 
                        'nome',
                        'useremail',
                        'cliente_id',
                        'usuario',
                        'acesso', 
                        'inativo', 
                        'senha', 
                        'portal',
                        'agencia_id', 
                        'DtHrBloqueio'
        );
        $wV = array(
                        $codigo, 
                        paramstostring($this->params['nome']), 
                        paramstostring($this->params['email']), 
                        crypto::decrypt($this->params['cliente_id']), 
                        paramstostring($this->params['usuario']), 
                        'U', 
                        'F',
                        $password, 
                        'COLETORMRS',
                        (crypto::decrypt($this->params['agencia'])>0?crypto::decrypt($this->params['agencia']):NULL), 
                        '31/12/1899'
                        
        );
        
        $retorno = $this->conexao->inserir("UsuariosWeb", $wC, $wV, $wC, $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            $this->conexao->cancelar_transacao();
            exit("ajax_htm\nn0\nn{$this->msg}");
        }
        
        if (!$this->incluiracessos($codigo)){
            $this->conexao->cancelar_transacao();
            exit("ajax_htm\nn0\nn{$this->msg}");    
        }
        
        $retorno = $this->conexao->efetivar_transacao();
        if ($retorno === false) {
            exit("ajax_htm\nn0\nn{$this->conexao->mensagem}");
        }

        exit("ajax_htm\nn1\nnUsuario salvo com sucesso!\nn{$this->params['url']}&acao=incluir");
    }

    private function incluiracessos($codigo) {
        $wV1 = array(str_pad($codigo, 6, "0", STR_PAD_LEFT));
        $wP1 = array('usuario');
        $retorno = $this->conexao->deletar("delete from acesso where usuario_id = ?", $wV1, $wP1, $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            return false;
        }

        if (isset($this->params['acessos'])) {
            $menu = array();
            $submenu = array();
            foreach ($this->params['acessos'] as $value) {
                $menu[] = $value;
                $submenu[] = $value;
                if (isset($this->params[$value . 'incluir'])) {
                    $menu[] = $value;
                    $submenu[] = $this->params[$value . 'incluir'];
                } 
                if (isset($this->params[$value . 'alterar'])) {
                    $menu[] = $value;
                    $submenu[] = $this->params[$value . 'alterar'];
                } 
                if (isset($this->params[$value . 'excluir'])) {
                    $menu[] = $value;
                    $submenu[] = $this->params[$value . 'excluir'];
                } 
                if (isset($this->params[$value . 'consultar'])) {
                    $menu[] = $value;
                    $submenu[] = $this->params[$value . 'consultar'];
                } 
                if (isset($this->params[$value . 'gerar'])) {
                    $menu[] = $value;
                    $submenu[] = $this->params[$value . 'gerar'];
                }
                if (isset($this->params[$value . 'imprimir'])) {
                    $menu[] = $value;
                    $submenu[] = $this->params[$value . 'imprimir'];
                }
                if (isset($this->params[$value . 'visualizar'])) {
                    $menu[] = $value;
                    $submenu[] = $this->params[$value . 'visualizar'];
                }
                if (isset($this->params[$value . 'solicitar'])) {
                    $menu[] = $value;
                    $submenu[] = $this->params[$value . 'solicitar'];
                }
            }

            foreach ($menu as $key => $value) {
                $wC2 = array(
                                'menu',
                                'submenu', 
                                'usuario_id'
                );
                $wV2 = array(
                                $value, 
                                $submenu[$key], 
                                str_pad($codigo, 6, "0", STR_PAD_LEFT)
                );
                $retorno = $this->conexao->inserir('acesso', $wC2, $wV2, $wC2, $this->usuarioacesso->Codigo);
                if ($retorno === false) {
                    $this->msg = $this->conexao->mensagem;
                    return false;
                }
            }
        }
        return true;
    }

    public function alterar() {
        if (!$this->usuarioacesso->Alterar){
            exit("ajax_txt\nn0\nnUsuário não possui permissão de alteração.");    
        }
        $codigo = crypto::decrypt($this->params['codigo']);
        if ($codigo===false){
            exit("ajax_txt\nn0\nnCódigo invalido.");    
        }
        
        $password = $this->params['senha'];
        $SQL = "SELECT senha from usuariosweb where codigo = ?";
        $retorno = $this->conexao->consultar($SQL, array($codigo), array('codigo'), $this->usuarioacesso->Codigo);
        if ($retorno === False) {
            exit("ajax_htm\nn0\nn{$this->conexao->mensagem}");
        }else if ($this->conexao->contagem===0){
            exit("ajax_htm\nn0\nnFalha na alteração. Usuário não encontrado. ");    
        }
        if ($this->params['senha'] !== $retorno[0]['senha']) {
            $password = strtoupper(sha1('M1' . htmlentities(stripslashes($this->params['senha'])) . 'D45'));
        }
        
        $this->conexao->setRequisicao(true);
        $retorno = $this->conexao->inicializar_transacao();
        if ($retorno === false) {
            exit("ajax_txt\nn0\nn" . $this->conexao->mensagem);
        }
        $wC = array(
                        'codigo', 
                        'nome',
                        'useremail',
                        'cliente_id',
                        'usuario',
                        //'acesso', 
                        'inativo', 
                        'senha', 
                        'portal', 
                        'agencia_id'
            
        );
        $wV = array(
                        $codigo, 
                        paramstostring($this->params['nome']), 
                        paramstostring($this->params['email']), 
                        crypto::decrypt($this->params['cliente_id']), 
                        paramstostring($this->params['usuario']), 
                        //'U', 
                        'F',
                        $password, 
                        'COLETORMRS', 
                        (crypto::decrypt($this->params['agencia'])>0?crypto::decrypt($this->params['agencia']):NULL),
                        $codigo
                        
        );
        $wP = $wC;
        $wP[] = 'codigo_id';
        $retorno = $this->conexao->alterar("UsuariosWeb", $wC, $wV, "Where Codigo = ?", $wP, $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            $this->conexao->cancelar_transacao();
            exit("ajax_htm\nn0\nn{$this->msg}");
        }
        
        if (!$this->incluiracessos($codigo)){
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
        $retorno = $this->conexao->alterar("UsuariosWeb", $wC, $wV, "Where Codigo = ?", $wP, $this->usuarioacesso->Codigo);
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
        $SQL = "Select usuariosweb.nome, codigo id, usuario, UserEmail email, ISNULL(agencia.nome, 'ADMINISTRADOR') agencia from usuariosweb "
                . "left join agencia on agencia.id = usuariosweb.agencia_id "
                .($this->filtro<>""?"Where {$this->filtro}":"").
                " Order by codigo ";
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
        $wHeader[] = 'Email'; $wRegistros[] = 'email';
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
