<?php
require('uses/funcoes.php');
require('uses/conexao.php');
require('uses/usuarioacesso.php');
require('classes/agendamento.php');
require('controller/agendamentoController.php');
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
$acao = ((isset($_REQUEST['acao'])) ? $_REQUEST['acao'] :false);
$pag = ((isset($_REQUEST['pag'])) ? $_REQUEST['pag']  :false);
if (($acao===false)||$pag===false){
    exit(header('location:home'));    
}
$url = getURL($_REQUEST);
$pagina = explode("/", $_SERVER['PHP_SELF']);
$pagina = end($pagina);
$pagina = explode(".", $pagina);
$pagina = $pagina[0];
$usuarioacesso = new usuarioacesso($_SESSION['Codigo'], $conexao, $pagina);
if ($usuarioacesso->Retorno === false) {
    header('location:sair');
}
if ($usuarioacesso->Password){
    exit(header('location:novasenha'));    
}
$_REQUEST['cidata'] = (isset($_REQUEST['cidata']) ? $_REQUEST['cidata'] : date('d/m/Y'));
$_REQUEST['cfdata'] = (isset($_REQUEST['cfdata']) ? $_REQUEST['cfdata'] : date('d/m/Y'));
$menu = ("Cadastro");
$controller = new agendamentoController($_REQUEST, $conexao, $usuarioacesso);
?>

<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <?php include("template/head.php"); ?>
    <body>
        <?php include('template/header.php'); ?>
        <div class="container container-color-white">
             <div id="conteudo" class="conteudo">	
                <?php include "pages/{$pagina}/{$acao}.php"; ?> 
            </div>
        </div><!-- end .conteudo -->
        <?php include 'template/footer.php'; ?>
        <?php include 'template/mensagens.php'; ?>
    </body>
</html>