<?php
$controller->index();
?>

<div class="container margim-footer top-50">        
    <div class="panel-body">
        <div class="form-row">
            <div  class="col-12 form-login ">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="clearfix">
                            <h1 class="panel-title float-left"><b>Alteração de senha.</b></h1>
                            <a  class="btn-link float-right a-link" href="reloadpassword">Voltar</a>
                        </div>
                    </div>
                    <div class="panel-heading">
                        <div class="clearfix">
                            <?php if ($controller->error == "") { ?>
                                <span class="observacao">** Senha deve conter minimo 8 caracteres.</span><br/>
                                <span class="observacao">** Senha diferente que usuario.</span><br/>
                                <span class="observacao">** Senha deve conter ao menos uma letra minúscula.</span><br/>
                                <span class="observacao">** Senha deve conter ao menos uma letra maiúscula.</span><br/>
                                <span class="observacao">** Senha deve conter ao menos um numero.</span><br/>
                                <span class="observacao">** Senha deve conter ao menos um caractere especial.</span><br/>
                                <span class="observacao">** Não utilizar sequencias exemplo: 00 - aa</span><br/>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="panel-body top-15">
                        <div class="panel panel-default clearfix">
                            <div id="resposta"></div>                   
                            <?php if ($controller->error == "") { ?>

                                <form class="form-horizontal form-panel" method="POST" action="app"
                                      onsubmit="return excecuta_grava(this, this.senha, this.confsenha)">
                                    <input type="hidden" name="acao" value="incluir"/>
                                    <input type="hidden" name="class" value="<?php echo $pagina ?>"/>
                                    <input type="hidden" name="codigo" id="codigo" value="<?php echo $controller->codigo ?>"/>
                                    <div class="form-group" id="form-senha">
                                        <div class="offset-md-4 col-md-8">
                                            <div class='col-sm-8'> 
                                                <label for="senha" class='control-label'>Nova Senha</label>
                                                <div class="input-group">
                                                    <input  type="password" class="form-control" placeholder="########"  value="" id="senha" name="senha"
                                                            onKeyUp="verifica_senha(senha, confsenha)"/>
                                                    <div class="input-group-addon" id="val">
                                                        <i class="fas fa-times fa-2x i-index" style="color: red; margin-left: 15px"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group" id="form-confsenha">
                                        <div class="offset-md-4 col-md-8">
                                            <div class='col-sm-8'> 
                                                <label for="confsenha" class='control-label'>Confirmação Senha</label>
                                                <div class="input-group">
                                                    <input  type="password" class="form-control" placeholder="########" value="" id="confsenha" name="confsenha"
                                                            onKeyUp="confirmacao_senha(senha, confsenha)" />
                                                    <div class="input-group-addon" id="conf">
                                                        <i class="fas fa-times fa-2x i-index" style="color: red; margin-left: 15px"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="form-group">
                                        <div class="offset-md-4 col-md-8">
                                            <div class='col-sm-8 text-center'> 
                                                <button type="submit" id="salvar" disabled="true" class="btn btn-primary btn-fooder disabled">Salvar nova senha</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                            <?php } else { ?>
                                <div class="alert alert-danger"><?php echo $controller->error ?></div>
                                <div class="form-group">
                                    <div class="text-center">
                                        <a class="btn btn-danger" href="reloadpassword">Solicitar novo código</a>
                                    </div>
                                </div>
                            <?php } ?>
                        </div> 

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
