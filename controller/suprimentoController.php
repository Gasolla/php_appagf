<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of suprimentoController
 *
 * @author marcelo
 */
class suprimentoController {
    //put your code here
    
    private $params;
    private $conexao;
    private $filtro;
    private $suprimento = array();
    private $fArray = array();
    private $fDescricaoArray = array();
    private $fParmsArray = array();
    private $fParmsNameArray = array();
    private $usuarioacesso;
    public $msg;
    public $count = 0;
    public $descricaofiltro;
    
    function getSuprimento() {
        return $this->suprimento;
    }

    public function __construct($params, $conexao, $usuarioacesso) {
        $this->params = $params;
        $this->conexao = $conexao;
        $this->usuarioacesso = $usuarioacesso;
    }
    
    private function preparafiltro(){
        $this->fDescricaoArray[] = "(suprimentos.inativo = 'F')";
        $this->fArray[] = "(suprimentos.inativo = ?)";
        $this->fParmsArray[] = "F";
        $this->fParmsNameArray[] = "inativo"; 
        if (isset($this->params['nome']) && ($this->params['nome']!=="")){
            $this->fArray[] = "(Suprimentos.nome like ?)";
            $this->fDescricaoArray[] = "(Suprimentos.nome like '%". paramstostring($this->params['nome'])."%')";
            $this->fParmsArray[] = "%". paramstostring($this->params['nome'])."%";
            $this->fParmsNameArray[] = "nome";            
        }
        if (isset($this->params['sigla'])&&($this->params['sigla']!=="")){
            $this->fArray[] = "(Suprimentos.sigla = ?)";
            $this->fDescricaoArray[] = "(Suprimentos.sigla = '".trocaAspas($this->params['sigla'])."')";
            $this->fParmsArray[] = trocaAspas($this->params['sigla']);
            $this->fParmsNameArray[] = "sigla";
        }
        $this->filtro = implode(' and ', $this->fArray);
        $this->descricaofiltro = crypto::encrypt(implode(' and ', $this->fDescricaoArray));    
    }
        

    public function lista(){
        $this->conexao->setRequisicao(true);
        $this->preparafiltro();
        $SQL = "Select count(*) total from suprimentos where ".$this->filtro;
        $retorno = $this->conexao->consultar($SQL, $this->fParmsArray, $this->fParmsNameArray, $this->usuarioacesso->Codigo);
        if ($retorno===false){
            $this->msg = $this->conexao->mensagem;
            return false;
        }
        $this->count = $retorno[0]['total'];
        $SQL = "Select nome, id, sigla, case when isnull(inativo, 'F') = 'F' then 'Nao' else 'Sim' end inativo from suprimentos where ".$this->filtro.
                "ORDER BY id desc OFFSET (100 * {$this->params['pag']}) - 100 ROWS FETCH NEXT 100 ROWS ONLY";
        $retorno = $this->conexao->consultar($SQL, $this->fParmsArray, $this->fParmsNameArray, $this->usuarioacesso->Codigo);
        if ($retorno===false){
            $this->msg = $this->conexao->mensagem;
            return false;
        }if ($this->conexao->contagem===0){
            $this->msg = "Nenhum registro encontrado";
            return false;
        }
        foreach ($retorno as $key => $value) {
            $suprimento = new suprimento();
            $suprimento->setNome($value['nome']);
            $suprimento->setSigla($value['sigla']);
            $suprimento->setId($value['id']);
            $suprimento->setInativo($value['inativo']);
            $this->suprimento[$key] = $suprimento;
        }
        return true;
    }
    
    
    public function index($codigo) {
        $suprimento = new suprimento();
        $codigo = ((crypto::decrypt($codigo)===false)?0:crypto::decrypt($codigo));
        if ($codigo === 0) {
            $this->suprimento = $suprimento;
            return true;
        }

        $this->conexao->setRequisicao(true);
        $this->preparafiltro();
        $SQL = "Select nome, id, sigla, isnull(inativo, 'F') inativo "
                . "from suprimentos "
                . "where ".$this->filtro
                . " and id = ? ";
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
        
        $suprimento->setNome($retorno[0]['nome']);
        $suprimento->setSigla($retorno[0]['sigla']);
        $suprimento->setId($retorno[0]['id']);
        $suprimento->setInativo($retorno[0]['inativo']);
        $this->suprimento = $suprimento;
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
        $wC = array(
                        'nome', 
                        'sigla',
                        'inativo', 
                        'usuario'
        );
        $wV = array(
                        paramstostring($this->params['nome']),
                        paramstostring($this->params['sigla']),
                        (isset($this->params['inativo'])?"T":"F"), 
                        $this->usuarioacesso->Codigo
        );
        
        $retorno = $this->conexao->inserir("suprimentos", $wC, $wV, $wC, $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            $this->conexao->cancelar_transacao();
            exit("ajax_htm\nn0\nn{$this->msg}");
        }
        $retorno = $this->conexao->efetivar_transacao();
        if ($retorno === false) {
            exit("ajax_htm\nn0\nn{$this->conexao->mensagem}");
        }

        exit("ajax_htm\nn1\nnSuprimento salvo com sucesso!\nn{$this->params['url']}&acao=incluir");
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
                        'nome', 
                        'sigla',
                        'inativo', 
                        'usuario'
        );
        $wV = array(
                        paramstostring($this->params['nome']),
                        paramstostring($this->params['sigla']),
                        (isset($this->params['inativo'])?"T":"F"), 
                        $this->usuarioacesso->Codigo, 
                        $codigo
        );
        $wP = $wC;
        $wP[] = 'codigo';
        $retorno = $this->conexao->alterar("suprimentos", $wC, $wV, "Where id = ? ", $wP, $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            $this->conexao->cancelar_transacao();
            exit("ajax_htm\nn0\nn{$this->msg}");
        }
        
        $retorno = $this->conexao->efetivar_transacao();
        if ($retorno === false) {
            exit("ajax_htm\nn0\nn{$this->conexao->mensagem}");
        }

        exit("ajax_htm\nn1\nnSuprimento salvo com sucesso!\nn{$this->params['url']}&acao=index");
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
        $wP = array('inativo', 'codigo');
        $retorno = $this->conexao->alterar("suprimentos", $wC, $wV, "Where id = ? ", $wP, $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            exit("ajax_htm\nn0\nn{$this->msg}");
        }
        exit("ajax_htm\nn1\nnSuprimento removido com sucesso!\nn{$this->params['url']}&acao=index");
    }

    public function excel(){
        if (!$this->usuarioacesso->Gerar){
            exit("ajax_txt\nn0\nnUsuário não possui permissão de geração.");    
        }
        
        $this->conexao->setRequisicao(true);
        $this->filtro = crypto::decrypt($this->params['filtro']);
        $SQL = "Select nome, id, sigla, case when isnull(inativo, 'F') = 'F' then 'Nao' else 'Sim' end inativo from suprimentos "
                .($this->filtro<>""?"Where {$this->filtro}":"").
                " Order by id ";
        $retorno = $this->conexao->requisitar($SQL, array(), array(), $this->usuarioacesso->Codigo, false);
        if ($retorno === false) {
            exit("ajax_htm\nn0\nn{$this->conexao->mensagem}");
        }if ($this->conexao->contagem === 0) {
            exit("ajax_htm\nn0\nnNenhum registro encontrado");
        }
        $wHeader = array(); $wRegistros = array();
        $wHeader[] = 'Nome'; $wRegistros[] = 'nome';
        $wHeader[] = 'Sigla'; $wRegistros[] = 'sigla';
        $wHeader[] = 'Inativo'; $wRegistros[] = 'inativo';
        $caminho = ajusta_temporario_excel($this->usuarioacesso->Codigo)."excel.xls";
        $excel = new excel($wHeader, $wRegistros, $retorno);
        $excel->gerar($caminho);        
    }

    
}
