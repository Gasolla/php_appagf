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
    <table border="0" width="3000" class="table-bordered">
        <caption>Total: <?php echo $Total ?></caption>
        <thead>
            <tr>
                <th scope="col" class='text-left th-grid'>PENDÊNCIA</th>	   				
                <th scope="col" class='text-left th-grid <?php echo ($usuarioacesso->Agencia==0?"":"d-none")?>'>AGÊNCIA</th>	 
                <th scope="col" class='text-left th-grid'>NOME</th>	   				
                <th scope="col" class='text-letf th-grid'>CONTATO</th>
                <th scope="col" class='text-left th-grid'>RAMO ATIVIDADE</th>
                <th scope="col" class='text-left th-grid'>VOLUME MÉDIO</th>
                <th scope="col" class='text-left th-grid'>EMAIL CONTATO</th>
                <th scope="col" class='text-center th-grid'>TELEFONE CONTATO</th>
                <th scope="col" class='text-left th-grid'>COMERCIAL</th>
                <th scope="col" class='text-left th-grid'>USUÁRIO CADASTRO</th>
                <th scope="col" class='text-left th-grid'>SEGUIMENTO</th>
                <th scope="col" class='text-center th-grid'>DATA DO CONTATO</th>
                <th scope="col" class='text-center th-grid'>DATA DO PRÓXIMO CONTATO</th>
                <th scope="col" class='text-left th-grid'>STATUS</th>
                <th scope="col" class='text-left th-grid'>STATUS NÃO FECHADO</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $contzebra = 0;
            if ($sucesso === true) {
                foreach ($controller->getProspeccaorel() as $key => $value) {
                    if ($contzebra % 2 == 0) {
                        $color = " bgcolor=#CED8F6 ";
                    } else {
                        $color = " bgcolor=#FFFFFF ";
                    }
                    ?>		
                    <tr>   									   
                        <td class='text-center td-grid' <?php echo $color ?>><span class="badge <?php echo (($value->getPendencia()==="NAO")?"badge-success":"badge-danger") ?>"><?php echo $value->getPendencia() ?></span></td>  									   
                        <td class='text-left td-grid <?php echo ($usuarioacesso->Agencia==0?"":"d-none")?>' <?php echo $color ?>><?php echo $value->getAgencia() ?></td>  									   
                        <td class='text-left td-grid' <?php echo $color ?>><?php echo $value->getNome() ?></td>  									   
                        <td class='text-left td-grid' <?php echo $color ?>><?php echo $value->getContato() ?></td>  									   
                        <td class='text-left td-grid' <?php echo $color ?>><?php echo $value->getRamo() ?></td> 
                        <td class='text-left td-grid' <?php echo $color ?>><?php echo $value->getVolume() ?></td> 
                        <td class='text-left td-grid' <?php echo $color ?>><?php echo $value->getEmail() ?></td> 
                        <td class='text-center td-grid' <?php echo $color ?>><?php echo $value->getFone() ?></td> 
                        <td class='text-left td-grid' <?php echo $color ?>><?php echo $value->getComercial() ?></td> 
                        <td class='text-left td-grid' <?php echo $color ?>><?php echo $value->getCadastro() ?></td> 
                        <td class='text-left td-grid' <?php echo $color ?>><?php echo $value->getSeguimento() ?></td>
                        <td class='text-center td-grid' <?php echo $color ?>><?php echo $value->getDatacontato() ?></td>
                        <td class='text-center td-grid' <?php echo $color ?>><?php echo $value->getDatanovo() ?></td>
                        <td class='text-left td-grid' <?php echo $color ?>><?php echo $value->getOcorrencia() ?></td>
                        <td class='text-left td-grid' <?php echo $color ?>><?php echo $value->getNaofechado() ?></td>
                    </tr>
                    <?php
                    $contzebra++;
                }
            } else {
                ?>
                <tr>
                    <td colspan="<?php echo ($usuarioacesso->Agencia==0?"14":"14")?>" class="background-white ">
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
