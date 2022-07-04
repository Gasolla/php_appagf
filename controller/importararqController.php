<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of importararqController
 *
 * @author Marcelo
 */
require('classes/extractexcellayout.php');
class importararqController {
    //put your code here
    //put your code here
    private $params;
    private $conexao;
    private $filtro;
    //private $fArray = array();
    //private $fDescricaoArray = array();
    private $fParmsArray = array();
    private $fParmsNameArray = array();
    private $usuarioacesso;
    public $msg;
    public $count = 0;
    public $descricaofiltro;
    public $importararq = array();

    function getImportararq() {
        return $this->importararq;
    }

  
    public function __construct($params, $conexao, $usuarioacesso) {
        $this->params = $params;
        $this->conexao = $conexao;
        $this->usuarioacesso = $usuarioacesso;
    }
    
    public function lista() {
        $this->conexao->setRequisicao(true);
        //$this->preparafiltro();
        $SQL = "Select count(*) total from VW_ImportarArq "
                . ($this->filtro <> "" ? "Where {$this->filtro}" : "");
        $retorno = $this->conexao->consultar($SQL, $this->fParmsArray, $this->fParmsNameArray, $this->usuarioacesso->Codigo, false);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            return false;
        }
        $this->count = $retorno[0]['total'];
        $SQL = "Select 
                  id, 
                  CONVERT(nvarchar(10), dthr, 103) data, 
                  qtde, usuario, arqnome, arq 
                From VW_ImportarArq "
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
            $importararq = new importararq();
            $importararq->setId($value['id']);
            $importararq->setData($value['data']);
            $importararq->setQtde($value['qtde']);
            $importararq->setUsuario($value['usuario']);
            $importararq->setArquivo($value['arq']);
            $importararq->setArqnome($value['arqnome']);
            
            $this->importararq[$key] = $importararq;
        }
        return true;
    }
    
    public function index(){
        if ($this->usuarioacesso->Codigo === "") {
            exit("ajax_htm\nn0\nnSessão expirada!");
        }
        $dir = ajusta_temporario_excel($this->usuarioacesso->Codigo);
        if (!isset($_FILES['arq'])) {
            exit("ajax_htm\nn0\nnArquivo excel não foi definido");
        }
        $arquivo = $_FILES['arq'];
        $retorno = checarArquivo($arquivo);
        if ($retorno !== "OK") {
            exit("ajax_htm\nn0\nn{$retorno}");
        }

        $extensao = explode('.', $arquivo['name']);
        $extensao = strtoupper(end($extensao));
        if (($extensao !== 'XLS') && ($extensao !== 'XLSX')) {
            exit("ajax_htm\nn0\nnArquivo com extensão diferente de 'XLS'/'XLSX'");
        }
        $extrair = new extractexcellayout($arquivo['tmp_name'], $extensao);

        
        $retorno = $extrair->extrair();
        if ($retorno === false) {
            exit("ajax_htm\nn0\nn{$extrair->msg}");
        }
        
        $wdir = "doc/importararq/";
        if (!file_exists($wdir)) {
            if (!mkdir($wdir, 0755, true)) {
                exit("ajax_htm\nn0\nnFalha ao criar o diretorio!");
            }
        }
        
        date_default_timezone_set('America/Sao_Paulo');
        $retorno = $this->conexao->inicializar_transacao();
        if ($retorno === false) {
            exit("ajax_htm\nn0\nn{$this->conexao->mensagem}");
        }

        $planilha = $this->getCodigo();
        if ($planilha === False) {
            $this->conexao->cancelar_transacao();
            exit("ajax_htm\nn0\nn{$this->msg}");
        }
        $extensao = explode('.', $arquivo['name']);
        $extensao = $wdir.($planilha) . "." . strtoupper(end($extensao));
        
        $C = array(
            "arq",
            "arqnome",
            "dthr",
            "qtde",
            "usuario"
        );

        $V = array(
            $extensao,
            trocaAspas($arquivo['name']),
            date('d/m/Y H:i:s'),
            $extrair->contagem,
            $this->usuarioacesso->Codigo
        );
        $retorno = $this->conexao->inserir("ImportarArq", $C, $V, $C, $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            $this->conexao->cancelar_transacao();
            exit("ajax_htm\nn0\nn{$this->msg}");
        }
        
         
        if ((!move_uploaded_file($arquivo['tmp_name'], $extensao))) {
            $this->conexao->cancelar_transacao();
            exit("ajax_htm\nn0\nnFalha na importacao do arquivo!");
        }
        
        foreach ($extrair->getImportararqitens() as $value) {
            $C = array(
                "ImportarArq",
                "objeto",
                "peso",
                "valor",
            );

            $V = array(
                $planilha,
                paramstostring($value->getObjeto()),
                paramstostring($value->getPeso()),
                strtofloat(paramstostring($value->getValor())),
                
            );
            $retorno = $this->conexao->inserir("ImportarArqItens", $C, $V, $C, $this->usuarioacesso->Codigo);
            if ($retorno === false) {
                $this->msg = $this->conexao->mensagem;
                $this->conexao->cancelar_transacao();
                exit("ajax_htm\nn0\nn{$this->msg}");
            }
            
            $C = array(
                "peso",
                "valor"
            );

            $V = array(
                paramstostring($value->getPeso()),
                strtofloat(paramstostring($value->getValor())),
                paramstostring($value->getObjeto())    
            );
            
            $P = $C;
            $P[] = 'objeto';
            $retorno = $this->conexao->alterar("CorreioObjetos", $C, $V, " Where objeto = ?  ", $P, $this->usuarioacesso->Codigo);    
            if ($retorno === false) {
                $this->msg = $this->conexao->mensagem;
                $this->conexao->cancelar_transacao();
                exit("ajax_htm\nn0\nn{$this->msg}");
            }
        }
        
        $retorno = $this->conexao->efetivar_transacao();
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            $this->conexao->cancelar_transacao();
            exit("ajax_htm\nn0\nn{$this->msg}");
        }
        
        exit("ajax_htm\nn1\nnArquivo cadastrada com sucesso!");
    }

    
    private function getCodigo() {
        $SQL = "SELECT IDENT_CURRENT('ImportarArq') AS Codigo";
        $retorno = $this->conexao->consultar($SQL, array(), array(), $this->usuarioacesso->Codigo, false);
        if ($retorno === False) {
            $this->msg = $this->conexao->mensagem;
            return false;
        }
        return ($retorno[0]['Codigo'] + 1);
    }

    public function excel() {
        if (!$this->usuarioacesso->Gerar) {
            exit("ajax_txt\nn0\nnUsuário não possui permissão de geração.");
        }

        $this->conexao->setRequisicao(true);
        $this->params['filtro'] = crypto::decrypt($this->params['filtro']);
        $SQL = "Select 
                  id, 
                  CONVERT(nvarchar(10), dthr, 103) data, 
                  qtde, usuario, arqnome, arq 
                From VW_ImportarArq  "
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
        if ($this->usuarioacesso->Agencia==0){
            $wHeader[] = 'Agencia';
            $wRegistros[] = 'agencia';
        }
        
        $wHeader[] = 'Data';
        $wRegistros[] = 'data';
        $wHeader[] = 'Qtde';
        $wRegistros[] = 'qtde';
        $wHeader[] = 'Usuario';
        $wRegistros[] = 'usuario';
        $wHeader[] = 'Nome Arquivo';
        $wRegistros[] = 'arqnome';
        $caminho = ajusta_temporario_excel($this->usuarioacesso->Codigo) . "excel.xls";
        $excel = new excel($wHeader, $wRegistros, $retorno);
        $excel->gerar($caminho);
    }
    
}
