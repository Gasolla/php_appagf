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
    <a onclick="<?php echo ($usuarioacesso->Gerar ? "Gera_Excel()" : "") ?>" <?php echo ($usuarioacesso->Gerar ? "" : "disabled='true'") ?> class="badge badge-success float-left <?php echo ($usuarioacesso->Gerar ? "" : "disabled") ?>"><i class="fas fa-file-excel" aria-hidden="true"></i> Excel</a>
    <a onclick="<?php echo ($usuarioacesso->Imprimir ? "Imprimir(formulario)" : "") ?>" <?php echo ($usuarioacesso->Imprimir ? "" : "disabled='true'") ?> class="badge badge-danger float-left <?php echo ($usuarioacesso->Imprimir ? "" : "disabled") ?>"><i class="fas fa-file-pdf" aria-hidden="true"></i> Imprimir Etiqueta</a>
    <a onclick="<?php echo ($usuarioacesso->Solicitar ? "Solicitar(formulario)" : "") ?>" <?php echo ($usuarioacesso->Solicitar ? "" : "disabled='true'") ?> class="badge badge-info float-left <?php echo ($usuarioacesso->Solicitar ? "" : "disabled") ?>"><i class="fas fa-truck" aria-hidden="true"></i> Solicitar Coleta</a>
    <span class="float-right" id="vcliente">Total: <?php echo $Total ?></span>
</div>
<div  class="table-responsive class-grid" id="listacliente" >
    <table border="0" width="2500" class="table-bordered">
        <caption>Total: <?php echo $Total ?></caption>
        <thead>
            <tr>
                <th scope="col" class='text-center th-grid-btn'></th>	
                <th  scope="col" class='text-center th-grid'>
                    <input  type="checkbox" value="imprimir" name="imprimir" onclick="return clickCheck(this.form, this)"
                            data-placement="bottom" title="Selecionar todos para impressão" data-toggle="tooltip" >
                </th>
                <th  scope="col" class='text-center th-grid'>
                    <input  type="checkbox" value="agendar" name="agendar" onclick="return clickCheck(this.form, this)"
                            data-placement="bottom" title="Selecionar todos para solicitar coleta" data-toggle="tooltip" >
                </th>

                <th scope="col" class='text-left th-grid <?php echo ($usuarioacesso->Agencia == 0 ? "" : "d-none") ?>'>AGÊNCIA</th>	 
                <th scope="col" class='text-left th-grid <?php echo ($usuarioacesso->Agencia == 0 ? "" : "d-none") ?>'>CLIENTE</th>	 
                <th scope="col" class='text-left th-grid'>NOME DESTINATÁRIO</th>	   				
                <th scope="col" class='text-left th-grid'>STATUS</th>
                <th scope="col" class="text-center th-grid">OBJETO</th>
                <th scope="col" class="text-center th-grid">PESO</th>
                <th scope="col" class="text-center th-grid">VALOR</th>
                <th scope="col" class="text-center th-grid">DATA REGISTRO</th>
                <th scope="col" class="text-center th-grid">DATA COLETA</th>
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
                foreach ($controller->getApiweb() as $key => $value) {
                    if ($contzebra % 2 == 0) {
                        $color = " bgcolor=#CED8F6 ";
                    } else {
                        $color = " bgcolor=#FFFFFF ";
                    }
                    ?>		
                    <tr id='<?php echo crypto::encrypt($value->getId()) ?>'> 
                        <input type="hidden" name="impresso[]" value="<?php echo $value->getImpressao() ?>">
                        <td class='text-center td-grid' <?php echo $color ?>>
                            <?php if ($usuarioacesso->Alterar){ ?>
                                <a class="btn-link float-left" data-toggle="tooltip" title="Alterar"
                                   href="<?php echo "{$pagina}?pag={$pag}&codigo=".crypto::encrypt($value->getId())."&acao=incluir{$url}"; ?>"><i class="fas fa-edit"></i></a>
                            <?php }else{ ?>
                                <a class="btn-link float-left disabled" 
                                   data-toggle="tooltip" title="Bloqueado" disabled='true' /> <i class="fas fa-edit">
                                    </i></a>
                            
                            <?php } ?>
                            <a class="btn-link float-right" data-toggle="tooltip" title="Mais Detalhes" data-placement="top"
                               onclick="visualizar('<?php echo crypto::encrypt($value->getId()) ?>', '<?php echo $pagina ?>')">
                                <i class="fas fa-plus"></i>
                            </a>
                            
                        </td>  
                        <td class='text-center td-grid' <?php echo $color ?>>
                            <div class="form-check td-ckeck">
                                <input class="form-check-input" 
                                       type="checkbox" value="<?php echo crypto::encrypt($value->getId()) ?>" 
                                       data-placement="bottom" title="Selecionar para impressão" data-toggle="tooltip"
                                       name="imprimir[]">
                            </div>   
                        </td>  
                        <td class='text-center td-grid' <?php echo $color ?>>
                            <div id="divsolicitar<?php echo crypto::encrypt($value->getId()) ?>" class="form-check td-ckeck <?php echo ($value->getStatusagendamento() ? "disabled" : "") ?>">
                                <input class="form-check-input " id="checksolicitar<?php echo crypto::encrypt($value->getId()) ?>"
                                       type="checkbox" value="<?php echo crypto::encrypt($value->getId()) ?>" 
                                       data-placement="bottom" title="Selecionar para solicitar coleta" data-toggle="tooltip"
                                       <?php echo ($value->getStatusagendamento() ? "disabled='true'" : "") ?>
                                       name="agendar[]">
                            </div>
                        </td>  
                        <td class='text-left td-grid <?php echo ($usuarioacesso->Agencia == 0 ? "" : "d-none") ?>' <?php echo $color ?>><?php echo $value->getAgencia() ?></td>  									   
                        <td class='text-left td-grid <?php echo ($usuarioacesso->Agencia == 0 ? "" : "d-none") ?>' <?php echo $color ?>><?php echo $value->getCliente() ?></td>  									   
                        <td class='text-left td-grid' <?php echo $color ?>><?php echo $value->getNomedestino() ?></td>  									   
                        <td class='text-left td-grid' <?php echo $color ?>><?php echo $value->getStatus() ?></td> 
                        <td class='text-center td-grid' <?php echo $color ?>><?php echo $value->getObjeto() ?></td> 
                        <td class='text-center td-grid' <?php echo $color ?>><?php echo $value->getPeso() ?></td> 
                        <td class='text-center td-grid' <?php echo $color ?>><?php echo $value->getValor() ?></td> 
                        <td class='text-center td-grid' <?php echo $color ?>><?php echo $value->getDatahora() ?></td>
                        <td class='text-center td-grid' <?php echo $color ?>><?php echo $value->getDatacoleta() ?></td>
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
                    <td colspan="<?php echo ($usuarioacesso->Agencia == 0 ? "17" : "15") ?>" class="background-white ">
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