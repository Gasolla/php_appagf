<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PreparaExcel
 *
 * @author marcelo
 */
class PreparaExcel {

    //put your code here
    public function PreparaExcel($Array, $Caminho) {
        include 'uses/domxls/PHPExcel.php';
        // Instanciamos a classe
        $objPHPExcel = new PHPExcel();
        //criando bordas
        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        $objPHPExcel->getDefaultStyle()->applyFromArray($styleArray);
        $inicial = 'A';
        $atual = 0;
        $colunas = array();
        while (count($Array) > $atual) {
            $colunas[] = $inicial++;
            $atual++;
        }
        $objPHPExcel->getActiveSheet()->getStyle("A1:{$colunas[$atual-1]}1")->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle("A1:{$colunas[$atual-1]}1")->getFont()->setSize(12);
        foreach ($colunas as $key => $value) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue("{$value}1", $Array[$key]);
        }
        foreach ($colunas as $key => $value) {
            $objPHPExcel->getActiveSheet()->getColumnDimension("{$value}")->setAutoSize(true);
        }
        // Salva diretamente no output, poderíamos mudar arqui para um nome de arquivo em um diretório ,caso não quisessemos jogar na tela
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save($Caminho);
    }

}
