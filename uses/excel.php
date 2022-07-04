<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of excel
 *
 * @author marcelo
 */
require_once( "/uses/Excel2007/Classes/PHPExcel.php");

class excel {

    private $header = array();
    private $registros = array();
    private $dados = array();
    private $colunas = array();
    private $atual;
    private $objPHPExcel;

    //put your code here
    public function __construct($header, $registros, $dados) {
        $this->header = $header;
        $this->registros = $registros;
        $this->dados = $dados;
    }

    public function gerar($caminho) {
        $this->objPHPExcel = new PHPExcel();
        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        );
        $this->objPHPExcel->getDefaultStyle()->applyFromArray($styleArray);
        $this->preparaheader();
        $this->prepararegistros();
        $objWriter = PHPExcel_IOFactory::createWriter($this->objPHPExcel, 'Excel5');
        $objWriter->save($caminho);
        exit("ajax_htm\nn1\nn{$caminho}");

    }

    private function preparaheader() {
        $inicial = 'A';
        $this->atual = 0;
        $this->colunas = array();
        while (count($this->header) > $this->atual) {
            $this->colunas[] = $inicial++;
            $this->atual++;
        }
        $this->objPHPExcel->getActiveSheet()->getStyle("A1:{$this->colunas[$this->atual - 1]}1")->getFont()->setColor(new PHPExcel_Style_Color(PHPExcel_Style_Color::COLOR_WHITE));
        $this->objPHPExcel->getActiveSheet()->getStyle("A1:{$this->colunas[$this->atual - 1]}1")->getFont()->setBold(true);
        $this->objPHPExcel->getActiveSheet()->getStyle("A1:{$this->colunas[$this->atual - 1]}1")->getFont()->setSize(13);
        $this->objPHPExcel->getActiveSheet()->getStyle("A1:{$this->colunas[$this->atual - 1]}1")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->objPHPExcel->getActiveSheet()->getStyle("A1:{$this->colunas[$this->atual - 1]}1")->applyFromArray(
                array('fill' => array(
                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                        'color' => array('rgb' => '000000')
                    ),
                )
        );
        foreach ($this->colunas as $key => $value) {
            $this->objPHPExcel->setActiveSheetIndex(0)->setCellValue("{$value}1", $this->header[$key]);
        }
        foreach ($this->colunas as $key => $value) {
            $this->objPHPExcel->getActiveSheet()->getColumnDimension("{$value}")->setAutoSize(true);
        }
    }

    private function prepararegistros() {
        $cont = 2;
        foreach ($this->dados as $value) {
            extract(array_map('_corrige_saida_txt', $value));
            if ($cont % 2 == 0) {
                $this->objPHPExcel->getActiveSheet()->getStyle("A{$cont}:{$this->colunas[$this->atual - 1]}{$cont}")->applyFromArray(
                        array('fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array('rgb' => 'DCDCDC')
                            ),
                        )
                );
            }
            foreach ($this->registros as $key => $values) {
                $this->objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($key, $cont, (Trim($$values)));
            }
            $cont++;
        }
    }

}
