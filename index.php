<?php
require('uses/crypto.php');
//echo crypto::encrypt("192.168.1.11");
//exit;
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
        <div style="color: white">
            <h5 style="position: absolute; left: 50px; top: 50px;">APP AGF</h5>
        </div>
        <div class="container h-100 container-color-white">
            <div class="d-flex flex-row h-100 justify-content-center align-items-center">
                <div  class="col-sm-10 col-md-10 col-xs-12 offset-sm-2 offset-md-2">
                     <?php include "pages/{$pagina}/index.php"; ?> 
                </div>
                <!-- /#page-content-wrapper -->
            </div>
        </div>
        <?php include 'template/Mensagens.php'; ?>
        <?php include 'template/Footer.php'; ?>                
    </body>
</html>