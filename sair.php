<?php
 include_once 'uses/Funcoes.php';
 sec_session_start();
 sec_session_destroy();
 header('Location: Index.php');
?>
