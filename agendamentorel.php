<?php
require('uses/funcoes.php');
require('uses/conexao.php');
require('uses/usuarioacesso.php');
require('classes/agendamentorel.php');
require('controller/agendamentorelController.php');
require('uses/crypto.php');
sec_session_start(); // Nossa segurança personalizada para iniciar uma sessão php.
$conexao = new Conexao();
$retorno = $conexao->conectar();
if ($retorno === false) {
    header('location:Sair.php');
}
if ((login_check($conexao) === false)) {
    header('location:Sair.php');
}
$acao = ((isset($_REQUEST['acao'])) ? $_REQUEST['acao'] :false);
$pag = ((isset($_REQUEST['pag'])) ? $_REQUEST['pag']  :false);
if (($acao === false) || $pag === false) {
    exit(header('location:home'));
}
$url = getURL($_REQUEST);
$pagina = explode("/", $_SERVER['PHP_SELF']);
$pagina = end($pagina);
$pagina = explode(".", $pagina);
$pagina = $pagina[0];
$usuarioacesso = new usuarioacesso($_SESSION['Codigo'], $conexao, $pagina);
if ($usuarioacesso->Retorno === false) {
    header('location:Sair');
}
if ($usuarioacesso->Password){
    exit(header('location:novasenha'));    
}
if ($usuarioacesso->Pagina === false){
    exit(header('location:home'));    
}

$menu = ("Relatorio");
$controller = new agendamentorelController($_REQUEST, $conexao, $usuarioacesso);
?>

<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <?php include("template/Head.php"); ?>
    <body>
        <?php include('template/Header.php'); ?>
        <div class="container container-color-white">
            <div id="conteudo" class="conteudo">	
                <?php include "pages/{$pagina}/{$acao}.php"; ?> 
            </div>
        </div><!-- end .conteudo -->
        <?php include 'template/footer.php'; ?>
        <?php include 'template/mensagens.php'; ?>
    </body>
</html>