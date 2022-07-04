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
    <a onclick="<?php echo ($usuarioacesso->Gerar?"Gera_Excel()":"") ?>" <?php echo ($usuarioacesso->Gerar?"":"disabled='true'") ?> class="badge badge-success float-left <?php echo ($usuarioacesso->Gerar?"":"disabled") ?>"><i class="fas fa-file-excel " aria-hidden="true"></i> Excel</a>
    <span class="float-right" id="vusuario">Total: <?php echo $Total ?></span>
</div>
<div  class="table-responsive class-grid" id="listacliente" >
    <table border="0" width="1500" class="table-bordered">
        <caption>Total: <?php echo $Total ?></caption>
        <thead>
            <tr>
                <th scope="col" class='text-left th-grid <?php echo ($usuarioacesso->Agencia==0?"":"d-none")?>'>AGÊNCIA</th>	 
                <th scope="col" class='text-left th-grid'>CLIENTE</th>	   				
                <th scope="col" class='text-left th-grid'>SUPRIMENTO</th>
                <th scope="col" class="text-center th-grid">SIGLA</th>
                <th scope="col" class="text-center th-grid">DISPONIBILIZADO</th>
                <th scope="col" class="text-center th-grid">UTILIZADO</th>
                <th scope="col" class="text-center th-grid">DISPONÍVEL</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $contzebra = 0;
            if ($sucesso===true) {
                foreach ($controller->getSuprimentocliente() as $key => $value) {
                    if($contzebra % 2 == 0){
                        $color = " bgcolor=#CED8F6 ";}
                    else{
                        $color = " bgcolor=#FFFFFF ";}
                    ?>		
                    <tr > 
                        <td class='text-left td-grid <?php echo ($usuarioacesso->Agencia==0?"":"d-none")?>' <?php echo $color ?>><?php echo $value->getAgencia() ?></td>  									   
                        <td class='text-left td-grid' <?php echo $color ?>><?php echo $value->getCliente() ?></td>  									   
                        <td class='text-left td-grid' <?php echo $color ?>><?php echo $value->getSuprimento() ?></td> 
                        <td class='text-center td-grid' <?php echo $color ?>><?php echo $value->getSigla() ?></td>
                        <td class='text-center td-grid' <?php echo $color ?>><?php echo $value->getDisponibilizado() ?></td>
                        <td class='text-center td-grid' <?php echo $color ?>><?php echo $value->getUtilizado() ?></td>
                        <td class='text-center td-grid' <?php echo $color ?>><?php echo $value->getDisponivel() ?></td>
                    </tr>
                    <?php
                    $contzebra++;
                }
            } else {
                ?>
                <tr>
                    <td colspan="<?php echo ($usuarioacesso->Agencia==0?"7":"6")?>" class="background-white ">
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
