<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Log
 *
 * @author MRS027
 */

class Log {
    //put your code here
    public $mail;
    public function Log($Assunto){
        $this->mail = new PHPMailer();
        new Email($this->mail);
        $this->mail->Subject = "Erros Sistema Coletor MRS";
        $this->mail->Body = $this->preparaEmail($Assunto);
        $this->mail->AddAddress("desenvolvimento2@grupomrs.com.br"); 
        $this->mail->Send();
        
    }
    
    private function preparaEmail($Assunto){
        date_default_timezone_set('America/Sao_Paulo');
        $corpo=
		"<html>".
			"<head>".
				"<title>Erro Sistema Dossie</title>".
                                "<meta http-equiv='content-type' content='text/html; charset=utf-8' /> ".        
				"<style>".
					"*{border:0px;font-color:rgb(0,0,0);".
					"font-family:arial;".
					"font-size:14px;".
					"margin:0px;padding:0px}".
					"br{clear: both}".
				"</style>".
			"</head>".
			"<body>".
				"<br><br><b>".
                                "Data: ".date('d/m/Y h:m:s')."<br>".
				"<br>".      
				utf8_decode($Assunto).
				"<br><br>".
                                "Atensiosamente<br>GrupoMRS".
			"</body>".
		"</html>";
        return $corpo;                        
    }
}
