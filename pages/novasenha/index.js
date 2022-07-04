const regsenha = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[$*&@#])(?:([0-9a-zA-Z$*&@#])(?!\1)){8,}$/;
function excecuta_grava(formulario, senha, confsenha){
    if (($.trim(senha.value) === '') || ($.trim(senha.value).length<8)) {
        $('#msgs').find('.modal-body').html("<h4><b>Senha inválida!</b></h4>"); 
        $('#msgs').modal();
        $('#senha').addClass('has-error');
        return false;
    }else if ($.trim(senha.value) !== $.trim(confsenha.value)){
        $('#msgs').find('.modal-body').html("<h4><b>Senha e confirmação da senha não são validos!</b></h4>"); 
        $('#msgs').modal();
        $('#senha').addClass('has-error');
        $('#confsenha').addClass('has-error');
        return false;        
    }
    if ((!regsenha.test(senha.value))){
        $('#msgs').find('.modal-body').html("<h4><b>Senha inválida!</b></h4>"); 
        $('#msgs').modal();
        $('#senha').addClass('has-error');
        $('#confsenha').addClass('has-error');
        $('#senha').focus();
        return false;        
    }
    
    _envia_formulario(formulario, excecuta_grava_retorno);
    return false;   
}

function excecuta_grava_retorno(retorno){
    //alert(retorno);
    if ($.trim(retorno[0]) === '1') {
        alert('Senha alterada com sucesso!');
        location.href = 'index';
    } else {
        $('#resposta').removeClass("alert alert-success");
        $('#resposta').addClass("alert alert-danger");
        $('#resposta').html(retorno[1]);
        $('#msgs').find('.modal-body').html("<h4><b>"+retorno[1]+"</b></h4>"); 
        $('#msgs').modal();
    }
    return false;
}


function confirmacao_senha(senha, confirmacao) {
    if (((senha.value === confirmacao.value) && (confirmacao.value !== ''))&&(regsenha.test(senha.value))) {
        $('#conf').html("<i class='fas fa-check fa-2x i-index' style='color: green; margin-left: 15px'></i>");
        $('#salvar').attr('disabled', false);
        $('#salvar').removeClass('disabled');
    } else {
        $('#conf').html("<i class='fas fa-times fa-2x i-index' style='color: red; margin-left: 15px'></i>");
        $('#salvar').addClass('disabled');
        $('#salvar').attr('disabled', true);
    }
}


function verifica_senha(senha, confirmacao) {
    if (((senha.value.length > 7))&&(regsenha.test(senha.value))) {
        $('#val').html("<i class='fas fa-check fa-2x i-index' style='color: green; margin-left: 15px'></i>");
    } else {
        $('#val').html("<i class='fas fa-times fa-2x i-index' style='color: red; margin-left: 15px'></i>");
    }

    confirmacao_senha(senha, confirmacao);
}
