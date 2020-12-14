const NajApi  = new Naj();
const container = document.getElementById('content-minhas-mensagens');
let taRodando = false;
let hasInfo   = false;

$(document).ready(function() {

    //Atualizando a data de acesso ao sistema
    updateDataAcessoSistema();
    
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

    loadContainers();

    setInterval(() => {
        //Somente se não tiver info é para animar
        if(!hasInfo) {
            container.setAttribute('animacao', taRodando ? 'animacao-qualquer' : '' );
            taRodando = !taRodando;
        }
    }, 3000);
});

async function loadContainers() {
    await loadContainerMensagens();
    await loadContainerAtividade();
    await loadContainerProcesso();
    await loadContainerFinanceiro();
}

async function loadContainerMensagens() {
    let resultMessages = await NajApi.getData(`mensagens/indicador`);

    if(resultMessages.sem_chat) {
        $('#qtde_mensagens_novas')[0].innerHTML = `0`;
        $('#qtde_mensagens_todas')[0].innerHTML = `0`;

        return false;
    }

    if(!resultMessages.todas[0]) {
        $('#qtde_mensagens_todas')[0].innerHTML = `0`;
    }

    if(!resultMessages.novas) {
        $('#qtde_mensagens_novas')[0].innerHTML = `0`;
    }

    if(resultMessages.todas[0]) {
        $('#qtde_mensagens_todas')[0].innerHTML = `${resultMessages.todas[0].todas}`;

        if(resultMessages.todas[0].todas > 0) {
            $('#content-minhas-mensagens').removeClass('pulse-naj');
            hasInfo = true;
        }
    }

    if(resultMessages.novas) {
        hasInfo = true;
        $('#content-minhas-mensagens').removeClass('pulse-naj');
        if(resultMessages.novas > 0) {
            $('#qtde_mensagens_novas')[0].innerHTML = `
                ${resultMessages.novas}
                <div class="notify" style="top: -15px !important;">
                    <span class="heartbit"></span>
                    <span class="point"></span>
                </div>
            `;
        } else {
            $('#qtde_mensagens_novas')[0].innerHTML = `${resultMessages.novas}`;
        }
    }
}

async function loadContainerAtividade() {
    let filter = await filterUsuario();

    let parametrosAtividade = {
        'data_inicial': getDateProperties(new Date(new Date().getTime() - (30 * 86400000))).fullDate,
        'data_final'  : getDateProperties(new Date()).fullDate,
        'id_usuario'  : idUsuarioLogado
    };

    let resultAtividade = await NajApi.getData(`atividades/indicador/${btoa(JSON.stringify(parametrosAtividade))}?filterUser=${filter}`);

    if(resultAtividade.todas[0] && resultAtividade.trinta_dias[0]) {
        if(resultAtividade.trinta_dias[0].qtde_30_dias > 0) {
            $('#qtde_atividade_trinta_dias')[0].innerHTML = `
                ${resultAtividade.trinta_dias[0].qtde_30_dias}
                <div class="notify" style="top: -15px !important; left: -45px;">
                    <span class="heartbit"></span>
                    <span class="point"></span>
                </div>
            `;
        } else {
            if(resultAtividade.trinta_dias[0].qtde_30_dias != 0) {
                $('#content-minhas-mensagens').removeClass('pulse-naj');
                hasInfo = true;
            }

            $('#qtde_atividade_trinta_dias')[0].innerHTML = `${resultAtividade.trinta_dias[0].qtde_30_dias}`;
        }

        if(resultAtividade.todas[0].todas != 0) {
            $('#content-minhas-mensagens').removeClass('pulse-naj');
            hasInfo = true;
        }
        
        $('#qtde_atividade_todas')[0].innerHTML = `${resultAtividade.todas[0].todas}`;
    }
}

async function loadContainerProcesso() {
    let filter = await filterUsuario();
    let resultProcesso = await NajApi.getData(`processos/indicador?f=${filter}`);

    if(resultProcesso.data[0]) {
        if(resultProcesso.data[1]) {
            hasInfo = true;
            $('#content-minhas-mensagens').removeClass('pulse-naj');
            $('#qtde_processo_baixado')[0].innerHTML = `${resultProcesso.data[1].QTDE}`;
        } else{
            $('#qtde_processo_baixado')[0].innerHTML = `0`;
        }

        if(resultProcesso.data[0]) {
            hasInfo = true;
            $('#content-minhas-mensagens').removeClass('pulse-naj');
            $('#qtde_processo_ativos')[0].innerHTML = `${resultProcesso.data[0].QTDE}`;
        } else {
            $('#qtde_processo_ativos')[0].innerHTML = `0`;
        }
    } else {
        $('#qtde_processo_ativos')[0].innerHTML = `0`;
        $('#qtde_processo_baixado')[0].innerHTML = `0`;
    }
}

async function loadContainerFinanceiro() {
    let filter = await filterUsuario();
    let resultFinanceiro = await NajApi.getData(`financeiro/indicador?filterUser=${filter}`);

    $('#qtde_pagar_aberto')[0].innerHTML = `R$0,00`;
    $('#qtde_pagar_pago')[0].innerHTML = `R$0,00`;
    $('#qtde_receber_aberto')[0].innerHTML = `R$0,00`;
    $('#qtde_receber_recebido')[0].innerHTML = `R$0,00`;

    if(resultFinanceiro.total_pagar[0]) {
        $('#qtde_pagar_aberto')[0].innerHTML = `${formatter.format(resultFinanceiro.total_pagar[0].TOTAL_EM_ABERTO)}`;
    }

    if(resultFinanceiro.total_pago[0]) {
        $('#qtde_pagar_pago')[0].innerHTML = `${formatter.format(resultFinanceiro.total_pago[0].TOTAL_PAGO)}`;
    }

    if(resultFinanceiro.total_receber[0]) {
        $('#qtde_receber_aberto')[0].innerHTML = `${formatter.format(resultFinanceiro.total_receber[0].TOTAL_EM_ABERTO)}`;
    }

    if(resultFinanceiro.total_recebido[0]) {
        $('#qtde_receber_recebido')[0].innerHTML = `${formatter.format(resultFinanceiro.total_recebido[0].TOTAL_PAGO)}`;
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

    if(myDropzone.files[0].type.split('/')[1] != 'png') {
        NajAlert.toastWarning('Você deve selecionar uma imagem do tipo PNG!');
        return;
    }

    let parseFile = await toBase64(myDropzone.files[0]);

    let result = await NajApi.postData(`empresas/logo`, {'file': parseFile});

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

async function filterUsuario() {
    return btoa(JSON.stringify([{'val': idUsuarioLogado}]));
}

async function updateDataAcessoSistema() {
    let key = btoa(JSON.stringify({'id_usuario' : idUsuarioLogado}));

    let data = {
        'data_acesso' : getDataHoraAtual()
    };

    let result = await NajApi.updateData(`usuarios/acesso/${key}?XDEBUG_SESSION_START`, data);

    if(result.status_code == 200) {
        console.log('Ultimo acesso registrado com sucesso!');
        sessionStorage.setItem('@NAJ_WEB/ultimo_acesso', true);
    } else {
        console.log(result.mensagem);
    }
}