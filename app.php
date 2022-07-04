<?php
require('uses/funcoes.php');
require('uses/conexao.php');
require('uses/usuarioacesso.php');
require('uses/excel.php');
require('uses/crypto.php');
sec_session_start(); // Nossa segurança personalizada para iniciar uma sessão php.
$conexao = new Conexao();
$retorno = $conexao->conectar();
if ($retorno === false) {
    exit("ajax_htm\nn0\nn{$conexao->mensagem}");
}
$pagina = "app";
$codigousuarioacesso = (isset($_SESSION['Codigo'])?$_SESSION['Codigo']:"0");
$usuarioacesso = new usuarioacesso($codigousuarioacesso, $conexao, $_REQUEST['class']);
$classe = $_REQUEST['class'];
$metodo = $_REQUEST['acao'];
$classe .= 'Controller';
require_once 'controller/'.$classe.'.php';
$obj = new $classe($_REQUEST, $conexao, $usuarioacesso);
$obj->$metodo();