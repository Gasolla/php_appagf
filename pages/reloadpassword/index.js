executa_grava = (formulario) => {
    for (var i = 0; i < formulario.length; i++) {
        if ((formulario[i].name === 'email') &&
            (formulario[i].value === '')) {
            erroElement(formulario[i]);
            return false;
        } 
    }
    
    _envia_formulario(formulario, executa_grava_retorno);
    return false;
};

executa_grava_retorno = (retorno) => {
    console.log(retorno);
    if ($.trim(retorno[0]) === '1') {
        $('#MsnCodigoVerificacao').find('#email').val(retorno[1]);
        $('#MsnCodigoVerificacao').find('#id').val(retorno[2]);
        $('#MsnCodigoVerificacao').modal();
    } else {
        $('#resposta').removeClass("alert alert-success");
        $('#resposta').addClass("alert alert-danger");
        $('#resposta').html(retorno[1]);
        alert(retorno[1]);
    }
    return false;

};


excecuta_alterar = (elemento) =>{
    //alert(parseInt(elemento.value, 10));
    if (elemento.value===""){
        alert('Código invalido!');
        elemento.focus();
        return false;
    }else if (isNaN(parseInt(elemento.value, 10))){
        alert('Código invalido!');
        elemento.focus();
        return false;    
    }else if (parseInt(elemento.value, 10)<100000){
        alert('Código invalido!');
        elemento.focus();
        return false;
    }else if (parseInt(elemento.value, 10)>999999){
        alert('Código invalido!');
        elemento.focus();
        return false;
    }
    
    return true;
};




