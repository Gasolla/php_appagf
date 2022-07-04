clickCheck = (formulario, elemento) => {
    for (var i = 0; i < formulario.length; i++) {
        if ((formulario[i].tagName === 'INPUT') &&
                (formulario[i].type === 'checkbox') &&
                (formulario[i].name === elemento.name + '[]') &&
                (formulario[i].disabled === false)) {
            formulario[i].checked = elemento.checked;
        }
    }
    return true;
};


Imprimir = () => {
    var formulario = document.getElementById('formulario');
    var acao = document.getElementById('acao');
    var impressao = document.getElementsByName('impresso[]');
    var imprimir = document.getElementsByName('imprimir[]');
    const oldaction = formulario.action;
    const oldacao = acao.value;
    console.log(impressao);
    var enviar = true;
    var reimprimir = false;
    for (var i = 0; i < imprimir.length; i++) {
        if ((imprimir[i].tagName === 'INPUT') &&
                (imprimir[i].type === 'checkbox') &&
                (imprimir[i].name ===  'imprimir[]') &&
                (imprimir[i].checked === true)) {
            enviar = false;
            if (impressao[i].value==="F"){
                reimprimir = true;
            }
        }
    }
    
    if (enviar){
        alert("Nenhum registro selecionado!\nPor favor marcar o registro no Grid!");
        return false;
    }
    
    if (reimprimir){
        if (!confirm('Você selecionou resgistro que ja foi impresso!\nDeseja continuar?')){
            return false;
        }
    }
    formulario.action = 'app';
    acao.value = 'imprimir';
    _envia_formulario(formulario, Imprimir_Retorno);
    formulario.action = oldaction;
    acao.value = oldacao;
    return false;  
};


Solicitar = () => {
    var formulario = document.getElementById('formulario');
    var acao = document.getElementById('acao');
    const oldaction = formulario.action;
    const oldacao = acao.value;
  
    var enviar = true;
    for (var i = 0; i < formulario.length; i++) {
        if ((formulario[i].tagName === 'INPUT') &&
                (formulario[i].type === 'checkbox') &&
                (formulario[i].name ===  'agendar[]') &&
                (formulario[i].checked === true)) {
            enviar = false;
        }
    }
    
    if (enviar){
        alert("Nenhum registro selecionado!\nPor favor marcar o registro no Grid!");
        return false;
    }
    
    formulario.action = 'app';
    acao.value = 'solicitar';
    _envia_formulario(formulario, Solicitar_Retorno);
    formulario.action = oldaction;
    acao.value = oldacao;
    return false;  
};


Imprimirpost = (valor, pagina) => {
    var imprimiu = document.getElementById('imprimiu');
    if (imprimiu.value==="F"){
        if (!confirm('Este resgistro ja foi impresso!\nDeseja continuar?')){
            return false;
        }
    }
    let params = [
        ['class', pagina], 
        ['acao', 'imprimir'], 
        ['protocolo', valor]
    ];
    
    _envia_parametro(params, 'app',Imprimirpost_Retorno);
    return false;  
};

Solicitarpost = (valor, pagina) => {
    
    let params = [
        ['class', pagina], 
        ['acao', 'solicitar'], 
        ['protocolo', valor]
    ];
    
    _envia_parametro(params, 'app', Solicitarpost_Retorno);
    return false;  
};

Solicitar_Retorno = (retorno) =>{
    console.log(retorno);
    if ($.trim(retorno[0]) === '1') {
        alert(retorno[1]);
        window.location.reload();
    } else {
        $('#msgs').find('.modal-body').html("<h4><b>" + retorno[1] + "</b></h4>");
        $('#msgs').modal();
    }
    return false;
};

Imprimir_Retorno = async (retorno) =>{
    console.log(retorno);
    if ($.trim(retorno[0]) === '1') {
        window.open(retorno[1]);
        setTimeout(function () {window.location.reload();}, 10000);
    } else {
        $('#msgs').find('.modal-body').html("<h4><b>" + retorno[1] + "</b></h4>");
        $('#msgs').modal();
    }
    return false;
};


Solicitarpost_Retorno = (retorno) =>{
    console.log(retorno);
    sleep(500);
    if ($.trim(retorno[0]) === '1') {
        alert(retorno[1]);
        $("#btnsolicitar").attr('disabled', true); 
        $("#btnsolicitar"+retorno[3]).addClass('disabled');
        $("#divsolicitar"+retorno[3]).addClass('disabled');
        $("#checksolicitar"+retorno[3]).attr('disabled', true); 
        $("#checksolicitar"+retorno[3]).attr('checked', false); 
    } else {
        alert(retorno[1]);
    }
    return false;
};

Imprimirpost_Retorno = (retorno) =>{
    console.log(retorno);
    sleep(500);
    if ($.trim(retorno[0]) === '1') {
        var imprimiu = document.getElementById('imprimiu');
        imprimiu.value = 'F';
        $("#btnsolicitar").attr('disabled', ((retorno[2]==="true")?true:false)); 
        //$("#btnsolicitar"+retorno[3]).addClass('disabled');
        $("#divsolicitar"+retorno[3]).removeClass('disabled');
        $("#checksolicitar"+retorno[3]).attr('disabled', (retorno[2]==="true"?true:false)); 
        $("#checksolicitar"+retorno[3]).attr('checked', false);
        window.open(retorno[1]);
    } else {
        alert(retorno[1]);
    }
    return false;
};


visualizar = (codigo, pagina) =>{
    let params = [
        ['class', pagina], 
        ['acao', 'visualizar'], 
        ['protocolo', codigo]
    ];
    
    _envia_parametro(params, 'app', visualizar_retorno);
};

visualizar_retorno = (retorno) =>{
     //alert(retorno);
    console.log(retorno);
    if ($.trim(retorno[0]) === '1') {
        $('#MsnVisualizar').find('.modal-body').html(retorno[1]); 
        $('#MsnVisualizar').modal();
    } else {
        $('#msgs').find('.modal-body').html(retorno[1]); 
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

Gravar = (formulario) => {
    for (var i = 0; i < formulario.length; i++) {
        if ((formulario[i].name === 'nome') &&
                (formulario[i].value === '')) {
            erroElemento(formulario[i]);
            return false;
        }else if ((formulario[i].name === 'cep') &&
                (formulario[i].value === '')) {
            erroElemento(formulario[i]);
            return false;
        }else if ((formulario[i].name === 'endereco') &&
                (formulario[i].value === '')) {
            erroElemento(formulario[i]);
            return false;
        }else if ((formulario[i].name === 'bairro') &&
                (formulario[i].value === '')) {
            erroElemento(formulario[i]);
            return false;
        }else if ((formulario[i].name === 'cidade') &&
                (formulario[i].value === '')) {
            erroElemento(formulario[i]);
            return false;
        }else if ((formulario[i].name === 'numero') &&
                (formulario[i].value === '')) {
            erroElemento(formulario[i]);
            return false;
        }else if ((formulario[i].name === 'uf') &&
                (formulario[i].value === '')) {
            erroElemento(formulario[i]);
            return false;
        }else if ((formulario[i].name === 'servico') &&
                (formulario[i].value === '')) {
            erroElemento(formulario[i]);
            return false;
        }else if ((formulario[i].name === 'cartao') &&
                (formulario[i].value === '')) {
            erroElemento(formulario[i]);
            return false;
        }else if ((formulario[i].name === 'cliente') &&
                (formulario[i].value === '')) {
            erroElemento(formulario[i]);
            return false;
        }else if ((formulario[i].name === 'agencia') &&
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
        alert(retorno[1]);
        window.location.href = retorno[2];
    } else {
        $('#msgs').find('.modal-body').html("<h4><b>" + retorno[1] + "</b></h4>");
        $('#msgs').modal();
    }
    return false;
};
