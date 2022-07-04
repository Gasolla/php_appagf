<?php
$codigo = $acao = (filter_input(INPUT_GET, 'codigo') ?: 0);
$sucesso = $controller->index($codigo);
if ($sucesso === false) {
    exit(header("location:{$pagina}?pag={$pag}&acao=index{$url}"));
}

if (((!$usuarioacesso->Incluir) && (!$usuarioacesso->Alterar) && (!$usuarioacesso->Consultar))) {
    exit(header("location:{$pagina}?pag={$pag}&acao=index{$url}"));
}
$salvar = (($usuarioacesso->Incluir && (crypto::decrypt($codigo) === false)) || ($usuarioacesso->Alterar && (crypto::decrypt($codigo) > 0)));
?>
<div class="row" >
    <div class="col-md-12 col-sm-12">
        <h3  class="h3-1 float-left">Incluir Cliente</h3>
        <a href="<?php echo "{$pagina}?pag={$pag}&acao=index{$url}"; ?>" class="btn-link float-right link-voltar"><i class="fas fa-chevron-left"></i> Voltar</a>
    </div>
    <div class="col-md-12 col-sm-12">
        <hr>
    </div>
</div>
<form class="form-group" name="formulario" id="formulario" 
      action="app" method="POST" onsubmit="return Gravar(this)">
    <input type="hidden" name="acao" id="acao" value="<?php echo ((crypto::decrypt($codigo) > 0) ? "alterar" : "incluir") ?>"/>
    <input type="hidden" name="codigo" id="codigo" value="<?php echo $codigo ?>"/>
    <input type="hidden" name="class" id="class" value="<?php echo $pagina ?>"/>
    <input type="hidden" name="url" id="url" value="<?php echo "{$pagina}?pag={$pag}{$url}"; ?>"/> 
    <div class="panel-body">
        <div class="form-row">
            <?php $controller->addAgencia(); ?>
            <div class="form-group col-xs-6  col-sm-6 col-md-6  col-lg-6 <?php echo ($usuarioacesso->Agencia === 0 ? "" : "d-none") ?>" id="form-agencia"> 
                <div class="form-row <?php echo ($usuarioacesso->Agencia === 0 ? "d-flex" : "d-none") ?>">
                    <label for="agencia">Agência</label>
                    <select name="agencia" id="agencia" rel="select2" class="form-control"
                            onchange='onChangeAgencia("<?php echo "{$pagina}?pag={$pag}"; ?>&acao=incluir<?php echo (isset($_REQUEST['codigo']) ? "&codigo={$_REQUEST['codigo']}" : "") ?><?php echo $url ?>", this)'>
                        <option value="">Selecione a agência</option>
                        <?php foreach ($controller->getAgencia() as $value) { ?>
                            <option value="<?php echo crypto::encrypt($value->getId()) ?>" 
                                    <?php echo ($controller->getEstoquecliente()->getAgencia() == $value->getId() ? "selected='true'" : "") ?>><?php echo $value->getNome() ?></option>
                                <?php } ?>
                    </select>
                </div>
            </div>
            <?php $controller->addCliente(true); ?>
            <div class="form-group col-xs-6  col-sm-6 col-md-6  col-lg-6" id="form-cliente">  
                <label for="cliente">Cliente</label>
                <select name="cliente" id="cliente" rel="select2" class="form-control">
                    <option value="">Selecione o cliente</option>
                    <?php foreach ($controller->getCliente() as $value) { ?>
                        <option value="<?php echo crypto::encrypt($value->getId()) ?>" 
                                <?php echo ($controller->getEstoquecliente()->getCliente() == $value->getId() ? "selected='true'" : "") ?>><?php echo $value->getNome() ?></option>
                            <?php } ?>
                </select>
            </div>
        </div>
        <div class="form-row">
           
            <div class="form-group col-xs-12  col-sm-12 col-md-6  col-lg-6" id="form-suprimento">  
               <?php
                $SQL = "SELECT id, nome FROM Suprimentos "
                        . "Where (inativo = 'F') "
                        .(((crypto::decrypt($codigo) > 0) ? " or id = ".$controller->getEstoquecliente()->getSuprimento() : "")) 
                        . " Order by nome ";
                $retorno = $conexao->consultar($SQL, array(), array(), $usuarioacesso->Codigo, false);
                ?>
                <label for="suprimento">Suprimento</label>
                <select class="form-control" id="suprimento" name="suprimento" rel="select2">
                    <option value="">selecione o suprimento</option>
                    <?php
                    if ((is_array($retorno)) && (count($retorno) > 0)) {
                        foreach ($retorno as $value) {
                            ?>
                            <option value="<?php echo crypto::encrypt($value['id']) ?>" 
                                    <?php echo ((($controller->getEstoquecliente()->getSuprimento() == $value['id'])) ? "selected='true'" : "") ?> ><?php echo utf8_encode($value['nome']) ?></option> 
                                    <?php
                                }
                            }
                            ?> 
                </select>
            </div>     
            <div class="form-group col-xs-6  col-sm-6 col-md-4  col-lg-4" id="form-qtde">  
                <label for="qtde">Quantidade</label>
                <input  type="text" class="form-control" id="qtde"  placeholder="digite a quantidade"  maxlength="10" onkeypress="return OnlyNumber(event)"
                        name="qtde"  value="<?php echo $controller->getEstoquecliente()->getQtde() ?>"/>
            </div>   
        </div>
        <div class="form-row ">
            <div class="form-group col-sm-12 col-md-12 text-center"> 
                <button class="btn btn-success <?php echo ($salvar ? "" : "disabled") ?>" <?php echo ($salvar ? "" : "disabled") ?> id="salvar" rel="tooltip" data-placement="top" type="submit" title="Salvar">Salvar</button>
                <a class="btn btn-danger" rel="tooltip" data-placement="top"  title="Voltar" href="<?php echo "{$pagina}?pag={$pag}&acao=index{$url}"; ?>">Voltar</a>
            </div>
        </div>
    </div>
    <hr class="margim-footer">
</form>
