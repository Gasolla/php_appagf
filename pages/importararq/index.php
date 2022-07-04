<form method="POST" action="app" onsubmit="return executa_grava(this.arq, this)">
    <div class="card text-white bg-secondary top-20">        
        <div class="card-body jumbotron-upload">
            <div class="form-row">
                <div class="col-md-12">
                    <div class="clearfix">
                        <h3 class="float-left"><b>IMPORTAR ARQUIVO</b></h3>
                    </div>
                    <hr>
                </div>
                <hr>
                <input type="hidden" name="acao" value="<?php echo $acao ?>"/>
                <input type="hidden" name="class" value="<?php echo $pagina ?>"/>
                <div class="col-md-5 col-sm-9 col-xs-12 offset-md-2 offset-sm-0" id="form-arq">
                    <label for="arq">Arquivo</label>
                    <input type="file" id="arq" name="arq"  rel="filestyle">
                </div>
                <div class="col-md-5 col-sm-3 col-xs-3 text-center" >
                    <button type="submit" class="btn btn-success btn-upload">Enviar</button>
                </div>
                <div class="col-md-12">
                    <hr>
                </div>
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
