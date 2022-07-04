
<div class="row" >
    <div class="col-md-12 col-sm-12">
        <h3  class="h3-1">API Envio Web</h3>
        <hr>
    </div>
</div>
<form class="form-group" name="formulario" id="formulario" 
      action="<?php echo "{$pagina}?pag={$pag}&acao={$acao}{$url}"; ?>" method="POST">
    <input type="hidden" name="acao" id="acao" value="<?php echo $acao ?>"/>
    <input type="hidden" name="class" id="class" value="<?php echo $pagina ?>"/>

    <div class="panel-body">
        <div class="form-row">
            <div class="form-group col-xs-6  col-sm-6 col-md-3  col-lg-2 " id="form-cidata">  
                <label for="cidata">Data Cadastro Inicial</label><!--Este campo Pesquisa na Tabela notificação (DtHr)-->
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
            <div class="form-group col-xs-6  col-sm-6 col-md-3  col-lg-2" id="form-cfdata">  
                <label for="cfdata">Data Cadastro Final</label>
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
            <div class="form-group col-xs-6  col-sm-6 col-md-3  col-lg-2 " id="form-pidata">  
                <label for="pidata">Data Postagem Inicial</label><!--Este campo Pesquisa na Tabela notificação (DtHr)-->
                <div class="input-group date" data-provide="datepicker" data-date-format="dd/mm/yyyy" 
                     data-date-language="pt-BR" data-date-autoclose="true" 
                     data-date-today-highlight="true" data-date-orientation="bottom"> 
                    <input  type="text" class="form-control datepicker" id="pidata" placeholder="Data Inicial" 
                            name="pidata" maxlength="10" onkeypress="return MascaraData(this, event);"
                            value="<?php echo (isset($_REQUEST['pidata']) ? $_REQUEST['pidata'] : "") ?>"/>
                    <div class="input-group-addon imput-calendario">
                        <i class="fas fa-calendar-alt fa-2x"></i>
                    </div>
                </div>
            </div>
            <div class="form-group col-xs-6  col-sm-6 col-md-3  col-lg-2" id="form-pfdata">  
                <label for="pfdata">Data Postagem Final</label>
                <div class="input-group date" data-provide="datepicker" data-date-format="dd/mm/yyyy" 
                     data-date-language="pt-BR" data-date-autoclose="true" 
                     data-date-today-highlight="true" data-date-orientation="bottom"> 
                    <input  type="text" class="form-control datepicker" id="pfdata" placeholder="Data Final" 
                            name="pfdata" maxlength="10" onkeypress="return MascaraData(this, event);"
                            value="<?php echo (isset($_REQUEST['pfdata']) ? $_REQUEST['pfdata'] : "") ?>"/>
                    <div class="input-group-addon imput-calendario">
                        <i class="fas fa-calendar-alt fa-2x"></i>
                    </div>
                </div>
            </div>
            
            <div class="form-group col-xs-6  col-sm-6 col-md-6  col-lg-4" id="form-objeto">  
                <label for="objeto">Objeto</label>
                <input type="text" class="form-control" id="objeto" name="objeto" maxlength="13" 
                       value="<?php echo (isset($_REQUEST['objeto']) ? $_REQUEST['objeto'] : "") ?>"> 
            </div>  
            <div class="form-group col-xs-6  col-sm-6 col-md-6  col-lg-6" id="form-status"> 
                <div class="form-row">
                    <label for="status">Status</label>
                    <select name="status" id="status" rel="select2" class="form-control">
                        <option value="">TODOS</option>
                        <option value="C" <?php echo ((isset($_REQUEST['status']) && ($_REQUEST['status'] === "C")) ? "selected='true'" : "") ?>>Aguardando Coleta</option>
                        <option value="I" <?php echo ((isset($_REQUEST['status']) && ($_REQUEST['status'] === "I")) ? "selected='true'" : "") ?>>Aguardando Impressão</option>
                        <option value="A" <?php echo ((isset($_REQUEST['status']) && ($_REQUEST['status'] === "A")) ? "selected='true'" : "") ?>>Aguardando Postagem</option>
                        <option value="M" <?php echo ((isset($_REQUEST['status']) && ($_REQUEST['status'] === "M")) ? "selected='true'" : "") ?>>Coleta em andamento</option>
                        <option value="R" <?php echo ((isset($_REQUEST['status']) && ($_REQUEST['status'] === "R")) ? "selected='true'" : "") ?>>Coleta recusada</option>
                        <option value="T" <?php echo ((isset($_REQUEST['status']) && ($_REQUEST['status'] === "T")) ? "selected='true'" : "") ?>>Finalizado</option>
                        <option value="E" <?php echo ((isset($_REQUEST['status']) && ($_REQUEST['status'] === "E")) ? "selected='true'" : "") ?>>Impresso Expedição</option>
                        <option value="P" <?php echo ((isset($_REQUEST['status']) && ($_REQUEST['status'] === "P")) ? "selected='true'" : "") ?>>Postado</option>
                    </select>
                </div>
            </div>
             <div class="form-group col-xs-6  col-sm-6 col-md-6  col-lg-6" id="form-nome">  
                <label for="nome">Nome Destinatario</label>
                <input type="text" class="form-control" id="nome" name="nome" maxlength="80" 
                       value="<?php echo (isset($_REQUEST['nome']) ? $_REQUEST['nome'] : "") ?>"> 
            </div> 
             <?php $controller->addAgencia(); ?>
            <div class="form-group col-xs-6  col-sm-6 col-md-6  col-lg-6 <?php echo ($usuarioacesso->Agencia === 0 ? "" : "d-none") ?>" id="form-agencia_id"> 
                <div class="form-row <?php echo ($usuarioacesso->Agencia === 0 ? "d-flex" : "d-none") ?>">
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
            <div class="form-group col-xs-6  col-sm-6 col-md-6  col-lg-6 <?php echo ($usuarioacesso->Cliente === 0 ? "" : "d-none") ?>" id="form-cliente_id">  
                 <?php $controller->addCliente(false); ?>
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
