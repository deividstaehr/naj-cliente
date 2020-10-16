$(document).ready(function() {
    
    //Evento do click no bloco MINHAS MENSAGENS
    $('#content-minhas-mensagens').on('click', function() {
        window.location.href = `${baseURL}mensagens`;
    });

    //Evento do click no bloco MEUS PROCESSOS
    $('#content-meus-processos').on('click', function() {
        window.location.href = `${baseURL}processos`;
    });

    //Evento do click no bloco ATIVIDADES
    $('#content-atividades').on('click', function() {
        window.location.href = `${baseURL}atividades`;
    });

    let nomeEmpresa = sessionStorage.getItem('@NAJ_CLIENTE/nomeEmpresa');

    if(nomeEmpresa) {
        $('#nomeEmpresa')[0].innerHTML = `${nomeEmpresa}`;
    }
});