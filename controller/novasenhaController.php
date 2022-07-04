<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of novasenhaController
 *
 * @author Marcelo
 */
class novasenhaController {
    //put your code here
    private $params;
    private $conexao;
    private $usuarioacesso;
    
    public function __construct($params, $conexao, $usuarioacesso) {
        $this->params = $params;
        $this->conexao = $conexao;
        $this->usuarioacesso = $usuarioacesso;
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
