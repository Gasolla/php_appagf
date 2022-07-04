<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of agendamentoController
 *
 * @author marcelo
 */
require 'classes/item.php';
class agendamentoController {

    //put your code here
    private $params;
    private $conexao;
    private $filtro;
    private $agendamento = array();
    private $fArray = array();
    private $fDescricaoArray = array();
    private $agencia = array();
    private $cliente = array();
    private $motorista = array();
    private $fParmsArray = array();
    private $fParmsNameArray = array();
    private $usuarioacesso;
    public $msg;
    public $count = 0;
    public $descricaofiltro;
    private $rotamsg = '';

    public function __construct($params, $conexao, $usuarioacesso) {
        $this->params = $params;
        $this->conexao = $conexao;
        $this->usuarioacesso = $usuarioacesso;
    }

    function getMotorista() {
        return $this->motorista;
    }
    
    function getagendamento() {
        return $this->agendamento;
    }
    
    function getAgencia(){
        return $this->agencia;
    }
    
    function getCliente(){
        return $this->cliente;
    }


    private function preparafiltro() {
        if ($this->usuarioacesso->Cliente > 0) {
            $this->descricaofiltro[] = "(agendamento.Cliente = {$this->usuarioacesso->Cliente})";
            $this->fArray[] = "(agendamento.Cliente = ?)";
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
        
        if (isset($this->params['cidata']) && ($this->params['cidata'] !== "")) {
            $this->fDescricaoArray[] = "(agendamento.Data >= '{$this->params['cidata']} 00:00:00')";
            $this->fArray[] = "(agendamento.Data >= ?)";
            $this->fParmsArray[] = "{$this->params['cidata']} 00:00:00";
            $this->fParmsNameArray[] = "cidata";
        }
        if (isset($this->params['cfdata']) && ($this->params['cfdata'] !== "")) {
            $this->fDescricaoArray[] = "(agendamento.Data <= '{$this->params['cfdata']} 23:59:59')";
            $this->fArray[] = "(agendamento.Data <= ?)";
            $this->fParmsArray[] = "{$this->params['cfdata']} 23:59:59";
            $this->fParmsNameArray[] = "cfdata";
        }
        if (isset($this->params['cliente_id']) && ($this->params['cliente_id'] !== "")) {
            $this->fDescricaoArray[] = "(agendamento.cliente = '" . crypto::decrypt($this->params['cliente_id']) . "')";
            $this->fArray[] = "(agendamento.cliente = ?)";
            $this->fParmsArray[] = crypto::decrypt($this->params['cliente_id']);
            $this->fParmsNameArray[] = "cliente_id";
        }
        if (isset($this->params['usuario_id']) && ($this->params['usuario_id'] !== "")) {
            $this->fDescricaoArray[] = "(agendamento.usuario= '" . crypto::decrypt($this->params['usuario_id']) . "')";
            $this->fArray[] = "(agendamento.usuario = ?)";
            $this->fParmsArray[] = crypto::decrypt($this->params['usuario_id']);
            $this->fParmsNameArray[] = "usuario_id";
        }

        if (isset($this->params['status']) && ($this->params['status'] !== "")) {
            $this->fDescricaoArray[] = "(agendamento.status= '" . ($this->params['status']) . "')";
            $this->fArray[] = "(agendamento.status = ?)";
            $this->fParmsArray[] = ($this->params['status']);
            $this->fParmsNameArray[] = "status";
        }

        $this->filtro = implode(' and ', $this->fArray);
        if (count($this->fDescricaoArray) > 0) {
            $this->descricaofiltro = crypto::encrypt(implode(' and ', $this->fDescricaoArray));
        }
    }

    public function lista() {
        $this->preparafiltro();
        $this->conexao->setRequisicao(true);
        $SQL = "Select count(*) total from agendamento "
                . "inner join clientes on "
                . "  clientes.id = agendamento.cliente "
                . "inner join agencia on "
                . "  clientes.agencia_id = agencia.id "
                . "inner join usuarios on "
                . "  usuarios.id = clientes.usuario "
                . ($this->filtro !== "" ? "Where {$this->filtro}" : "");
        $retorno = $this->conexao->consultar($SQL, $this->fParmsArray, $this->fParmsNameArray, $this->usuarioacesso->Codigo, false);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            return false;
        }
        $this->count = $retorno[0]['total'];
        $SQL = "Select agendamento.id, clientes.nome cliente, imediata, "
                . "    convert(nvarchar(10), agendamento.data, 103) data, "
                . "    usuarios.nome usuario, "
                . "    Case when ISNULL(agendamento.status, 'F') = 'T' then  'FINALIZADO' "
                . "         when ISNULL(agendamento.status, 'F') = 'R' then  'NAO VISITADO' "
                . "         else 'PENDENTE' end status, "
                . "    convert(nvarchar(10), agendamento.DtHrColeta, 103) + ' ' + "
                . "    convert(nvarchar(8), agendamento.DtHrColeta, 114) datacoleta, "
                . "    agencia.nome agencia "
                . "from agendamento "
                . "inner join clientes on "
                . "  clientes.id = agendamento.cliente "
                . "inner join agencia on "
                . "  clientes.agencia_id = agencia.id "
                . "inner join usuarios on "
                . "  usuarios.id = agendamento.usuario "
                . ($this->filtro !== "" ? "Where {$this->filtro}" : "") .
                " ORDER BY agendamento.data desc OFFSET (100 * {$this->params['pag']}) - 100 ROWS FETCH NEXT 100 ROWS ONLY";
        $retorno = $this->conexao->consultar($SQL, $this->fParmsArray, $this->fParmsNameArray, $this->usuarioacesso->Codigo, false);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            return false;
        }if ($this->conexao->contagem === 0) {
            $this->msg = "Nenhum registro encontrado";
            return false;
        }
        foreach ($retorno as $key => $value) {
            $agendamento = new agendamento();
            $agendamento->setId($value['id']);
            $agendamento->setCliente(utf8_encode($value['cliente']));
            $agendamento->setData($value['data']);
            $agendamento->setFinalizacao($value['datacoleta']);
            $agendamento->setStatus($value['status']);
            $agendamento->setUsuario($value['usuario']);
            $agendamento->setImediata($value['imediata']);
            $agendamento->setAgencia($value['agencia']);
            $this->agendamento[$key] = $agendamento;
        }
        return true;
    }

    public function index($codigo) {
        $agendamento = new agendamento();
        $codigo = ((crypto::decrypt($codigo) === false) ? 0 : crypto::decrypt($codigo));
        if ($codigo === 0) {
            $agendamento->setStatus("F");
            $agendamento->setAgencia((isset($this->params['agencia'])? crypto::decrypt($this->params['agencia']):$this->usuarioacesso->Agencia));
            $agendamento->setCliente((isset($this->params['cliente'])? crypto::decrypt($this->params['cliente']):""));
            $agendamento->setUsuario(((isset($this->params['cliente'])&&($this->params['cliente']!==""))? $this->getUsuario(crypto::decrypt($this->params['cliente'])):""));
            $this->params['agencia'] = (isset($this->params['agencia'])?$this->params['agencia']: crypto::encrypt($this->usuarioacesso->Agencia)); 
            $this->agendamento = $agendamento;
            return true;
        }

        $this->preparafiltro();
        $this->conexao->setRequisicao(true);
        $SQL = "Select agendamento.id, agendamento.cliente, imediata, "
                . "    convert(nvarchar(10), agendamento.data, 103) data, "
                . "    agendamento.usuario, agendamento.status, "
                . "    convert(nvarchar(10), agendamento.DtHrColeta, 103) + ' ' + "
                . "    convert(nvarchar(8), agendamento.DtHrColeta, 114) datacoleta, "
                . "    clientes.agencia_id agencia "
                . "from agendamento "
                . "inner join clientes on "
                . "  clientes.id = agendamento.cliente "
                . "inner join agencia on "
                . "  clientes.agencia_id = agencia.id "
                . "inner join usuarios on "
                . "  usuarios.id = agendamento.usuario "
                . ($this->filtro !== "" ? " Where {$this->filtro}" : "") . ($this->filtro !== "" ? " and agendamento.id = ?" : "Where agendamento.id = ?");
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

        $agendamento->setId($retorno[0]['id']);
        $agendamento->setCliente((isset($this->params['cliente'])? crypto::decrypt($this->params['cliente']):$retorno[0]['cliente']));
        $agendamento->setData($retorno[0]['data']);
        $agendamento->setFinalizacao($retorno[0]['datacoleta']);
        $agendamento->setStatus($retorno[0]['status']);
        $agendamento->setUsuario(((isset($this->params['cliente'])&&($this->params['cliente']!==""))? $this->getUsuario(crypto::decrypt($this->params['cliente'])):$retorno[0]['usuario']));    
        $agendamento->setImediata($retorno[0]['imediata']);
        $agendamento->setAgencia((isset($this->params['agencia'])? crypto::decrypt($this->params['agencia']):$retorno[0]['agencia']));
        $this->agendamento = $agendamento;
        $this->params['agencia'] = (isset($this->params['agencia'])?$this->params['agencia']: crypto::encrypt($agendamento->getAgencia()));
        
        return true;
    }

    private function checaAndamento($codigo, $cliente) {
        $SQL = "Select CONVERT(NVARCHAR(10), data, 103) data from agendamento where id <> ? and cliente = ? and (ISNULL(Status, 'F') = 'F') order by data";
        $retorno = $this->conexao->consultar($SQL, array($codigo, $cliente), array('codigo', 'cliente'), $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            return false;
        }if ($this->conexao->contagem > 0) {
            $this->msg = "Cliente ja possui um agendamento em aberto para data {$retorno[0]['data']}!";
            return false;
        }

        if ($codigo>0){
            $SQL = "Select ISNULL(status, 'F') status from agendamento where id = ? ";
            $retorno = $this->conexao->consultar($SQL, array($codigo), array('codigo'), $this->usuarioacesso->Codigo);
            if ($retorno === false) {
                $this->msg = $this->conexao->mensagem;
                return false;
            }if ($retorno[0]['status']!=="F") {
                $this->msg = "Agendamento finalizado! Não pode ser alterado!";
                return false;
            }
        }
        
        return true;
    }

    public function incluirrota($codigo, $cliente, $usuario, $alterar) {
        if ($alterar > 0) {
            $SQL = "select count(*) total from rotaitens where agendamento_id = {$codigo}";
            $retorno = $this->conexao->consultar($SQL, array(), array(), $this->usuarioacesso->Codigo, false);
            if ($retorno === false) {
                $this->msg = $this->conexao->mensagem;
                return false;
            }if ($retorno[0]['total'] > 0) {
                $this->rotamsg = "Agendamento já possui rota.";
                return true;
            }
        }
        if ($cliente > 0) {
            $SQL = "Select  VW_ROTAANDAMENTO.id, (select max(item) from RotaItens where rota = VW_ROTAANDAMENTO.id) item,
                            Enderecos.latitude, Enderecos.longitude
                    From clientes
                        inner join Enderecos on
                            Enderecos.Cliente = Clientes.id
                        inner join VW_ROTAANDAMENTO on 
                            VW_ROTAANDAMENTO.usuario = {$usuario}
                    where Clientes.id = {$cliente}";

            $retorno = $this->conexao->consultar($SQL, array(), array(), $this->usuarioacesso->Codigo, false);
            if ($retorno === false) {
                $this->msg = $this->conexao->mensagem;
                return false;
            }if ($this->conexao->contagem === 0) {
                $this->rotamsg = "Motoqueiro não possui rota cadastrada.";
                return true;
            }
            $wC1 = array(
                        'rota', 
                        'item', 
                        'cliente', 
                        'longitude', 
                        'latitude', 
                        'agendamento_id',

            );
            $wV1 = array(
                $retorno[0]['id'],
                ($retorno[0]['item']+1),
                $cliente,
                $retorno[0]['longitude'],
                $retorno[0]['latitude'],
                $codigo
            );

            $retorno = $this->conexao->inserir("RotaItens", $wC1, $wV1, $wC1, $this->usuarioacesso->Codigo);
            if ($retorno === false) {
                $this->msg = $this->conexao->mensagem;
                return false;
            }
            $this->rotamsg = "Agendamento lançado na rota do motoqueiro.";
        } else {
            $this->rotamsg = "Cliente inválido.";
        }

        return true;
    }

    public function incluir() {
        if (!$this->usuarioacesso->Incluir) {
            exit("ajax_txt\nn0\nnUsuário não possui permissão de inclusão.");
        }
        $this->params['cliente'] = crypto::decrypt($this->params['cliente']);
        $this->params['usuario'] = crypto::decrypt($this->params['usuario']);
        
        $this->conexao->setRequisicao(true);
        $retorno = $this->conexao->inicializar_transacao();
        if ($retorno === false) {
            exit("ajax_txt\nn0\nn" . $this->conexao->mensagem);
        }

        if (!$this->checaAndamento(0, paramstostring($this->params['cliente']))) {
            $this->conexao->cancelar_transacao();
            exit("ajax_htm\nn0\nn{$this->msg}");
        }

        $wC1 = array(
            'cliente',
            'data',
            'imediata',
            'usuario'
        );
        $wV1 = array(
            paramstostring($this->params['cliente']),
            paramstostring($this->params['data']),
            (isset($this->params['imediata']) ? 'T' : 'F'), 
            paramstostring($this->params['usuario'])
        );

        $retorno = $this->conexao->inserir("agendamento", $wC1, $wV1, $wC1, $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            $this->conexao->cancelar_transacao();
            exit("ajax_htm\nn0\nn{$this->msg}");
        }

        if (isset($this->params['imediata'])){
            $codigo = $this->getID();
            if ($codigo === false) {
                $this->conexao->cancelar_transacao();
                exit("ajax_htm\nn0\nn{$this->msg}");
            }
            if (!$this->incluirrota($codigo, paramstostring($this->params['cliente']), paramstostring($this->params['usuario']), false)){
                $this->conexao->cancelar_transacao();
                exit("ajax_htm\nn0\nn{$this->msg}");    
            }
        }
        
        $retorno = $this->conexao->efetivar_transacao();
        if ($retorno === false) {
            exit("ajax_htm\nn0\nn{$this->conexao->mensagem}");
        }

        exit("ajax_htm\nn1\nnAgendamento salvo com sucesso!\nn{$this->rotamsg}\nn{$this->params['url']}&acao=incluir");
    }

    private function getID(){
        $SQL = "Select ISNULL(max(id), 0) id from agendamento";
        $retorno = $this->conexao->consultar($SQL, array(), array(), $this->usuarioacesso->Codigo, false);
        if ($retorno === false) {
            exit("ajax_htm\nn0\nn{$this->conexao->mensagem}");
        }if ($this->conexao->contagem === 0) {
            exit("ajax_htm\nn0\nnNenhum registro encontrado");
        }
        if ($retorno[0]['id']==='0'){
            return 1;
        }
        return $retorno[0]['id'];
    }
    
    private function getUsuario($cliente){
        $SQL = "Select usuario from clientes where id = ?";
        $retorno = $this->conexao->consultar($SQL, array($cliente), array('cliente'), $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            return 0;
        }if ($this->conexao->contagem === 0) {
            return 0;
        }
        return $retorno[0]['usuario'];
    }
    
    private function alterarUsuario($codigo){
        $SQL = "Select rota.id rota, rotaitens.item local, ISNULL(agendamento.Status, 'F') status, 
                    (select count(*) from rotaitens rot where rot.rota = rota.id) total
                From rota
                inner Join rotaitens on RotaItens.Rota = rota.id
                inner join Agendamento on Agendamento.id = agendamento_id
                where agendamento_id = ?";
        $retorno = $this->conexao->consultar($SQL, array($codigo), array('codigo'), $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            return false;
        }if ($this->conexao->contagem === 0) {
            return true;
        }
        if ($retorno[0]['status']!=="F"){
            $this->msg = 'Agendamento finalizado! Não pode ser alterado!';
            return false;    
        }
        
        $wV1 = array($retorno[0]['rota'], $retorno[0]['local']);
        $wP1 = array('rota', 'local');
        $retorno = $this->conexao->deletar("delete from rotaitens where rota = ? and item = ?", $wV1, $wP1, $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            return false;
        }
        
        if ((int)$retorno[0]['total']==1){
            $wV1 = array($retorno[0]['rota']);
            $wP1 = array('rota');
            $retorno = $this->conexao->deletar("delete from rota where id = ?", $wV1, $wP1, $this->usuarioacesso->Codigo);
            if ($retorno === false) {
                $this->msg = $this->conexao->mensagem;
                return false;
            }
            
        }
        
    }
    
    public function alterar() {
        if (!$this->usuarioacesso->Alterar) {
            exit("ajax_txt\nn0\nnUsuário não possui permissão de alteração.");
        }
        if (!isset($this->params['status'])||(crypto::decrypt($this->params['status'])!=="F")){
            exit("ajax_txt\nn0\nnAgendamento não pode ser alterado");    
        }
        $codigo = crypto::decrypt($this->params['codigo']);
        $this->params['cliente'] = crypto::decrypt($this->params['cliente']);
        $this->params['usuario'] = crypto::decrypt($this->params['usuario']);
        
        
        if ($codigo === false) {
            exit("ajax_txt\nn0\nnCódigo invalido.");
        }
        $this->conexao->setRequisicao(true);
        $retorno = $this->conexao->inicializar_transacao();
        if ($retorno === false) {
            exit("ajax_txt\nn0\nn" . $this->conexao->mensagem);
        }
        
        if ($this->params['usuario']!== $this->params['usuarioold']){
            if ($this->alterarUsuario($codigo)===false){
                $this->conexao->cancelar_transacao();
                exit("ajax_htm\nn0\nn{$this->msg}");
            } 
        }
             
        if (!$this->checaAndamento($codigo, paramstostring($this->params['cliente']))) {
            $this->conexao->cancelar_transacao();
            exit("ajax_htm\nn0\nn{$this->msg}");
        }

        $wC1 = array(
            'cliente',
            'data',
            'imediata', 
            'usuario'
        );

        $wV1 = array(
            paramstostring($this->params['cliente']),
            paramstostring($this->params['data']),
            (isset($this->params['imediata']) ? 'T' : 'F'),
            paramstostring($this->params['usuario']),
            $codigo
        );


        $wPar1 = $wC1;
        $wPar1[] = 'codigo';
        $retorno = $this->conexao->alterar("agendamento", $wC1, $wV1, " Where id = ? ", $wPar1, $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            $this->conexao->cancelar_transacao();
            exit("ajax_htm\nn0\nn{$this->msg}");
        }

        if (isset($this->params['imediata'])){
            if (!$this->incluirrota($codigo, paramstostring($this->params['cliente']), paramstostring($this->params['usuario']), true)){
                $this->conexao->cancelar_transacao();
                exit("ajax_htm\nn0\nn{$this->msg}");    
            }
        }
        
        $retorno = $this->conexao->efetivar_transacao();
        if ($retorno === false) {
            exit("ajax_htm\nn0\nn{$this->conexao->mensagem}");
        }

        exit("ajax_htm\nn1\nnAgendamento salvo com sucesso!\nn{$this->rotamsg}\nn{$this->params['url']}&acao=index");
    }

    public function excel() {
        if (!$this->usuarioacesso->Gerar) {
            exit("ajax_txt\nn0\nnUsuário não possui permissão de geração.");
        }

        $this->conexao->setRequisicao(true);
        $this->params['filtro'] = crypto::decrypt($this->params['filtro']);
        $SQL = "Select agendamento.id, clientes.nome cliente, "
                . "    convert(nvarchar(10), agendamento.data, 103) data, "
                . "    usuarios.nome usuario,  "
                . "    Case when ISNULL(agendamento.status, 'F') = 'T' then  'FINALIZADO' "
                . "         when ISNULL(agendamento.status, 'F') = 'R' then  'NAO VISITADO' "
                . "         else 'PENDENTE' end status, "
                . "    convert(nvarchar(10), agendamento.DtHrColeta, 103) + ' ' + "
                . "    convert(nvarchar(8), agendamento.DtHrColeta, 114) datacoleta, "
                . "    case when isnull(imediata, 'T') = 'T' then 'SIM' else 'NAO' end imediata, "
                . "    agencia.nome agencia "
                . "from agendamento "
                . "inner join clientes on "
                . "  clientes.id = agendamento.cliente "
                . "inner join agencia on "
                . "  clientes.agencia_id = agencia.id "
                . "inner join usuarios on "
                . "  usuarios.id = agendamento.usuario "
                . ($this->params['filtro'] !== false ? "Where {$this->params['filtro']}" : "") .
                " ORDER BY agendamento.data desc";

        $retorno = $this->conexao->consultar($SQL, $this->fParmsArray, $this->fParmsNameArray, $this->usuarioacesso->Codigo, false);
        if ($retorno === false) {
            exit("ajax_htm\nn0\nn{$this->conexao->mensagem}");
        }if ($this->conexao->contagem === 0) {
            exit("ajax_htm\nn0\nnNenhum registro encontrado");
        }
        $wHeader = array();
        $wRegistros = array();
        $wHeader[] = 'Status';
        $wRegistros[] = 'status';
        $wHeader[] = 'Agencia';
        $wRegistros[] = 'agencia';
        $wHeader[] = 'Cliente';
        $wRegistros[] = 'cliente';
        $wHeader[] = 'Data';
        $wRegistros[] = 'data';
        $wHeader[] = 'Cadastro imediato';
        $wRegistros[] = 'imediata';
        $wHeader[] = 'Motoqueiro';
        $wRegistros[] = 'usuario';
        $wHeader[] = 'Data Hora Coleta';
        $wRegistros[] = 'datacoleta';

        $caminho = ajusta_temporario_excel($this->usuarioacesso->Codigo) . "excel.xls";
        $excel = new excel($wHeader, $wRegistros, $retorno);
        $excel->gerar($caminho);
    }

    public function excluir() {
        if (!$this->usuarioacesso->Excluir) {
            exit("ajax_txt\nn0\nnUsuário não possui permissão de exclusão.");
        }
        $codigo = crypto::decrypt($this->params['codigo']);
        if ($codigo === false) {
            exit("ajax_txt\nn0\nnCódigo invalido.");
        }
        $this->conexao->setRequisicao(true);
        $SQL = "Delete from agendamento Where id = ? ";
        $retorno = $this->conexao->deletar($SQL, array($codigo), array('codigo'), $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            exit("ajax_htm\nn0\nn{$this->msg}");
        }
        exit("ajax_htm\nn1\nnAgendamento removido com sucesso!\nn{$this->params['url']}&acao=index");
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
    
    public function addMotorista($incluir){
        $SQL = "SELECT id, nome FROM usuarios where motoqueiro = 1 "
                .($this->usuarioacesso->Agencia>0?" and agencia_id = {$this->usuarioacesso->Agencia}":"")
                .($incluir?(isset($this->params['agencia'])?" and agencia_id = ".crypto::decrypt($this->params['agencia']):" and agencia_id = 0"):"")
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
    
    
}
