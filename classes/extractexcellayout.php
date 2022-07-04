<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of extractexcellayout
 *
 * @author Marcelo
 */
require('classes/importararqitens.php');

class extractexcellayout {

    //put your code here
    //put your code here
    private $arquivo;
    private $extensao;
    private $importararqitens = array();
    public $msg;
    public $contagem;

    function getImportararqitens() {
        return $this->importararqitens;
    }

    public function __construct($arquivo, $extensao) {
        $this->arquivo = $arquivo;
        $this->extensao = $extensao;
    }

    public function extrair() {
        return ($this->extensao == "XLS" ? $this->extrairxls() : $this->extrairxlsx());
    }

    private function extrairxls() {
        $data = new Spreadsheet_Excel_Reader($this->arquivo);
        $data->setColumnFormat(7, 0x16);
        $data->setColumnFormat(8, 0x16);
        $totalLinhas = $data->rowcount(); //Normalmente pega mais linhas que realmente tem
        $totalColunas = $data->colcount();
        //////////////////////////////////////////////////////////////////->TRATAMENTO
        $linha = 0;
        for ($x = 2; $x <= $totalLinhas; $x++) {
            $importararqitens = new importararqitens();
            $encontrou = false;
            for ($y = 1; $y <= $totalColunas; $y++) {
                $coluna1 = Trim(preparaString($data->val(1, $y)));
                $coluna2 = Trim($data->val($x, $y));
                if (($coluna1 == "HISTORICO") && (substr($coluna2, 0, 7) == "Objeto:")) {
                    $encontrou = true;
                    $importararqitens->setObjeto(Trim(substr($coluna2, 8, 11)."BR"));
                    $importararqitens->setPeso(substr(Trim($data->val($x+1, $y)), 23, 5));
                    $importararqitens->setValor(Trim($data->val($x+1, $y+2)));
                }
            }
            if ($encontrou){
                $linha++;
                if ($this->validar($importararqitens, $linha)) {
                    $this->importararqitens[] = $importararqitens;
                } else {
                    return false;
                }
            }
        }
        $this->contagem = $linha;
        if ($linha === 0) {
            $this->msg = "Não encontrado registro no excel";
            return false;
        }

        return true;
    }

    private function validar($importararqitens, $linha) {
        if (in_array($importararqitens->getObjeto(), array("", null))) {
            $this->msg = "Campo Objeto inválido linha: " . $linha;
            return false;
        }else  if (in_array($importararqitens->getPeso(), array("", null))) {
            $this->msg = "Campo pesso inválido linha: " . $linha;
            return false;
        }else  if (in_array($importararqitens->getValor(), array("", null))) {
            $this->msg = "Campo valor inválido linha: " . $linha;
            return false;
        }
        return true;
    }

    private function extrairxlsx() {
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objReader->setReadDataOnly(true);
        $objPHPExcel = $objReader->load($this->arquivo);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestColumn = $objWorksheet->getHighestColumn();
        $totalLinhas = $objWorksheet->getHighestRow();
        $totalColunas = PHPExcel_Cell::columnIndexFromString($highestColumn);
        //////////////////////////////////////////////////////////////////
        $linha = 0;
        for ($x = 2; $x <= $totalLinhas; $x++) {
            $importararqitens = new importararqitens();
            $encontrou = false;
            for ($y = 1; $y <= $totalColunas; $y++) {
                $coluna1 = trim(preparaString($objWorksheet->getCellByColumnAndRow($y - 1, 1)->getValue()));
                $coluna2 = Trim($objPHPExcel->getActiveSheet()->getCellByColumnAndRow($y - 1, $x)->getValue());
                if (($coluna1 == "HISTORICO") && (substr($coluna2, 0, 7) == "Objeto:")) {
                    $encontrou = true;
                    $importararqitens->setObjeto(Trim(substr($coluna2, 8, 11)."BR"));
                    $importararqitens->setPeso(substr(Trim($objPHPExcel->getActiveSheet()->getCellByColumnAndRow($y - 1, $x+1)->getValue()), 23, 5));
                    $importararqitens->setValor(Trim($objPHPExcel->getActiveSheet()->getCellByColumnAndRow((($y - 1)+2), $x+1)->getValue()));    
                }
            }
            if ($encontrou){
                $linha++;
                if ($this->validar($importararqitens, $linha)) {
                    $this->importararqitens[] = $importararqitens;
                } else {
                    return false;
                }
            }
        }
        $this->contagem = $linha;
        if ($linha === 0) {
            $this->msg = "Não encontrado registro no excel";
            return false;
        }

        return true;
    }

}
