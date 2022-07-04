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
    <table border="0" width="2500" class="table-bordered">
        <caption>Total: <?php echo $Total ?></caption>
        <thead>
            <tr>
                <th scope="col" class='text-center th-grid-btn'></th>	 
                <th scope="col" class='text-center th-grid-btn'>PENDÊNCIA</th>	 
                <th scope="col" class='text-center th-grid-btn'>TIPO PENDÊNCIA</th>	 
                <th scope="col" class='text-left th-grid <?php echo ($usuarioacesso->Agencia==0?"":"d-none")?>'>AGÊNCIA</th>	 
                <th scope="col" class='text-left th-grid'>COMERCIAL</th>
                <th scope="col" class='text-left th-grid'>NOME</th>	   				
                <th scope="col" class="text-center th-grid">TELEFONE</th>
                <th scope="col" class="text-left th-grid">EMAIL</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $contzebra = 0;
            if ($sucesso===true) {
                foreach ($controller->getProspeccao() as $key => $value) {
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
                                    <?php echo ($usuarioacesso->Consultar?"":"disabled='true'") ?>
                                   data-toggle="tooltip" title="<?php echo ($usuarioacesso->Consultar?"Consultar":"Bloqueado") ?>"
                                   href="<?php echo ($usuarioacesso->Consultar?"{$pagina}?pag={$pag}&codigo=".crypto::encrypt($value->getId())."&acao=incluir{$url}":"") ?>"><i class="fas fa-search"></i></a>
                            
                            <?php } ?>
                            
                            <a onclick="ConfirmaExcluir('<?php echo $pagina ?>', '<?php echo crypto::encrypt($value->getId()) ?>', 'Deseja excluir o cliente '+'<?php echo $value->getNome() ?>'+'?')" 
                               class="btn-link float-left <?php echo ($usuarioacesso->Excluir?"":"disabled") ?>" 
                                   data-toggle="tooltip"
                                   title="<?php echo ($usuarioacesso->Consultar?"Excluir":"Bloqueado") ?>"
                                   <?php echo ($usuarioacesso->Excluir?"disabled='true'":"disabled='true'") ?>><i class="fas fa-trash-alt"></i></a>
                        
                        </td>  									   
                        <td class='text-center td-grid' <?php echo $color ?>><span class="badge <?php echo (($value->getPendencia()==="NAO")?"badge-success":"badge-danger") ?>"><?php echo $value->getPendencia() ?></span></td>  									   
                        <td class='text-center td-grid' <?php echo $color ?>><span class="badge <?php echo (($value->getTipopendencia()==="Sem Pendencia")?"badge-success":"badge-danger") ?>"><?php echo $value->getTipopendencia() ?></span></td>  									   
                        <td class='text-left td-grid <?php echo ($usuarioacesso->Agencia==0?"":"d-none")?>' <?php echo $color ?>><?php echo $value->getAgencia() ?></td>  									   
                        <td class='text-left td-grid' <?php echo $color ?>><?php echo $value->getUsuario() ?></td>
                        <td class='text-left td-grid' <?php echo $color ?>><?php echo $value->getNome() ?></td>  									   
                        <td class='text-center td-grid' <?php echo $color ?>><?php echo $value->getFone() ?></td> 
                        <td class='text-left td-grid' <?php echo $color ?>><?php echo $value->getEmail() ?></td>
                    </tr>
                    <?php
                    $contzebra++;
                }
            } else {
                ?>
                <tr>
                    <td colspan="<?php echo ($usuarioacesso->Agencia==0?"8":"7")?>" class="background-white ">
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