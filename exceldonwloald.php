<?php
    require('uses/crypto.php');
    ini_set("memory_limit", "1G");
    $nome = crypto::decrypt($_REQUEST['arquivo']);
	 
    $extensao = explode('.', $nome);
    $extensao = strtoupper(end($extensao));
    //Le o arquivo todo
    //$Arquivo = file_get_contents($nome);
    
    // Determina que o arquivo é uma planilha do Excel
    if (($extensao=="XLS")){
        header("Content-type: application/vnd.ms-excel");
    }else if ($extensao=="XLSX"){
        header("Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");    
    }else{
        exit(header("location:{$_REQUEST['arquivo']}"));
    }

    // Força o download do arquivo
    //header("Content-type: application/force-download");  
    // Seta o nome do arquivo
    header("Content-Disposition: attachment; filename=file.".$extensao);
    header("Pragma: no-cache");
    header("Expires: 0");

    
    header('Content-Description: File Transfer');
    header('Content-Transfer-Encoding: binary');
    header('Cache-Control: must-revalidate');
    header('Content-Length: ' . filesize($nome));
    	
    //Imprime todo arquivo
    readfile($nome);
    //echo $Arquivo;
    
    //exclui o arquivo
    //unlink($nome);
        
?>	

