<?php
require('uses/funcoes.php');
require('uses/conexao.php');
require('uses/usuarioacesso.php');
require('controller/homeController.php');
require('uses/crypto.php');
sec_session_start(); // Nossa segurança personalizada para iniciar uma sessão php.
$conexao = new Conexao();
$retorno = $conexao->conectar();
if ($retorno === false) {
    exit(header('location:sair'));
}
if ((login_check($conexao) === false)) {
    exit(header('location:sair'));
}
$pagina = explode("/", $_SERVER['PHP_SELF']);
$pagina = end($pagina);
$pagina = explode(".", $pagina);
$pagina = $pagina[0];
$pag = 1;
$url = '';
$usuarioacesso = new usuarioacesso($_SESSION['Codigo'], $conexao, $pagina);
if ($usuarioacesso->Retorno === false) {
    exit(header('location:sair'));
}
if ($usuarioacesso->Password){
    exit(header('location:novasenha'));    
}
$menu = ("");
if ($usuarioacesso->Codigo === '000003') {
    exit(header('location:agendamento?pag=1&acao=incluir'));
}
$controller = new homeController($_REQUEST, $conexao, $usuarioacesso);
?>

<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <?php include("template/Head.php"); ?>
    <body>
        <?php include('template/Header.php'); ?>
        <div class="container d-flex flex-row" >
            <div class="row col justify-content-center align-self-center" style="margin-top: 50px; margin-bottom: 100px">
                <?php include "pages/{$pagina}/index.php"; ?> 
            </div>
        </div>
        <?php include 'template/footer.php'; ?>
        <?php include 'template/mensagens.php'; ?>
    </body>
</html>