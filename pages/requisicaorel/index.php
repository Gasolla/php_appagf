
<div class="row" >
    <div class="col-md-12 col-sm-12">
        <h3  class="h3-1">Relatório Coleta Requisição</h3>
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
            <div class="form-group col-xs-6  col-sm-6 col-md-3  col-lg-3 " id="form-cidata">  
                <label for="cidata">Inicial Coleta</label><!--Este campo Pesquisa na Tabela notificação (DtHr)-->
                <div class="input-group date" data-provide="datepicker" data-date-format="dd/mm/yyyy" 
                     data-date-language="pt-BR" data-date-autoclose="true" 
                     data-date-today-highlight="true" data-date-orientation="bottom"> 
                    <input  type="text" class="form-control datepicker" id="cidata" placeholder="Data Inicial" 
                            name="cidata" maxlength="10" onkeypress="return MascaraData(this, event);"
                            value="<?php echo (isset($_REQUEST['cidata']) ? $_REQUEST['cidata'] : "") ?>"/>
                    <div class="input-group-addon imput-calendario">
                        <i class="fas fa-calendar-alt fa-2x"></i>
                    </div>
                </div>
            </div>
            <div class="form-group col-xs-6  col-sm-6 col-md-3  col-lg-3" id="form-cfdata">  
                <label for="cfdata">Final Coleta</label>
                <div class="input-group date" data-provide="datepicker" data-date-format="dd/mm/yyyy" 
                     data-date-language="pt-BR" data-date-autoclose="true" 
                     data-date-today-highlight="true" data-date-orientation="bottom"> 
                    <input  type="text" class="form-control datepicker" id="cfdata" placeholder="Data Final" 
                            name="cfdata" maxlength="10" onkeypress="return MascaraData(this, event);"
                            value="<?php echo (isset($_REQUEST['cfdata']) ? $_REQUEST['cfdata'] : "") ?>"/>
                    <div class="input-group-addon imput-calendario">
                        <i class="fas fa-calendar-alt fa-2x"></i>
                    </div>
                </div>
            </div>
            <div class="form-group col-xs-12  col-sm-12 col-md-6  col-lg-6" id="form-cliente_id">              
                <?php $controller->addCliente(); ?>
                <label for="cliente_id">Cliente</label>
                <select class="form-control" id="cliente_id" name="cliente_id" rel="select2">
                    <option value="">TODOS</option>
                    <?php foreach ($controller->getCliente() as $value) { ?>
                        <option value="<?php echo crypto::encrypt($value->getId()) ?>" 
                                <?php echo ((isset($_REQUEST['cliente_id']) && ($_REQUEST['cliente_id'] === crypto::encrypt($value->getId()))) ? "selected='true'" : "") ?>><?php echo $value->getNome() ?></option>
                            <?php } ?> 
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
