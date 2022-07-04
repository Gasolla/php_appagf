<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of prospeccaohistorico
 *
 * @author marcelo
 */
class prospeccaohistorico {
    //put your code here
    private $data;
    private $datahora;
    private $novadata;
    private $ocorrencia;
    private $motivo;
    private $comentario;
    
    function getData() {
        return $this->data;
    }

    function getDatahora() {
        return $this->datahora;
    }

    function getNovadata() {
        return $this->novadata;
    }

    function getOcorrencia() {
        return $this->ocorrencia;
    }

    function getMotivo() {
        return $this->motivo;
    }

    function getComentario() {
        return $this->comentario;
    }

    function setData($data) {
        $this->data = $data;
    }

    function setDatahora($datahora) {
        $this->datahora = $datahora;
    }

    function setNovadata($novadata) {
        $this->novadata = $novadata;
    }

    function setOcorrencia($ocorrencia) {
        $this->ocorrencia = $ocorrencia;
    }

    function setMotivo($motivo) {
        $this->motivo = $motivo;
    }

    function setComentario($comentario) {
        $this->comentario = $comentario;
    }


}
