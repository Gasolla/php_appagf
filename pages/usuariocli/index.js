
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


Gravar = (formulario) => {
    const senha = document.getElementById('senha');
    const confirmacao = document.getElementById('confsenha');
    
    for (var i = 0; i < formulario.length; i++) {
        if ((formulario[i].name === 'nome') &&
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
