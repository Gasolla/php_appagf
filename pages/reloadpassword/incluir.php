<div class="container margim-footer">        
    <div class="panel-body">
        <div class="form-row">
            <div  class="col-md-12 form-login">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="clearfix">
                            <h1 class="panel-title float-left"><b>Solicitar nova senha.</b></h1>
                            <a  class="btn btn-link float-right a-link" href="index">Voltar</a>
                        </div>
                    </div>
                    <div class="panel-heading">
                        <div class="clearfix">
                            <h5 class="observacao">** Digite seu email no campo abaixo.</h5>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="panel panel-default clearfix">
                            <div id="resposta"></div>                   
                            <div class="top-50">
                                <form class="form-horizontal form-panel" method="POST" action="app"
                                      onsubmit="return executa_grava(this)">
                                    <input type="hidden" name="acao" value="incluir"/>
                                    <input type="hidden" name="class" value="<?php echo $pagina ?>"/>
                                    <div class="form-group" id="form-email">
                                        <div class="offset-md-3 col-md-8">
                                            <div class='col-sm-8'> 
                                                <label for="email" class='control-label'>Email</label>
                                                <input  type="text" class="form-control" placeholder="exemplo@email.com"
                                                        onblur="onblurEmail(this)" value="" id="email" name="email"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group ">
                                        <div class="offset-md-3 col-md-8">
                                            <div class="col-sm-8 text-center">
                                                <button type="submit" class="btn btn-primary top-20">Solicitar Código</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div> 
                        </div>
                    </div>
                </div>
                <script>//setTimeout($('#email').focus(), 1000);</script>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="MsnCodigoVerificacao" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form class="form-horizontal form-panel" method="POST" action="alterpassword" 
                  onsubmit="return eexcecuta_alterar(this.codigo)">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Digite o código de verificação!</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                        <span aria-hidden="true">Fechar</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success" role="alert">O código de verificção foi enviado para caixa de e-mail cadastrado. </div>
                    <input type="hidden" name="email" id="email" value=""/>
                    <input type="hidden" name="id" id="id" value=""/>
                    <div class="panel-body">
                        <div class="form-row">
                            <div class='col-10 offset-1' id="form-codigo"> 
                                <label for="codigo" class='control-label'>Código</label>
                                <input  type="text" class="form-control" placeholder="Código de verificação" value="" id="codigo" name="codigo"/>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Enviar</button>
                </div>
            </form>
        </div>
    </div>
</div>