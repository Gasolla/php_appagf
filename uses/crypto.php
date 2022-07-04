<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of crypto
 *
 * @author marcelo
 */
class crypto {

    //put your code here
    const METHOD = 'AES-256-CBC';
    const key = '65242145fdggf%g*#()gb';
    const key_iv = 'gf8548gf*&fse21?hg5r';

    /**
     * Encrypts (but does not authenticate) a message
     * 
     * @param string $message - plaintext message
     * @return string (raw binary)
     */
    public static function encrypt($message) {
        $key = hash('sha256',self::key);
        $iv = substr(hash('sha256', self::key_iv), 0, 16);
        $output = openssl_encrypt($message, self::METHOD, $key, 0, $iv);
        return  base64_encode($output);
    }
    
    /**
     * Decrypts (but does not verify) a message
     * 
     * @param string $message - ciphertext message
     * @return string
     */
    public static function decrypt($message) {
        $key = hash('sha256',self::key);
        $iv = substr(hash('sha256', self::key_iv), 0, 16);
        $output = openssl_decrypt(base64_decode($message), self::METHOD, $key, 0, $iv);
        return  $output;
    }

}
