<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Etiquetas
 *
 * @author Marcelo
 */
define('FPDF_FONTPATH','../lib/FPDF/font/');
require('../lib/FPDF/fpdf.php');
require('../lib/FPDF/fpdi.php');
require 'PDF.php';
//require('../lib/barcode.inc.php');
//require_once('barcode/Barcode.php');
require 'vendor/autoload.php';
class etiquetas {
    
    //put your code here
    private $conexao;
    private $params;
    private $usuarioacesso;
    private $filtro;
    public $msg;
    public $status;
    
    public function __construct($conexao, $params, $usuario) {
        $this->conexao = $conexao;
        $this->params = $params;
        $this->usuarioacesso = $usuario;
    }

    private function preparafiltro() {
        $this->filtro = (isset($this->params['filtro']) ? crypto::decrypt($this->params['filtro']) : "");
        if (isset($this->params['imprimir'])) {
            $this->filtro = ($this->filtro == "" ? "" : $this->filtro . " and ");
            if (is_array($this->params['imprimir'])) {
                $wprotocolo = array();
                foreach ($this->params['imprimir'] as $value) {
                    $wprotocolo[] = "'" . crypto::decrypt($value) . "'";
                }
                $this->filtro .= "(id in (" . implode(",", $wprotocolo) . "))";
            } else {
                $this->filtro .= "(id = '" . crypto::decrypt($this->params['imprimir']) . "')";
            }
        }
    }
    
    private function preparadados() {
        $this->preparafiltro();
        $SQL = "Select id, cliente, agencia, objeto, nomedestino, ufdestino,
                    cidadedestino, bairrodestino, enderecodestino, numerodestino,
                    cepdestino, nomeremetente, ufremetente, cidadeagencia, ufagencia,
                    cidaderemetente, bairroremetente, enderecoremetente,
                    numeroremetente, cepremetente, datapostagem, nomeetiqueta,
                    dataentrega, statussro, descricaosro, datahora, descricaostatus status, servico, 
                    statusagendamento, cpfcnpj
                From VW_APIWEB "
                . (($this->filtro != "") ? "Where {$this->filtro}" : "")
                . " ORDER BY id desc ";
        $retorno = $this->conexao->consultar($SQL, array(), array(), $this->usuarioacesso->Codigo, false);
        if ($retorno === false) {
            $this->msg = $this->conexao->mensagem;
            return false;
        } else if (!($this->conexao->contagem > 0)) {
            $this->msg = "Nenhum registro encontrado!".$this->filtro;
            return false;
        }
        
        ini_set("memory_limit", "3G");
        ini_set("max_input_time", 60 * $this->conexao->contagem);
        ini_set("max_execution_time", 60 * $this->conexao->contagem);
        ini_set("mssql.timeout", 60 * $this->conexao->contagem);
        ini_set("set_time_limit", 60 * $this->conexao->contagem);
        return $retorno;
    }

    public function gerar($diretorio, $caminho) {
        $retorno = $this->preparadados();
        if ($retorno === false) {
            return false;
        }
        
        $wEtiquetas = array();
        foreach ($retorno as $key => $value) {
            $wEtiquetas[] =  $this->imprimir($diretorio, $value, ($key+1));
        }
        
        if ((count($wEtiquetas) == 0)){
            $this->msg = "Falha na criação do arquivo!";
            return false;
        } 
                
        if (UnificarEtiquetas($wEtiquetas, $caminho) != TRUE) {
            $this->msg = "Falha na criação do arquivo!";
            return false;                
        }
        
        if (!$this->atualizadados($retorno)){
            return false;    
        }
        return true;
    }
    
    private function pularlinha($pdf, $total){
        while ($total>0){
            $pdf->Ln();
            $total--;
        }
    }
    
    private function imprimir($diretorio, $dados, $cont){
        
        $pdf=new PDF(); ///Altura , Largurra 
        $redColor = [0,0,0];
        $dados['cepdestino'] = leftcaracter(soNumero($dados['cepdestino']), "0", 8);
        $dados['enderecodestino'] = $dados['enderecodestino']. " ". $dados['numerodestino'];
        $dados['enderecodestino'] = substr($dados['enderecodestino'], 0, 60);
        $dados['bairrodestino'] = substr($dados['bairrodestino'], 0, 60);
        $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
        file_put_contents($diretorio."{$dados['objeto']}.png", $generator->getBarcode($dados['objeto'], $generator::TYPE_CODE_128_A, 3, 50, $redColor));
        file_put_contents($diretorio."{$dados['objeto']}{$dados['cepdestino']}.png", $generator->getBarcode('0170078', $generator::TYPE_CODE_128_A, 3, 50, $redColor));
        
        //new barCodeGenrator('QG009663245BR',1,$diretorio."QG009663245BR.gif", 190, 130, false);
	//new barCodeGenrator('0170078',1,$diretorio."QG009663245BR0170078.gif", 190, 130, false);
	$pdf->AliasNbPages();
	$pdf->SetMargins(2,5,5,2);
	$pdf->SetAutoPageBreak(true,5);
	$pdf->AddPage('P',array(115,100));
        $pdf->Image("image/{$dados['servico']}.png",80,5,15,0,'PNG');
        //$pdf->Image('image/iconeetiqueta.png',90,83,5,0,'PNG');
        
        $pdf->Image($diretorio."{$dados['objeto']}.png",4,27,70,22,'PNG');
	$pdf->Image($diretorio."{$dados['objeto']}{$dados['cepdestino']}.png",5,81,35,14,'PNG');
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(0,1,$cont,0,1,'L');
        $pdf->SetMargins(5,5,5,5);
	$this->pularlinha($pdf, 14);
        $pdf->Cell(0,1,"              {$dados['servico']}",0,1,'L');
        $this->pularlinha($pdf, 3);
        $pdf->Cell(0,1,"              {$dados['objeto']}",0,1,'L');
	$this->pularlinha($pdf, 1);
        $this->pularlinha($pdf, 26);
        
	//$pdf->SetLeftMargin(-30);
	$pdf->Cell(0,1,"DESTINATARIO:",0,1,'L');
	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(0,0,"Entrega no Vizinho Nao Autorizada",0,1,'R');
	$pdf->Cell(0,1,"",0,1,'R');
	$this->pularlinha($pdf, 2);
        $pdf->SetFont('Arial','',9);
	$pdf->Cell(0,1,"{$dados['nomedestino']}",0,1,'L');
	$this->pularlinha($pdf, 9);
        $pdf->Cell(0,1,"{$dados['enderecodestino']}",0,1,'L');
        $this->pularlinha($pdf, 2);
        $pdf->Cell(0,1,"{$dados['bairrodestino']}",0,1,'L');
        $pdf->SetFont('Arial','B',8);
	$this->pularlinha($pdf, 8);
        $pdf->Cell(0,1, maskcep($dados['cepdestino']),0,1,'L');
        $pdf->SetFont('Arial','',8);
        $pdf->Cell(0, -1,"                          {$dados['cidadedestino']} / {$dados['ufdestino']}",0,1,'L');
        $pdf->Cell(0, 2,"",0,1,'R');
        $pdf->Cell(0, 1,"",0,1,'L');
        $this->pularlinha($pdf, 1);
        $pdf->Cell(0, 1,"{$dados['agencia']}",0,1,'R');
        $pdf->SetFont('Arial','B',8);
        $this->pularlinha($pdf, 14);
        $pdf->Cell(0, 1,"Remetente:",0,1,'L');
        $pdf->SetFont('Arial','',7);
        $this->pularlinha($pdf, 2);
        $pdf->Cell(0, 1,"{$dados['nomeetiqueta']}",0,1,'L');
        $this->pularlinha($pdf, 2);
        $pdf->Cell(0, 1,"{$dados['enderecoremetente']} {$dados['numeroremetente']}",0,1,'L');
        $this->pularlinha($pdf, 2);
        $pdf->Cell(0, 1,"{$dados['cidaderemetente']} / {$dados['ufremetente']}",0,1,'L');
        $this->pularlinha($pdf, 2);
        $pdf->Cell(0, 1, maskcep($dados['cepremetente']),0,1,'L');
        //$pdf->Cell(0, 1,"5 - PAC OE",0,1,'R');
	$pdf->SetFillColor(235);
        
        $pdf->AddPage('L',array(115,100), 90);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(105,8,utf8_decode('DECLARAÇÃO DE CONTEÚDO'),1,1,'C');
        //$this->pularlinha($pdf, 1);
        $pdf->SetFont('Arial','B',4);
	$pdf->Cell(0,-3,$dados['objeto'],0,0,'R');
        $pdf->Cell(0,+1,"",0,0,'L');
        $this->pularlinha($pdf, 1);
        $pdf->SetFont('Arial','B',4);
	$pdf->Cell(51,4,utf8_decode('REMETENTE'),1,0,'C');
        $pdf->Cell(3,4,"",0,0,'C');
        $pdf->Cell(51,4,utf8_decode('DESTINATARIO'),1,0,'C');
        $this->pularlinha($pdf, 1);
        $pdf->SetFont('Arial','B',4);
	$pdf->Cell(51,4,utf8_decode('NOME: ').$dados['nomeetiqueta'],1);
        $pdf->Cell(3,4,"",0,0,'C');
        $pdf->Cell(51,4,utf8_decode('NOME: ').$dados['nomedestino'],1);
        $this->pularlinha($pdf, 1);
        $pdf->Cell(51,4,utf8_decode('ENDEREÇO: ').$dados['enderecoremetente']." ".$dados['numeroremetente'],1);
        $pdf->Cell(3,4,"",0,0,'C');
        $pdf->Cell(51,4,utf8_decode('BAIRRO: ').$dados['enderecodestino'],1);
        $this->pularlinha($pdf, 1);
        $pdf->Cell(51,4,utf8_decode('BAIRRO: ').$dados['bairroremetente'],1);
        $pdf->Cell(3, 4,"",0,0,'C');
        $pdf->Cell(51,4,utf8_decode('BAIRRO: ').$dados['bairrodestino'],1);
        $this->pularlinha($pdf, 1);
        $pdf->Cell(43,4,utf8_decode('CIDADE: ').$dados['cidaderemetente'],1);
        $pdf->Cell(8,4,utf8_decode('UF: ').$dados['ufremetente'],1);
        $pdf->Cell(3,4,"",0,0,'C');
        $pdf->Cell(43,4,utf8_decode('CIDADE: ').$dados['cidadedestino'],1);
        $pdf->Cell(8,4,utf8_decode('UF: ').$dados['ufdestino'],1);
        $this->pularlinha($pdf, 1);
        $pdf->Cell(22,4,utf8_decode('CEP: ').$dados['cepremetente'],1);
        $pdf->Cell(29,4,utf8_decode('CPF/CNPJ: ').$dados['cpfcnpj'],1);
        $pdf->Cell(3,4,"",0,0,'C');
        $pdf->Cell(22,4,utf8_decode('CEP: ').$dados['cepdestino'],1);
        $pdf->Cell(29,4,utf8_decode('CPF/CNPJ: '),1);
        $this->pularlinha($pdf, 1);
        $pdf->Cell(105,4,utf8_decode('IDENTIFICAÇÃO DOS BENS'),1,1,'C');
        //$this->pularlinha($pdf, 1);
        $pdf->Cell(10,4,"ITEM",1,0,'C');
        $pdf->Cell(70,4,utf8_decode('CONTEÚDO'),1,0,'C');
        $pdf->Cell(10,4,"QTD.",1,0,'C');
        $pdf->Cell(15,4,utf8_decode('VALOR'),1, 0, 'C');
        $this->pularlinha($pdf, 1);
        $pdf->SetFont('Arial','',4);
	$pdf->Cell(10,4,"1",1,0,'C');
        $pdf->Cell(70,4,utf8_decode('AMOSTRA GRATIS'),1,0,'C');
        $pdf->Cell(10,4,"1",1,0,'C');
        $pdf->Cell(15,4,utf8_decode('0.00'),1, 0,'C');
        $this->pularlinha($pdf, 1);
        $pdf->SetFont('Arial','B',4);
	$pdf->Cell(80,4,"TOTAIS",1,0,'R');
        $pdf->Cell(10,4,"1",1,0,'C');
        $pdf->Cell(15,4,utf8_decode('0.00'),1, 0,'C');
        $this->pularlinha($pdf, 1);
        $pdf->Cell(80,4,"PESO TOTAL (Kg)",1,0,'R');
        $this->pularlinha($pdf, 1);
        $pdf->Cell(105,4,utf8_decode('DECLARAÇÃO'),1,1,'C');
        $pdf->SetFont('Arial','',4);
        //$pdf->SetY(2);
        $pdf->SetWidths(array(105));
	$pdf->Row(array(utf8_decode("                Declaro que não me enquadro no conceito de contribuinte previsto no artigo 4º ".
                                    "da Lei Complementar nº 87/1996, uma vez que não realizo, com habitualidade ou em ".
                                    "volume que caracterize ituito comercial, operações de circulação de mercadoria, ".
                                    "ainda que se iniciem no exterior, ou estou dispensado da emissão da nota fiscal por ".
                                    "força da legislação tributária vigente, responsabilizando-me, nos termos da lei e a ".
                                    "quem de direito, por informações inverídicas.".
                                    "                                 ".
                                    "Declaro ainda que não estou postando conteúdo inflamável, explosivo, ".
                                    "causador de combustão espontânea, tóxico, corrosivo, gás ou qualquer outro conteúdo que ".
                                    "constitua perigo, conforme o art. 13 da Lei Postal nº 6.538/78.".
                                    str_pad(" ", 840, " ", STR_PAD_RIGHT).
                                    "Assinatura do Declarante/Remetente")));
        //$pdf->SetFont('Arial','U',4);
        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');
        //echo strftime('%A, %d de %B de %Y', strtotime('today'));
        $pdf->Write(-8, str_pad(substr($dados['cidaderemetente'].", ".strftime('%d de %B de %Y', strtotime('today')), 0, 125), 125, " ", STR_PAD_RIGHT)."______________________________________________________");
        $pdf->Cell(1,+4,"",0,0,'C');
        $pdf->Cell(1,0,"",0,0,'C');
        $pdf->Cell(1,1,"",0,0,'C');
        
        $this->pularlinha($pdf, 1);
        $pdf->SetWidths(array(105));
	$pdf->Row(array(utf8_decode(str_pad(" ", 525, " ", STR_PAD_RIGHT).
                                    "Constitui crime contra a ordem tributária suprimir ou reduzir tributo, ou contribuição social e qualquer acessório (Lei 8.137/90 Art. 1º, V).                                  ")));
        $pdf->SetFont('Arial','B',8);
        $pdf->Write(-8, utf8_decode("OBSERVAÇÃO:"));
        $pdf->Image('image/tesoura.png',1,86,5,0,'PNG');
        $pdf->Cell(1,+4,"",0,0,'C');
        $pdf->Cell(1,0,"",0,0,'C');
        $pdf->Cell(1,1,"",0,0,'C');
        $this->pularlinha($pdf, 1);
        $pdf->Cell(5,1,"....................................................................................................................................");
        //$pdf->Row(array(utf8_decode("   Declaro ainda que não estou postando conteúdo inflamável, explosivo, causador de combustão espontânea, tóxico, corrosivo, gás ou qualquer outro conteúdo que constitua perigo, conforme o art. 13 da Lei Postal nº 6.538/78.")));
        //$this->Cell($w[$i],7,$header[$i],1,0,'C');
        //$this->Ln();
        
	// Salva diretamente no output, poderíamos mudar arqui para um nome de arquivo em um diretório ,caso não quisessemos jogar na tela
        $var_gera_pdf = Date('d/m/Yh:m:s')."_{$cont}.PDF";
        $var_gera_pdf = str_replace("/","",$var_gera_pdf);
        $var_gera_pdf = str_replace(":","",$var_gera_pdf);
        $var_gera_pdf = $diretorio."{$var_gera_pdf}";
        $pdf->Output($var_gera_pdf,'F');
	//$objWriter->save($var_gera_excel); 
        //echo "ajax_htm\nn1\nn{$tmp}";
        return $var_gera_pdf;
        
    }
    
    private function atualizadados($dados){
        foreach ($dados as $key => $value) {
            $C = array('Impressao', 'ImpressaoUser');
            $V = array(date('d/m/Y H:i:s'), $this->usuarioacesso->Codigo, $value['id']);
            $P = $C;
            $P[] = 'codigo';
            $retorno = $this->conexao->alterar("CorreioObjetos", $C, $V, " Where id = ? and Impressao is null ", $P, $this->usuarioacesso->Codigo);
            if ($retorno === false) {
                $this->msg = $this->conexao->mensagem;
                return false;
            }
        }
        return true;
    }

}
