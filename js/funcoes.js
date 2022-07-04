function sleep(milliseconds) {
    var start = new Date().getTime();
    for (var i = 0; i < 1e7; i++) {
        if ((new Date().getTime() - start) > milliseconds) {
            break;
        }
    }
}

function abreEspera() {
    var maskHeight = $(document).height();
    var maskWidth = $(window).width();
    $('#espera').css({'width': maskWidth, 'height': maskHeight});
    $('#espera').fadeIn(1000);
    $('#espera').fadeTo("slow", 0.8);
    var winH = $(window).height();
    var winW = $(window).width();
    $('#dialogo').css('top', winH / 2 - $('#dialogo').height() / 2);
    $('#dialogo').css('left', winW / 2 - $('#dialogo').width() / 2);
    $('#dialogo').fadeIn(2000);
}

function fechaEspera() {
    $('#espera').hide();
    $('.window').hide();
}

function _envia_parametro(parametro, url, funcao) {
    if (!AJAX_inicializa()) {
        return false;
    }
    var requisicao = AJAX_nova_requisicao();
    requisicao.metodo = 'POST';
    requisicao.endereco = url;
    requisicao.parametros = parametro;
    requisicao.funcao_depois_receber = funcao;
    if (!AJAX_adiciona_requisicao_fila(requisicao)) {
        return false;
    }
    if (!AJAX_executa_fila()) {
        return false;
    }
    return false;
}

function _envia_formulario(elemento, funcao_retorno) {
    if (!AJAX_inicializa()) {
        return false;
    }
    var requisicao = AJAX_nova_requisicao();
    requisicao.metodo = elemento.method.toUpperCase();
    requisicao.endereco = elemento.action;
    requisicao.funcao_depois_receber = funcao_retorno;
    AJAX_serializa_formulario(elemento, requisicao);
    if (!AJAX_adiciona_requisicao_fila(requisicao)) {
        return false;
    }
    if (!AJAX_executa_fila()) {
        return false;
    }
    return false;
}

function CriaInput(name, val) {
    var inp;
    try {
        inp = document.createElement('<input type="hidden" name="' + name + '" />');
    } catch (e) {
        inp = document.createElement("input");
        inp.type = "hidden";
        inp.name = name;
        inp.id = name;
    }
    inp.value = val;
    return inp;
}

function removeElement(elementId) {
    // Removes an element from the document
    var element = document.getElementById(elementId);
    element.parentNode.removeChild(element);
}

function erroElemento(elemento) {
    //alert('Campos ' + elemento.name + ' não pode ser nulo!');
    $('#' + elemento.name).removeClass('is-valid');
    $('#' + elemento.name).addClass('is-invalid');
    $('#' + elemento.name).focus();
    $('#msgs').find('.modal-body').html("<h4><b>" + 'Campos ' + elemento.name + ' não pode ser nulo!' + "</b></h4>");
    $('#msgs').modal();
}

function erroElemento_msg(elemento, msg) {
    //alert('Campos ' + elemento.name + ' não pode ser nulo!');
    $('#' + elemento.name).removeClass('is-valid');
    $('#' + elemento.name).addClass('is-invalid');
    $('#' + elemento.name).focus();
    $('#msgs').find('.modal-body').html("<h4><b>" + msg + "</b></h4>");
    $('#msgs').modal();
}


function sucessoElemento(elemento) {
    $('#' + elemento.name).removeClass('is-invalid');
    $('#' + elemento.name).addClass('is-valid');
}


function IsEmail(email) {
    var exclude = /[^@\-\.\w]|^[_@\.\-]|[\._\-]{2}|[@\.]{2}|(@)[^@]*\1/;
    var check = /@[\w\-]+\./;
    var checkend = /\.[a-zA-Z]{2,3}$/;
    if (((email.search(exclude) != -1) || (email.search(check)) == -1) || (email.search(checkend) == -1)) {
        return false;
    } else {
        return true;
    }
}

$(document).ready(function () {
    $('[rel="select2"]').select2({placeholder: 'Selecione a opção'});
    $('[rel="filestyle"]').filestyle({icon: true, buttonText: " Arquivo", buttonName: "btn-success", placeholder: "Selecione o arquivo!"});
    $('[data-toggle="tooltip"]').tooltip();
    $("input[rel*='telefone']").inputmask({
        mask: ['(99)9999-9999', '(99)99999-9999'],
        keepStatic: true
    });
    $("input[id*='cpfcnpj']").inputmask({
        mask: ['999.999.999-99', '99.999.999/9999-99'],
        keepStatic: true
    });
});

function OnlyNumber(evt) {
    //console.log('aqui');
    var theEvent = evt || window.event;
    var key = theEvent.keyCode || theEvent.which;
    key = String.fromCharCode(key);
    //var regex = /^[0-9.,]+$/;
    var regex = /^[0-9.]+$/;
    if (!regex.test(key)) {
        theEvent.returnValue = false;
        if (theEvent.preventDefault)
            theEvent.preventDefault();
    }

}

function onChangeAgencia(url, agencia) {
    window.location.href = url + "&agencia=" + agencia.value;
    return false;
}

function onChangeCliente(url, cliente) {
    window.location.href = url + "&cliente=" + cliente.value;
    return false;
}

function onBlurcpfcnpj(val) {
    if ((val.value !== "") && (!validaCpfCnpj(val.value))) {
        val.value = '';
        erroElemento_msg(val, 'Campo cpf/cnpj invalido');
        return false;
    }
}

function validaCpfCnpj(val) {
    if (val.length === 14) {
        var cpf = val.trim();

        cpf = cpf.replace(/\./g, '');
        cpf = cpf.replace('-', '');
        cpf = cpf.split('');

        var v1 = 0;
        var v2 = 0;
        var aux = false;

        for (var i = 1; cpf.length > i; i++) {
            if (cpf[i - 1] !== cpf[i]) {
                aux = true;
            }
        }

        if (aux === false) {
            return false;
        }

        for (var i = 0, p = 10; (cpf.length - 2) > i; i++, p--) {
            v1 += cpf[i] * p;
        }

        v1 = ((v1 * 10) % 11);

        if (v1 === 10) {
            v1 = 0;
        }

        if (v1 != cpf[9]) {
            return false;
        }

        for (var i = 0, p = 11; (cpf.length - 1) > i; i++, p--) {
            v2 += cpf[i] * p;
        }

        v2 = ((v2 * 10) % 11);

        if (v2 === 10) {
            v2 = 0;
        }

        if (v2 != cpf[10]) {
            return false;
        } else {
            return true;
        }
    } else if (val.length === 18) {
        var cnpj = val.trim();

        cnpj = cnpj.replace(/\./g, '');
        cnpj = cnpj.replace('-', '');
        cnpj = cnpj.replace('/', '');
        cnpj = cnpj.split('');

        var v1 = 0;
        var v2 = 0;
        var aux = false;

        for (var i = 1; cnpj.length > i; i++) {
            if (cnpj[i - 1] !== cnpj[i]) {
                aux = true;
            }
        }

        if (aux === false) {
            return false;
        }

        for (var i = 0, p1 = 5, p2 = 13; (cnpj.length - 2) > i; i++, p1--, p2--) {
            if (p1 >= 2) {
                v1 += cnpj[i] * p1;
            } else {
                v1 += cnpj[i] * p2;
            }
        }

        v1 = (v1 % 11);

        if (v1 < 2) {
            v1 = 0;
        } else {
            v1 = (11 - v1);
        }

        if (v1 != cnpj[12]) {
            return false;
        }

        for (var i = 0, p1 = 6, p2 = 14; (cnpj.length - 1) > i; i++, p1--, p2--) {
            if (p1 >= 2) {
                v2 += cnpj[i] * p1;
            } else {
                v2 += cnpj[i] * p2;
            }
        }

        v2 = (v2 % 11);

        if (v2 < 2) {
            v2 = 0;
        } else {
            v2 = (11 - v2);
        }

        if (v2 != cnpj[13]) {
            return false;
        } else {
            return true;
        }
    } else {
        return false;
    }
}


function onblurEmail(elemento) {
    if ((elemento.value !== '') && (!IsEmail(elemento.value))) {
        elemento.value = "";
        $('#' + elemento.name).removeClass('is-valid');
        $('#' + elemento.name).addClass('is-invalid');
        $('#' + elemento.name).focus();
        $('#msgs').find('.modal-body').html("<h4><b>" + 'Email invalido!' + "</b></h4>");
        $('#msgs').modal();
        return false;
    } else if (elemento.value !== '') {
        $('#' + elemento.name).removeClass('is-invalid');
    }
    return true;
}