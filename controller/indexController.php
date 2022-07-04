<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of indexController
 *
 * @author marcelo
 */
class indexController {

    //put your code here
    private $params;
    private $conexao;
    private $usuarioacesso;

    public function __construct($params, $conexao, $usuarioacesso) {
        $this->params = $params;
        $this->conexao = $conexao;
        $this->usuarioacesso = $usuarioacesso;
    }

    public function login() {
        if (isset($this->params['usuario'], $this->params['p'])) {
            $usu = $this->params['usuario'];
            $pas = $this->params['p']; // The hashed password.

            $this->conexao->setRequisicao(true);
            if (login($usu, $pas, $this->conexao) == true) {
                exit("ajax_htm\nn1\nnSeja bem vindo {$_SESSION['Usuario']}");
            }
        }else{
            exit("ajax_htm\nn0\nnParametros indefinidos.");
        }
    }

}
