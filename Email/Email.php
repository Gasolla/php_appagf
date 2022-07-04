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
        $mail->Host = 'smtp.grupomrs.com.br'; 
        $mail->SetLanguage("br","./uses/phpmailer/language/");
        $mail->Port = 587; //porta usada pelo gmail.
        $mail->Username = "noreply@grupomrs.com.br"; //'seuemail@gmail.com'; // usuario gmail.   
        $mail->Password = "MsR22#NrY@";//'suasenhadogmail'; // senha do email.
        $mail->From = "noreply@grupomrs.com.br";
        $mail->Sender = "noreply@grupomrs.com.br"; // Seu e-mail
        $mail->FromName = "GrupoMRS"; // Seu nome
        $mail->IsHTML(true); 
        
    }
}
