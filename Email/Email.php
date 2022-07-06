<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Email
 *
 * @author MRS027
 */

class Email {
    //put your code here
    public function Email($mail){
        $mail->IsSMTP();
       $mail->SMTPAuth = true; 
        //para que o erro seja mostrado descomente a linha abaixo 
        //$mail->SMTPDebug = 3;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Host = 'host'; 
        $mail->SetLanguage("br","./uses/phpmailer/language/");
        $mail->Port = 587; //porta usada pelo gmail.
        $mail->Username = "email"; //'seuemail@gmail.com'; // usuario gmail.   
        $mail->Password = "senha";//'suasenhadogmail'; // senha do email.
        $mail->From = "email";
        $mail->Sender = "email"; // Seu e-mail
        $mail->FromName = "GrupoMRS"; // Seu nome
        $mail->IsHTML(true); 
        
    }
}
