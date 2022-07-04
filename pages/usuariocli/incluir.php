<?php
$codigo = (filter_input(INPUT_GET, 'codigo') ?: 0);
$sucesso = $controller->index($codigo);
if ($sucesso === false) {
    exit(header("location:{$pagina}?pag={$pag}&acao=index{$url}"));
}
if (((!$usuarioacesso->Incluir) && (!$usuarioacesso->Alterar) && (!$usuarioacesso->Consultar))) {
    exit(header("location:{$pagina}?pag={$pag}&acao=index{$url}"));
}
$salvar = (($usuarioacesso->Incluir && (crypto::decrypt($codigo) === false)) || ($usuarioacesso->Alterar && (crypto::decrypt($codigo) > 0)));
?>
<div class="row" >
    <div class="col-md-12 col-sm-12">
        <h3  class="h3-1 float-left">Incluir Usuário app clientes</h3>
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
    <div class="panel-body">
        <div class="form-row <?php echo ($usuarioacesso->Agencia === 0 ? "d-flex" : "d-none") ?>">
            <?php $controller->addAgencia(); ?>
            <div class="form-group col-xs-6  col-sm-6 col-md-6  col-lg-6" id="form-agencia">  
                <label for="agencia">Agência</label>
                <select name="agencia" id="agencia" rel="select2" class="form-control"
                        onchange='onChangeAgencia("<?php echo "{$pagina}?pag={$pag}"; ?>&acao=incluir<?php echo (isset($_REQUEST['codigo']) ? "&codigo={$_REQUEST['codigo']}" : "") ?><?php echo $url ?>", this)'>
                            <?php foreach ($controller->getAgencia() as $value) { ?>
                        <option value="<?php echo crypto::encrypt($value->getId()) ?>" 
                                <?php echo ($controller->getUsuario()->getAgencia() === crypto::encrypt($value->getId()) ? "selected='true'" : "") ?>><?php echo $value->getNome() ?></option>
                            <?php } ?>
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-xs-6  col-sm-6 col-md-6  col-lg-6" id="form-cliente_id"> 
                <?php $controller->addCliente(); ?>
                <label for="cliente_id">Cliente</label>
                <select class="form-control" id="cliente_id" name="cliente_id" rel="select2">
                    <?php foreach ($controller->getCliente() as $value) { ?>
                        <option value="<?php echo crypto::encrypt($value->getId()) ?>" 
                                <?php echo ($controller->getUsuario()->getCliente() === ($value->getId()) ? "selected='true'" : "") ?>><?php echo $value->getNome() ?></option>
                            <?php } ?>
                </select>
            </div>
            <div class="form-group col-xs-6  col-sm-6 col-md-6  col-lg-6" id="form-nome">  
                <label for="nome">Nome</label>
                <input  type="text" class="form-control" id="nome" placeholder="digite o nome"
                        name="nome"  value="<?php echo $controller->getUsuario()->getNome() ?>"/>

            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-xs-6  col-sm-6 col-md-6  col-lg-6" id="form-sobrenome">  
                <label for="sobrenome">Sobrenome</label>
                <input  type="text" class="form-control" id="sobrenome"  placeholder="digite o sobrenome"  maxlength="100" 
                        name="sobrenome"  value="<?php echo $controller->getUsuario()->getSobrenome() ?>"/>
            </div>     

            <div class="form-group col-xs-6  col-sm-6 col-md-6  col-lg-6" id="form-usuario">  
                <label for="usuario">Usuário</label>
                <input  type="text" class="form-control" id="usuario"  placeholder="digite o usuario"  maxlength="30" onkeyup="return Maiuscula(this)"
                        name="usuario"  value="<?php echo $controller->getUsuario()->getUsuario() ?>"/>
            </div>     
        </div>
        <div class="form-row">
            <div class="form-group col-xs-6  col-sm-6 col-md-6  col-lg-6" id="form-senha">  
                <label for="senha">Senha</label>
                <div class="input-group">
                    <input  type="password" class="form-control" id="senha" placeholder="digite a senha" onkeyup="VerificaSenha(this, confsenha)"
                            name="senha"  value="<?php echo $controller->getUsuario()->getSenha() ?>"/>
                    <div class="input-group-addon" id="val">
                        <i class="fas <?php echo ((crypto::decrypt($codigo) === 0) ? "fa-times" : "fa-check") ?> fa-2x i-group-pass"></i>

                    </div>
                </div>
            </div>
            <div class="form-group col-xs-6  col-sm-6 col-md-6  col-lg-6" id="form-confsenha">  
                <label for="confsenha">Confirmação Senha</label>
                <div class="input-group">
                    <input  type="password" class="form-control" id="confsenha"  placeholder="digite a confirmação" onKeyUp="ConfirmacaoSenha(senha, this)"
                            name="confsenha"  value="<?php echo $controller->getUsuario()->getSenha() ?>"/>
                    <div class="input-group-addon" id="conf">
                        <i class="fas <?php echo ((crypto::decrypt($codigo) === 0) ? "fa-times" : "fa-check") ?> fa-2x i-group-pass"></i>
                    </div>
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
    <hr class="footer-incluirusuario" style="margin-bottom: 70px">
</form>
