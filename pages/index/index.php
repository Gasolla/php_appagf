<form class="form-horizontal" method="POST"  name="login" id="login" 
      action="app" onsubmit="return registra_acesso(this)"> 
    <input type="hidden" name="class" value="index"/>
    <input type="hidden" name="acao" value="login"/>
    <div class="form-group" id="form-usuario">
        <div class="col-xs-10 col-sm-10 col-md-8 col-lg-6 offset-lg-2 offset-md-2"> 
            <label for="usuario" class="control-label ">Usuario</label>
            <div class="input-group">
                <div class="input-group-addon">
                    <i class="fas fa-user fa-2x i-group"></i>
                </div>
                <input type="text" placeholder="digite seu usuário" class="form-control uppercase input-index" id="usuario" name="usuario"/>

            </div>
        </div>
    </div>
    <div class="form-group" id="form-password">
        <div class="col-xs-10 col-sm-10 col-md-8 col-lg-6 offset-lg-2 offset-md-2">  
            <label for="password" class="control-label">Senha</label>
            <div class="input-group">
                <div class="input-group-addon ">
                    <i class="fas fa-lock fa-2x i-group"></i>
                </div>
                <input type="password" placeholder="digite sua senha" class="form-control uppercase input-index" id="password" name="password"/>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div   class="col-xs-10 col-sm-10 col-md-8 col-lg-6 offset-lg-2 offset-md-2 text-center">
            <a class="btn btn-link a-link" href="reloadpassword">Solicitar nova senha</a>
        </div>
    </div>
    <div class="form-group">
        <div   class="col-xs-10 col-sm-10 col-md-8 col-lg-6 offset-lg-2 offset-md-2 text-center">
            <button type="submit" class="btn btn-success form-control top-10">Entrar</button>
        </div>
    </div>
</form>