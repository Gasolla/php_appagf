
Gravar = (formulario) => {
    for (var i = 0; i < formulario.length; i++) {
        if ((formulario[i].name === 'cliente') &&
                (formulario[i].value === '')) {
            erroElemento(formulario[i]);
            return false;
        } else if ((formulario[i].name === 'agencia') &&
                (formulario[i].value === '')) {
            erroElemento(formulario[i]);
            return false;
        } else if ((formulario[i].name === 'usuario') &&
                (formulario[i].value === '')) {
            erroElemento_msg(formulario[i], "Motorista não foi selecionado");
            return false;
        } else if ((formulario[i].name === 'data') &&
                (formulario[i].value === '')) {
            erroElemento(formulario[i]);
            return false;
        }
    }

    _envia_formulario(formulario, Gravar_Retorno);
    return false;
};

Gravar_Retorno = (retorno) => {
    console.log(retorno);
    //alert(retorno);
    if ($.trim(retorno[0]) === '1') {
        alert(retorno[1] + '\n' + retorno[2]);
        window.location.href = retorno[3];
    } else {
        $('#msgs').find('.modal-body').html("<h4><b>" + retorno[1] + "</b></h4>");
        $('#msgs').modal();
    }
    return false;
};

ConfirmaExcluir = (classe, codigo, msg) => {
    $('#MsnExcluir').find('.modal-body span').html("<h4>" + msg + "</h4>");
    $('#MsnExcluir').find('#codigo').val(codigo);
    $('#MsnExcluir').find('#class').val(classe);
    $('#MsnExcluir').modal();
    return false;
};

Excluir = (formulario) => {
    _envia_formulario(formulario, Excluir_Retorno);
    return false;
};

Excluir_Retorno = (retorno) => {
    console.log(retorno);
    //alert(retorno);
    if ($.trim(retorno[0]) === '1') {
        alert(retorno[1]);
        window.location.href = retorno[2];
    } else {
        $('#msgs').find('.modal-body').html("<h4><b>" + retorno[1] + "</b></h4>");
        $('#msgs').modal();
    }
    return false;
};


Gera_Excel = () => {
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

Gera_Excel_Retrono = (retorno) => {
    console.log(retorno);
    //alert(retorno);
    if ($.trim(retorno[0]) === '1') {
        window.location.href = retorno[1];
    } else {
        $('#msgs').find('.modal-body').html("<h4><b>" + retorno[1] + "</b></h4>");
        $('#msgs').modal();
    }
    return false;
};

