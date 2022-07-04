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
    <div class="form-group col-xs-6  col-sm-6 col-md-6  col-lg-6" id="form-email">  
        <label for="email">Email</label>
        <input  type="text" class="form-control" id="email"  placeholder="digite o email"  maxlength="100"  onblur="ValidaEmail(this)"
                name="email"  value="<?php echo $controller->getUsuario()->getEmail() ?>"/>
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
