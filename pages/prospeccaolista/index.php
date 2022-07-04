
<div class="row" >
    <div class="col-md-12 col-sm-12">
        <h3  class="h3-1">Listagem Prospecção</h3>
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
            <div class="form-group col-xs-12  col-sm-6 col-md-6  col-lg-6" id="form-nome">  
                <label for="nome">Cliente</label>
                <input  type="text" class="form-control" id="nome" placeholder="Cliente" maxlength="80"
                        name="nome"  value="<?php echo (isset($_REQUEST['nome']) ? $_REQUEST['nome'] : '') ?>"/>
            </div>    
            <div class="form-group col-xs-12  col-sm-12 col-md-6  col-lg-6" id="form-seguimento"> 
                <?php $controller->addSeguimento(); ?>
                <label for="seguimento">Seguimento</label>
                <select class="form-control" id="seguimento" name="seguimento" rel="select2">
                    <option value="">TODOS</option>
                    <?php foreach ($controller->getSeguimento() as $value) { ?>
                        <option value="<?php echo crypto::encrypt($value->getId()) ?>" 
                                <?php echo ((isset($_REQUEST['seguimento']) && ($_REQUEST['seguimento'] === crypto::encrypt($value->getId()))) ? "selected='true'" : "") ?>><?php echo $value->getNome() ?></option>
                            <?php } ?> 
                </select>
            </div>   
        </div>
        <div class="form-row <?php echo (($usuarioacesso->Acesso === "A") ? "d-flex" : "d-none") ?>">
            <div class="form-group col-xs-12  col-sm-12 col-md-6  col-lg-6" id="form-status"> 
                <label for="status">Status</label>
                <select class="form-control" id="status" name="status" rel="select2">
                    <option value="">Todos</option>
                    <option <?php echo (isset($_REQUEST['status'])&&($_REQUEST['status']==="F")?"selected='true'":"") ?> value="F">Não Publicacos</option>
                    <option <?php echo (isset($_REQUEST['status'])&&($_REQUEST['status']==="T")?"selected='true'":"") ?> value="T">Publicados</option>          
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
