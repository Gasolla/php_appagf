<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of apiwebController
 *
 * @author Marcelo
 */
require 'classes/item.php';
require('classes/apiweb.php');
require('classes/etiquetas.php');
require('classes/rastreamento.php');

class apiwebController {

    //put your code here
    private $params;
    private $conexao;
    private $filtro;
    private $agencia = array();
    private $cliente = array();
    private $fArray = array();
    private $servico = array();
    private $fDescricaoArray = array();
    private $fParmsArray = array();
    private $fParmsNameArray = array();
    private $usuarioacesso;
    public $msg;
    public $count = 0;
    public $descricaofiltro;
    public $apiweb = array();
    public $rastreamento = array();

    function getRastreamento() {
        return $this->rastreamento;
    }

    function getApiweb() {
        return $this->apiweb;
    }

    function getAgencia() {
        return $this->agencia;
    }

    function getServico() {
        return $this->servico;
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
            $this->fDescricaoArray[] = "(status = '" . $this->params['status'] . "')";
            $this->fArray[] = "(status = ?)";
            $this->fParmsArray[] = $this->params['status'];
            $this->fParmsNameArray[] = "status";
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

        if (isset($this->params['pidata']) && ($this->params['pidata'] !== "")) {
            $this->fDescricaoArray[] = "(dthrpostagem >= '{$this->params['pidata']} 00:00:00')";
            $this->fArray[] = "(dthrpostagem >= ?)";
            $this->fParmsArray[] = "{$this->params['pidata']} 00:00:00";
            $this->fParmsNameArray[] = "pidata";
        }
        if (isset($this->params['pfdata']) && ($this->params['pfdata'] !== "")) {
            $this->fDescricaoArray[] = "(dthrpostagem <= '{$this->params['pfdata']} 23:59:59')";
            $this->fArray[] = "(dthrpostagem <= ?)";
            $this->fParmsArray[] = "{$this->params['pfdata']} 23:59:59";
            $this->fParmsNameArray[] = "pfdata";
        }



        $this->fDescricaoArray[] = "(tpservico = 'API')";
        $this->fArray[] = "(tpservico = ?)";
        $this->fParmsArray[] = "API";
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
        $SQL = "Select id, cliente, agencia, objeto, nomedestino, ufdestino,
                    cidadedestino, bairrodestino, enderecodestino,
                    cepdestino, nomeremetente, ufremetente, datacoleta,
                    cidaderemetente, bairroremetente, enderecoremetente,
                    numeroremetente, cepremetente, datapostagem, peso, isnull(valor, 0) valor,
                    dataentrega, statussro, descricaosro, datahora, descricaostatus status, servico, 
                    statusagendamento, case when status = 'I' then 'T' else 'F' end impressao
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
            $apiweb = new apiweb();
            $apiweb->setCliente(utf8_encode($value['cliente']));
            $apiweb->setId($value['id']);
            $apiweb->setAgencia($value['agencia']);
            $apiweb->setObjeto($value['objeto']);
            $apiweb->setNomedestino(utf8_encode($value['nomedestino']));
            $apiweb->setUfdestino($value['ufdestino']);
            $apiweb->setCidadedestino(utf8_encode($value['cidadedestino']));
            $apiweb->setBairrodestino(utf8_encode($value['bairrodestino']));
            $apiweb->setEnderecodestino(utf8_encode($value['enderecodestino']));
            $apiweb->setCepdestino($value['cepdestino']);
            $apiweb->setNomeremetente(utf8_encode($value['nomeremetente']));
            $apiweb->setUfremetente($value['ufremetente']);
            $apiweb->setCidaderemetente(utf8_encode($value['cidaderemetente']));
            $apiweb->setBairroremetente(utf8_encode($value['bairroremetente']));
            $apiweb->setEnderecoremetente(utf8_encode($value['enderecoremetente']));
            $apiweb->setNumeroremetente($value['numeroremetente']);
            $apiweb->setCepremetente($value['cepremetente']);
            $apiweb->setDatapostagem($value['datapostagem']);
            $apiweb->setDataentrega($value['dataentrega']);
            $apiweb->setStatussro(utf8_encode($value['statussro']));
            $apiweb->setDescricao(utf8_encode($value['descricaosro']));
            $apiweb->setDatahora($value['datahora']);
            $apiweb->setStatus(utf8_encode($value['status']));
            $apiweb->setServico($value['servico']);
            $apiweb->setPeso($value['peso']);
            $apiweb->setValor(number_format(doubleval($value['valor']), 2, ",", "."));
            $apiweb->setDatacoleta($value['datacoleta']);
            $apiweb->setStatusagendamento($value['statusagendamento'] == "T");
            $apiweb->setImpressao($value['impressao']);
            $this->apiweb[$key] = $apiweb;
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

    public function addServico() {
        $SQL = "Select codigo id, nome from CorreioServicos " .
                " Order by nome ";
        $retorno = $this->conexao->consultar($SQL, array(), array(), $this->usuarioacesso->Codigo, false);
        if ($retorno === false) {
            return false;
        }
        if (is_array($retorno) && (count($retorno) > 0)) {
            foreach ($retorno as $value) {
                $servico = new item();
                $servico->setId($value['id']);
                $servico->setNome($value['nome']);
                $this->servico[] = $servico;
            }
        }
    }

    public function index($codigo) {
        $apiweb = new apiweb();
        $codigo = ((crypto::decrypt($codigo) === false) ? 0 : crypto::decrypt($codigo));
        if ($codigo === 0) {
            $apiweb->setAgencia((isset($this->params['agencia']) ? crypto::decrypt($this->params['agencia']) : $this->usuarioacesso->Agencia));
            $apiweb->setCliente($this->usuarioacesso->Cliente === 0 ? "" : $this->usuarioacesso->Cliente);
            $this->params['agencia'] = (isset($this->params['agencia']) ? $this->params['agencia'] : crypto::encrypt($this->usuarioacesso->Agencia));
            $this->apiweb = $apiweb;
            return true;
        }

        $this->conexao->setRequisicao(true);
        $this->preparafiltro();
        $SQL = "Select id, cliente, agencia, objeto, nomedestino, ufdestino, agencia_id, cliente_id,
                    cidadedestino, bairrodestino, enderecodestino, numerodestino,
                    cepdestino, nomeremetente, ufremetente, datacoleta, codservico,
                    cidaderemetente, bairroremetente, enderecoremetente,
                    numeroremetente, cepremetente, datapostagem, peso, isnull(valor, 0) valor,
                    dataentrega, statussro, descricaosro, datahora, descricaostatus status, servico, 
                    statusagendamento, cartao, case when status = 'I' then 'T' else 'F' end impressao
                From VW_APIWEB "
                . ($this->filtro <> "" ? "Where {$this->filtro}" : "")
                . ($this->filtro <> "" ? " and " : "Where ")
                . " id = ? ";
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

        $apiweb->setCliente($retorno[0]['cliente_id']);
        $apiweb->setId($retorno[0]['id']);
        $apiweb->setAgencia($retorno[0]['agencia_id']);
        $apiweb->setObjeto($retorno[0]['objeto']);
        $apiweb->setNomedestino(utf8_encode($retorno[0]['nomedestino']));
        $apiweb->setUfdestino($retorno[0]['ufdestino']);
        $apiweb->setCidadedestino(utf8_encode($retorno[0]['cidadedestino']));
        $apiweb->setBairrodestino(utf8_encode($retorno[0]['bairrodestino']));
        $apiweb->setEnderecodestino(utf8_encode($retorno[0]['enderecodestino']));
        $apiweb->setCepdestino($retorno[0]['cepdestino']);
        $apiweb->setNomeremetente(utf8_encode($retorno[0]['nomeremetente']));
        $apiweb->setUfremetente($retorno[0]['ufremetente']);
        $apiweb->setCidaderemetente(utf8_encode($retorno[0]['cidaderemetente']));
        $apiweb->setBairroremetente(utf8_encode($retorno[0]['bairroremetente']));
        $apiweb->setEnderecoremetente(utf8_encode($retorno[0]['enderecoremetente']));
        $apiweb->setNumeroremetente($retorno[0]['numeroremetente']);
        $apiweb->setCepremetente($retorno[0]['cepremetente']);
        $apiweb->setDatapostagem($retorno[0]['datapostagem']);
        $apiweb->setNumerodestino(utf8_encode($retorno[0]['numerodestino']));
        $apiweb->setDataentrega($retorno[0]['dataentrega']);
        $apiweb->setStatussro(utf8_encode($retorno[0]['statussro']));
        $apiweb->setDescricao(utf8_encode($retorno[0]['descricaosro']));
        $apiweb->setDatahora($retorno[0]['datahora']);
        $apiweb->setStatus(utf8_encode($retorno[0]['status']));
        $apiweb->setServico($retorno[0]['codservico']);
        $apiweb->setPeso($retorno[0]['peso']);
        $apiweb->setValor(number_format(doubleval($retorno[0]['valor']), 2, ",", "."));
        $apiweb->setDatacoleta($retorno[0]['datacoleta']);
        $apiweb->setStatusagendamento($retorno[0]['statusagendamento'] == "T");
        $apiweb->setCartao($retorno[0]['cartao']);
        $apiweb->setImpressao($retorno[0]['impressao']);
        $this->apiweb = $apiweb;
        $this->params['agencia'] = (isset($this->params['agencia'])?$this->params['agencia']: crypto::encrypt($apiweb->getAgencia()));
        return true;
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

    public function imprimir() {
        if (!$this->usuarioacesso->Imprimir) {
            exit("ajax_txt\nn0\nnUsuário não possui permissão de impressão.");
        }
        if (isset($this->params['protocolo'])) {
            $this->params['imprimir'] = array($this->params['protocolo']);
        }

        $diretorio = ajusta_temporario_excel($this->usuarioacesso->Codigo);
        $caminho = $diretorio . (string) rand(1000, 9999) . "_arq.pdf";
        $etiquetas = new etiquetas($this->conexao, $this->params, $this->usuarioacesso);
        $retorno = $etiquetas->gerar($diretorio, $caminho);
        if ($retorno === false) {
            exit("ajax_htm\nn0\nn{$etiquetas->msg}");
        }

        $ret = ((isset($this->params['protocolo'])) ? $this->getStatusandamento(crypto::decrypt($this->params['protocolo'])) : false);
        exit("ajax_htm\nn1\nn{$caminho}\nn{$ret}\nn" . (isset($this->params['protocolo']) ? $this->params['protocolo'] : ""));
    }

    private function getStatusandamento($id) {
        $SQL = "Select
                    statusagendamento
                From VW_APIWEB 
                Where id = ?";
        $retorno = $this->conexao->consultar($SQL, array($id), array('id'), $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            return "false";
        }if ($this->conexao->contagem == 0) {
            return "false";
        }

        return (($retorno[0]['statusagendamento'] == "T") ? "true" : "false");
    }

    public function solicitar() {
        if (!$this->usuarioacesso->Solicitar) {
            exit("ajax_txt\nn0\nnUsuário não possui permissão para agendamento.");
        }

        if (isset($this->params['protocolo'])) {
            $this->params['agendar'] = array($this->params['protocolo']);
        }

        $retorno = $this->conexao->inicializar_transacao();
        if ($retorno === false) {
            exit("ajax_txt\nn0\nn" . $this->conexao->mensagem);
        }

        $start = new DateTime('17:00:00');
        $now = new DateTime('now');

        if ($start < $now) {
            $SQL = "select top 1 convert(nvarchar(10), Dt_Referencia, 103) data from Dias_Agenda
                    where Dt_Referencia > CAST(CURRENT_TIMESTAMP as date) 
                    order by Dt_Referencia";
            $retorno = $this->conexao->consultar($SQL, array(), array(), $this->usuarioacesso->Codigo, false);
            if ($retorno === false) {
                $this->msg = $this->conexao->mensagem;
                $this->conexao->cancelar_transacao();
                exit("ajax_txt\nn0\nn" . $this->msg);
            }
            $data = $retorno[0]['data'];
        } else {
            $data = date('d/m/Y');
        }

        if (is_array($this->params['agendar'])) {
            foreach ($this->params['agendar'] as $value) {
                $SQL = "Select cliente_id cliente from  CorreioObjetos where id = ?";
                $P = array('codigo');
                $V = array(crypto::decrypt($value));
                $retorno = $retorno = $this->conexao->consultar($SQL, $V, $P, $this->usuarioacesso->Codigo);
                if ($retorno === false) {
                    $this->msg = $this->conexao->mensagem;
                    $this->conexao->cancelar_transacao();
                    exit("ajax_txt\nn0\nn" . $this->msg);
                } else if ($this->conexao->contagem === 0) {
                    $this->conexao->cancelar_transacao();
                    exit("ajax_txt\nn0\nnRegistro nao encontrado");
                }
                $cliente = $retorno[0]['cliente'];
                $usuario = $this->getUsuario($cliente);
                $codigo = $this->getID($cliente, $data);
                if ($codigo === false) {
                    exit("ajax_txt\nn0\nn" . $this->msg);
                } else if ($codigo == "INSERIR") {
                    $codigo = $this->inseriragendamento($cliente, $data, $usuario);
                    if (($codigo === false) || ($codigo == "INSERIR")) {
                        $this->conexao->cancelar_transacao();
                        exit("ajax_txt\nn0\nnFalha no agendamento.");
                    }
                }

                $C = array('agendamento_id');
                $V = array($codigo, crypto::decrypt($value));
                $P = $C;
                $P[] = 'codigo';
                $retorno = $this->conexao->alterar("CorreioObjetos", $C, $V, " Where id = ?  ", $P, $this->usuarioacesso->Codigo);
                if ($retorno === false) {
                    $this->msg = $this->conexao->mensagem;
                    $this->conexao->cancelar_transacao();
                    exit("ajax_txt\nn0\nn" . $this->msg);
                }
            }
            $retorno = $this->conexao->efetivar_transacao();
            if ($retorno === false) {
                exit("ajax_htm\nn0\nn{$this->conexao->mensagem}");
            }
            exit("ajax_txt\nn1\nnColeta agendamento para o dia {$data}.\nntrue\nn" . (isset($this->params['protocolo']) ? $this->params['protocolo'] : ""));
        } else {
            $this->conexao->cancelar_transacao();
            exit("ajax_txt\nn0\nnFormulario inválido.");
        }
    }

    private function inseriragendamento($cliente, $data, $usuario) {
        $wC1 = array(
            'cliente',
            'data',
            'imediata',
            'usuario'
        );
        $wV1 = array(
            $cliente,
            $data,
            'F',
            $usuario
        );

        $retorno = $this->conexao->inserir("agendamento", $wC1, $wV1, $wC1, $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            return false;
        }

        return $this->getID($cliente, $data);
    }

    private function getID($cliente, $data) {
        $SQL = "Select id from agendamento where cliente = ? and data = ? and isnull(status, 'F') = ? ";
        $retorno = $this->conexao->consultar($SQL, array($cliente, $data, 'F'), array('cliente', 'data', 'status'), $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            return false;
        }if ($this->conexao->contagem === 0) {
            return "INSERIR";
        }
        return $retorno[0]['id'];
    }

    private function getUsuario($cliente) {
        $SQL = "select ISNULL(usuario, 0) usuario, nome from clientes where id = ? ";
        $retorno = $this->conexao->consultar($SQL, array($cliente), array('cliente'), $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            return false;
        }if ($this->conexao->contagem === 0) {
            $this->msg = "Cliente não encontrado";
            return false;
        }

        if ($retorno[0]['usuario'] === 0) {
            $this->msg = "Cliente {$retorno[0]['nome']} não possui motorista.";
            return false;
        }
        return $retorno[0]['usuario'];
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
                    statusagendamento, case when status = 'I' then 'T' else 'F' end impressao
                From VW_APIWEB "
                . ($this->filtro <> "" ? "Where {$this->filtro}" : "");
        $retorno = $this->conexao->consultar($SQL, $this->fParmsArray, $this->fParmsNameArray, $this->usuarioacesso->Codigo, false);
        if ($retorno === false) {
            exit("ajax_txt\nn0\nn" . $this->conexao->mensagem);
        }if ($this->conexao->contagem === 0) {
            exit("ajax_txt\nn0\nnNenhum registro encontrado.");
        }
        $apiweb = new apiweb();
        $apiweb->setCliente(utf8_encode($retorno[0]['cliente']));
        $apiweb->setId($retorno[0]['id']);
        $apiweb->setAgencia($retorno[0]['agencia']);
        $apiweb->setObjeto($retorno[0]['objeto']);
        $apiweb->setNomedestino(utf8_encode($retorno[0]['nomedestino']));
        $apiweb->setUfdestino($retorno[0]['ufdestino']);
        $apiweb->setCidadedestino(utf8_encode($retorno[0]['cidadedestino']));
        $apiweb->setBairrodestino(utf8_encode($retorno[0]['bairrodestino']));
        $apiweb->setEnderecodestino(utf8_encode($retorno[0]['enderecodestino']));
        $apiweb->setCepdestino($retorno[0]['cepdestino']);
        $apiweb->setNomeremetente(utf8_encode($retorno[0]['nomeremetente']));
        $apiweb->setUfremetente($retorno[0]['ufremetente']);
        $apiweb->setCidaderemetente(utf8_encode($retorno[0]['cidaderemetente']));
        $apiweb->setBairroremetente(utf8_encode($retorno[0]['bairroremetente']));
        $apiweb->setEnderecoremetente(utf8_encode($retorno[0]['enderecoremetente']));
        $apiweb->setNumeroremetente($retorno[0]['numeroremetente']);
        $apiweb->setCepremetente($retorno[0]['cepremetente']);
        $apiweb->setDatapostagem($retorno[0]['datapostagem']);
        $apiweb->setDataentrega($retorno[0]['dataentrega']);
        $apiweb->setStatussro(utf8_encode($retorno[0]['statussro']));
        $apiweb->setDescricao(utf8_encode($retorno[0]['descricaosro']));
        $apiweb->setDatahora($retorno[0]['datahora']);
        $apiweb->setStatus(utf8_encode($retorno[0]['status']));
        $apiweb->setServico($retorno[0]['servico']);
        $apiweb->setDatacoleta($retorno[0]['datacoleta']);
        $apiweb->setNumerodestino($retorno[0]['numerodestino']);
        $apiweb->setPeso($retorno[0]['peso']);
        $apiweb->setValor(number_format(doubleval($retorno[0]['valor']), 2, ",", "."));
        $apiweb->setStatusagendamento($retorno[0]['statusagendamento'] == "T");
        $apiweb->setImpressao($retorno[0]['impressao']);
        $this->apiweb = $apiweb;

        if (($this->getApiweb()->getObjeto() !== null) &&
                ($this->getApiweb()->getObjeto() !== "")) {
            $SQL = "Select 
                        cidade,  uf, local, data, descricao, objeto
                    From vw_CorreioEventosWeb
                    Where Objeto = ?    
                    Order By datahora ";
            $retorno = $this->conexao->consultar($SQL, array($this->getApiweb()->getObjeto()), array('objeto'), $this->usuarioacesso->Codigo);
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
        include 'pages/apiweb/visualizar.php';
    }

    private function getObjeto($cliente, $codigo) {
        $SQL = "Select objeto from CorreioEtiquetas where status = ? and cliente_id = ? and codigo = ?";
        $retorno = $this->conexao->consultar($SQL, array('F', $cliente, $codigo), array('status', 'cliente', $codigo), $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            return false;
        }if ($this->conexao->contagem === 0) {
            $this->msg = "Cliente não possui sequencia de objetos";
            return false;
        }

        return $retorno[0]['objeto'];
    }

    private function getRemetente($cliente) {
        $SQL = "select nome, endereco, numero, complemento, bairro, cidade, uf, cepnumero  from Clientes
                    inner join Enderecos on
                        Enderecos.Cliente = Clientes.ID
                Where clientes.id = ?";
        $retorno = $this->conexao->consultar($SQL, array($cliente), array('cliente'), $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            return false;
        }if ($this->conexao->contagem === 0) {
            $this->msg = "Cliente não possui endereco";
            return false;
        }

        return $retorno[0];
    }

    public function incluir() {
        if (!$this->usuarioacesso->Incluir) {
            exit("ajax_txt\nn0\nnUsuário não possui permissão de inclusão.");
        }
        $retorno = $this->conexao->inicializar_transacao();
        if ($retorno === false) {
            exit("ajax_txt\nn0\nn" . $this->conexao->mensagem);
        }

        $cliente = crypto::decrypt($this->params['cliente']);
        $servico = crypto::decrypt($this->params['servico']);
        $objeto = $this->getObjeto($cliente, $servico);
        if ($objeto === false) {
            $this->conexao->cancelar_transacao();
            exit("ajax_txt\nn0\nn" . $this->msg);
        }

        $remetente = $this->getRemetente($cliente);
        if ($objeto === false) {
            $this->conexao->cancelar_transacao();
            exit("ajax_txt\nn0\nn" . $this->msg);
        }

        date_default_timezone_set('America/Sao_Paulo');
        $this->conexao->setRequisicao(true);
        $wC1 = array(
            'objeto',
            'nomedestino',
            'enderecodestino',
            'bairrodestino',
            'cepdestino',
            'ufdestino',
            'cidadedestino',
            'numerodestino',
            'cliente_id',
            'cartao',
            'servico',
            'nomeremetente',
            'ufremetente',
            'cidaderemetente',
            'bairroremetente',
            'enderecoremetente',
            'numeroremetente',
            'cepremetente',
            'duplicidade',
            'useralteracao',
            'usercadastro',
            'dthr', 
            'reenvio'
        );

        $wV1 = array(
            $objeto,
            paramstostring(utf8_encode($this->params['nome'])),
            paramstostring(utf8_encode($this->params['endereco'])),
            paramstostring(utf8_encode($this->params['bairro'])),
            paramstostring(utf8_encode($this->params['cep'])),
            paramstostring(utf8_encode($this->params['uf'])),
            paramstostring(utf8_encode($this->params['cidade'])),
            paramstostring(utf8_encode($this->params['numero'])),
            $cliente,
            paramstostring(utf8_encode($this->params['cartao'])),
            $servico,
            $remetente['nome'],
            $remetente['uf'],
            $remetente['cidade'],
            $remetente['bairro'],
            $remetente['endereco'],
            $remetente['numero'],
            $remetente['cepnumero'],
            paramstostring(utf8_encode($this->params['endereco'])) . paramstostring(utf8_encode($this->params['cep'])),
            $this->usuarioacesso->Codigo,
            $this->usuarioacesso->Codigo,
            date('d/m/Y H:i:s'), 
            (isset($this->params['reenvio'])?"T":"F")
        );
        $retorno = $this->conexao->inserir("CorreioObjetos", $wC1, $wV1, $wC1, $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            $this->conexao->cancelar_transacao();
            $this->msg = $this->conexao->mensagem;
            exit("ajax_htm\nn0\nn{$this->msg}");
        }
        $wC1 = array(
            'status'
        );
        $wV1 = array(
            'T',
            $objeto
        );
        $wP1 = array(
            'status',
            'objeto'
        );

        $retorno = $this->conexao->alterar("CorreioEtiquetas", $wC1, $wV1, "Where objeto = ? ", $wP1, $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            $this->conexao->cancelar_transacao();
            $this->msg = $this->conexao->mensagem;
            exit("ajax_htm\nn0\nn{$this->msg}");
        }

        $retorno = $this->conexao->efetivar_transacao();
        if ($retorno === false) {
            exit("ajax_htm\nn0\nn{$this->conexao->mensagem}");
        }
        
        exit("ajax_htm\nn1\nnRegistro salvo com sucesso!\nn{$this->params['url']}&acao=incluir");
    }

    public function alterar() {
        if (!$this->usuarioacesso->Alterar) {
            exit("ajax_txt\nn0\nnUsuário não possui permissão de alteração.");
        }
        if (isset($this->params['reenvio'])&&($this->params['reenvio']=="T")){
            $this->usuarioacesso->Incluir = true;
            $this->incluir();
            return;
        }
        
        $codigo = crypto::decrypt($this->params['codigo']);
        if ($codigo === false) {
            exit("ajax_txt\nn0\nnCódigo invalido.");
        }


        $this->conexao->setRequisicao(true);
        $wC1 = array(
            'nomedestino',
            'enderecodestino',
            'bairrodestino',
            'cepdestino',
            'ufdestino',
            'cidadedestino',
            'numerodestino',
            'useralteracao'
        );

        $wV1 = array(
            paramstostring(utf8_encode($this->params['nome'])),
            paramstostring(utf8_encode($this->params['endereco'])),
            paramstostring(utf8_encode($this->params['bairro'])),
            paramstostring(utf8_encode($this->params['cep'])),
            paramstostring(utf8_encode($this->params['uf'])),
            paramstostring(utf8_encode($this->params['cidade'])),
            paramstostring(utf8_encode($this->params['numero'])),
            $this->usuarioacesso->Codigo,
            $codigo
        );


        $wPar1 = $wC1;
        $wPar1[] = 'codigo';
        $retorno = $this->conexao->alterar("CorreioObjetos", $wC1, $wV1, " Where id = ? ", $wPar1, $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            exit("ajax_htm\nn0\nn{$this->msg}");
        }

        exit("ajax_htm\nn1\nnRegistro salvo com sucesso!\nn{$this->params['url']}&acao=index");
    }

    public function excel() {
        if (!$this->usuarioacesso->Gerar) {
            exit("ajax_txt\nn0\nnUsuário não possui permissão de geração.");
        }

        $this->conexao->setRequisicao(true);
        $this->params['filtro'] = crypto::decrypt($this->params['filtro']);
        $SQL = "Select id, cliente, agencia, objeto, nomedestino, ufdestino,
                    cidadedestino, bairrodestino, enderecodestino,
                    cepdestino, nomeremetente, ufremetente, numerodestino,
                    cidaderemetente, bairroremetente, enderecoremetente,
                    numeroremetente, cepremetente, datapostagem, datacoleta,
                    dataentrega, statussro, descricaosro, datahora, descricaostatus status, servico, 
                    statusagendamento, peso, isnull(valor, 0) valor
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
        $wHeader[] = 'Status';
        $wRegistros[] = 'status';
        $wHeader[] = 'Objeto';
        $wRegistros[] = 'objeto';
        $wHeader[] = 'Peso';
        $wRegistros[] = 'peso';
        $wHeader[] = 'Valor';
        $wRegistros[] = 'valor';
        $wHeader[] = 'Data Registro';
        $wRegistros[] = 'datahora';
        $wHeader[] = 'Data Coleta';
        $wRegistros[] = 'datacoleta';
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
