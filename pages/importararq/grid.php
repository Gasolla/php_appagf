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
    <a onclick="<?php echo ($usuarioacesso->Gerar?"Gera_Excel()":"") ?>" <?php echo ($usuarioacesso->Gerar?"":"disabled='true'") ?> class="badge badge-success float-left <?php echo ($usuarioacesso->Gerar?"":"disabled") ?>"><i class="fas fa-file-excel" aria-hidden="true"></i> Excel</a>
    <span class="float-right" id="vcliente">Total: <?php echo $Total ?></span>
</div>
<div  class="table-responsive class-grid" id="listacliente" >
    <table border="0" width="100%" class="table-bordered">
        <caption>Total: <?php echo $Total ?></caption>
        <thead>
            <tr>
                <th scope="col" class='text-center th-grid-btn'></th>	
                <th scope="col" class='text-left th-grid'>DATA</th>
                <th scope="col" class="text-center th-grid">QTDE</th>
                <th scope="col" class="text-center th-grid">USUÁRIO</th>
                <th scope="col" class="text-center th-grid">ARQUIVO</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $contzebra = 0;
            if ($sucesso===true) {
                foreach ($controller->getImportararq() as $key => $value) {
                    if($contzebra % 2 == 0){
                        $color = " bgcolor=#CED8F6 ";}
                    else{
                        $color = " bgcolor=#FFFFFF ";}
                    ?>		
                    <tr id='<?php echo crypto::encrypt($value->getId()) ?>'> 
                        <td class='text-center td-grid' <?php echo $color ?>>
                             <a class="btn btn-link" data-toggle="tooltip" title="visualizar arquivo" data-placement="top"
                                target="_blank" href="exceldonwloald?arquivo=<?php echo crypto::encrypt($value->getArquivo()) ?>">
                                <i class="fas fa-file-excel" style="color: green"></i>
                            </a>
                        </td>    
                        <td class='text-center td-grid' <?php echo $color ?>><?php echo $value->getData() ?></td> 
                        <td class='text-center td-grid' <?php echo $color ?>><?php echo $value->getQtde() ?></td>
                        <td class='text-center td-grid' <?php echo $color ?>><?php echo $value->getUsuario() ?></td>
                        <td class='text-center td-grid' <?php echo $color ?>> 
                            <a class="btn btn-link" data-toggle="tooltip" title="visualizar arquivo" data-placement="top"
                               target="_blank" href="exceldonwloald?arquivo=<?php echo crypto::encrypt($value->getArquivo()) ?>">
                                 <?php echo $value->getArquivo() ?>
                            </a>
                        </td>
                    </tr>
                    <?php
                    $contzebra++;
                }
            } else {
                ?>
                <tr>
                    <td colspan="5" class="background-white ">
                        <span><?php echo $controller->msg ?></span>
                    </td>
                </tr>           

                <?php
            }
            ?>       
        </tbody>
    </table>
</div>
<?php include 'template/paginador.php'; ?>