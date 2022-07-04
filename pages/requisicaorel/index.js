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


Visualizar_Imagem = (img) => {
    //Primeiramente vamos relacionar os elementos com as variáveis
    //<div> principal com id "janelaModal".
    var modal = document.getElementById("janelaModal");
    //tag <img> que irá receber a imagem clicada.
    var modalImg = document.getElementById("imgModal");
    //<div> que recebe o texto relacionado com a imagem, o texto está no alt de cada imagem
    var captionTexto = document.getElementById("txtImg");
    //tag <span> que contém a letra "x" que será usada para fechar a janela modal
    var btFechar = document.getElementsByClassName("fechar")[0];

    //Configura <div> em "block" para que fique visível, inicialmente no CSS este display é none
    modal.style.display = "block";
    //Capturamos o src da imagem clicada e passamos a tag <img> que tem id="imgModal"
    modalImg.src = img.src;
    //Capturamos o alt da imagem clicada e passamos a tag <img> que tem id="imgModal"
    modalImg.alt = img.alt;
    //Inserimos o texto do parâmetro alt da imagem dentro do <span> com id="txtImg"
    captionTexto.innerHTML = img.alt;



    //Adicionamos uma função do evento onclick do <span> que usa a classe CSS "fechar"
    btFechar.onclick = function () {
        //Para fechar a janela modal simplesmente configuramos seu display como none.
        modal.style.display = "none";
    }
}