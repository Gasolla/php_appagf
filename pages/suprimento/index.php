
<div class="row" >
    <div class="col-md-12 col-sm-12">
        <h3  class="h3-1">Cadastro Suprimento</h3>
        <hr>
    </div>
</div>
<form class="form-group" name="formulario" id="formulario" 
      action="<?php echo "{$pagina}?pag={$pag}&acao={$acao}{$url}"; ?>" method="POST">
    <input type="hidden" name="acao" id="acao" value="<?php echo $acao ?>"/>
    <input type="hidden" name="class" id="class" value="<?php echo $pagina ?>"/>

    <div class="panel-body">
        <div class="form-row">
            <div class="form-group col-xs-10  col-sm-10 col-md-10  col-lg-10" id="form-nome">  
                <label for="nome">Nome</label>
                <input  type="text" class="form-control" id="cliente" placeholder="Suprimento"
                        name="nome"  value="<?php echo (isset($_REQUEST['nome']) ? $_REQUEST['nome'] : '') ?>"/>

            </div>
            <div class="form-group col-xs-2  col-sm-2 col-md-2  col-lg-2" id="form-sigla">  
                <label for="sigla">Sigla</label>
                <input  type="text" class="form-control" id="sigla"  placeholder="Sigla"  maxlength="2"
                        name="sigla"  value="<?php echo (isset($_REQUEST['sigla']) ? $_REQUEST['sigla'] : '') ?>"/>
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
