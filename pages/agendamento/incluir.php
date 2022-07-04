<?php
$codigo = (filter_input(INPUT_GET, 'codigo') ?: 0);
$sucesso = $controller->index($codigo);
if ($sucesso === false) {
    exit(header("location:{$pagina}?pag={$pag}&acao=index{$url}"));
}

if (((!$usuarioacesso->Incluir) && (!$usuarioacesso->Alterar) && (!$usuarioacesso->Consultar))) {
    exit(header("location:{$pagina}?pag={$pag}&acao=index{$url}"));
}
$salvar = (($usuarioacesso->Incluir && (crypto::decrypt($codigo) === false)) || ($usuarioacesso->Alterar && (crypto::decrypt($codigo) > 0))) && ($controller->getAgendamento()->getStatus()==="F");

?>
<div class="row" >
    <div class="col-md-12 col-sm-12">
        <h3  class="h3-1 float-left">Incluir Agendamento</h3>
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
    <input type="hidden" name="usuarioold" id="usuarioold" value="<?php echo (isset($_REQUEST['usuarioold'])?$_REQUEST['usuarioold']:$controller->getAgendamento()->getUsuario()) ?>"/> 
    <input type="hidden" name="status" id="status" value="<?php echo crypto::encrypt($controller->getAgendamento()->getStatus()) ?>"/> 
    
    <div class="panel-body">
        <div class="form-row <?php echo ($usuarioacesso->Agencia === 0 ? "d-flex" : "d-none") ?>">
            <?php $controller->addAgencia(); ?>
            <div class="form-group col-xs-6  col-sm-6 col-md-6  col-lg-6 <?php echo ($usuarioacesso->Agencia === 0 ? "" : "d-none") ?>" id="form-agencia"> 
                <label for="agencia">Agência</label>
                <select name="agencia" id="agencia" rel="select2" class="form-control"
                        onchange='onChangeAgencia("<?php echo "{$pagina}?pag={$pag}"; ?>&acao=incluir<?php echo (isset($_REQUEST['codigo']) ? "&codigo={$_REQUEST['codigo']}" : "") ?><?php echo (isset($_REQUEST['usuarioold']) ? "&usuarioold={$_REQUEST['usuarioold']}" : "&usuarioold=".$controller->getAgendamento()->getUsuario()) ?><?php echo $url ?>", this)'>
                    <option value="">Selecione a agência</option>
                    <?php foreach ($controller->getAgencia() as $value) { ?>
                        <option value="<?php echo crypto::encrypt($value->getId()) ?>" 
                                <?php echo ($controller->getAgendamento()->getAgencia() == $value->getId() ? "selected='true'" : "") ?>><?php echo $value->getNome() ?></option>
                            <?php } ?>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-xs-12 col-sm-12 col-md-8 col-lg-8" id="form-cliente">  
                 <?php $controller->addCliente(true); ?>
                <label for="cliente">Cliente</label>
                <select class="form-control" id="cliente" name="cliente" rel="select2"
                        onchange='onChangeCliente("<?php echo "{$pagina}?pag={$pag}"; ?>&acao=incluir<?php echo (isset($_REQUEST['codigo']) ? "&codigo={$_REQUEST['codigo']}" : "") ?><?php echo (isset($_REQUEST['agencia']) ? "&agencia={$_REQUEST['agencia']}" : "") ?><?php echo (isset($_REQUEST['usuarioold']) ? "&usuarioold={$_REQUEST['usuarioold']}" : "&usuarioold=".$controller->getAgendamento()->getUsuario()) ?><?php echo $url ?>", this)'>
                    <option value="">Selecione o cliente</option>
                    <?php foreach ($controller->getCliente() as $value) { ?>
                            <option value="<?php echo crypto::encrypt($value->getId()) ?>" 
                                    <?php echo ($controller->getAgendamento()->getCliente() == $value->getId() ? "selected='true'" : "") ?>><?php echo $value->getNome() ?></option>
                                <?php } ?>
                </select>
            </div>
            <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4" id="form-data">  
                <label for="cidata">Data</label><!--Este campo Pesquisa na Tabela notificação (DtHr)-->
                <div class="input-group date" data-provide="datepicker" data-date-format="dd/mm/yyyy" 
                     data-date-language="pt-BR" data-date-autoclose="true" 
                     data-date-today-highlight="true" data-date-orientation="bottom"> 
                    <input  type="text" class="form-control datepicker" id="data" placeholder="Data" 
                            name="data" maxlength="10" onkeypress="return MascaraData(this, event);"
                            value="<?php echo ((($controller->getAgendamento()->getData() != "")) ? $controller->getAgendamento()->getData() : date("d/m/Y")) ?>"/>
                    <div class="input-group-addon imput-calendario">
                        <i class="fas fa-calendar-alt fa-2x"></i>
                    </div>
                </div>
                <script>
                    $('.date').datepicker({
                        startDate: "0d",
                        daysOfWeekDisabled: "0,6"
                    });
                </script>
            </div>   
        </div>
        <div class="form-row">
        
         <div class="form-group col-xs-12 col-sm-12 col-md-4 col-lg-4" id="form-usuario">  
                 <?php $controller->addMotorista(true); ?>
                <label for="usuario">Motorista</label>
                <select class="form-control" id="usuario" name="usuario" rel="select2">
                    <option value="">Selecione o motorista</option>
                    <?php foreach ($controller->getMotorista() as $value) { ?>
                            <option value="<?php echo crypto::encrypt($value->getId()) ?>" 
                                    <?php echo ($controller->getAgendamento()->getusuario() == $value->getId() ? "selected='true'" : "") ?>><?php echo $value->getNome() ?></option>
                                <?php } ?>
                </select>
            </div>
            <div class="form-group col-xs-12 col-sm-12 offset-md-4 offset-lg-4 col-md-4 col-lg-4 top-20">  
                <div class="form-check">
                    <input <?php echo ($controller->getAgendamento()->getImediata() == "T" ? "checked" : "") ?>
                        class="form-check-input" type="checkbox" name="imediata" id="imediata" value="T">
                    <label class="form-check-label" for="imediata">
                        Realizar coleta imediatamente
                    </label>
                </div>
            </div>
        </div>
        <div class="form-row ">
            <div class="form-group col-sm-12 col-md-12 text-center"> 
                <button class="btn btn-success <?php echo ($salvar ? "" : "disabled") ?>" <?php echo ($salvar ? "" : "disabled") ?> id="salvar" rel="tooltip" data-placement="top" type="submit" title="Salvar">Salvar</button>
                <a class="btn btn-danger" rel="tooltip" data-placement="top"  title="Voltar" href="<?php echo "{$pagina}?pag={$pag}&acao=index{$url}"; ?>">Voltar</a>
            </div>
        </div>
    </div>
    <hr>
</form>
