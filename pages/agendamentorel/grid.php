<?php
$sucesso = $controller->lista();
$Total = $controller->count;
$inteiro = (int) ($controller->count / 100);
$resto = $controller->count % 100;
$page = $inteiro + ($resto > 0 ? 1 : 0);
if ($_REQUEST['pag'] > $page) {
    $_REQUEST['pag'] = 1;
}
?>
<input type="hidden" id="filtro" name="filtro" value="<?php echo $controller->descricaofiltro ?>">
<div class="text-center cabecalho">
    <a onclick="<?php echo ($usuarioacesso->Gerar ? "Gera_Excel()" : "") ?>" <?php echo ($usuarioacesso->Gerar ? "" : "disabled='true'") ?> class="badge badge-success float-left <?php echo ($usuarioacesso->Gerar ? "" : "disabled") ?>"><i class="fas fa-file-excel " aria-hidden="true"></i> Excel</a>
    <span class="float-right" id="vusuario">Total: <?php echo $Total ?></span>
</div>
<div  class="table-responsive class-grid" id="listacliente" >
    <table border="0" width="4000" class="table-bordered">
        <caption>Total: <?php echo $Total ?></caption>
        <thead>
            <tr>
                <th scope="col" class='text-center th-grid'>STATUS</th>	   				
                <th scope="col" class='text-left th-grid <?php echo ($usuarioacesso->Agencia==0?"":"d-none")?>'>AGÊNCIA</th>	 
                <th scope="col" class='text-left th-grid'>CLIENTE</th>	   				
                <th scope="col" class='text-center th-grid'>DATA INPUT SISTEMA</th>
                <th scope="col" class='text-center th-grid'>DATA SER REALIZADA</th>
                <th scope="col" class='text-center th-grid'>DATA INICIO ROTA</th>
                <th scope="col" class='text-center th-grid'>DATA EFETUADA COLETA</th>
                <th scope="col" class='text-center th-grid'>STATUS COLETA</th>
                <th scope="col" class='text-center th-grid'>QTDE OBJETOS</th>
                <th scope="col" class='text-center th-grid'>TIPO OBJETOS</th>
                <th scope="col" class='text-center th-grid'>VALOR</th>
                <th scope="col" class='text-left th-grid'>COMERCIAL</th>
                <th scope="col" class='text-left th-grid'>MOTORISTA</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $contzebra = 0;
            if ($sucesso === true) {
                foreach ($controller->getAgendamentorel() as $key => $value) {
                    if ($contzebra % 2 == 0) {
                        $color = " bgcolor=#CED8F6 ";
                    } else {
                        $color = " bgcolor=#FFFFFF ";
                    }
                    ?>		
                    <tr>   									   
                        <td class='text-center td-grid' <?php echo $color ?>><span class="badge <?php echo (($value->getStatus()==="FINALIZADO")?"badge-success":(($value->getStatus()==="PENDENTE")?"badge-warning":"badge-danger")) ?>"><?php echo $value->getStatus() ?></span></td>  									   
                        <td class='text-left td-grid <?php echo ($usuarioacesso->Agencia==0?"":"d-none")?>' <?php echo $color ?>><?php echo $value->getAgencia() ?></td>  									   
                        <td class='text-left td-grid' <?php echo $color ?>><?php echo $value->getCliente() ?></td>  									   
                        <td class='text-center td-grid' <?php echo $color ?>><?php echo $value->getDatainput() ?></td> 
                        <td class='text-center td-grid' <?php echo $color ?>><?php echo $value->getDatarealizar() ?></td> 
                        <td class='text-center td-grid' <?php echo $color ?>><?php echo $value->getDatarotainicio() ?></td> 
                        <td class='text-center td-grid' <?php echo $color ?>><?php echo $value->getDatacoleta() ?></td> 
                        <td class='text-center td-grid' <?php echo $color ?>><span class="badge <?php echo (($value->getStatuscoleta()==="COLETADO")?"badge-success":(($value->getStatuscoleta()==="A COLETAR")?"badge-warning":(($value->getStatuscoleta()==="NA ROTA")?"badge-primary":(($value->getStatuscoleta()==="SEM REMESSA")?"badge-dark":"badge-danger")))) ?>"><?php echo $value->getStatuscoleta() ?></span></td>  									   
                        <td class='text-center td-grid' <?php echo $color ?>><?php echo $value->getQtde() ?></td> 
                        <td class='text-center td-grid' <?php echo $color ?>><?php echo $value->getTipo() ?></td> 
                        <td class='text-center td-grid' <?php echo $color ?>><?php echo $value->getValor() ?></td> 
                        <td class='text-center td-grid' <?php echo $color ?>><?php echo $value->getComercial() ?></td>
                        <td class='text-left td-grid' <?php echo $color ?>><?php echo $value->getMotorista() ?></td>  									   
                    </tr>
                    <?php
                    $contzebra++;
                }
            } else {
                ?>
                <tr>
                    <td colspan="<?php echo ($usuarioacesso->Agencia==0?"13":"12")?>" class="background-white ">
                        <span><?php echo $controller->msg ?></span>
                    </td>
                </tr>           

                <?php
            }
            ?>       
        </tbody>
    </table>
</div>
<div id="janelaModal" class="modalVisual">
      <span class="fechar">x</span>
      <img class="modalConteudo" id="imgModal">
      <div id="txtImg"></div>
</div>
<?php include 'template/paginador.php'; ?>
