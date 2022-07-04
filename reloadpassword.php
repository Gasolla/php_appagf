<?php
require('uses/crypto.php');
require('uses/funcoes.php');
sec_session_start(); // Nossa segurança personalizada para iniciar uma sessão php.
require('uses/conexao.php');
$conexao = new Conexao();
$retorno = $conexao->conectar();
if ($retorno !== false) {
    if ((login_check($conexao) === true)) {
        header('location:home.php');
    }
}
$pagina = explode("/", $_SERVER['PHP_SELF']);
$pagina = end($pagina);
$pagina = explode(".", $pagina);
$pagina = $pagina[0];
$pag = 1;
$url = '';
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <?php include("template/Head.php"); ?>
    <body>
        <div class="h-100">
            <div class="d-flex h-100 align-items-center">
                <div class="container">
                    <div id="conteudo" class="conteudo">	
                        <?php include "pages/{$pagina}/incluir.php"; ?> 
                    </div>
                </div><!-- end .conteudo -->        <!-- /#page-content-wrapper -->
            </div>
        </div>
        <?php include 'template/Mensagens.php'; ?>
        <?php include 'template/Footer.php'; ?>                
    </body>
</html>