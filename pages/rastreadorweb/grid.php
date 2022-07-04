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
    <a onclick="<?php echo ($usuarioacesso->Gerar ? "Gera_Excel()" : "") ?>" <?php echo ($usuarioacesso->Gerar ? "" : "disabled='true'") ?> class="badge badge-success float-left <?php echo ($usuarioacesso->Gerar ? "" : "disabled") ?>"><i class="fas fa-file-excel" aria-hidden="true"></i> Excel</a>
    <span class="float-right" id="vcliente">Total: <?php echo $Total ?></span>
</div>
<div  class="table-responsive class-grid" id="listacliente" >
    <table border="0" width="2500" class="table-bordered">
        <caption>Total: <?php echo $Total ?></caption>
        <thead>
            <tr>
                <th scope="col" class='text-center th-grid-btn'></th>	
                <th scope="col" class='text-left th-grid <?php echo ($usuarioacesso->Agencia == 0 ? "" : "d-none") ?>'>AGÊNCIA</th>	 
                <th scope="col" class='text-left th-grid <?php echo ($usuarioacesso->Agencia == 0 ? "" : "d-none") ?>'>CLIENTE</th>	 
                <th scope="col" class='text-left th-grid'>NOME DESTINATÁRIO</th>	   				
                <th scope="col" class="text-center th-grid">OBJETO</th>
                <th scope="col" class="text-center th-grid">DATA REGISTRO</th>
                <th scope="col" class="text-center th-grid">DATA POSTAGEM</th>
                <th scope="col" class="text-center th-grid">DATA ENTREGA</th>
                <th scope="col" class="text-center th-grid">STATUS SRO</th>
                <th scope="col" class="text-center th-grid">DESCRIÇÃO SRO</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $contzebra = 0;
            if ($sucesso === true) {
                foreach ($controller->getRastreadorweb() as $key => $value) {
                    if ($contzebra % 2 == 0) {
                        $color = " bgcolor=#CED8F6 ";
                    } else {
                        $color = " bgcolor=#FFFFFF ";
                    }
                    ?>		
                    <tr id='<?php echo crypto::encrypt($value->getId()) ?>'> 
                        <td class='text-center td-grid' <?php echo $color ?>>
                            <a class="btn-link" data-toggle="tooltip" title="Mais Detalhes" data-placement="top"
                               onclick="visualizar('<?php echo crypto::encrypt($value->getId()) ?>', '<?php echo $pagina ?>')">
                                <i class="fas fa-plus"></i>
                            </a> 
                        </td>  
                        <td class='text-left td-grid <?php echo ($usuarioacesso->Agencia == 0 ? "" : "d-none") ?>' <?php echo $color ?>><?php echo $value->getAgencia() ?></td>  									   
                        <td class='text-left td-grid <?php echo ($usuarioacesso->Agencia == 0 ? "" : "d-none") ?>' <?php echo $color ?>><?php echo $value->getCliente() ?></td>  									   
                        <td class='text-left td-grid' <?php echo $color ?>><?php echo $value->getNomedestino() ?></td>  									   
                        <td class='text-center td-grid' <?php echo $color ?>><?php echo $value->getObjeto() ?></td> 
                        <td class='text-center td-grid' <?php echo $color ?>><?php echo $value->getDatahora() ?></td>
                        <td class='text-center td-grid' <?php echo $color ?>><?php echo $value->getDatapostagem() ?></td>
                        <td class='text-center td-grid' <?php echo $color ?>><?php echo $value->getDataentrega() ?></td>
                        <td class='text-center td-grid' <?php echo $color ?>><?php echo $value->getStatussro() ?></td>
                        <td class='text-center td-grid' <?php echo $color ?>><?php echo $value->getDescricao() ?></td>
                    </tr>
                    <?php
                    $contzebra++;
                }
            } else {
                ?>
                <tr>
                    <td colspan="<?php echo ($usuarioacesso->Agencia == 0 ? "11" : "9") ?>" class="background-white ">
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