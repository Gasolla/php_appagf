<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of prospeccaoController
 *
 * @author marcelo
 */
require 'classes/item.php';
class prospeccaoController {
    private $params;
    private $conexao;
    private $filtro;
    private $prospeccao = array();
    private $agencia = array();
    private $prospeccaohistorico = array();
    private $fArray = array();
    private $fDescricaoArray = array();
    private $fParmsArray = array();
    private $fParmsNameArray = array();
    private $usuarioacesso;
    public $msg;
    public $count = 0;
    public $descricaofiltro;
    
    function getAgencia(){
        return $this->agencia;
    }
    
    function getProspeccao() {
        return $this->prospeccao;
    }
    
    function getProspeccaohistorico(){
        return $this->prospeccaohistorico;
    }
    
    private function preparafiltro(){
        $this->fDescricaoArray[] = "(prospeccao.inativo = 'F')";
        $this->fArray[] = "(prospeccao.inativo = ?)";
        $this->fParmsArray[] = "F";
        $this->fParmsNameArray[] = 'inativo';
        
        $this->fDescricaoArray[] = "(isnull(prospeccao.comercial, '') <> '')";
        $this->fArray[] = "(isnull(prospeccao.comercial, '') <> ?)";
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
            $this->fDescricaoArray[] = "(prospeccao.comercial = '{$this->usuarioacesso->Codigo}')";
            $this->fArray[] = "(prospeccao.comercial = ?)";
            $this->fParmsArray[] = $this->usuarioacesso->Codigo;
            $this->fParmsNameArray[] = 'comercial_id2'; 
        }
        
        if (isset($this->params['nome']) && ($this->params['nome']!=="")){
            $this->fArray[] = "(prospeccao.nome like ?)";
            $this->fDescricaoArray[] = "(prospeccao.nome like '%". paramstostring($this->params['nome'])."%')";
            $this->fParmsArray[] = "%". paramstostring($this->params['nome'])."%";
            $this->fParmsNameArray[] = "nome";
        }
        if (isset($this->params['comercial'])&&($this->params['comercial']!=="")){
            $this->fArray[] = "(usuariosweb.nome like ?)";
            $this->fDescricaoArray[] = "(usuariosweb.nome = like '%".trocaAspas($this->params['comercial'])."%')";
            $this->fParmsArray[] =  "%".trocaAspas($this->params['comercial'])."%";
            $this->fParmsNameArray[] = "comercial";
        }
        
        if (isset($this->params['pendencia'])&&($this->params['pendencia']!=="")){
            $this->fArray[] = "(ISNULL(prospeccao.pendencia, 'F') = ?)";
            $this->fDescricaoArray[] = "(ISNULL(prospeccao.pendencia, 'F') = '".trocaAspas($this->params['pendencia'])."')";
            $this->fParmsArray[] = trocaAspas($this->params['pendencia']);
            $this->fParmsNameArray[] = "pendencia";
        }
        
        
        $this->filtro = implode(' and ', $this->fArray);
        //echo implode(' and ', $this->fDescricaoArray);
        $this->descricaofiltro = crypto::encrypt(implode(' and ', $this->fDescricaoArray));        
    }
        

    public function __construct($params, $conexao, $usuarioacesso) {
        $this->params = $params;
        $this->conexao = $conexao;
        $this->usuarioacesso = $usuarioacesso;
    }
    
    public function lista(){
        $this->preparafiltro();
        $this->conexao->setRequisicao(true);
        $SQL = "Select count(*) total from prospeccao "
                ." inner join agencia on agencia.id = prospeccao.agencia_id "
                ." inner join usuariosweb on usuariosweb.codigo = prospeccao.comercial "
                . "where ".$this->filtro;
        $retorno = $this->conexao->consultar($SQL, $this->fParmsArray, $this->fParmsNameArray, $this->usuarioacesso->Codigo);
        if ($retorno===false){
            $this->msg = $this->conexao->mensagem;
            return false;
        }
        $this->count = $retorno[0]['total'];
        $SQL = "Select prospeccao.nome, prospeccao.id, prospeccao.email, fone, "
                . "usuariosweb.nome usuario, agencia.nome agencia, "
                . "Case When ISNULL(pendencia, 'F') = 'F' then 'SIM' else 'NAO' end pendencia, "
                . "Case when (ISNULL(pendencia, 'F') = 'F') and (ISNULL(tppendencia, 'C') = 'C') then '1° Contato' "
                . "     when (ISNULL(pendencia, 'F') = 'F') and (ISNULL(tppendencia, 'C') = 'R') then 'Retorno Contato' "
                . "     else 'Sem Pendencia' end tppendencia "
                . "from prospeccao "
                ." inner join agencia on agencia.id = prospeccao.agencia_id "
                ." inner join usuariosweb on usuariosweb.codigo = prospeccao.comercial "
                ." where ".$this->filtro.
                " ORDER BY prospeccao.id desc OFFSET (100 * {$this->params['pag']}) - 100 ROWS FETCH NEXT 100 ROWS ONLY";
        //echo $SQL;
        $retorno = $this->conexao->consultar($SQL, $this->fParmsArray, $this->fParmsNameArray, $this->usuarioacesso->Codigo);
        if ($retorno===false){
            $this->msg = $this->conexao->mensagem;
            return false;
        }if ($this->conexao->contagem===0){
            $this->msg = "Nenhum registro encontrado";
            return false;
        }
        foreach ($retorno as $key => $value) {
            $prospeccao = new prospeccao();
            $prospeccao->setNome(utf8_encode($value['nome']));
            $prospeccao->setAgencia(utf8_encode($value['agencia']));
            $prospeccao->setId($value['id']);
            $prospeccao->setEmail($value['email']);
            $prospeccao->setUsuario($value['usuario']);
            $prospeccao->setFone($value['fone']);
            $prospeccao->setPendencia($value['pendencia']);
            $prospeccao->setTipopendencia($value['tppendencia']);
            $this->prospeccao[$key] = $prospeccao;
        }
        return true;
    }
    
    public function index($codigo) {
        $prospecao = new prospeccao();
        $codigo = ((crypto::decrypt($codigo)===false)?0:crypto::decrypt($codigo));
        if ($codigo === 0) {
            $prospecao->setAgencia(($this->usuarioacesso->Agencia>0?$this->usuarioacesso->Agencia:""));
            $this->prospeccao = $prospecao;
            return true;
        }
        
        $this->conexao->setRequisicao(true);
        $this->preparafiltro();
        $SQL = "Select prospeccao.id, prospeccao.nome, prospeccao.email, prospeccao.fone,"
                . "prospeccao.endereco, prospeccao.numero, prospeccao.complemento, prospeccao.naofechado, "
                . "convert(nvarchar(10), prospeccao.datanovo, 103) datanovo, "
                . "prospeccao.contato, prospeccao.ramo, prospeccao.volume, usuariosweb.nome comercial, "
                . "convert(nvarchar(10), prospeccao.datacontato, 103) datacontato, prospeccao.agencia_id agencia, "
                . "prospeccao.bairro, prospeccao.cidade, prospeccao.uf, prospeccao.cep, "
                . "prospeccao.latitude, prospeccao.longitude, prospeccao.ocorrencia, prospeccao.comentario, "
                . "enderecoextenso from prospeccao "
                ." inner join agencia on agencia.id = prospeccao.agencia_id "
                ." inner join usuariosweb on usuariosweb.codigo = prospeccao.comercial "
                . "where " . $this->filtro .
                " and prospeccao.id = ? and prospeccao.comercial is not null";
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
        $prospecao->setId($retorno[0]['id']);
        $prospecao->setEmail($retorno[0]['email']);
        $prospecao->setFone($retorno[0]['fone']);
        $prospecao->setRua($retorno[0]['endereco']);
        $prospecao->setNumero($retorno[0]['numero']);
        $prospecao->setComplemento($retorno[0]['complemento']);
        $prospecao->setBairro($retorno[0]['bairro']);
        $prospecao->setCidade($retorno[0]['cidade']);
        $prospecao->setUf($retorno[0]['uf']);
        $prospecao->setCep($retorno[0]['cep']);
        $prospecao->setLatitude($retorno[0]['latitude']);
        $prospecao->setLongitude($retorno[0]['longitude']);
        $prospecao->setEnderecoExtenso(utf8_encode($retorno[0]['enderecoextenso']));
        //$prospecao->setComentario($retorno[0]['comentario']);
        $prospecao->setOcorrencia(utf8_encode($retorno[0]['ocorrencia']));
        $prospecao->setDatacontato((($retorno[0]['datacontato']!=="31/12/1899")?$retorno[0]['datacontato']:""));
        $prospecao->setDatanovo($retorno[0]['datanovo']);
        $prospecao->setContato($retorno[0]['contato']);
        $prospecao->setNaofechado($retorno[0]['naofechado']);
        $prospecao->setRamo($retorno[0]['ramo']);
        $prospecao->setVolume($retorno[0]['volume']);
        $prospecao->setUsuario($retorno[0]['comercial']);
        $this->prospeccao = $prospecao;
        
        $SQL = "Select 
                    CONVERT(nvarchar(10), datanovo, 103) datanovo, 
                    CONVERT(nvarchar(10), datacontato, 103) datacontato, 
                    CASE WHEN ocorrencia = 'N' THEN 'Postagem outra AGF'
                         WHEN ocorrencia = 'S'  THEN 'Fechado'
                         WHEN ocorrencia = 'C'  THEN 'Nao Fechado'
                         WHEN ocorrencia = 'R'  THEN 'Nao Atendeu Telefone'
                         
                             ELSE '' end ocorrencia, 
                    CASE WHEN naofechado = 'N' THEN ' Sem interesse no momento'
                         WHEN naofechado = 'A'  THEN 'Em processo de avaliacao'
                         WHEN naofechado = 'V'  THEN 'Verificando com responsavel'
                         WHEN naofechado = 'R'  THEN 'Nao Atendeu Telefone'
                             ELSE '' end naofechado,
                    comentario
                from Prospeccaohistorico
                Where prospeccao_id = ?
                Order by dthr ";
        $retorno = $this->conexao->consultar($SQL, array($codigo), array('codigo'), $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            return false;
        }if ($this->conexao->contagem > 0) {
            foreach ($retorno as $value) {
                $prospeccaohistorico = new prospeccaohistorico();
                $prospeccaohistorico->setComentario(utf8_encode($value['comentario']));
                $prospeccaohistorico->setData(($value['datacontato']));
                $prospeccaohistorico->setNovadata(($value['datanovo']));
                $prospeccaohistorico->setMotivo(($value['naofechado']));
                $prospeccaohistorico->setOcorrencia(($value['ocorrencia']));
                $this->prospeccaohistorico[] = $prospeccaohistorico;
            }
        }
        return true;
    }
    
    public function incluir() {
        if (!$this->usuarioacesso->Incluir){
            exit("ajax_txt\nn0\nnUsuário não possui permissão de inclusão.");    
        }
        if (ChecarCampo("prospeccao", "(inativo = 'F') and "
                                     . "(comercial = '{$this->usuarioacesso->Codigo}') and "
                                     . "(ISNULL(email, '')<>'') and "
                                     . "((email = '{$this->params['email']}') or "
                                     . "(fone = '{$this->params['telefone']}'))", $this->conexao)) {
            exit("ajax_txt\nn0\nnEmail ou telefone já cadastrado.");
        }
        if ((!isset($this->params['ocorrencia']))||($this->params['ocorrencia']=="")){
            exit("ajax_txt\nn0\nnInformação do contato inválido.");    
        }
        if (in_array($this->params['ocorrencia'] ,array("C", "R"))){
            if (!isset($this->params['naofechado'])||($this->params['naofechado']=="")){
                exit("ajax_txt\nn0\nnMotivo Não Fechado/Atendeu Telefone inválido.");
            }else if (!isset($this->params['datanovo'])||($this->params['datanovo']=="")){
                exit("ajax_txt\nn0\nnData novo contato inválido.");
            }
        }else {
            $this->params['naofechado'] = '';
            $this->params['datanovo'] = '31/12/1899';
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
                        'numerofone',
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
                        'enderecoextenso',
                        'inativo', 
                        'usuario', 
                        'comercial', 
                        'comentario', 
                        'ocorrencia', 
                        'contato', 
                        'ramo', 
                        'volume', 
                        'naofechado', 
                        'datanovo', 
                        'datacontato', 
                        'pendencia', 
                        'agencia_id'
            );
        $wV1 = array(
                        paramstostring(utf8_encode($this->params['nome'])), 
                        paramstostring(utf8_encode($this->params['email'])), 
                        trocaAspas($this->params['telefone']), 
                        soNumero($this->params['telefone']), 
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
                        'F', 
                        $this->usuarioacesso->Codigo, 
                        $this->usuarioacesso->Codigo, 
                        paramstostring(utf8_encode($this->params['comentario'])), 
                        paramstostring($this->params['ocorrencia']), 
                        paramstostring(utf8_encode($this->params['contato'])),
                        paramstostring(utf8_encode($this->params['ramo'])),
                        paramstostring(utf8_encode($this->params['volume'])),
                        paramstostring(utf8_encode($this->params['naofechado'])),
                        paramstostring(utf8_encode($this->params['datanovo'])),
                        paramstostring(utf8_encode($this->params['datacontato'])),
                        'T', 
                        ($this->usuarioacesso->Agencia>0?$this->usuarioacesso->Agencia: trocaAspas(crypto::decrypt($this->params['agencia'])))
                );
        
        $retorno = $this->conexao->inserir("prospeccao", $wC1, $wV1, $wC1, $this->usuarioacesso->Codigo);
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
                        'prospeccao_id', 
                        'usuario', 
                        'comentario', 
                        'ocorrencia', 
                        'naofechado', 
                        'datanovo', 
                        'datacontato'
            );
        $wV2 = array(
                        $codigo, 
                        $this->usuarioacesso->Codigo, 
                        paramstostring(utf8_encode($this->params['comentario'])), 
                        paramstostring($this->params['ocorrencia']), 
                        paramstostring(utf8_encode($this->params['naofechado'])),
                        paramstostring(utf8_encode($this->params['datanovo'])),
                        paramstostring(utf8_encode($this->params['datacontato'])),
                );
        
        $retorno = $this->conexao->inserir("prospeccaohistorico", $wC2, $wV2, $wC2, $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            $this->conexao->cancelar_transacao();
            exit("ajax_htm\nn0\nn{$this->msg}");
        }
        
        $retorno = $this->conexao->efetivar_transacao();
        if ($retorno === false) {
            exit("ajax_htm\nn0\nn{$this->conexao->mensagem}");
        }

        exit("ajax_htm\nn1\nnPropecção salvo com sucesso!\nn{$this->params['url']}&acao=incluir");
    }
    
     private function getID(){
        $SQL = "Select ISNULL(max(id), 0) id from prospeccao";
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
    
    public function alterar() {
        if (!$this->usuarioacesso->Alterar){
            exit("ajax_txt\nn0\nnUsuário não possui permissão de alteração.");    
        }
        $codigo = crypto::decrypt($this->params['codigo']);
        if ($codigo===false){
            exit("ajax_txt\nn0\nnCódigo invalido.");    
        }
        if (ChecarCampo("prospeccao", "(id<>{$codigo}) and "
                                     . "(inativo = 'F') and "
                                     . "(comercial = '{$this->usuarioacesso->Codigo}') and "
                                     . "(ISNULL(email, '')<>'') and "
                                     . "((email = '{$this->params['email']}') or "
                                     . "(fone = '{$this->params['telefone']}'))", $this->conexao)) {
            exit("ajax_txt\nn0\nnEmail ou telefone já cadastrado.");
        }
        if ((!isset($this->params['ocorrencia']))||($this->params['ocorrencia']=="")){
            exit("ajax_txt\nn0\nnInformação do contato inválido.");    
        }
        if (in_array($this->params['ocorrencia'] ,array("C", "R"))){
            if (!isset($this->params['naofechado'])||($this->params['naofechado']=="")){
                exit("ajax_txt\nn0\nnMotivo Não Fechado/Atendeu Telefone inválido.");
            }else if (!isset($this->params['datanovo'])||($this->params['datanovo']=="")){
                exit("ajax_txt\nn0\nnData novo contato inválido.");
            }
        }else {
            $this->params['naofechado'] = '';
            $this->params['datanovo'] = '31/12/1899';
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
                        'numerofone',
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
                        'enderecoextenso',
                        'inativo',
                        'comentario', 
                        'ocorrencia',
                        'contato', 
                        'ramo', 
                        'volume', 
                        'naofechado', 
                        'datanovo', 
                        'datacontato', 
                        'pendencia', 
                        'agencia_id'
            );
        $wV1 = array(
                        paramstostring(utf8_encode($this->params['nome'])), 
                        paramstostring(utf8_encode($this->params['email'])), 
                        trocaAspas($this->params['telefone']), 
                        soNumero($this->params['telefone']), 
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
                        'F',
                        paramstostring(utf8_encode($this->params['comentario'])), 
                        paramstostring($this->params['ocorrencia']), 
                        paramstostring(utf8_encode($this->params['contato'])),
                        paramstostring(utf8_encode($this->params['ramo'])),
                        paramstostring(utf8_encode($this->params['volume'])),
                        paramstostring(utf8_encode($this->params['naofechado'])),
                        paramstostring(utf8_encode($this->params['datanovo'])),
                        paramstostring(utf8_encode($this->params['datacontato'])),
                        'T',
                       ($this->usuarioacesso->Agencia>0?$this->usuarioacesso->Agencia: trocaAspas(crypto::decrypt($this->params['agencia']))),
                        $codigo
                );
        $wPar1 = $wC1;
        $wPar1[] = 'codigo';
        $retorno = $this->conexao->alterar("prospeccao", $wC1, $wV1, " Where id = ? ", $wPar1, $this->usuarioacesso->Codigo);
         if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            $this->conexao->cancelar_transacao();
            exit("ajax_htm\nn0\nn{$this->msg}");
        }
        
        $wC2 = array(
                        'prospeccao_id', 
                        'usuario', 
                        'comentario', 
                        'ocorrencia', 
                        'naofechado', 
                        'datanovo', 
                        'datacontato'
            );
        $wV2 = array(
                        $codigo, 
                        $this->usuarioacesso->Codigo, 
                        paramstostring(utf8_encode($this->params['comentario'])), 
                        paramstostring($this->params['ocorrencia']), 
                        paramstostring(utf8_encode($this->params['naofechado'])),
                        paramstostring(utf8_encode($this->params['datanovo'])),
                        paramstostring(utf8_encode($this->params['datacontato'])),
                );
        
        $retorno = $this->conexao->inserir("prospeccaohistorico", $wC2, $wV2, $wC2, $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            $this->conexao->cancelar_transacao();
            exit("ajax_htm\nn0\nn{$this->msg}");
        }
        
        $retorno = $this->conexao->efetivar_transacao();
        if ($retorno === false) {
            exit("ajax_htm\nn0\nn{$this->conexao->mensagem}");
        }

        exit("ajax_htm\nn1\nnPropecção salvo com sucesso!\nn{$this->params['url']}&acao=index");       
        
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
        $SQL = "Select prospeccao.nome, prospeccao.id, prospeccao.email, fone, "
                . "usuariosweb.nome usuario, agencia.nome agencia, "
                . "Case When ISNULL(pendencia, 'F') = 'F' then 'SIM' else 'NAO' end pendencia, "
                . "Case when (ISNULL(pendencia, 'F') = 'F') and (ISNULL(tppendencia, 'C') = 'C') then '1° Contato' "
                . "     when (ISNULL(pendencia, 'F') = 'F') and (ISNULL(tppendencia, 'C') = 'R') then 'Retorno Contato' "
                . "     else 'Sem Pendencia' end tppendencia "
                . "from prospeccao "
                ." inner join agencia on agencia.id = prospeccao.agencia_id "
                ." inner join usuariosweb on usuariosweb.codigo = prospeccao.comercial "
               .($this->params['filtro']<>""?"Where {$this->params['filtro']}":"").
                " Order by prospeccao.id ";
        $retorno = $this->conexao->consultar($SQL, $this->fParmsArray, $this->fParmsNameArray, $this->usuarioacesso->Codigo, false);
        if ($retorno === false) {
            exit("ajax_htm\nn0\nn{$this->conexao->mensagem}");
        }if ($this->conexao->contagem === 0) {
            exit("ajax_htm\nn0\nnNenhum registro encontrado");
        }
        $wHeader = array(); $wRegistros = array();
        $wHeader[] = 'Pendência'; $wRegistros[] = 'pendencia';
        $wHeader[] = 'Tipo Pendência'; $wRegistros[] = 'tppendencia';
        $wHeader[] = 'Agencia'; $wRegistros[] = 'agencia';
        $wHeader[] = 'Nome'; $wRegistros[] = 'nome';
        $wHeader[] = 'Email'; $wRegistros[] = 'email';
        $wHeader[] = 'Telefone'; $wRegistros[] = 'fone';
        $wHeader[] = 'Comercial'; $wRegistros[] = 'usuario';
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
    
}
