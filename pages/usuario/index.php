
<div class="row" >
    <div class="col-md-12 col-sm-12">
        <h3  class="h3-1">Cadastro Usuário</h3>
        <hr>
    </div>
</div>
<form class="form-group" name="formulario" id="formulario" 
      action="<?php echo "{$pagina}?pag={$pag}&acao={$acao}{$url}"; ?>" method="POST">
    <input type="hidden" name="acao" id="acao" value="<?php echo $acao ?>"/>
    <input type="hidden" name="class" id="class" value="<?php echo $pagina ?>"/>

    <div class="panel-body ">
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
        </div>
        <div class="form-row">
            <div class="form-group col-xs-8  col-sm-8 col-md-8  col-lg-8" id="form-nome">  
                <label for="nome">Nome</label>
                <input  type="text" class="form-control" id="nome" placeholder="digite o nome " maxlength="80"
                        name="nome"  value="<?php echo (isset($_REQUEST['nome']) ? $_REQUEST['nome'] : '') ?>"/>

            </div>
            <div class="form-group col-xs-4  col-sm-4 col-md-4  col-lg-4" id="form-usuario">  
                <label for="usuario">Usuário</label>
                <input  type="text" class="form-control" id="usuario"  placeholder="digite o usuario"  maxlength="30" onkeyup="return Maiuscula(this)"
                        name="usuario"  value="<?php echo (isset($_REQUEST['usuario']) ? $_REQUEST['usuario'] : '') ?>"/>
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
        <div class="row" id="retotnovisualisa">
            <?php include 'grid.php'; ?>
        </div>
    </div>
</form>


