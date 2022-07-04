<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of clienteController
 *
 * @author marcelo
 */
require 'classes/item.php';

class clienteController {

    //put your code here
    private $params;
    private $conexao;
    private $filtro;
    private $cliente = array();
    private $endereco = array();
    private $agencia = array();
    private $comercial = array();
    private $motorista = array();
    private $fArray = array();
    private $fDescricaoArray = array();
    private $fParmsArray = array();
    private $fParmsNameArray = array();
    private $usuarioacesso;
    public $msg;
    public $count = 0;
    public $descricaofiltro;

    function getComercial() {
        return $this->comercial;
    }

    function getAgencia() {
        return $this->agencia;
    }

    function getCliente() {
        return $this->cliente;
    }

    function getEndereco() {
        return $this->endereco;
    }

    function setEndereco($endereco) {
        $this->endereco = $endereco;
    }

    function getMotorista() {
        return $this->motorista;
    }

    private function preparafiltro() {
        if ($this->usuarioacesso->Cliente > 0) {
            $this->descricaofiltro[] = "(Cliente.id = {$this->usuarioacesso->Cliente})";
            $this->fArray[] = "(Clientes.id = ?)";
            $this->fParmsArray[] = $this->usuarioacesso->Cliente;
            $this->fParmsNameArray[] = "cliente";
        }

        $this->fDescricaoArray[] = "(clientes.inativo = 'F')";
        $this->fArray[] = "(clientes.inativo = ?)";
        $this->fParmsArray[] = 'F';
        $this->fParmsNameArray[] = "inativo";

        if ($this->usuarioacesso->Agencia > 0) {
            $this->fDescricaoArray[] = "(Clientes.Agencia_Id = {$this->usuarioacesso->Agencia})";
            $this->fArray[] = "(Clientes.Agencia_Id = ?)";
            $this->fParmsArray[] = $this->usuarioacesso->Agencia;
            $this->fParmsNameArray[] = "agencia";
        }
        if (isset($this->params['agencia_id']) && ($this->params['agencia_id'] !== "")) {
            $this->fDescricaoArray[] = "(ISNULL(Clientes.Agencia_Id, 0) = " . crypto::decrypt($this->params['agencia_id']) . ")";
            $this->fArray[] = "(ISNULL(Clientes.Agencia_Id, 0) = ?)";
            $this->fParmsArray[] = crypto::decrypt($this->params['agencia_id']);
            $this->fParmsNameArray[] = "agencia2";
        }

        if (isset($this->params['comercial_id']) && ($this->params['comercial_id'] !== "")) {
            $this->fDescricaoArray[] = "(Clientes.comercial_Id = " . crypto::decrypt($this->params['comercial_id']) . ")";
            $this->fArray[] = "(Clientes.comercial_Id = ?)";
            $this->fParmsArray[] = crypto::decrypt($this->params['comercial_id']);
            $this->fParmsNameArray[] = "comercial";
        }

        if (isset($this->params['nome']) && ($this->params['nome'] !== "")) {
            $this->fArray[] = "((Clientes.nome like ?) or (Clientes.apelido like ?))";
            $this->fDescricaoArray[] = "((Clientes.nome like '%" . paramstostring($this->params['nome']) . "%') or "
                    . "(Clientes.apelido like '%" . paramstostring($this->params['nome']) . "%'))";
            $this->fParmsArray[] = "%" . paramstostring($this->params['nome']) . "%";
            $this->fParmsArray[] = "%" . paramstostring($this->params['nome']) . "%";
            $this->fParmsNameArray[] = "nome1";
            $this->fParmsNameArray[] = "nome2";
        }
        if (isset($this->params['documento']) && ($this->params['documento'] !== "")) {
            $this->fArray[] = "(clientes.cpf = ?)";
            $this->fDescricaoArray[] = "(clientes.cpf = '" . trocaAspas($this->params['documento']) . "')";
            $this->fParmsArray[] = trocaAspas($this->params['documento']);
            $this->fParmsNameArray[] = "cpf";
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
        $SQL = "Select count(*) total from clientes "
                . "inner join agencia on agencia.id = clientes.agencia_id "
                . "where " . $this->filtro;
        $retorno = $this->conexao->consultar($SQL, $this->fParmsArray, $this->fParmsNameArray, $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            return false;
        }
        $this->count = $retorno[0]['total'];
        $SQL = "Select clientes.nome, clientes.id, cpf, email, agencia.nome agencia, fone from clientes " .
                "inner join agencia on agencia.id = clientes.agencia_id " .
                "where " . $this->filtro .
                "ORDER BY clientes.nome  OFFSET (100 * {$this->params['pag']}) - 100 ROWS FETCH NEXT 100 ROWS ONLY";
        $retorno = $this->conexao->consultar($SQL, $this->fParmsArray, $this->fParmsNameArray, $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            return false;
        }if ($this->conexao->contagem === 0) {
            $this->msg = "Nenhum registro encontrado";
            return false;
        }
        foreach ($retorno as $key => $value) {
            $cliente = new cliente();
            $cliente->setNome(utf8_encode($value['nome']));
            $cliente->setDocumento($value['cpf']);
            $cliente->setId($value['id']);
            $cliente->setEmail($value['email']);
            $cliente->setFone($value['fone']);
            $cliente->setAgencia($value['agencia']);
            $this->cliente[$key] = $cliente;
        }
        return true;
    }

    public function index($codigo) {
        $cliente = new cliente();
        $endereco = new endereco();
        $codigo = ((crypto::decrypt($codigo) === false) ? 0 : crypto::decrypt($codigo));
        if ($codigo === 0) {
            $cliente->setAgencia((isset($this->params['agencia']) ? crypto::decrypt($this->params['agencia']) : $this->usuarioacesso->Agencia));
            $this->params['agencia'] = (isset($this->params['agencia']) ? $this->params['agencia'] : crypto::encrypt($this->usuarioacesso->Agencia));
            $this->cliente = $cliente;
            $this->endereco = $endereco;
            return true;
        }

        $this->preparafiltro();
        $this->conexao->setRequisicao(true);
        $SQL = "Select clientes.nome, clientes.id, clientes.cpf, clientes.email, clientes.fone, "
                . " enderecos.latitude, enderecos.longitude, enderecos.enderecoExtenso, apelido, "
                . " enderecos.Endereco rua, enderecos.numero, enderecos.complemento, enderecos.bairro, "
                . " enderecos.cidade, enderecos.UF, enderecos.cep, usuario motoqueiro, agencia_id agencia, "
                . " comercial_id comercial, microvisual "
                . " from clientes "
                . "   inner join enderecos on enderecos.cliente = clientes.id "
                . " where " . $this->filtro
                . " and clientes.id = ? ";
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

        $cliente->setNome(utf8_encode($retorno[0]['nome']));
        $cliente->setApelido($retorno[0]['apelido']);
        $cliente->setDocumento($retorno[0]['cpf']);
        $cliente->setId($retorno[0]['id']);
        $cliente->setEmail($retorno[0]['email']);
        $cliente->setFone($retorno[0]['fone']);
        $cliente->setMicrovisual($retorno[0]['microvisual']);
        $cliente->setMotoqueiro($retorno[0]['motoqueiro']);
        $cliente->setAgencia((isset($this->params['agencia']) ? crypto::decrypt($this->params['agencia']) : $retorno[0]['agencia']));
        $cliente->setComercial($retorno[0]['comercial']);
        $this->cliente = $cliente;
        $this->params['agencia'] = (isset($this->params['agencia']) ? $this->params['agencia'] : crypto::encrypt($cliente->getAgencia()));

        $endereco->setRua($retorno[0]['rua']);
        $endereco->setBairro($retorno[0]['bairro']);
        $endereco->setNumero($retorno[0]['numero']);
        $endereco->setComplemento($retorno[0]['complemento']);
        $endereco->setCep($retorno[0]['cep']);
        $endereco->setUf($retorno[0]['UF']);
        $endereco->setLatitude($retorno[0]['latitude']);
        $endereco->setLongitude($retorno[0]['longitude']);
        $endereco->setEnderecoExtenso(utf8_encode($retorno[0]['enderecoExtenso']));
        $endereco->setCidade($retorno[0]['cidade']);
        $this->endereco = $endereco;

        return true;
    }

    private function ChecarCampo($tabela, $condicao) {
        $SQL = "Select count(*) total from {$tabela} " . (($condicao <> '') ? "Where {$condicao}" : "");
        $retorno = $this->conexao->consultar($SQL, array(), array(), $this->usuarioacesso->Codigo, false);
        if ($retorno === false) {
            return true;
        } else {
            return ($retorno[0]['total'] > 0);
        }
    }

    public function incluir() {
        if (!$this->usuarioacesso->Incluir) {
            exit("ajax_txt\nn0\nnUsuário não possui permissão de inclusão.");
        }

        if ($this->ChecarCampo("clientes", "(inativo = 'F') and ".
                        " (microvisual = '".soNumero($this->params['microvisual'])."') and ".
                        " (agencia_id = ".($this->usuarioacesso->Agencia > 0 ? $this->usuarioacesso->Agencia : trocaAspas(crypto::decrypt($this->params['agencia']))).")  ", $this->conexao)) {
            exit("ajax_txt\nn0\nncodigo microvisual já cadastrado.");
        }else if ($this->ChecarCampo("clientes", "(inativo = 'F') and ".
                        " (cpf = '". paramstostring($this->params['cpfcnpj'])."')and ".
                        " (agencia_id = ".($this->usuarioacesso->Agencia > 0 ? $this->usuarioacesso->Agencia : trocaAspas(crypto::decrypt($this->params['agencia']))).")  ", $this->conexao)) {
            exit("ajax_txt\nn0\nncnpj já cadastrado.");
        }

        $this->conexao->setRequisicao(true);
        $retorno = $this->conexao->inicializar_transacao();
        if ($retorno === false) {
            exit("ajax_txt\nn0\nn" . $this->conexao->mensagem);
        }

        $wC1 = array(
            'nome',
            'apelido',
            'email',
            'cpf',
            'fone',
            'numerofone',
            'inativo',
            'usuario',
            'agencia_id',
            'comercial_id',
            'microvisual'
        );
        $wV1 = array(
            paramstostring(utf8_encode($this->params['nome'])),
            paramstostring(utf8_encode($this->params['apelido'])),
            paramstostring(utf8_encode($this->params['email'])),
            trocaAspas($this->params['cpfcnpj']),
            trocaAspas($this->params['telefone']),
            soNumero($this->params['telefone']),
            'F',
            trocaAspas(crypto::decrypt($this->params['motoqueiro'])),
            ($this->usuarioacesso->Agencia > 0 ? $this->usuarioacesso->Agencia : trocaAspas(crypto::decrypt($this->params['agencia']))),
            trocaAspas(crypto::decrypt($this->params['comercial'])),
            soNumero($this->params['microvisual']),
        );

        $retorno = $this->conexao->inserir("clientes", $wC1, $wV1, $wC1, $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            $this->conexao->cancelar_transacao();
            exit("ajax_htm\nn0\nn{$this->msg}");
        }

        $codigo = $this->getID();
        if ($codigo === false) {
            $this->conexao->cancelar_transacao();
            exit("ajax_htm\nn0\nn{$this->msg}");
        }

        $wC2 = array(
            'cliente',
            'endereco',
            'numero',
            'complemento',
            'bairro',
            'cidade',
            'uf',
            'cep',
            'cepnumero',
            'longitude',
            'latitude',
            'enderecoextenso'
        );
        $wV2 = array(
            $codigo,
            paramstostring(utf8_encode($this->params['rua'])),
            paramstostring(utf8_encode($this->params['numero'])),
            paramstostring(utf8_encode($this->params['complemento'])),
            paramstostring(utf8_encode($this->params['bairro'])),
            paramstostring(utf8_encode($this->params['cidade'])),
            paramstostring(utf8_encode($this->params['estado'])),
            paramstostring(utf8_encode($this->params['cep'])),
            soNumero($this->params['cep']),
            paramstostring($this->params['txtLongitude']),
            paramstostring($this->params['txtLatitude']),
            trocaAspas($this->params['txtEndereco'])
        );

        $retorno = $this->conexao->inserir("enderecos", $wC2, $wV2, $wC2, $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            $this->conexao->cancelar_transacao();
            exit("ajax_htm\nn0\nn{$this->msg}");
        }

        $retorno = $this->conexao->efetivar_transacao();
        if ($retorno === false) {
            exit("ajax_htm\nn0\nn{$this->conexao->mensagem}");
        }

        exit("ajax_htm\nn1\nnCliente salvo com sucesso!\nn{$this->params['url']}&acao=incluir");
    }

    private function getID() {
        $SQL = "Select ISNULL(max(id), 0) id from clientes";
        $retorno = $this->conexao->consultar($SQL, array(), array(), $this->usuarioacesso->Codigo, false);
        if ($retorno === false) {
            exit("ajax_htm\nn0\nn{$this->conexao->mensagem}");
        }if ($this->conexao->contagem === 0) {
            exit("ajax_htm\nn0\nnNenhum registro encontrado");
        }
        if ($retorno[0]['id'] === '0') {
            return 1;
        }
        return $retorno[0]['id'];
    }

    public function alterar() {
        if (!$this->usuarioacesso->Alterar) {
            exit("ajax_txt\nn0\nnUsuário não possui permissão de alteração.");
        }
        $codigo = crypto::decrypt($this->params['codigo']);
        if ($codigo === false) {
            exit("ajax_txt\nn0\nnCódigo invalido.");
        }
        
        if ($this->ChecarCampo("clientes", "(id <> {$codigo}) and (inativo = 'F') and ".
                        " (microvisual = '".soNumero($this->params['microvisual'])."') and ".
                        " (agencia_id = ".($this->usuarioacesso->Agencia > 0 ? $this->usuarioacesso->Agencia : trocaAspas(crypto::decrypt($this->params['agencia']))).")  ", $this->conexao)) {
            exit("ajax_txt\nn0\nncodigo microvisual já cadastrado.");
        }else if ($this->ChecarCampo("clientes", "(id <> {$codigo}) and (inativo = 'F') and ".
                        " (cpf = '". paramstostring($this->params['cpfcnpj'])."')and ".
                        " (agencia_id = ".($this->usuarioacesso->Agencia > 0 ? $this->usuarioacesso->Agencia : trocaAspas(crypto::decrypt($this->params['agencia']))).")  ", $this->conexao)) {
            exit("ajax_txt\nn0\nncnpj já cadastrado.");
        }

        $this->conexao->setRequisicao(true);
        $retorno = $this->conexao->inicializar_transacao();
        if ($retorno === false) {
            exit("ajax_txt\nn0\nn" . $this->conexao->mensagem);
        }
        $this->params['nome'] = paramstostring(utf8_encode($this->params['nome']));
        $this->params['email'] = paramstostring(utf8_encode($this->params['email']));
        $this->params['cpfcnpj'] = trocaAspas($this->params['cpfcnpj']);
        $this->params['telefone'] = trocaAspas($this->params['telefone']);
        $fonenumero = (soNumero($this->params['telefone']));
        $inativo = 'F';
        $wC1 = array(
            'nome',
            'apelido',
            'email',
            'cpf',
            'fone',
            'numerofone',
            'inativo',
            'usuario',
            'agencia_id',
            'comercial_id',
            'microvisual'
        );

        $wV1 = array(
            paramstostring(utf8_encode($this->params['nome'])),
            paramstostring(utf8_encode($this->params['apelido'])),
            paramstostring(utf8_encode($this->params['email'])),
            trocaAspas($this->params['cpfcnpj']),
            trocaAspas($this->params['telefone']),
            soNumero($this->params['telefone']),
            'F',
            trocaAspas(crypto::decrypt($this->params['motoqueiro'])),
            ($this->usuarioacesso->Agencia > 0 ? $this->usuarioacesso->Agencia : trocaAspas(crypto::decrypt($this->params['agencia']))),
            trocaAspas(crypto::decrypt($this->params['comercial'])),
            soNumero($this->params['microvisual']),
            $codigo
        );


        $wPar1 = $wC1;
        $wPar1[] = 'codigo';
        $retorno = $this->conexao->alterar("clientes", $wC1, $wV1, " Where id = ? ", $wPar1, $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            $this->conexao->cancelar_transacao();
            exit("ajax_htm\nn0\nn{$this->msg}");
        }

        $wC2 = array(
            'cliente',
            'endereco',
            'numero',
            'complemento',
            'bairro',
            'cidade',
            'uf',
            'cep',
            'cepnumero',
            'longitude',
            'latitude',
            'enderecoextenso'
        );
        $wV2 = array(
            $codigo,
            paramstostring(utf8_encode($this->params['rua'])),
            paramstostring(utf8_encode($this->params['numero'])),
            paramstostring(utf8_encode($this->params['complemento'])),
            paramstostring(utf8_encode($this->params['bairro'])),
            paramstostring(utf8_encode($this->params['cidade'])),
            paramstostring(utf8_encode($this->params['estado'])),
            paramstostring(utf8_encode($this->params['cep'])),
            soNumero($this->params['cep']),
            paramstostring($this->params['txtLongitude']),
            paramstostring($this->params['txtLatitude']),
            trocaAspas($this->params['txtEndereco']),
            $codigo
        );

        $wPar2 = $wC2;
        $wPar2[] = 'cliente';
        $retorno = $this->conexao->alterar("enderecos", $wC2, $wV2, "Where cliente = ? ", $wPar2, $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            $this->conexao->cancelar_transacao();
            exit("ajax_htm\nn0\nn{$this->msg}");
        }


        $retorno = $this->conexao->efetivar_transacao();
        if ($retorno === false) {
            exit("ajax_htm\nn0\nn{$this->conexao->mensagem}");
        }

        exit("ajax_htm\nn1\nnCliente salvo com sucesso!\nn{$this->params['url']}&acao=index");
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
        $wC = array('Inativo');
        $wV = array('T', $codigo);
        $wPar = $wC;
        $wPar[] = 'codigo';
        $retorno = $this->conexao->alterar("clientes", $wC, $wV, "Where id = ? ", $wPar, $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            exit("ajax_htm\nn0\nn{$this->msg}");
        }
        exit("ajax_htm\nn1\nnCliente removido com sucesso!\nn{$this->params['url']}&acao=index");
    }

    public function excel() {
        if (!$this->usuarioacesso->Gerar) {
            exit("ajax_txt\nn0\nnUsuário não possui permissão de geração.");
        }

        $this->conexao->setRequisicao(true);
        $this->params['filtro'] = crypto::decrypt($this->params['filtro']);
        $SQL = "Select clientes.nome, clientes.id, cpf, email, fone, agencia.nome agencia, "
                . " comercial.nome comercial from clientes " .
                "left join agencia on agencia.id = clientes.agencia_id " .
                "left join comercial on comercial.id = clientes.comercial_id "
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
        $wHeader[] = 'Agencia';
        $wRegistros[] = 'agencia';
        $wHeader[] = 'Nome';
        $wRegistros[] = 'nome';
        $wHeader[] = 'CPF/CNPJ';
        $wRegistros[] = 'cpf';
        $wHeader[] = 'Email';
        $wRegistros[] = 'email';
        $wHeader[] = 'Telefone';
        $wRegistros[] = 'fone';
        $wHeader[] = 'Comercial';
        $wRegistros[] = 'comercial';
        $caminho = ajusta_temporario_excel($this->usuarioacesso->Codigo) . "excel.xls";
        $excel = new excel($wHeader, $wRegistros, $retorno);
        $excel->gerar($caminho);
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

    public function addComercial($incluir) {
        $SQL = "Select id, nome from comercial where (id > 0) " .
                ($this->usuarioacesso->Agencia > 0 ? " and agencia_id = {$this->usuarioacesso->Agencia}" : "") .
                ($incluir ? (isset($this->params['agencia']) ? " and agencia_id = " . crypto::decrypt($this->params['agencia']) : " and agencia_id = 0") : "") .
                " Order by nome ";
        $retorno = $this->conexao->consultar($SQL, array(), array(), $this->usuarioacesso->Codigo, false);
        if ($retorno === false) {
            return false;
        }
        if (is_array($retorno) && (count($retorno) > 0)) {
            foreach ($retorno as $value) {
                $comercial = new item();
                $comercial->setId($value['id']);
                $comercial->setNome(utf8_encode($value['nome']));
                $this->comercial[] = $comercial;
            }
        }
    }

    public function addMotorista() {
        $SQL = "SELECT id, nome FROM usuarios where motoqueiro = 1 "
                . ($this->usuarioacesso->Agencia > 0 ? " and agencia_id = {$this->usuarioacesso->Agencia}" : "")
                . (isset($this->params['agencia']) ? " and agencia_id = " . crypto::decrypt($this->params['agencia']) : " and agencia_id = 0")
                . " Order by nome ";
        $retorno = $this->conexao->consultar($SQL, array(), array(), $this->usuarioacesso->Codigo, false);
        if ($retorno === false) {
            return false;
        }
        if (is_array($retorno) && (count($retorno) > 0)) {

            foreach ($retorno as $value) {
                $motorista = new item();
                $motorista->setId($value['id']);
                $motorista->setNome(utf8_encode($value['nome']));
                $this->motorista[] = $motorista;
            }
        }
    }

}
