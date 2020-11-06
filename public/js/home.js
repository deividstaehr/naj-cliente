const NajApi  = new Naj();

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

    //Evento do click no bloco FINANCEIRO RECEBER
    $('#content-financeiro-receber').on('click', function() {
        window.location.href = `${baseURL}financeiro/index/receber`;
    });

    //Evento do click no bloco FINANCEIRO PAGAR
    $('#content-financeiro-pagar').on('click', function() {
        window.location.href = `${baseURL}financeiro/index/pagar`;
    });

    let nomeEmpresa = sessionStorage.getItem('@NAJ_CLIENTE/nomeEmpresa');

    if(nomeEmpresa) {
        $('#nomeEmpresa')[0].innerHTML = `${nomeEmpresa}`;
    }

    loadContainerMensagens();
    loadContainerAtividade();
    loadContainerProcesso();
    loadContainerFinanceiro();
});

async function loadContainerMensagens() {
    let resultMessages = await NajApi.getData(`mensagens/indicador`);

    if(resultMessages.sem_chat) {
        $('#qtde_mensagens_novas')[0].innerHTML = `0`;
        $('#qtde_mensagens_todas')[0].innerHTML = `0`;
        return;
    }

    if(resultMessages.todas[0] && resultMessages.novas[0]) {
        if(resultMessages.novas[0].qtde_novas > 0) {
            $('#qtde_mensagens_novas')[0].innerHTML = `
                ${resultMessages.novas[0].qtde_novas}
                <div class="notify" style="top: -15px !important;">
                    <span class="heartbit"></span>
                    <span class="point"></span>
                </div>
            `;
        } else {
            $('#qtde_mensagens_novas')[0].innerHTML = `${resultMessages.novas[0].qtde_novas}`;
        }
        
        $('#qtde_mensagens_todas')[0].innerHTML = `${resultMessages.todas[0].todas}`;
    }
}

async function loadContainerAtividade() {
    let parametrosAtividade = {
        'data_inicial': getDateProperties(new Date(new Date().getTime() - (30 * 86400000))).fullDate,
        'data_final'  : getDateProperties(new Date()).fullDate,
        'id_usuario'  : idUsuarioLogado
    };

    let resultAtividade = await NajApi.getData(`atividades/indicador/${btoa(JSON.stringify(parametrosAtividade))}?XDEBUG_SESSION_START`);

    if(resultAtividade.todas[0] && resultAtividade.trinta_dias[0]) {
        $('#qtde_atividade_trinta_dias')[0].innerHTML = `${resultAtividade.trinta_dias[0].qtde_30_dias}`;
        $('#qtde_atividade_todas')[0].innerHTML = `${resultAtividade.todas[0].todas}`;
    }
}

async function loadContainerProcesso() {
    let resultProcesso = await NajApi.getData(`processos/indicador`);

    if(resultProcesso.data[0]) {
        $('#qtde_processo_ativos')[0].innerHTML = `${resultProcesso.data[0].QTDE}`;
        $('#qtde_processo_baixado')[0].innerHTML = `${resultProcesso.data[1].QTDE}`;
    }
}

async function loadContainerFinanceiro() {
    let resultFinanceiro = await NajApi.getData(`financeiro/indicador`);

    if(resultFinanceiro.pagar[0] && resultFinanceiro.receber[0]) {
        $('#qtde_pagar_pago')[0].innerHTML = `${formatter.format(resultFinanceiro.pagar[0].TOTAL_PAGO)}`;
        $('#qtde_pagar_aberto')[0].innerHTML = `${formatter.format(resultFinanceiro.pagar[0].TOTAL_EM_ABERTO)}`;
        $('#qtde_receber_recebido')[0].innerHTML = `${formatter.format(resultFinanceiro.receber[0].TOTAL_PAGO)}`;
        $('#qtde_receber_aberto')[0].innerHTML = `${formatter.format(resultFinanceiro.receber[0].TOTAL_EM_ABERTO)}`;
    }
}

function onClickExibirModalLogo() {
    $('#previews')[0].innerHTML = '';
    $('#modal-upload-logo-empresa').modal('show');
}

async function onClickSendLogo() {
    if(myDropzone.files.length == 0) {
        NajAlert.toastWarning('Você deve selecionar uma imagem para enviar!');
        return;
    }

    debugger;
    if(myDropzone.files[0].type.split('/')[1] != 'png') {
        NajAlert.toastWarning('Você deve selecionar uma imagem do tipo PNG!');
        return;
    }

    let parseFile = await toBase64(myDropzone.files[0]);

    let result = await NajApi.postData(`empresas/logo?XDEBUG_SESSION_START`, {'file': parseFile});

    if(result) {
        NajAlert.toastSuccess('Logo alterada com sucesso!');
        $('#previews')[0].innerHTML = '';
        $('#modal-upload-logo-empresa').modal('hide');
        window.location.href = window.location.href;
    }
}

function toBase64(file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();

        reader.readAsDataURL(file);
        reader.onload = () => resolve(reader.result),
        reader.onerror = error => reject(error)
    });
}