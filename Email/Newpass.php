<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of newpass
 *
 * @author Marcelo
 */
class Newpass {
    //put your code here
    private $mail;
    public $sucesso;
    public function Newpass($email, $verificacao){
        $this->mail = new PHPMailer();
        new Email($this->mail);
        $this->mail->Subject = "CODIGO DE VERIFICACAO GRUPO MRS";
        $this->mail->Body = utf8_decode($this->preparaEmail($verificacao));
        $this->mail->AddAddress($email);
        $this->sucesso = $this->mail->Send();
    }
    
    private function preparaEmail($codigo){
        date_default_timezone_set('America/Sao_Paulo');
        $corpo=
		"<html>".
			"<head>".
				"<title>Código de Verificação</title>".
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
				"<br><br>".
                                "Segue a baixo o código solicitado para alteração da senha.<br>".
				"O código é valido por apenas 15 minutos após a solicitação. ".
                                "Caso ultrapasse esse período sera necessário uma nova solicitação.<br>".
				"<br><br>".
                               "Código: <b>{$codigo}</b><br><br>".
                               "IP: " . get_client_ip() . "<br><br>" .
                                "Atensiosamente<br>GrupoMRS".
			"</body>".
		"</html>";
        return $corpo;                        
    }
}
