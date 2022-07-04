<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of prospeccaolistaController
 *
 * @author marcelo
 */
require 'classes/item.php';
class prospeccaolistaController {

    private $params;
    private $conexao;
    private $filtro;
    private $prospeccao = array();
    private $agencia = array();
    private $seguimento = array();
    private $fArray = array();
    private $fDescricaoArray = array();
    private $fParmsArray = array();
    private $fParmsNameArray = array();
    private $usuarioacesso;
    public $msg;
    public $count = 0;
    public $descricaofiltro;

    function getSeguimento() {
        return $this->seguimento;
    }

    function getProspeccao() {
        return $this->prospeccao;
    }

    function getAgencia(){
        return $this->agencia;
    }
    
    private function preparafiltro() {
        $this->fDescricaoArray[] = "(prospeccao.inativo = 'F')";
        $this->fArray[] = "(prospeccao.inativo = ?)";
        $this->fParmsArray[] = "F";
        $this->fParmsNameArray[] = 'inativo';

        $this->fDescricaoArray[] = "(prospeccao.carteira = 'F')";
        $this->fArray[] = "(prospeccao.carteira = ?)";
        $this->fParmsArray[] = "F";
        $this->fParmsNameArray[] = 'carteira';

        $this->fDescricaoArray[] = "(isnull(prospeccao.comercial, '') = '')";
        $this->fArray[] = "(isnull(prospeccao.comercial, '') = ?)";
        $this->fParmsArray[] = '';
        $this->fParmsNameArray[] = 'comercial_id';

        if ($this->usuarioacesso->Agencia > 0) {
            $this->fDescricaoArray[] = "(prospeccao.Agencia_Id = {$this->usuarioacesso->Agencia})";
            $this->fArray[] = "(prospeccao.Agencia_Id = ?)";
            $this->fParmsArray[] = $this->usuarioacesso->Agencia;
            $this->fParmsNameArray[] = "agencia";
        }
        if (isset($this->params['agencia_id']) && ($this->params['agencia_id'] !== "")) {
            $this->fDescricaoArray[] = "(ISNULL(prospeccao.Agencia_Id, 0) = ".crypto::decrypt($this->params['agencia_id']).")";
            $this->fArray[] = "(ISNULL(prospeccao.Agencia_Id, 0) = ?)";
            $this->fParmsArray[] = crypto::decrypt($this->params['agencia_id']);
            $this->fParmsNameArray[] = "agencia2";
        }
        
        
        if ($this->usuarioacesso->Acesso!=="A"){
            $this->fDescricaoArray[] = "(isnull(prospeccao.publicar, 'F') = 'T')";
            $this->fArray[] = "(isnull(prospeccao.publicar, 'F') = ?)";
            $this->fParmsArray[] = "T";
            $this->fParmsNameArray[] = 'publicar'; 
        }
        
        if (isset($this->params['seguimento']) && ($this->params['seguimento'] !== "")) {
            $this->fArray[] = "(prospeccao.seguimento = ?)";
            $this->fDescricaoArray[] = "(prospeccao.seguimento = " . paramstostring(crypto::decrypt($this->params['seguimento'])) . ")";
            $this->fParmsArray[] = paramstostring(crypto::decrypt($this->params['seguimento']));
            $this->fParmsNameArray[] = "seguimento";
        }

        if (isset($this->params['nome']) && ($this->params['nome'] !== "")) {
            $this->fArray[] = "(prospeccao.nome like ?)";
            $this->fDescricaoArray[] = "(prospeccao.nome like '%" . paramstostring($this->params['nome']) . "%')";
            $this->fParmsArray[] = "%" . paramstostring($this->params['nome']) . "%";
            $this->fParmsNameArray[] = "nome";
        }

        $this->filtro = implode(' and ', $this->fArray);
        $this->descricaofiltro = crypto::encrypt(implode(' and ', $this->fDescricaoArray));
    }

    public function __construct($params, $conexao, $usuarioacesso) {
        $this->params = $params;
        $this->conexao = $conexao;
        $this->usuarioacesso = $usuarioacesso;
    }

    public function lista() {
        $this->preparafiltro();
        $this->conexao->setRequisicao(true);
        $SQL = "Select count(*) total from prospeccao "
                ." inner join agencia on agencia.id = prospeccao.agencia_id "
                . "where " . $this->filtro;
        $retorno = $this->conexao->consultar($SQL, $this->fParmsArray, $this->fParmsNameArray, $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            return false;
        }
        $this->count = $retorno[0]['total'];
        $SQL = "Select prospeccao.nome, prospeccao.id, prospeccao.email, fone, "
                . "EnderecoExtenso, prospeccao.cidade, agencia.nome agencia "
                . "from prospeccao "
                ." inner join agencia on agencia.id = prospeccao.agencia_id "
                . " where " . $this->filtro .
                "ORDER BY prospeccao.id desc OFFSET (100 * {$this->params['pag']}) - 100 ROWS FETCH NEXT 100 ROWS ONLY";
        $retorno = $this->conexao->consultar($SQL, $this->fParmsArray, $this->fParmsNameArray, $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            return false;
        }if ($this->conexao->contagem === 0) {
            $this->msg = "Nenhum registro encontrado";
            return false;
        }
        foreach ($retorno as $key => $value) {
            $prospeccao = new prospeccao();
            $prospeccao->setNome(utf8_encode($value['nome']));
            $prospeccao->setAgencia(utf8_encode($value['agencia']));
            $prospeccao->setEnderecoExtenso(utf8_encode($value['EnderecoExtenso']));
            $prospeccao->setId($value['id']);
            $prospeccao->setEmail($value['email']);
            $prospeccao->setCidade($value['cidade']);
            $prospeccao->setFone($value['fone']);
            $this->prospeccao[$key] = $prospeccao;
        }
        return true;
    }

    public function index($codigo) {
        $prospecao = new prospeccao();
        $codigo = ((crypto::decrypt($codigo) === false) ? 0 : crypto::decrypt($codigo));
        if ($codigo === 0) {
            $prospecao->setAgencia(($this->usuarioacesso->Agencia>0?$this->usuarioacesso->Agencia:""));
            $this->prospeccao = $prospecao;
            return true;
        }

        $this->conexao->setRequisicao(true);
        $this->preparafiltro();
        $SQL = "Select id, nome, email, fone, EnderecoExtenso, agencia_id agencia, "
                . " cidade, uf "
                . "from prospeccao "
                . "where " . $this->filtro .
                " and id = ? ";
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
        $prospecao->setNome(utf8_encode($retorno[0]['nome']));
        $prospecao->setAgencia($retorno[0]['agencia']);
        $prospecao->setEnderecoExtenso(utf8_encode($retorno[0]['EnderecoExtenso']));
        $prospecao->setId($retorno[0]['id']);
        $prospecao->setEmail($retorno[0]['email']);
        $prospecao->setFone($retorno[0]['fone']);
        $prospecao->setCidade($retorno[0]['cidade']);
        $prospecao->setUf($retorno[0]['uf']);
        $this->prospeccao = $prospecao;
        return true;
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
        
        $wC1 = array(
                        'nome', 
                        'email', 
                        'fone', 
                        'cidade', 
                        'uf', 
                        'enderecoextenso',
                        'inativo', 
                        'usuario',
                        'seguimento', 
                        'publicar', 
                        'agencia_id'
            );
        $wV1 = array(
                        paramstostring(utf8_encode($this->params['nome'])), 
                        paramstostring(utf8_encode($this->params['email'])), 
                        trocaAspas($this->params['telefone']), 
                        paramstostring(utf8_encode($this->params['cidade'])), 
                        paramstostring(utf8_encode($this->params['uf'])), 
                        trocaAspas($this->params['txtEndereco']),
                        'F', 
                        $this->usuarioacesso->Codigo, 
                        '2', 
                        'T',
                        ($this->usuarioacesso->Agencia>0?$this->usuarioacesso->Agencia: trocaAspas(crypto::decrypt($this->params['agencia'])))
                
                );
        
        $retorno = $this->conexao->inserir("prospeccao", $wC1, $wV1, $wC1, $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            $this->conexao->cancelar_transacao();
            exit("ajax_htm\nn0\nn{$this->msg}");
        }
        
        
        $retorno = $this->conexao->efetivar_transacao();
        if ($retorno === false) {
            exit("ajax_htm\nn0\nn{$this->conexao->mensagem}");
        }

        exit("ajax_htm\nn1\nnLista propecção salvo com sucesso!\nn{$this->params['url']}&acao=incluir");
    
    }
    
    public function alterar() {
        date_default_timezone_set('America/Sao_Paulo');
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
        $wC1 = array(
                        'nome', 
                        'email', 
                        'fone', 
                        'cidade', 
                        'uf', 
                        'enderecoextenso',
                        'inativo', 
                        'agencia_id'
            );
        $wV1 = array(
                        paramstostring(utf8_encode($this->params['nome'])), 
                        paramstostring(utf8_encode($this->params['email'])), 
                        trocaAspas($this->params['telefone']), 
                        paramstostring(utf8_encode($this->params['cidade'])), 
                        paramstostring(utf8_encode($this->params['uf'])), 
                        trocaAspas($this->params['txtEndereco']),
                        'F',
                        ($this->usuarioacesso->Agencia>0?$this->usuarioacesso->Agencia: trocaAspas(crypto::decrypt($this->params['agencia'])))
                
                );
        
        if (isset($this->params['visitar'])){
            if($this->params['visitar']==="T"){
                $wC1[] = "comercial";
                $wV1[] = $this->usuarioacesso->Codigo;
                $wC1[] = "dataselecionado"; 
                $wV1[] = date("d/m/Y H:i:s"); 
            }else if($this->params['visitar']==="F"){
                $wC1[] = "carteira";
                $wV1[] = 'T';
                $wC1[] = "carteirauser";
                $wV1[] = $this->usuarioacesso->Codigo;
                $wC1[] = "carteiradthr"; 
                $wV1[] = date("d/m/Y H:i:s");
            }
        }
        $wV1[] = $codigo;
        $wPar1 = $wC1;
        $wPar1[] = 'codigo';
        $retorno = $this->conexao->alterar("prospeccao", $wC1, $wV1, " Where id = ? ", $wPar1, $this->usuarioacesso->Codigo);
         if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            $this->conexao->cancelar_transacao();
            exit("ajax_htm\nn0\nn{$this->msg}");
        }
        
        $retorno = $this->conexao->efetivar_transacao();
        if ($retorno === false) {
            exit("ajax_htm\nn0\nn{$this->conexao->mensagem}");
        }

        exit("ajax_htm\nn1\nnLista propecção salvo com sucesso!\nn{$this->params['url']}&acao=index");       
        
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
        $wC = array('inativo');
        $wV = array('T', $codigo);
        $wPar = $wC;
        $wPar[] = 'codigo';
        $retorno = $this->conexao->alterar("prospeccao", $wC, $wV, "Where id = ? ", $wPar, $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            exit("ajax_htm\nn0\nn{$this->msg}");
        }
        exit("ajax_htm\nn1\nnCliente removido com sucesso!\nn{$this->params['url']}&acao=index");
    }

    
    public function excel(){
        if (!$this->usuarioacesso->Gerar){
            exit("ajax_txt\nn0\nnUsuário não possui permissão de geração.");    
        }
        
        $this->conexao->setRequisicao(true);
        $this->params['filtro'] = crypto::decrypt($this->params['filtro']);
        $SQL = "Select prospeccao.nome, prospeccao.email, fone, "
                . "enderecoextenso, prospeccao.cidade, uf, agencia.nome agencia "
                . "from prospeccao "
                ." inner join agencia on agencia.id = prospeccao.agencia_id "
                .($this->params['filtro']<>""?"Where {$this->params['filtro']}":"").
                " Order by prospeccao.nome ";
        $retorno = $this->conexao->consultar($SQL, $this->fParmsArray, $this->fParmsNameArray, $this->usuarioacesso->Codigo, false);
        if ($retorno === false) {
            exit("ajax_htm\nn0\nn{$this->conexao->mensagem}");
        }if ($this->conexao->contagem === 0) {
            exit("ajax_htm\nn0\nnNenhum registro encontrado");
        }
        $wHeader = array(); $wRegistros = array();
        $wHeader[] = 'Nome'; $wRegistros[] = 'nome';
        $wHeader[] = 'Agencia'; $wRegistros[] = 'agencia';
        $wHeader[] = 'Email'; $wRegistros[] = 'email';
        $wHeader[] = 'Telefone'; $wRegistros[] = 'fone';
        $wHeader[] = 'Endereço'; $wRegistros[] = 'enderecoextenso';
        $wHeader[] = 'Cidade'; $wRegistros[] = 'cidade';
        $wHeader[] = 'UF'; $wRegistros[] = 'uf';
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
    
    public function addSeguimento(){
        $SQL = "SELECT id, nome FROM seguimento where inativo = 'F' Order by nome ";
        $retorno = $this->conexao->consultar($SQL, array(), array(), $this->usuarioacesso->Codigo, false);
        if ($retorno===false){
            return false;
        }
        if (is_array($retorno)&&(count($retorno)>0)){
            foreach ($retorno as $value) {
                $seguimento = new item();
                $seguimento->setId($value['id']);
                $seguimento->setNome(utf8_encode($value['nome']));
                $this->seguimento[] = $seguimento;
            }
        }   
    }

}
