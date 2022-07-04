<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of alterpasswordController
 *
 * @author Marcelo
 */
class alterpasswordController {

    //put your code here
    //put your code here
    private $params;
    private $conexao;
    private $usuarioacesso;
    public $error = "";
    public $count = 0;
    public $descricaofiltro;
    public $codigo;

    public function __construct($params, $conexao, $usuarioacesso) {
        $this->params = $params;
        $this->conexao = $conexao;
        $this->usuarioacesso = $usuarioacesso;
    }

    public function index() {
        if (!isset($this->params['email'], $this->params['id'], $this->params['codigo'])) {
            $this->error = 'Falha no envio dos parametros.<br>Por favor tente novamente.';
            return false;
        }
        $email = $this->params['email'];
        $this->codigo = $this->params['id'];
        $verificador = $this->params['codigo'];
        $SQL = "Select UsuariosWeb.codigo From UsuariosWeb
                Where  (UsuariosWeb.Codigo = ?) and 
                       (UsuariosWeb.UserEmail = ?) and 
                       (UsuariosWeb.CodigoVerificacao = ?) and 
                       (CURRENT_TIMESTAMP<DtHrVerificacao) and 
                       (UsuariosWeb.Inativo = 'F') ";
        $V = array(crypto::decrypt($this->codigo), $email, strtoupper(sha1('M1' . htmlentities(stripslashes($verificador)) . 'D45')));
        $C = array("codigo", "email1", "codigoverificaçao");
        $retorno = $this->conexao->consultar($SQL, $V, $C, $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            $this->error = "Falha: {$this->conexao->mensagem}.<br>Por favor tente novamente mais tarde.<br>Caso o erro persistir entre em contato com o administrador.";
        } else if ($this->conexao->contagem === 0) {
            $this->error = "Código inválido ou expirado.<br>Por favor tente novamente.<br>Caso o erro persistir entre em contato com o administrador.";
        }
    }

    public function incluir() {
        date_default_timezone_set('America/Sao_Paulo');
        if (!isset($this->params['senha'])) {
            exit("ajax_htm\nn0\nnNova senha não definida.");
        } else if (!isset($this->params['codigo'])) {
            exit("ajax_htm\nn0\nnUsuário não definido.");
        }
        $password = strtoupper(sha1('M1' . htmlentities(stripslashes($this->params['senha'])) . 'D45'));
        $codigo = crypto::decrypt($this->params['codigo']);
        $SQL = "SELECT COUNT(*) USADA FROM UsuarioSenhas "
                . "WHERE (USUARIO = ?) AND"
                . "      (SENHA = ?) ";
        $retorno = $this->conexao->consultar($SQL, array($codigo, $password), array("codigo", "passa"), $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            exit("ajax_htm\nn0\nn{$this->conexao->mensagem}");
        } else if ($retorno[0]['USADA'] > 0) {
            exit("ajax_htm\nn0\nnSenha digitada ja foi utlizada recentemente.<br>Por favor digite uma nova senha.");
        }
        $C = array('senha', 'tentativa', 'dthrexpirar', 'alterar');
        $V = array($password, '0', date('d/m/Y H:i:s'), 'F', $codigo);
        $P = $C;
        $P[] = 'codigo';
        $retorno = $this->conexao->alterar("UsuariosWeb", $C, $V, " Where codigo = ? ", $P, $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            exit("ajax_htm\nn0\nn{$this->conexao->mensagem}");
        }
        exit("ajax_htm\nn1\nn");
    }

}
