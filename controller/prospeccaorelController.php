<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of prospeccaorelController
 *
 * @author Marcelo
 */
require 'classes/item.php';
class prospeccaorelController {
    //put your code here
    //put your code here
    //put your code here
    private $params;
    private $conexao;
    private $filtro;
    private $prospeccaorel = array();
    private $fArray = array();
    private $fDescricaoArray = array();
    private $comercial = array();
    private $fParmsArray = array();
    private $fParmsNameArray = array();
    private $usuario = array();
    private $usuarioacesso;
    public $msg;
    public $count = 0;
    public $descricaofiltro;
    private $agencia = array();
    private $seguimento = array();
    
        
    function getAgencia(){
        return $this->agencia;
    }
    
    function getComercial(){
        return $this->comercial;
    }
    
    function getUsuario() {
        return $this->usuario;
    }
        
    function getSeguimento(){
        return $this->seguimento;
    }
    
    
    function getProspeccaorel() {
        return $this->prospeccaorel;
    }


     public function __construct($params, $conexao, $usuarioacesso) {
        $this->params = $params;
        $this->conexao = $conexao;
        $this->usuarioacesso = $usuarioacesso;
    }
    
    private function preparafiltro() {
       
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
        
        if (isset($this->params['comercial_id']) && ($this->params['comercial_id'] !== "")) {
            $this->fDescricaoArray[] = "(comercial_id = '".crypto::decrypt($this->params['comercial_id'])."')";
            $this->fArray[] = "(comercial_id = ?)";
            $this->fParmsArray[] = crypto::decrypt($this->params['comercial_id']);
            $this->fParmsNameArray[] = "comercial_id";
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
            $this->fDescricaoArray[] = "(statusocorrencia= '" . ($this->params['status']) . "')";
            $this->fArray[] = "(statusocorrencia = ?)";
            $this->fParmsArray[] = ($this->params['status']);
            $this->fParmsNameArray[] = "status";
        }
        if (isset($this->params['usuario_id']) && ($this->params['usuario_id'] !== "")) {
            $this->fDescricaoArray[] = "(usuario_id = '" . crypto::decrypt($this->params['usuario_id']) . "')";
            $this->fArray[] = "(usuario_id = ?)";
            $this->fParmsArray[] = crypto::decrypt($this->params['usuario_id']);
            $this->fParmsNameArray[] = "usuario_id";
        }
        
        if (isset($this->params['seguimento_id']) && ($this->params['seguimento_id'] !== "")) {
            $this->fDescricaoArray[] = "(seguimento_id = '" . crypto::decrypt($this->params['seguimento_id']) . "')";
            $this->fArray[] = "(seguimento_id = ?)";
            $this->fParmsArray[] = crypto::decrypt($this->params['seguimento_id']);
            $this->fParmsNameArray[] = "seguimento_id";
        }
        
        if (isset($this->params['statusmotivo']) && ($this->params['statusmotivo'] !== "")) {
            $this->fDescricaoArray[] = "(statusnaofechado= '" . ($this->params['statusmotivo']) . "')";
            $this->fArray[] = "(statusnaofechado = ?)";
            $this->fParmsArray[] = ($this->params['statusmotivo']);
            $this->fParmsNameArray[] = "statusmotivo";
        }
        
        $this->filtro = implode(' and ', $this->fArray);
        if (count($this->fDescricaoArray)>0){
            $this->descricaofiltro = crypto::encrypt(implode(' and ', $this->fDescricaoArray)); 
        }
    }
    
    public function lista(){
        $this->conexao->setRequisicao(true);
        $this->preparafiltro();
        $SQL = "Select count(*) total from VW_PROSPECCAORELATORIO "
                .($this->filtro<>""?"Where {$this->filtro}":"");
        $retorno = $this->conexao->consultar($SQL, $this->fParmsArray, $this->fParmsNameArray, $this->usuarioacesso->Codigo, false);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            return false;
        }
        $this->count = $retorno[0]['total'];
        $SQL = "Select  agencia, nome, contato, ramo, volume, email, fone, comercial, 
                        cadastro, seguimento, datacontato, datanovo, ocorrencia, 
                        naofechado, comentario, pendencia
                From VW_PROSPECCAORELATORIO " 
                .($this->filtro<>""?"Where {$this->filtro}":"")
                ."ORDER BY nome OFFSET (100 * {$this->params['pag']}) - 100 ROWS FETCH NEXT 100 ROWS ONLY";
        $retorno = $this->conexao->consultar($SQL, $this->fParmsArray, $this->fParmsNameArray, $this->usuarioacesso->Codigo, false);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            return false;
        }if ($this->conexao->contagem === 0) {
            $this->msg = "Nenhum registro encontrado";
            return false;
        }
        foreach ($retorno as $key => $value) {
            $propeccaorel = new prospeccaorel();
            $propeccaorel->setNome(utf8_encode($value['nome']));
            $propeccaorel->setAgencia($value['agencia']);
            $propeccaorel->setComercial($value['comercial']);
            $propeccaorel->setDatacontato($value['datacontato']);
            $propeccaorel->setDatanovo($value['datanovo']);
            $propeccaorel->setContato($value['contato']);
            $propeccaorel->setRamo($value['ramo']);
            $propeccaorel->setVolume($value['volume']);
            $propeccaorel->setEmail($value['email']);
            $propeccaorel->setFone($value['fone']);
            $propeccaorel->setCadastro($value['cadastro']);
            $propeccaorel->setSeguimento($value['seguimento']);
            $propeccaorel->setOcorrencia($value['ocorrencia']);
            $propeccaorel->setNaofechado($value['naofechado']);
            $propeccaorel->setComentario($value['comentario']);
            $propeccaorel->setPendencia($value['pendencia']);
            $this->prospeccaorel[$key] = $propeccaorel;
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
        $SQL = "Select  agencia, nome, contato, ramo, volume, email, fone, comercial, 
                        cadastro, seguimento, datacontato, datanovo, ocorrencia, 
                        naofechado, comentario, pendencia
                From VW_PROSPECCAORELATORIO " 
                .($this->filtro<>""?"Where {$this->filtro}":"").
                " Order by nome ";
        $retorno = $this->conexao->consultar($SQL, $this->fParmsArray, $this->fParmsNameArray, $this->usuarioacesso->Codigo, false);
        if ($retorno === false) {
            exit("ajax_htm\nn0\nn{$this->conexao->mensagem}");
        }if ($this->conexao->contagem === 0) {
            exit("ajax_htm\nn0\nnNenhum registro encontrado");
        }
        $wHeader = array(); $wRegistros = array();
        $wHeader[] = 'Pendencia'; $wRegistros[] = 'pendencia';
        $wHeader[] = 'Agencia'; $wRegistros[] = 'agencia';
        $wHeader[] = 'Nome'; $wRegistros[] = 'nome';
        $wHeader[] = 'Contato'; $wRegistros[] = 'contato';
        $wHeader[] = 'Ramo Atividade'; $wRegistros[] = 'ramo';
        $wHeader[] = 'Volume Medio'; $wRegistros[] = 'volume';
        $wHeader[] = 'Email Contato'; $wRegistros[] = 'email';
        $wHeader[] = 'Telefone Contato'; $wRegistros[] = 'fone';
        $wHeader[] = 'Comercial'; $wRegistros[] = 'comercial';
        $wHeader[] = 'Usuario Cadastro'; $wRegistros[] = 'cadastro';
        $wHeader[] = 'Seguimento'; $wRegistros[] = 'seguimento';
        $wHeader[] = 'Data do Contato'; $wRegistros[] = 'datacontato';
        $wHeader[] = 'Data Proximo Contato'; $wRegistros[] = 'datanovo';
        $wHeader[] = 'Status'; $wRegistros[] = 'ocorrencia';
        $wHeader[] = 'Status Motivo Nao Fechado'; $wRegistros[] = 'naofechado';
        $wHeader[] = 'Observacao'; $wRegistros[] = 'comentario';
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
    
    public function addComercial(){
        $SQL = "Select distinct comercial id, UsuariosWeb.nome From Prospeccao
                    Inner join UsuariosWeb on UsuariosWeb.codigo = Prospeccao.comercial "
                .($this->usuarioacesso->Agencia>0?" and Prospeccao.agencia_id = {$this->usuarioacesso->Agencia}":"")
                . " Order by UsuariosWeb.nome ";
                //echo $SQL;
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
    
    public function addUsuario(){
        $SQL = "Select distinct Prospeccao.usuario id, UsuariosWeb.nome from Prospeccao
                    Inner join UsuariosWeb on UsuariosWeb.codigo = Prospeccao.usuario "
                .($this->usuarioacesso->Agencia>0?" and Prospeccao.agencia_id = {$this->usuarioacesso->Agencia}":"")
                . " Order by UsuariosWeb.nome ";
        $retorno = $this->conexao->consultar($SQL, array(), array(), $this->usuarioacesso->Codigo, false);
        if ($retorno===false){
            return false;
        }
        if (is_array($retorno)&&(count($retorno)>0)){
           
            foreach ($retorno as $value) {
                $usuario = new item();
                $usuario->setId($value['id']);
                $usuario->setNome(utf8_encode($value['nome']));
                $this->usuario[] = $usuario;
            }
        }   
    }
    
    public function addSeguimento(){
        $SQL = "select id, nome from seguimento ".
                " Order by nome ";
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
