
<div class="row" >
    <div class="col-md-12 col-sm-12">
        <h3  class="h3-1">Cadastro Prospecção</h3>
        <hr>
    </div>
</div>
<form class="form-group" name="formulario" id="formulario" 
      action="<?php echo "{$pagina}?pag={$pag}&acao={$acao}{$url}"; ?>" method="POST">
    <input type="hidden" name="acao" id="acao" value="<?php echo $acao ?>"/>
    <input type="hidden" name="class" id="class" value="<?php echo $pagina ?>"/>

    <div class="panel-body">
        <div class="form-row <?php echo ($usuarioacesso->Agencia === 0 ? "d-flex" : "d-none") ?>">
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
        </div>

        <div class="form-row">
            <div class="form-group col-xs-12  col-sm-4 col-md-5  col-lg-5" id="form-nome">  
                <label for="nome">Cliente</label>
                <input  type="text" class="form-control" id="nome" placeholder="nome" maxlength="80"
                        name="nome"  value="<?php echo (isset($_REQUEST['nome']) ? $_REQUEST['nome'] : '') ?>"/>

            </div>
            <div class="form-group col-xs-12  col-sm-4 col-md-5  col-lg-5" id="form-comercial">  
                <label for="comercial">Comercial</label>
                <input  type="text" class="form-control" id="comercial" placeholder="Comercial" maxlength="80"
                        name="comercial"  value="<?php echo (isset($_REQUEST['comercial']) ? $_REQUEST['comercial'] : '') ?>"/>

            </div> 
            <div class="form-group col-xs-12  col-sm-4 col-md-2  col-lg-2" id="form-pendencia">  
                <label for="pendencia">Situação</label>
                <select class="form-control" id="pendencia" name="pendencia" rel="select2">
                    <option value="">TODOS</option>
                    <option <?php echo (isset($_REQUEST['pendencia']) && ($_REQUEST['pendencia'] === "F") ? "selected='true'" : "") ?> value="F">Pendentes</option>
                    <option <?php echo (isset($_REQUEST['pendencia']) && ($_REQUEST['pendencia'] === "T") ? "selected='true'" : "") ?> value="T">Visitados</option>
                </select>
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
