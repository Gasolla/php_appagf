var _AJAX_conexao = null;
var _AJAX_fila_requisicoes = [];
var _AJAX_ocupado = false;
var _AJAX_files = [];
var _AJAX_proteger = true;
var AJAX_requisicao = null;

function AJAX_inicializa() {
    if (typeof XMLHttpRequest !== "undefined") {
        _AJAX_conexao = new XMLHttpRequest();
    } else {
        if (window.ActiveXObject) {
            var versoes = ["MSXML2.XMLHttp.6.0",
                "MSXML2.XMLHttp.5.0",
                "MSXML2.XMLHttp.4.0",
                "MSXML2.XMLHttp.3.0",
                "MSXML2.XMLHttp.2.6",
                "MSXML2.XMLHttp",
                "Microsoft.XMLHTTP.1.0",
                "Microsoft.XMLHTTP.1",
                "Microsoft.XMLHTTP"];
            for (var i = 0; i < versoes.length; i++) {
                try {
                    _AJAX_conexao = new ActiveXObject(versoes[i]);
                } catch (e) {
                    _AJAX_conexao = null;
                }
            }
        }
    }
    if (_AJAX_conexao === null) {
        alert("Navegador sem suporte a tecnologia AJAX");
        _AJAX_conexao = null;
        return false;
    }

    return true;
}


function AJAX_nova_requisicao() {
    return {metodo: "GET",
        endereco: "",
        parametros: [],
        nome: "",
        senha: "",
        enctype: "",
        tipo_retorno: "TXT",
        funcao_antes_enviar: null,
        funcao_depois_receber: null};
}

AJAX_serializa_formulario = function (formulario, requisicao) {
    _AJAX_files = [];
    for (var i = 0; i < formulario.length; i++) {
        switch (formulario[i].tagName) {
            case "INPUT":
                switch (formulario[i].type) {
                    case "hidden":
                    case "password":
                    case "text":
                        requisicao.parametros.push([formulario[i].name, formulario[i].value]);
                        break;
                    case "radio":
                        if (formulario[i].checked) {
                            requisicao.parametros.push([formulario[i].name, formulario[i].value]);
                        }
                        break;
                    case "checkbox":
                        if (formulario[i].checked) {
                            requisicao.parametros.push([formulario[i].name, formulario[i].value]);
                        }
                        break;
                    case "file":
                        if ($.trim(formulario[i].value) !== "") {
                            //requisicao.parametros.push([formulario[i].name,formulario[i].files[0]]);
                            _AJAX_files.push([formulario[i].name, formulario[i].files[0]]);
                        }
                        break;
                }
                break;
            case "SELECT":
                switch (formulario[i].type) {
                    case "select-one":
                        if (formulario[i].selectedIndex > -1) {
                            requisicao.parametros.push([formulario[i].name, formulario[i].value]);
                        }
                        break;
                    case "select-multiple":
                        var ii = 0;
                        for (var option = 0; option < formulario[i].length; option++) {
                            if (formulario[i].options[option].selected) {
                                requisicao.parametros.push([formulario[i].name + "[" + ii + "]", formulario[i].options[option].value]);
                                ii++;
                            }
                        }
                        break;
                }
                break;
            case "TEXTAREA":
                requisicao.parametros.push([formulario[i].name, formulario[i].value]);
                break;
        }
    }
    return true;
}

function AJAX_adiciona_requisicao_fila(requisicao) {
    if (typeof (requisicao) !== "object") {
        alert("Item não é um objeto");
        return false;
    }

    requisicao.metodo = $.trim(requisicao.metodo.toString().toUpperCase());
    switch (requisicao.metodo) {
        case "GET":
        case "POST":
            break;
        default:
            alert("Método de envio não suportado");
            return false;
    }
    requisicao.endereco = $.trim(requisicao.endereco.toString());
    if (requisicao.endereco === "") {
        alert("Endereço de envio não definido");
        return false;
    }

    if ((typeof (requisicao.parametros) !== "undefined") && (typeof (requisicao.parametros) !== "object")) {
        alert("Parâmetros inconsistentes");
        return false;
    }

    if (requisicao.parametros.length > 0) {
        var aux = [];
        for (var i = 0; i < requisicao.parametros.length; i++) {
            if (($.trim(requisicao.parametros[i][0]) === "") || (requisicao.parametros[i].length !== 2)) {
                continue;
            }
            aux.push(new Array($.trim(requisicao.parametros[i][0]), escape(requisicao.parametros[i][1].toString()))); /* escape(requisicao.parametros[i][1].toString()).replace(/\+/g,"%2B").replace(/\//g,"%2F") */
        }
        requisicao.parametros = aux;
    }
    requisicao.tipo_retorno = $.trim(requisicao.tipo_retorno.toString().toUpperCase());
    switch (requisicao.tipo_retorno) {
        case "TXT":
        case "XML":
            break;
        default:
            alert("Tipo de retorno não suportado");
            return false;
    }
    if (typeof (requisicao.funcao_antes_enviar) !== "function") {
        requisicao.funcao_antes_enviar = null;
    }
    if (typeof (requisicao.funcao_depois_receber) !== "function") {
        requisicao.funcao_depois_receber = null;
    }

    _AJAX_fila_requisicoes.push(requisicao);
    return true;
}

function AJAX_executa_fila() {
    if (_AJAX_ocupado || (_AJAX_conexao === null) ||
            (_AJAX_fila_requisicoes.length === 0)) {
        fechaEspera();
        return false;
    }

    if (_AJAX_proteger) {
        abreEspera();
    } else {
        _AJAX_proteger = true;
    }

    _AJAX_ocupado = true;
    AJAX_requisicao = _AJAX_fila_requisicoes.shift();
    if (typeof (AJAX_requisicao.funcao_antes_enviar) === "function") {
        if (AJAX_requisicao.funcao_antes_enviar() === false) {
            for (var i = 0; i < _AJAX_fila_requisicoes.length; i++) {
                _AJAX_fila_requisicoes[i] = null;
            }

            _AJAX_fila_requisicoes = [];
            AJAX_requisicao = null;
            _AJAX_ocupado = false;
            fechaEspera();
            return false;
        }
    }

    var parametros = null;
    if (AJAX_requisicao.parametros.length > 0) {
        var aux = [];
        for (var i = 0; i < AJAX_requisicao.parametros.length; i++) {
            aux.push($.trim(AJAX_requisicao.parametros[i][0]) + "=" + AJAX_requisicao.parametros[i][1].toString());
        }
        parametros = aux.join("&");
        if ((AJAX_requisicao.metodo === "GET") && (parametros !== "")) {
            AJAX_requisicao.endereco = AJAX_requisicao.endereco + ((AJAX_requisicao.endereco.indexOf("?") === -1) ? ("?") : ("&")) + parametros;
            parametros = null;
        }
    }

    AJAX_requisicao.parametros = parametros;
    if ((AJAX_requisicao.tipo_retorno === "XML") && (_AJAX_conexao.overrideMimeType)) {
        _AJAX_conexao.overrideMimeType("text/xml");
    }
    if ((AJAX_requisicao.nome !== "") && (AJAX_requisicao.senha !== "")) {
        _AJAX_conexao.open(AJAX_requisicao.metodo, AJAX_requisicao.endereco, true, AJAX_requisicao.nome, AJAX_requisicao.senha);
    } else {
        _AJAX_conexao.open(AJAX_requisicao.metodo, AJAX_requisicao.endereco, true);
    }

    _AJAX_conexao.onreadystatechange = _AJAX_trata_retorno;
    if (AJAX_requisicao.metodo === "POST") {
        _AJAX_conexao.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        _AJAX_conexao.setRequestHeader("Method", "POST " + AJAX_requisicao.endereco + " HTTP/1.1");
    }

    //_AJAX_conexao.setRequestHeader("User-Agent",navigator.userAgent);
    //_AJAX_conexao.setRequestHeader("Referer",AJAX_requisicao.endereco);
    _AJAX_conexao.setRequestHeader("Cache-Control", "no-store, no-cache, must-revalidate");
    _AJAX_conexao.setRequestHeader("Cache-Control", "post-check=0, pre-check=0");
    _AJAX_conexao.setRequestHeader("Pragma", "no-cache");
    //if((AJAX_requisicao.metodo==="POST") && (typeof(AJAX_requisicao.parametros)=="string")){
    //	_AJAX_conexao.setRequestHeader("Content-length",AJAX_requisicao.parametros.length); }

    _AJAX_conexao.send(AJAX_requisicao.parametros);
    return true;
}

function _AJAX_trata_retorno() {
    if (_AJAX_conexao.readyState === 4) {
        if (_AJAX_conexao.status === 200) {
            if (typeof (AJAX_requisicao.funcao_depois_receber) === "function") {
                if (AJAX_requisicao.tipo_retorno === "XML") {
                    var retorno = _AJAX_conexao.responseXML;
                } else {
                    var retorno = _AJAX_conexao.responseText;
                    if (retorno.substr(0, 4) === "ajax") {
                        retorno = unescape(retorno).split("\nn");
                        if (retorno[0].substr(0, 8) === "ajax_htm") {
                            retorno[retorno.length - 1] = retorno[retorno.length - 1].replace(/\<xbr\>/gi, "\nn");
                        }

                        retorno.shift();
                    }
                }

                if (AJAX_requisicao.funcao_depois_receber(retorno) === false) {
                    for (var i = 0; i < _AJAX_fila_requisicoes.length; i++) {
                        _AJAX_fila_requisicoes[i] = null;
                    }

                    _AJAX_fila_requisicoes = [];
                    AJAX_requisicao = null;
                    _AJAX_ocupado = false;
                    fechaEspera();
                    return false;
                }
            }

            AJAX_requisicao = null;
            _AJAX_ocupado = false;
            if (_AJAX_fila_requisicoes.length > 0) {
                setTimeout(AJAX_executa_fila, 100);
            }
        } else {
            alert("Erro " + _AJAX_conexao.status);
            fechaEspera();
        }
    }
    return true;
}


function fileUpload(form, action_url, div_id) {
    // Create the iframe...
    var iframe = document.createElement("iframe");
    iframe.setAttribute("id", "upload_iframe");
    iframe.setAttribute("name", "upload_iframe");
    iframe.setAttribute("width", "0");
    iframe.setAttribute("height", "0");
    iframe.setAttribute("border", "0");
    iframe.setAttribute("style", "width: 0; height: 0; border: none;");

    // Add to document...
    form.parentNode.appendChild(iframe);
    window.frames['upload_iframe'].name = "upload_iframe";

    iframeId = document.getElementById("upload_iframe");

    // Add event...
    var eventHandler = function () {
        if (iframeId.detachEvent)
            iframeId.detachEvent("onload", eventHandler);
        else
            iframeId.removeEventListener("load", eventHandler, false);

        // Message from server...
        if (iframeId.contentDocument) {
            content = iframeId.contentDocument.body.innerHTML;
        } else if (iframeId.contentWindow) {
            content = iframeId.contentWindow.document.body.innerHTML;
        } else if (iframeId.document) {
            content = iframeId.document.body.innerHTML;
        }

        fechaEspera();
        content = content.split("nn");
        if ((content[0] === '1') && (div_id === 'Reposta')) {
            document.getElementById(div_id).style.color = "#136B05";
            document.getElementById(div_id).style.backgroundColor = "transparent";
            var div = "alert alert-success";
        } else if (div_id === 'Reposta') {
            document.getElementById(div_id).style.color = "#DD021F";
            document.getElementById(div_id).style.backgroundColor = "transparent";
            var div = "alert alert-danger";
        }

        if (div_id === 'Reposta') {
            document.getElementById(div_id).innerHTML = "<div class='" + div + "'>" + content[1] + "</div>";
            ;
        } else {
            document.getElementById(div_id).innerHTML = content;
        }

        // Del the iframe...
        setTimeout('iframeId.parentNode.removeChild(iframeId)', 1000);
    };

    if (iframeId.addEventListener)
        iframeId.addEventListener("load", eventHandler, true);
    if (iframeId.attachEvent)
        iframeId.attachEvent("onload", eventHandler);

    // Set properties of form...
    form.setAttribute("target", "upload_iframe");
    form.setAttribute("action", action_url);
    form.setAttribute("method", "post");
    form.setAttribute("enctype", "multipart/form-data");
    form.setAttribute("encoding", "multipart/form-data");


    // Submit the form...
    abreEspera();
    form.submit();
    document.getElementById(div_id).innerHTML = "Carregando...........<img src='image/ajax-loader.gif' id='loading-img' alt='Aguarde'/>";
}


function _envia_upload(form, url, funcao) {
    // Create the iframe...
    var iframe = document.createElement("iframe");
    iframe.setAttribute("id", "upload_iframe");
    iframe.setAttribute("name", "upload_iframe");
    iframe.setAttribute("width", "0");
    iframe.setAttribute("height", "0");
    iframe.setAttribute("border", "0");
    iframe.setAttribute("style", "width: 0; height: 0; border: none;");

    // Add to document...
    form.parentNode.appendChild(iframe);
    window.frames['upload_iframe'].name = "upload_iframe";

    iframeId = document.getElementById("upload_iframe");

    // Add event...
    var eventHandler = function () {
        if (iframeId.detachEvent)
            iframeId.detachEvent("onload", eventHandler);
        else
            iframeId.removeEventListener("load", eventHandler, false);

        // Message from server...
        if (iframeId.contentDocument) {
            content = iframeId.contentDocument.body.innerHTML;
        } else if (iframeId.contentWindow) {
            content = iframeId.contentWindow.document.body.innerHTML;
        } else if (iframeId.document) {
            content = iframeId.document.body.innerHTML;
        }

        fechaEspera();
        //content = content.split("\nn");
        if (content.substr(0, 4) === "ajax") {
            content = unescape(content).split("\nn");
            if (content[0].substr(0, 8) === "ajax_htm") {
                content[content.length - 1] = content[content.length - 1].replace(/\<xbr\>/gi, "\nn");
            }

            content.shift();

        }
        funcao(content);
        // Del the iframe...
        setTimeout('iframeId.parentNode.removeChild(iframeId)', 1000);
    };

    if (iframeId.addEventListener)
        iframeId.addEventListener("load", eventHandler, true);
    if (iframeId.attachEvent)
        iframeId.attachEvent("onload", eventHandler);

// Set properties of form...
    form.setAttribute("target", "upload_iframe");
    form.setAttribute("action", url);
    form.setAttribute("method", "post");
    form.setAttribute("enctype", "multipart/form-data");
    form.setAttribute("encoding", "multipart/form-data");


// Submit the form...
    abreEspera();
    form.submit();
}


AJAX_inicializa();