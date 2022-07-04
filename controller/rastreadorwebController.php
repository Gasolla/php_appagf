<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of rastreadorwebController
 *
 * @author Marcelo
 */
require 'classes/item.php';
require('classes/rastreadorweb.php');
require('classes/rastreamento.php');


class rastreadorwebController {
    //put your code here
        //put your code here
    private $params;
    private $conexao;
    private $filtro;
    private $agencia = array();
    private $cliente = array();
    private $fArray = array();
    private $fDescricaoArray = array();
    private $fParmsArray = array();
    private $fParmsNameArray = array();
    private $usuarioacesso;
    public $msg;
    public $count = 0;
    public $descricaofiltro;
    public $rastreadorweb = array();
    public $rastreamento = array();
    

    function getRastreamento() {
        return $this->rastreamento;
    }

    function getRastreadorweb() {
        return $this->rastreadorweb;
    }

    function getAgencia() {
        return $this->agencia;
    }

    function getCliente() {
        return $this->cliente;
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
            $this->fParmsNameArray[] = "cliente_id";
        }

        if ($this->usuarioacesso->Agencia > 0) {
            $this->fDescricaoArray[] = "(agencia_Id = {$this->usuarioacesso->Agencia})";
            $this->fArray[] = "(agencia_Id = ?)";
            $this->fParmsArray[] = $this->usuarioacesso->Agencia;
            $this->fParmsNameArray[] = "agencia";
        }
        if (isset($this->params['agencia_id']) && ($this->params['agencia_id'] !== "")) {
            $this->fDescricaoArray[] = "(ISNULL(agencia_Id, 0) = " . crypto::decrypt($this->params['agencia_id']) . ")";
            $this->fArray[] = "(ISNULL(agencia_Id, 0) = ?)";
            $this->fParmsArray[] = crypto::decrypt($this->params['agencia_id']);
            $this->fParmsNameArray[] = "agencia2";
        }


        if (isset($this->params['cliente_id']) && ($this->params['cliente_id'] !== "")) {
            $this->fDescricaoArray[] = "(cliente_id = '" . crypto::decrypt($this->params['cliente_id']) . "')";
            $this->fArray[] = "(cliente_id = ?)";
            $this->fParmsArray[] = crypto::decrypt($this->params['cliente_id']);
            $this->fParmsNameArray[] = "cliente_id2";
        }

        if (isset($this->params['nome']) && ($this->params['nome'] !== "")) {
            $this->fDescricaoArray[] = "(nomedestino like '%" . $this->params['nome'] . "%')";
            $this->fArray[] = "(nomedestino like ?)";
            $this->fParmsArray[] = "%" . $this->params['nome'] . "%";
            $this->fParmsNameArray[] = "nome";
        }


        if (isset($this->params['objeto']) && ($this->params['objeto'] !== "")) {
            $this->fDescricaoArray[] = "(objeto = '" . $this->params['objeto'] . "')";
            $this->fArray[] = "(objeto = ?)";
            $this->fParmsArray[] = $this->params['objeto'];
            $this->fParmsNameArray[] = "objeto";
        }

        if (isset($this->params['protocolo']) && ($this->params['protocolo'] !== "")) {
            $this->fDescricaoArray[] = "(id = '" . crypto::decrypt($this->params['protocolo']) . "')";
            $this->fArray[] = "(id = ?)";
            $this->fParmsArray[] = crypto::decrypt($this->params['protocolo']);
            $this->fParmsNameArray[] = "protocolo";
        }

        if (isset($this->params['status']) && ($this->params['status'] !== "")) {
            $this->fDescricaoArray[] = "(statussro = '" . $this->params['status'] . "')";
            $this->fArray[] = "(statussro = ?)";
            $this->fParmsArray[] = $this->params['status'];
            $this->fParmsNameArray[] = "statussro";
        }

        if (isset($this->params['cidata']) && ($this->params['cidata'] !== "")) {
            $this->fDescricaoArray[] = "(dthr >= '{$this->params['cidata']} 00:00:00')";
            $this->fArray[] = "(dthr >= ?)";
            $this->fParmsArray[] = "{$this->params['cidata']} 00:00:00";
            $this->fParmsNameArray[] = "cidata";
        }
        if (isset($this->params['cfdata']) && ($this->params['cfdata'] !== "")) {
            $this->fDescricaoArray[] = "(dthr <= '{$this->params['cfdata']} 23:59:59')";
            $this->fArray[] = "(dthr <= ?)";
            $this->fParmsArray[] = "{$this->params['cfdata']} 23:59:59";
            $this->fParmsNameArray[] = "cfdata";
        }
        
        $this->fDescricaoArray[] = "(tpservico = 'RASTREADOR')";
        $this->fArray[] = "(tpservico = ?)";
        $this->fParmsArray[] = "RASTREADOR";
        $this->fParmsNameArray[] = "tpservico";
        
        $this->filtro = implode(' and ', $this->fArray);
        if (count($this->fDescricaoArray) > 0) {
            $this->descricaofiltro = crypto::encrypt(implode(' and ', $this->fDescricaoArray));
        }
    }

    public function lista() {
        $this->conexao->setRequisicao(true);
        $this->preparafiltro();
        $SQL = "Select count(*) total from VW_APIWEB "
                . ($this->filtro <> "" ? "Where {$this->filtro}" : "");
        $retorno = $this->conexao->consultar($SQL, $this->fParmsArray, $this->fParmsNameArray, $this->usuarioacesso->Codigo, false);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            return false;
        }
        $this->count = $retorno[0]['total'];
        $SQL = "Select id, cliente, agencia, objeto, nomedestino, 
                    cepdestino, datapostagem, 
                    dataentrega, statussro, descricaosro, datahora, descricaostatus status
                From VW_APIWEB "
                . ($this->filtro <> "" ? "Where {$this->filtro}" : "")
                . "ORDER BY dthr desc OFFSET (100 * {$this->params['pag']}) - 100 ROWS FETCH NEXT 100 ROWS ONLY";
        $retorno = $this->conexao->consultar($SQL, $this->fParmsArray, $this->fParmsNameArray, $this->usuarioacesso->Codigo, false);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            return false;
        }if ($this->conexao->contagem === 0) {
            $this->msg = "Nenhum registro encontrado";
            return false;
        }
        foreach ($retorno as $key => $value) {
            $rastreadorweb = new rastreadorweb();
            $rastreadorweb->setCliente(utf8_encode($value['cliente']));
            $rastreadorweb->setId($value['id']);
            $rastreadorweb->setAgencia($value['agencia']);
            $rastreadorweb->setObjeto($value['objeto']);
            $rastreadorweb->setNomedestino(utf8_encode($value['nomedestino']));
            $rastreadorweb->setCepdestino($value['cepdestino']);
            $rastreadorweb->setDatapostagem($value['datapostagem']);
            $rastreadorweb->setDataentrega($value['dataentrega']);
            $rastreadorweb->setStatussro(utf8_encode($value['statussro']));
            $rastreadorweb->setDescricao(utf8_encode($value['descricaosro']));
            $rastreadorweb->setDatahora($value['datahora']);
            $rastreadorweb->setStatus(utf8_encode($value['status']));
            $this->rastreadorweb[$key] = $rastreadorweb;
        }
        return true;
    }

    public function addAgencia() {
        $SQL = "Select id, nome from agencia " .
                ($this->usuarioacesso->Agencia > 0 ? "Where id = {$this->usuarioacesso->Agencia}" : "") .
                " Order by nome ";
        $retorno = $this->conexao->consultar($SQL, array(), array(), $this->usuarioacesso->Codigo, false);
        if ($retorno === false) {
            return false;
        }
        if (is_array($retorno) && (count($retorno) > 0)) {
            foreach ($retorno as $value) {
                $agencia = new item();
                $agencia->setId($value['id']);
                $agencia->setNome($value['nome']);
                $this->agencia[] = $agencia;
            }
        }
    }

    
    public function addCliente($incluir) {
        $SQL = "SELECT id, Ltrim(Concat(apelido, ' ', nome)) nome FROM CLIENTES where (id > 0) "
                . ($this->usuarioacesso->Cliente > 0 ? " and id = {$this->usuarioacesso->Cliente} " : "")
                . ($this->usuarioacesso->Agencia > 0 ? " and agencia_id = {$this->usuarioacesso->Agencia}" : "")
                . ($incluir ? (isset($this->params['agencia']) ? " and agencia_id = " . crypto::decrypt($this->params['agencia']) : " and agencia_id = 0") : "")
                . " Order by nome ";
        //echo $SQL;
        $retorno = $this->conexao->consultar($SQL, array(), array(), $this->usuarioacesso->Codigo, false);
        if ($retorno === false) {
            return false;
        }
        if (is_array($retorno) && (count($retorno) > 0)) {

            foreach ($retorno as $value) {
                $cliente = new item();
                $cliente->setId($value['id']);
                $cliente->setNome(utf8_encode($value['nome']));
                $this->cliente[] = $cliente;
            }
        }
    }

    
    public function visualizar() {
        $this->conexao->setRequisicao(true);
        $this->preparafiltro();
        $SQL = "Select id, cliente, agencia, objeto, nomedestino, ufdestino,
                    cidadedestino, bairrodestino, enderecodestino,
                    cepdestino, nomeremetente, ufremetente,
                    cidaderemetente, bairroremetente, enderecoremetente,
                    peso, isnull(valor, 0) valor, numerodestino,
                    numeroremetente, cepremetente, datapostagem, datacoleta,
                    dataentrega, statussro, descricaosro, datahora, descricaostatus status, servico, 
                    statusagendamento
                From VW_APIWEB "
                . ($this->filtro <> "" ? "Where {$this->filtro}" : "");
        $retorno = $this->conexao->consultar($SQL, $this->fParmsArray, $this->fParmsNameArray, $this->usuarioacesso->Codigo, false);
        if ($retorno === false) {
            exit("ajax_txt\nn0\nn" . $this->conexao->mensagem);
        }if ($this->conexao->contagem === 0) {
            exit("ajax_txt\nn0\nnNenhum registro encontrado.");
        }
        $rastreadorweb = new rastreadorweb();
        $rastreadorweb->setCliente(utf8_encode($retorno[0]['cliente']));
        $rastreadorweb->setId($retorno[0]['id']);
        $rastreadorweb->setAgencia($retorno[0]['agencia']);
        $rastreadorweb->setObjeto($retorno[0]['objeto']);
        $rastreadorweb->setNomedestino(utf8_encode($retorno[0]['nomedestino']));
        $rastreadorweb->setCepdestino($retorno[0]['cepdestino']);
        $rastreadorweb->setDatapostagem($retorno[0]['datapostagem']);
        $rastreadorweb->setDataentrega($retorno[0]['dataentrega']);
        $rastreadorweb->setStatussro(utf8_encode($retorno[0]['statussro']));
        $rastreadorweb->setDescricao(utf8_encode($retorno[0]['descricaosro']));
        $rastreadorweb->setDatahora($retorno[0]['datahora']);
        $rastreadorweb->setStatus(utf8_encode($retorno[0]['status']));
        $this->rastreadorweb = $rastreadorweb;

        
        if (($this->getRastreadorweb()->getObjeto() !== null) &&
                ($this->getRastreadorweb()->getObjeto() !== "")) {
            $SQL = "Select 
                        cidade,  uf, local, data, descricao, objeto
                    From vw_CorreioEventosWeb
                    Where Objeto = ?    
                    Order By datahora ";
            $retorno = $this->conexao->consultar($SQL, array($this->getRastreadorweb()->getObjeto()), array('objeto'), $this->usuarioacesso->Codigo);
            if ($retorno === false) {
                exit("ajax_txt\nn0\nn{$this->conexao->mensagem}");
            }if ($this->conexao->contagem > 0) {
                foreach ($retorno as $key => $value) {
                    $rastreamento = new rastreamento();
                    $rastreamento->setObjeto($value['objeto']);
                    $rastreamento->setCidade(utf8_encode($value['cidade']));
                    $rastreamento->setUf($value['uf']);
                    $rastreamento->setLocal(utf8_encode($value['local']));
                    $rastreamento->setData($value['data']);
                    $rastreamento->setDescricao(utf8_encode($value['descricao']));
                    $this->rastreamento[] = $rastreamento;
                }
            }
        }
        echo "ajax_txt\nn1\nn";
        include 'pages/rastreadorweb/visualizar.php';
    }
    
    public function excel() {
        if (!$this->usuarioacesso->Gerar) {
            exit("ajax_txt\nn0\nnUsuário não possui permissão de geração.");
        }

        $this->conexao->setRequisicao(true);
        $this->params['filtro'] = crypto::decrypt($this->params['filtro']);
        $SQL = "Select id, cliente, agencia, objeto, nomedestino, 
                    cepdestino, datapostagem, 
                    dataentrega, statussro, descricaosro, datahora
                From VW_APIWEB  "
                . ($this->params['filtro'] <> "" ? "Where {$this->params['filtro']}" : "") .
                " Order by id ";
        $retorno = $this->conexao->consultar($SQL, $this->fParmsArray, $this->fParmsNameArray, $this->usuarioacesso->Codigo, false);
        if ($retorno === false) {
            exit("ajax_htm\nn0\nn{$this->conexao->mensagem}");
        }if ($this->conexao->contagem === 0) {
            exit("ajax_htm\nn0\nnNenhum registro encontrado");
        }
        $wHeader = array();
        $wRegistros = array();
        if ($this->usuarioacesso->Agencia == 0) {
            $wHeader[] = 'Agencia';
            $wRegistros[] = 'agencia';
            $wHeader[] = 'Cliente';
            $wRegistros[] = 'cliente';
        }

        $wHeader[] = 'Nome Destinatario';
        $wRegistros[] = 'nomedestino';
        $wHeader[] = 'Objeto';
        $wRegistros[] = 'objeto';
        $wHeader[] = 'Data Registro';
        $wRegistros[] = 'datahora';
        $wHeader[] = 'Data Postagem';
        $wRegistros[] = 'datapostagem';
        $wHeader[] = 'Data Entrega';
        $wRegistros[] = 'dataentrega';
        $wHeader[] = 'Status SRO';
        $wRegistros[] = 'statussro';
        $wHeader[] = 'Descricao SRO';
        $wRegistros[] = 'descricaosro';

        $caminho = ajusta_temporario_excel($this->usuarioacesso->Codigo) . "excel.xls";
        $excel = new excel($wHeader, $wRegistros, $retorno);
        $excel->gerar($caminho);
    }

}
