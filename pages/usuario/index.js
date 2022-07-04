
ConfirmacaoSenha = (senha, confirmacao) => {
    if (((senha.value === confirmacao.value) && (confirmacao.value !== ''))) {
        $('#conf').html("<i class='fas fa-check fa-2x i-group-pass'></i>");
    } else {
        $('#conf').html("<i class='fas fa-times fa-2x i-group-pass'></i>");
    }
};


VerificaSenha = (senha, Confirmacao) => {
    if (((senha.value.length >= 6))) {
        $('#val').html("<i class='fas fa-check fa-2x i-group-pass'></i>");
    } else {
        $('#val').html("<i class='fas fa-times fa-2x i-group-pass'></i>");
    }

    ConfirmacaoSenha(senha, Confirmacao);
};


ValidaEmail = (elemento) =>{
    if ((elemento.value!=='')&&(!IsEmail(elemento.value))){
        elemento.value = "";
        $('#msgs').find('.modal-body').html("<h4><b>Email invalido!</b></h4>"); 
        $('#msgs').modal();
        $('#' + elemento.name).removeClass('is-valid');
        $('#' + elemento.name).addClass('is-invalid');
        elemento.value = elemento.value.trim();
        setTimeout(elemento.focus(), 1000);
        return false;
    }else if (elemento.value!=='') {
        $('#' + elemento.name).removeClass('is-invalid');
        $('#' + elemento.name).addClass('is-valid');
    }
    return true;
};

Gravar = (formulario) => {
    const senha = document.getElementById('senha');
    const confirmacao = document.getElementById('confsenha');
    
    for (var i = 0; i < formulario.length; i++) {
        if ((formulario[i].name === 'nome') &&
                (formulario[i].value === '')) {
            erroElemento(formulario[i]);
            return false;
        }else if ((formulario[i].name === 'email') &&
                (formulario[i].value === '')) {
            erroElemento(formulario[i]);
            return false;
        }else if ((formulario[i].name === 'cliente_id') &&
                (formulario[i].value === '')) {
            erroElemento(formulario[i]);
            return false;
        }else if ((formulario[i].name === 'usuario') &&
                (formulario[i].value === '')) {
            erroElemento(formulario[i]);
            return false;
        }
    }
    if (senha.value.trim().length<6){
        erroElemento_msg(senha, "Campo senha deve conter no minimo 6 caractres.");
        return false;
    }else if(senha.value!==confirmacao.value){
        erroElemento_msg(confirmacao, "Campo confirmação da denha invalida.");
        return false;    
    } 
    
    _envia_formulario(formulario, Gravar_Retorno);
    return false;
};

Gravar_Retorno = (retorno) =>{  
    console.log(retorno);
    //alert(retorno);
    if ($.trim(retorno[0])==='1'){
        alert(retorno[1]);    
        window.location.href = retorno[2];
    }else{
        $('#msgs').find('.modal-body').html("<h4><b>" + retorno[1] + "</b></h4>"); 
        $('#msgs').modal();
    }
    return false;   
 };
 
 
CheckAcesso = (formulario, valor) => {
    if (!valor.checked) {
        $('#div-' + valor.id + 'incluir').addClass('disabled');
        $('#div-' + valor.id + 'alterar').addClass('disabled');
        $('#div-' + valor.id + 'excluir').addClass('disabled');
        $('#div-' + valor.id + 'consultar').addClass('disabled');
        $('#div-' + valor.id + 'gerar').addClass('disabled');
        $('#div-' + valor.id + 'visualizar').addClass('disabled');
        $('#div-' + valor.id + 'imprimir').addClass('disabled');
        
        
        $("#" + valor.id + 'incluir').prop('disabled', true);
        $("#" + valor.id + 'alterar').prop('disabled', true);
        $("#" + valor.id + 'excluir').prop('disabled', true);
        $("#" + valor.id + 'consultar').prop('disabled', true);
        $("#" + valor.id + 'gerar').prop('disabled', true);
        $("#" + valor.id + 'visualizar').prop('disabled', true);
        $("#" + valor.id + 'imprimir').prop('disabled', true);
        

        for (var i = 0; i < formulario.length; i++) {
            if ((formulario[i].tagName === 'INPUT') &&
                    (formulario[i].type === 'checkbox') &&
                    (formulario[i].name === valor.id + 'incluir')) {
                formulario[i].checked = valor.checked;
            } else if ((formulario[i].tagName === 'INPUT') &&
                    (formulario[i].type === 'checkbox') &&
                    (formulario[i].name === valor.id + 'alterar')) {
                formulario[i].checked = valor.checked;
            } else if ((formulario[i].tagName === 'INPUT') &&
                    (formulario[i].type === 'checkbox') &&
                    (formulario[i].name === valor.id + 'excluir')) {
                formulario[i].checked = valor.checked;
            } else if ((formulario[i].tagName === 'INPUT') &&
                    (formulario[i].type === 'checkbox') &&
                    (formulario[i].name === valor.id + 'consultar')) {
                formulario[i].checked = valor.checked;
            }else if ((formulario[i].tagName === 'INPUT') &&
                    (formulario[i].type === 'checkbox') &&
                    (formulario[i].name === valor.id + 'gerar')) {
                formulario[i].checked = valor.checked;
            }else if ((formulario[i].tagName === 'INPUT') &&
                    (formulario[i].type === 'checkbox') &&
                    (formulario[i].name === valor.id + 'imprimir')) {
                formulario[i].checked = valor.checked;
            }else if ((formulario[i].tagName === 'INPUT') &&
                    (formulario[i].type === 'checkbox') &&
                    (formulario[i].name === valor.id + 'visualizar')) {
                formulario[i].checked = valor.checked;
            }
        }
    } else {
        $("#" + valor.id + 'incluir').prop('disabled', false);
        $("#" + valor.id + 'alterar').prop('disabled', false);
        $("#" + valor.id + 'excluir').prop('disabled', false);
        $("#" + valor.id + 'consultar').prop('disabled', false);
        $("#" + valor.id + 'gerar').prop('disabled', false);
        $("#" + valor.id + 'visualizar').prop('disabled', false);
        $("#" + valor.id + 'imprimir').prop('disabled', false);
        
        
        $('#div-' + valor.id + 'incluir').removeClass('disabled');
        $('#div-' + valor.id + 'alterar').removeClass('disabled');
        $('#div-' + valor.id + 'excluir').removeClass('disabled');
        $('#div-' + valor.id + 'consultar').removeClass('disabled');
        $('#div-' + valor.id + 'gerar').removeClass('disabled');
        $('#div-' + valor.id + 'visualizar').removeClass('disabled');
        $('#div-' + valor.id + 'imprimir').removeClass('disabled');
    }
};


ConfirmaExcluir = (classe, codigo, msg) => {
    $('#MsnExcluir').find('.modal-body span').html("<h4>"+ msg +"</h4>");
    $('#MsnExcluir').find('#codigo').val(codigo);
    $('#MsnExcluir').find('#class').val(classe);
    $('#MsnExcluir').modal();
    return false;
};

Excluir = (formulario) =>{
    _envia_formulario(formulario, Excluir_Retorno);
    return false;
};

Excluir_Retorno = (retorno) =>{  
    console.log(retorno);
    //alert(retorno);
    if ($.trim(retorno[0])==='1'){
        alert(retorno[1]);    
        window.location.href = retorno[2];
    }else{
        $('#msgs').find('.modal-body').html("<h4><b>" + retorno[1] + "</b></h4>"); 
        $('#msgs').modal();
    }
    return false;   
 };
 
 
  
 Gera_Excel = () =>{
  var formulario = document.getElementById('formulario');
  var acao = document.getElementById('acao');
  const oldaction = formulario.action;
  const oldacao = acao.value;
  
  formulario.action = 'app';
  acao.value = 'excel';
  _envia_formulario(formulario, Gera_Excel_Retrono);
  formulario.action = oldaction;
  acao.value = oldacao;
  return false;  
};

Gera_Excel_Retrono = (retorno) =>{
    console.log(retorno);
    //alert(retorno);
    if ($.trim(retorno[0])==='1'){
        window.location.href = retorno[1];
    }else{
        $('#msgs').find('.modal-body').html("<h4><b>" + retorno[1] + "</b></h4>"); 
        $('#msgs').modal();
    }
    return false;  
};

$(document).ready(function () {
    $('[rel="openclose"]').click(function () {
        let id = $(this).attr("data-togle");
        let ret = $(this).attr("data-result");
        document.getElementById(id).classList.add((ret === 'open')?'d-none':'d-flex');
        document.getElementById(id).classList.remove((ret === 'open')?'d-flex':'d-none');
        $(this).attr("data-result",(ret === 'open')?'close':'open');
        $(this).html((ret === 'open')?'<i class="fas fa-angle-down"></i>':'<i class="fas fa-angle-up"></i>');
    });
    
});