const NajApi  = new Naj();
const container = document.getElementById('content-minhas-mensagens');
let taRodando = false;
let hasInfo   = false;

$(document).ready(function() {

    $('#main-wrapper').addClass('naj-scrollable')

    if (isMobile())
        $('.page-wrapper')[0].style.height = 'auto'

    updateDataAcessoSistema()//Atualizando a data de acesso ao sistema
    
    //Evento do click no bloco MINHAS MENSAGENS
    $('#content-minhas-mensagens').on('click', function() {
        window.location.href = `${baseURL}mensagens`
    });

    //Evento do click no bloco MEUS PROCESSOS
    $('#content-meus-processos').on('click', function() {
        window.location.href = `${baseURL}processos`
    });

    //Evento do click no bloco ATIVIDADES
    $('#content-atividades').on('click', function() {
        window.location.href = `${baseURL}atividades`
    });

    //Evento do click no bloco FINANCEIRO RECEBER
    $('#content-financeiro-receber').on('click', function() {
        window.location.href = `${baseURL}financeiro/index/receber`
    });

    //Evento do click no bloco FINANCEIRO PAGAR
    $('#content-financeiro-pagar').on('click', function() {
        window.location.href = `${baseURL}financeiro/index/pagar`
    });

    $('#content-agendamentos').on('click', () => {
        // $('#modal-agendamentos').modal('show')
        window.location.href = `${baseURL}agenda`
    });

    let nomeEmpresa = sessionStorage.getItem('@NAJ_CLIENTE/nomeEmpresa')

    if(nomeEmpresa)
        $('#nomeEmpresa')[0].innerHTML = `${nomeEmpresa}`

    loadContainers() //Carrega os cards da pagina inicial

    checkPesquisaNps() // Checando as pesquisas NPS

    setInterval(() => {
        //Somente se não tiver info é para animar
        if(!hasInfo) {
            container.setAttribute('animacao', taRodando ? 'animacao-qualquer' : '' );
            taRodando = !taRodando;
        }
    }, 3000);
});

async function loadContainers() {
    await disableContainerWithoutPerm();
    
    await loadContainerMensagens();
    await loadContainerAtividade();
    await loadContainerProcesso();
    await loadContainerFinanceiro();
    await loadContainerAgenda();
}

async function disableContainerWithoutPerm() {
    const data = await NajApi.getData(`pessoa/permissions`);

    if (data.agenda.length == 0)
        $('#content-agendamentos').hide();

    if (data.contas_receber.length == 0)
        $('#content-financeiro-receber').hide();

    if (data.contas_pagar.length == 0)
        $('#content-financeiro-pagar').hide();

    if (data.processos.length == 0)
        $('#content-meus-processos').hide();

    if (data.atividades.length == 0)
        $('#content-atividades').hide();
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
                <div class="notify" style="top: -15px !important; left: 18px; z-index: 1;">
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

    let resultAtividade = await NajApi.getData(`atividades/indicador/${btoa(JSON.stringify(parametrosAtividade))}?filterUser=${filter}&XDEBUG_SESSION_START`);

    if(resultAtividade.todas[0] && resultAtividade.trinta_dias[0]) {
        if(resultAtividade.trinta_dias[0].qtde_30_dias > 0) {
            $('#qtde_atividade_trinta_dias')[0].innerHTML = `
                ${resultAtividade.trinta_dias[0].qtde_30_dias}
                <div class="notify custom-notify-naj" style="top: -15px !important; left: -59px; z-index: 1;">
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
    let filter = {
        'data_inicial': getDateProperties(new Date(new Date().getTime() - (30 * 86400000))).fullDate,
        'data_final'  : getDateProperties(new Date()).fullDate,
        'id_usuario'  : idUsuarioLogado
    };

    let resultProcesso = await NajApi.getData(`processos/indicador/${btoa(JSON.stringify({filter}))}?XDEBUG_SESSION_START`);

    let qtde_processo_todos = 0;
    if(resultProcesso.data.situacao[0]) {
        if(resultProcesso.data.situacao[0]) {
            hasInfo = true;
            $('#content-minhas-mensagens').removeClass('pulse-naj');
            qtde_processo_todos = qtde_processo_todos + resultProcesso.data.situacao[0].QTDE;
        }

        if(resultProcesso.data.situacao[1]) {
            hasInfo = true;
            $('#content-minhas-mensagens').removeClass('pulse-naj');
            qtde_processo_todos = qtde_processo_todos + resultProcesso.data.situacao[1].QTDE;
        }

        $('#qtde_processo_todos')[0].innerHTML = qtde_processo_todos;
    } else {
        $('#qtde_processo_todos')[0].innerHTML = `0`;
    }

    if(resultProcesso.data.trinta_dias) {
        if(resultProcesso.data.trinta_dias.total > 0) {
            hasInfo = true;
            $('#content-processos-trinta_dias').removeClass('pulse-naj');
            $('#qtde_processo_30_dias')[0].innerHTML = `
                ${resultProcesso.data.trinta_dias.total}
                <div class="notify" style="top: -15px !important; left: -50px; z-index: 1;">
                    <span class="heartbit"></span>
                    <span class="point"></span>
                </div>
            `;
        } else{
            $('#qtde_processo_30_dias')[0].innerHTML = `0`;
        }
    }
}

async function loadContainerFinanceiro() {
    let filter = await filterUsuario();
    let resultFinanceiro = await NajApi.getData(`financeiro/indicador?filterUser=${filter}`);

    $('#qtde_pagar_aberto')[0].innerHTML = `R$0,00`;
    $('#qtde_pagar_pago')[0].innerHTML = `R$0,00`;
    $('#qtde_pagar_atrasado')[0].innerHTML = `R$0,00`;
    $('#qtde_receber_aberto')[0].innerHTML = `R$0,00`;
    $('#qtde_receber_recebido')[0].innerHTML = `R$0,00`;
    $('#qtde_receber_atrasado')[0].innerHTML = `R$0,00`;

    if(resultFinanceiro.total_pagar[0]) {        
        $('#qtde_pagar_aberto')[0].innerHTML = `${formatter.format(resultFinanceiro.total_pagar[0].TOTAL_EM_ABERTO)}`;
        if(resultFinanceiro.total_pagar[0].TOTAL_EM_ABERTO && resultFinanceiro.total_pagar[0].TOTAL_EM_ABERTO != "0.00") hasInfo = true;
    }

    if(resultFinanceiro.total_pago[0]) {
        $('#qtde_pagar_pago')[0].innerHTML = `${formatter.format(resultFinanceiro.total_pago[0].TOTAL_PAGO)}`;
        if(resultFinanceiro.total_pago[0].TOTAL_PAGO && resultFinanceiro.total_pago[0].TOTAL_PAGO) hasInfo = true;
    }

    if(resultFinanceiro.total_receber[0]) {        
        $('#qtde_receber_aberto')[0].innerHTML = `${formatter.format(resultFinanceiro.total_receber[0].TOTAL_EM_ABERTO)}`;
        if(resultFinanceiro.total_receber[0].TOTAL_EM_ABERTO && resultFinanceiro.total_receber[0].TOTAL_EM_ABERTO != '0.00') hasInfo = true;
    }

    if(resultFinanceiro.total_recebido[0]) {        
        $('#qtde_receber_recebido')[0].innerHTML = `${formatter.format(resultFinanceiro.total_recebido[0].TOTAL_PAGO)}`;
        if(resultFinanceiro.total_recebido[0].TOTAL_PAGO && resultFinanceiro.total_recebido[0].TOTAL_PAGO != '0.00') hasInfo = true;
    }

    if(resultFinanceiro.total_receber_atrasado[0]) {
        $('#qtde_receber_atrasado')[0].innerHTML = `${formatter.format(resultFinanceiro.total_receber_atrasado[0].TOTAL_RECEBER_ATRASADO)}`;
        if(resultFinanceiro.total_receber_atrasado[0].TOTAL_RECEBER_ATRASADO && resultFinanceiro.total_receber_atrasado[0].TOTAL_RECEBER_ATRASADO != '0.00') hasInfo = true;
    }

    if(resultFinanceiro.total_pagar_atrasado[0]) {
        $('#qtde_pagar_atrasado')[0].innerHTML = `${formatter.format(resultFinanceiro.total_pagar_atrasado[0].TOTAL_PAGAR_ATRASADO)}`;
        if(resultFinanceiro.total_pagar_atrasado[0].TOTAL_PAGAR_ATRASADO && resultFinanceiro.total_pagar_atrasado[0].TOTAL_PAGAR_ATRASADO != '0.00') hasInfo = true;
    }
}

async function loadContainerAgenda() {
    const filter = await filterUsuario()
    const events = await NajApi.getData(`agenda/amountEvents/${filter}`)
    $('#quantidade-agendamento')[0].innerHTML = ``

    if(events.data[0]) {
        $('#quantidade-agendamento')[0].innerHTML = `
            ${events.data[0].quantidade_eventos}
        `;

        $('#text-agendamento')[0].innerHTML = `Próximos Eventos`

        if (events.data[0].quantidade_eventos > 0) {
            $('#quantidade-agendamento')[0].innerHTML = `
                ${events.data[0].quantidade_eventos}
                <div class="notify custom-notify-naj" style="top: -15px !important; left: -59px; z-index: 1;">
                    <span class="heartbit"></span>
                    <span class="point"></span>
                </div>
            `;

            hasInfo = true
        }   
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

async function checkPesquisaNps() {
    const searches = await NajApi.getData(`pesquisa/nps/pendentes`)

    console.log(searches)

    if (!searches.data.length > 0) return //Se não tiver achado nada retorna
    let notes = ``

    if (searches.data[0].range_max == 5) {
        notes = `
            <span class="button-note-nps ml-2 btn btn-danger btn-circle cursor-pointer">0</span>
            <span class="button-note-nps ml-2 btn btn-danger btn-circle cursor-pointer">1</span>
            <span class="button-note-nps ml-2 color-white btn btn-warning btn-circle cursor-pointer">2</span>
            <span class="button-note-nps ml-2 color-white btn btn-warning btn-circle cursor-pointer">3</span>
            <span class="button-note-nps ml-2 btn btn-success btn-circle cursor-pointer">4</span>
            <span class="button-note-nps mr-2 ml-2 btn btn-success btn-circle cursor-pointer">5</span>
        `
    } else if (searches.data[0].range_max == 10) {
        notes = `
            <span class="button-note-nps ml-2 btn btn-danger btn-circle cursor-pointer">0</span>
            <span class="button-note-nps ml-2 btn btn-danger btn-circle cursor-pointer">1</span>
            <span class="button-note-nps ml-2 btn btn-danger btn-circle cursor-pointer">2</span>
            <span class="button-note-nps ml-2 btn btn-danger btn-circle cursor-pointer">3</span>
            <span class="button-note-nps ml-2 btn btn-danger btn-circle cursor-pointer">4</span>
            <span class="button-note-nps ml-2 btn btn-danger btn-circle cursor-pointer">5</span>
            <span class="button-note-nps ml-2 btn btn-danger btn-circle cursor-pointer">6</span>
            <span class="button-note-nps ml-2 color-white btn btn-warning btn-circle cursor-pointer">7</span>
            <span class="button-note-nps ml-2 color-white btn btn-warning btn-circle cursor-pointer">8</span>
            <span class="button-note-nps ml-2 btn btn-success btn-circle cursor-pointer">9</span>
            <span class="button-note-nps mr-2 ml-2 btn btn-success btn-circle cursor-pointer">10</span>
        `
    }

    $('.content-title-pergunta-nps')[0].innerHTML = `${searches.data[0].pergunta.replace('{NOME}', nomeUsuarioLogado).replace('{APELIDO}', apelidoUsuarioLogado)}`
    $('.content-notes-respostas-nps')[0].innerHTML = `
        <span class="font-weight-light fs-13">${searches.data[0].range_min_info}</span>
        ${notes}
        <span class="font-weight-light fs-13">${searches.data[0].range_max_info}</span>
    `

    // setando algumas informações que serão utilizadas na hora do envio da resposta
    $('#id_resposta_nps').val(searches.data[0].id_resposta_nps)
    $('#amount_open').val(searches.data[0].count)

    await NajApi.postData(`pesquisa/nps/refresh`, {id_answer_nps: searches.data[0].id_resposta_nps, data_hora_visualizacao: getDataHoraAtual()})

    $('.button-note-nps').on('click', (ref) => {
        $('.button-note-nps').removeClass('button-note-nps-selected')
        $(ref.currentTarget).addClass('button-note-nps-selected')
    })

    $('#modal-pesquisa-nps-respostas').modal('show');
}

async function saveSearchNps() {
    const data = {
        id_search_nps : $('#id_pesquisa_nps').val(),
        id_answer_nps : $('#id_resposta_nps').val(),
        note : $('.button-note-nps-selected').text(),
        motive: $('#motivo').val(),
        data_hora_resposta: getDataHoraAtual(),
        data_hora_visualizacao: getDataHoraAtual(),
        amount_open: parseFloat($('#amount_open').val() || 0) + 1,
    }

    if (!data.note)
        return NajAlert.toastWarning('Você deve informar uma nota para confirmar o envio da resposta!')

    const result = await NajApi.postData(`pesquisa/nps/resposta`, data)

    if(result) {
        NajAlert.toastSuccess('Resposta registrada com sucesso!')
        window.location.href = window.location.href
    }
}

async function saveNotAnswerSearchNps() {
    const data = {
        id_answer_nps : $('#id_resposta_nps').val(),
        data_hora_visualizacao: getDataHoraAtual(),
        amount_open: parseFloat($('#amount_open').val() || 0) + 1,
    }

    const result = await NajApi.postData(`pesquisa/nps/naoresponder`, data)

    if(result) {
        NajAlert.toastSuccess('Resposta registrada com sucesso!')
        window.location.href = window.location.href
    }
}