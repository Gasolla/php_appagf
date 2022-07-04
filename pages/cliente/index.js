var geocoder;
var map;
var marker;
var lati;
var long;

Incia_Varial = (la, lo) =>{
    lati = la;
    long = lo;
};

ValidaEmail = (elemento) => {
    if ((elemento.value !== '') && (!IsEmail(elemento.value))) {
        elemento.value = "";
        $('#msgs').find('.modal-body').html("<h4><b>Email invalido!</b></h4>");
        $('#msgs').modal();
        $('#' + elemento.name).removeClass('is-valid');
        $('#' + elemento.name).addClass('is-invalid');
        elemento.value = elemento.value.trim();
        setTimeout(elemento.focus(), 1000);
        return false;
    } else if (elemento.value !== '') {
        $('#' + elemento.name).removeClass('is-invalid');
        $('#' + elemento.name).addClass('is-valid');
    }
    return true;
};

Gravar = (formulario) => {
    for (var i = 0; i < formulario.length; i++) {
        if ((formulario[i].name === 'nome') &&
                (formulario[i].value === '')) {
            erroElemento(formulario[i]);
            return false;
        } else if ((formulario[i].name === 'email') &&
                (formulario[i].value === '')) {
            erroElemento(formulario[i]);
            return false;
        } else if ((formulario[i].name === 'agencia') &&
                (formulario[i].value === '')) {
            erroElemento(formulario[i]);
            return false;
        } else if ((formulario[i].name === 'comercial') &&
                (formulario[i].value === '')) {
            erroElemento(formulario[i]);
            return false;
        }  else if ((formulario[i].name === 'cpfcnpj') &&
                (formulario[i].value === '')) {
            erroElemento_msg(formulario[i], 'Campo cpf/cnpj invalido');
            return false;
        } else if ((formulario[i].name === 'microvisual') &&
                (formulario[i].value === '')) {
            erroElemento_msg(formulario[i], 'Campo codigo microvisual invalido');
            return false;
        } else if ((formulario[i].name === 'telefone') &&
                (formulario[i].value === '')) {
            erroElemento(formulario[i]);
            return false;
        }else if ((formulario[i].name === 'cep') &&
                (formulario[i].value === '')) {
            erroElemento(formulario[i]);
            return false;
        }else if ((formulario[i].name === 'rua') &&
                (formulario[i].value === '')) {
            erroElemento(formulario[i]);
            return false;
        }else if ((formulario[i].name === 'numero') &&
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
        }else if ((formulario[i].name === 'UF') &&
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

Carrega_Mapa = () => {
    var latlng = new google.maps.LatLng(lati, long);
    var options = {
        zoom: 16,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    map = new google.maps.Map(document.getElementById("mapa"), options);

    geocoder = new google.maps.Geocoder();

    marker = new google.maps.Marker({
        map: map,
        draggable: true
    });
    marker.setPosition(latlng);
    google.maps.event.addListener(marker, 'drag', function () {
        geocoder.geocode({'latLng': marker.getPosition()}, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                    $('#txtEndereco').val(results[0].formatted_address);
                    $('#txtLatitude').val(marker.getPosition().lat());
                    $('#txtLongitude').val(marker.getPosition().lng());
                }
            }
        });
    });
};


Carrega_Mapa_Inicial = () => {
    var latlng = new google.maps.LatLng(-18.8800397, -47.05878999999999);
    var options = {
        zoom: 5,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    map = new google.maps.Map(document.getElementById("mapa"), options);

    geocoder = new google.maps.Geocoder();

    marker = new google.maps.Marker({
        map: map,
        draggable: true
    });
    marker.setPosition(latlng);
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) { // callback de sucesso
            // ajusta a posição do marker para a localização do usuário
            marker.setPosition(new google.maps.LatLng(position.coords.latitude, position.coords.longitude));
        },
                function (error) { // callback de erro
                    //alert('Erro ao obter localização!');
                    console.log('Erro ao obter localização.', error);
                });
    } else {
        alert('Navegador não suporta Geolocalização!');
    }

    google.maps.event.addListener(marker, 'drag', function () {
        geocoder.geocode({'latLng': marker.getPosition()}, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                    $('#txtEndereco').val(results[0].formatted_address);
                    $('#txtLatitude').val(marker.getPosition().lat());
                    $('#txtLongitude').val(marker.getPosition().lng());
                }
            }
        });
    });
};


Carregar_No_Mapa = (endereco) => {
    geocoder.geocode({'address': endereco + ', Brasil', 'region': 'BR'}, function (results, status) {
        if (status === google.maps.GeocoderStatus.OK) {
            if (results[0]) {
                var latitude = results[0].geometry.location.lat();
                var longitude = results[0].geometry.location.lng();
                let cidade = document.getElementById('cidade');
                let cep = document.getElementById('cep');
                let complemento = document.getElementById('complemento');
                let rua = document.getElementById('rua');
                let numero = document.getElementById('numero');
                let uf = document.getElementById('uf');
                let bairro = document.getElementById('bairro');
                let estado = document.getElementById('estado');
                
                //console.log("carregarNoMapa");
                console.log(results);
                for (var i = 0; i < results[0].address_components.length; i++) {
                    var addr = results[0].address_components[i];
                    if (addr.types[0] === 'administrative_area_level_2') {
                        cidade.value = addr.long_name;
                    } else if (addr.types[0] === 'street_number') {
                        numero.value = addr.long_name;
                    } else if ((addr.types[0] === 'route') || (addr.types[0] ==='establishment')) {
                        rua.value = addr.long_name;
                    } else if (addr.types[0] === 'political') {
                        bairro.value = addr.long_name;
                    } else if (addr.types[0] === 'postal_code') {
                        cep.value = addr.long_name;
                    } else if (addr.types[0] === 'administrative_area_level_1') {
                        uf.value = addr.short_name;
                        estado.value = addr.short_name;
                        $('#uf').trigger('change');
                    } else if (addr.types[0] === 'subpremise') {
                        complemento.value = addr.short_name;
                    }

                }

                $('#txtEndereco').val(results[0].formatted_address);
                $('#txtLatitude').val(latitude);
                $('#txtLongitude').val(longitude);

                var location = new google.maps.LatLng(latitude, longitude);
                marker.setPosition(location);
                map.setCenter(location);
                map.setZoom(16);
            }
        }
    });
};



$(document).ready(function () {

    $("#txtEndereco").blur(function () {
        if ($(this).val() !== "")
            Carregar_No_Mapa($(this).val());
    });

    $("#txtEndereco").autocomplete({
        source: function (request, response) {
            geocoder.geocode({'address': request.term + ', Brasil', 'region': 'BR'}, function (results, status) {
                response($.map(results, function (item) {
                    return {
                        label: item.formatted_address,
                        value: item.formatted_address,
                        latitude: item.geometry.location.lat(),
                        longitude: item.geometry.location.lng()
                    };
                }));
            });
        },
        select: function (event, ui) {
            $("#txtLatitude").val(ui.item.latitude);
            $("#txtLongitude").val(ui.item.longitude);
            var location = new google.maps.LatLng(ui.item.latitude, ui.item.longitude);
            marker.setPosition(location);
            map.setCenter(location);
            map.setZoom(16);
        }
    });

});