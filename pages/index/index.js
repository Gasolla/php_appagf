function registra_acesso(elemento){
    for(var i=0;i<elemento.length;i++){
        if ((elemento[i].tagName==='INPUT')  &&
            (elemento[i].type==='password')  &&
            (elemento[i].name==='password')){
            var password = elemento[i]; 
            }
        else if ((elemento[i].tagName==='INPUT') && 
            (elemento[i].type==='text') && 
            (elemento[i].name==='usuario')){
            var usuario = elemento[i]; 
            }
        }
    if((!usuario) || (usuario.value==='')){
        $('#form-usuario').addClass('has-error');
        $('#usuario').focus();
        $('#msgs').find('.modal-body').html("<h4><b>Campo usúario não pode ser nulo!</b></h4>"); 
        $('#msgs').modal();
        return false;
    }else{
        $('#form-usuario').removeClass('has-error');
    }
   
    if((!password) || (password.value==='')){
        $('#form-password').addClass('has-error');
        $('#password').focus();
        $('#msgs').find('.modal-body').html("<h4><b>Campo senha não pode ser nulo!</b></h4>"); 
        $('#msgs').modal();
        return false;
    }else{
        $('#form-password').removeClass('has-error');}
   
    
    var p = CriaInput('p', password.value);  
    elemento.appendChild(p);
    password.value = "";    
    _envia_formulario(elemento, _registra_acesso);
    return false;
}		

function _registra_acesso(retorno){
    //alert(retorno);
    if (retorno[0]==='1'){
        $('#MsnInicial').find('.modal-body span').html('<strong><h3>'+ retorno[1] + '</h3></strong>');
        $('#MsnInicial').modal();
    }else{
        $('#form-password').addClass('is-invalid');
        $('#form-usuario').addClass('is-invalid');
        $('#password').focus();
        $('#msgs').find('.modal-body').html("<h4><b>" + retorno[1] + "</b></h4>"); 
        $('#msgs').modal();
    }
    return false;
            
}

