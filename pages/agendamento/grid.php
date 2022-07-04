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
    <a href="<?php echo ($usuarioacesso->Incluir?"{$pagina}?pag={$pag}&acao=incluir{$url}":"#") ?>" <?php echo ($usuarioacesso->Incluir?"":"disabled='true'") ?> class="badge badge-primary float-left <?php echo ($usuarioacesso->Incluir?"":"disabled") ?>"><i class="fas fa-pencil-alt" aria-hidden="true"></i> Incluir</a>
    <a onclick="<?php echo ($usuarioacesso->Gerar?"Gera_Excel()":"") ?>" <?php echo ($usuarioacesso->Gerar?"":"disabled='true'") ?> class="badge badge-success float-left <?php echo ($usuarioacesso->Gerar?"":"disabled") ?>"><i class="fas fa-file-excel " aria-hidden="true"></i> Excel</a>
    <span class="float-right" id="vcliente">Total: <?php echo $Total ?></span>
</div>
<div  class="table-responsive class-grid" id="listacliente" >
    <table border="0" width="1500" class="table-bordered">
        <caption>Total: <?php echo $Total ?></caption>
        <thead>
            <tr>
                <th scope="col" class='text-center th-grid-btn'></th>	 
                <th scope="col" class='text-left th-grid'>STATUS</th>
                <th scope="col" class='text-left th-grid <?php echo ($usuarioacesso->Agencia==0?"":"d-none")?>'>AGÊNCIA</th>
                <th scope="col" class='text-left th-grid'>CLIENTE</th>
                <th scope="col" class="text-center th-grid">DATA</th>
                <th scope="col" class="text-left th-grid">MOTORISTA</th>
                <th scope="col" class="text-center th-grid">DATA HORA COLETA</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $contzebra = 0;
            if ($sucesso===true) {
                foreach ($controller->getAgendamento() as $key => $value) {
                    if($contzebra % 2 == 0){
                        $color = " bgcolor=#CED8F6 ";}
                    else{
                        $color = " bgcolor=#FFFFFF ";}
                    ?>		
                    <tr id='<?php echo crypto::encrypt($value->getId()) ?>'> 
                        <td class='text-center td-grid' <?php echo $color ?>>
                            <?php if ($usuarioacesso->Alterar){ ?>
                                <a class="btn-link float-right" data-toggle="tooltip" 
                                    title="<?php echo ($usuarioacesso->Consultar?"Alterar":"Bloqueado") ?>"
                                   href="<?php echo "{$pagina}?pag={$pag}&codigo=".crypto::encrypt($value->getId())."&acao=incluir{$url}"; ?>"><i class="fas fa-edit"></i></a>
                            <?php }else{ ?>
                                <a class="btn-link float-right <?php echo ($usuarioacesso->Consultar?"":"disabled") ?>" 
                                   data-toggle="tooltip" title="<?php echo ($usuarioacesso->Consultar?"Consultar":"Bloqueado") ?>"
                                    <?php echo ($usuarioacesso->Consultar?"":"disabled='true'") ?> href="<?php echo ($usuarioacesso->Consultar?"{$pagina}?pag={$pag}&codigo=".crypto::encrypt($value->getId())."&acao=incluir{$url}":"") ?>"><i class="fas fa-search"></i></a>
                            
                            <?php } ?>
                            
                            <a onclick="ConfirmaExcluir('<?php echo $pagina ?>', '<?php echo crypto::encrypt($value->getId()) ?>', 'Deseja excluir o cliente '+'<?php echo $value->getCliente() ?>'+'?')" 
                               class="btn-link float-left <?php echo ($usuarioacesso->Excluir?"":"disabled") ?>" 
                                data-toggle="tooltip" title="<?php echo ($usuarioacesso->Consultar?"Excluir":"Bloqueado") ?>"
                                <?php echo ($usuarioacesso->Excluir?"":"disabled='true'") ?>><i class="fas fa-trash-alt"></i></a>
                        
                        </td>  									   
                        <td class='text-left td-grid' <?php echo $color ?>><span class="badge <?php echo (($value->getStatus()==="FINALIZADO")?"badge-success":(($value->getStatus()==="PENDENTE")?"badge-warning":"badge-danger")) ?>"><?php echo $value->getStatus() ?></span></td>  									   
                        <td class='text-letf td-grid <?php echo ($usuarioacesso->Agencia==0?"":"d-none")?>' <?php echo $color ?>><?php echo $value->getAgencia() ?></td> 
                        <td class='text-letf td-grid' <?php echo $color ?>><?php echo $value->getCliente() ?></td> 
                        <td class='text-center td-grid' <?php echo $color ?>><?php echo $value->getData() ?></td> 
                        <td class='text-left td-grid' <?php echo $color ?>><?php echo $value->getUsuario() ?></td>
                        <td class='text-center td-grid' <?php echo $color ?>><?php echo $value->getFinalizacao() ?></td>
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