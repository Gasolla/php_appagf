<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of reloadpasswordController
 *
 * @author Marcelo
 */
include 'Email/Newpass.php';
class reloadpasswordController {

    //put your code here
    private $params;
    private $conexao;
    private $usuarioacesso;
    public $msg;
    public $count = 0;
    public $descricaofiltro;

    public function __construct($params, $conexao, $usuarioacesso) {
        $this->params = $params;
        $this->conexao = $conexao;
        $this->usuarioacesso = $usuarioacesso;
    }

    public function incluir() {
        if (!isset($this->params['email'])) {
            exit("ajax_htm\nn0\nnEmail não definido.");
        }
        $email = trocaAspas($this->params['email']);
        $SQL = "Select UsuariosWeb.codigo From UsuariosWeb
                Where (UsuariosWeb.UserEmail = ?) and (UsuariosWeb.Inativo = ?) ";
        $retorno = $this->conexao->consultar($SQL, array($email, 'F'), array("email1", "inativo"), $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            exit("ajax_htm\nn0\nn{$this->conexao->mensagem}");
        } else if ($this->conexao->contagem === 0) {
            exit("ajax_htm\nn0\nnEmail inválido ou não encontrado.");
        }
        $codigo = $retorno[0]['codigo'];
        $verificacao = getSeguro();
        $password = strtoupper(sha1('M1' . htmlentities(stripslashes($verificacao)) . 'D45'));
        $C = array(
            "senha", 
            "codigoverificacao", 
            "dthrverificacao"
        );
        $V = array(
            "BLOQUEADA", 
            $password, 
            date('d/m/Y H:i:s', strtotime('+15 minutes')), 
            $codigo
        );
        $P = $C;
        $P[] = 'codigo';
        $retorno = $this->conexao->alterar("UsuariosWeb", $C, $V, " Where codigo = ? ", $P, $this->usuarioacesso->Codigo);
        if ($retorno === false) {
            exit("ajax_htm\nn0\nn{$this->conexao->mensagem}");
        }
        $envio = new Newpass($email, $verificacao);
        if ($envio->sucesso === false) {
            exit("ajax_htm\nn0\nnFalha ao enviar o e-mail. Tente novamente mais tarde.");
        }
        $codigo = crypto::encrypt($codigo);
        exit("ajax_htm\nn1\nn{$email}\nn{$codigo}");
    }

}
