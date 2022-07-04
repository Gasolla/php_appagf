function executa_grava(arquivo, formulario) {
    if (arquivo.value === '') {
        erroElemento_msg(arquivo, 'Arquivo não selecionado!');
        return false;
    }
    _envia_upload(formulario, formulario.action, executa_grava_retorno);
    return false;
}

executa_grava_retorno = (retorno) => {
    console.log(retorno);
    if ($.trim(retorno[0]) === "1") {
        $('#arq').val("");
        $('#arq').filestyle('clear');
        $('#MsnUpload').find('.modal-body').html(retorno[1]);
        $('#MsnUpload').modal();
    }else if ($.trim(retorno[0]) === "0") {
        $('#msgs').find('.modal-body').html(retorno[1]);
        $('#msgs').modal();
    } else {
        console.log(retorno);
        $('#msgs').find('.modal-body').html('Falha ao realizar o upload!');
        $('#msgs').modal();
    }
    return false;
};
