
<div class="row" >
    <div class="col-md-12 col-sm-12">
        <h3  class="h3-1">Cadastro Clientes</h3>
        <hr>
    </div>
</div>
<form class="form-group" name="formulario" id="formulario" 
      action="<?php echo "{$pagina}?pag={$pag}&acao={$acao}{$url}"; ?>" method="POST">
    <input type="hidden" name="acao" id="acao" value="<?php echo $acao ?>"/>
    <input type="hidden" name="class" id="class" value="<?php echo $pagina ?>"/>

    <div class="panel-body">
         <div class="form-row <?php echo ($usuarioacesso->Agencia===0?"d-flex":"d-none") ?>">
            <?php $controller->addAgencia(); ?>
            <div class="form-group col-xs-6  col-sm-6 col-md-6  col-lg-6" id="form-agencia_id">  
                <label for="agencia_id">Agência</label>
                <select name="agencia_id" id="agencia_id" rel="select2" class="form-control">
                    <option value="">TODOS</option>
                    <?php foreach ($controller->getAgencia() as $value) { ?>
                        <option value="<?php echo crypto::encrypt($value->getId()) ?>" 
                                <?php echo ((isset($_REQUEST['agencia_id']) && ($_REQUEST['agencia_id'] === crypto::encrypt($value->getId()))) ? "selected='true'" : "") ?>><?php echo $value->getNome() ?></option>
                            <?php } ?>
                </select>
            </div>
            <?php $controller->addComercial(false); ?>
            <div class="form-group col-xs-6  col-sm-6 col-md-6  col-lg-6" id="form-comercial_id">  
                <label for="comercial_id">Comercial</label>
                <select name="comercial_id" id="comercial_id" rel="select2" class="form-control">
                    <option value="">TODOS</option>
                    <?php foreach ($controller->getComercial() as $value) { ?>
                        <option value="<?php echo crypto::encrypt($value->getId()) ?>" 
                                <?php echo ((isset($_REQUEST['comercial_id']) && ($_REQUEST['comercial_id'] === crypto::encrypt($value->getId()))) ? "selected='true'" : "") ?>><?php echo $value->getNome() ?></option>
                            <?php } ?>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-xs-8  col-sm-8 col-md-8  col-lg-8" id="form-nome">  
                <label for="cliente">Cliente</label>
                <input  type="text" class="form-control" id="nome" placeholder="Cliente" maxlength="80"
                        name="nome"  value="<?php echo (isset($_REQUEST['nome']) ? $_REQUEST['nome'] : '') ?>"/>

            </div>
            <div class="form-group col-xs-4  col-sm-4 col-md-4  col-lg-4" id="form-cpfcnpj">  
                <label for="cpfcnpj">CPF/CNPJ</label>
                <input  type="text" class="form-control" id="cpfcnpj"  placeholder="CPF ou CNPJ"  maxlength="18" onkeyup="return Maiuscula(this)"
                        name="cpfcnpj"  value="<?php echo (isset($_REQUEST['cpfcnpj']) ? $_REQUEST['cpfcnpj'] : '') ?>"/>
            </div>     
        </div>
        <div class="form-row ">
            <div class="form-group col-sm-12 col-md-12 text-center"> 
                <button class="btn btn-primary" rel="tooltip" onclick="abreEspera()" data-placement="top" type="submit" title="Se clicar com os campos vazios recebera todos os registros">Filtrar</button>
                <button class="btn btn-default" rel="tooltip" data-placement="top" type="reset" title="Limpar todos os campos?" type="button">Limpar</button>
            </div>
        </div>
    </div>
    <hr class="footer-filtro">
    <div class="panel-body">
        <div class="row margim-footer" id="retotnovisualisa">
            <?php include 'grid.php'; ?>
        </div>
    </div>
</form>
