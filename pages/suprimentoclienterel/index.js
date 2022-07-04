/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


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
