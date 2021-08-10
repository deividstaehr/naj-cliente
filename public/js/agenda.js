const NajApi    = new Naj('Eventos', null)

const weekNames = [
    'Segunda',
    'Terça',
    'Quarta',
    'Quinta',
    'Sexta',
    'Sábado',
    'Domingo',
]

const monthNames = [
    'Janeiro',
    'Fevereiro',
    'Março',
    'Abril',
    'Maio',
    'Junho',
    'Julho',
    'Agosto',
    'Setembro',
    'Outubro',
    'Novembro',
    'Dezembro',
]

let id_chat_current
let id_atendimento_current

$(document).ready(function() {
    
    loadEvents()
    searchInfoChat()
    
})

async function loadEvents() {
    let filters = {
        user_id: idUsuarioLogado
    }

    const events = await NajApi.getData(`agenda/all?filters=${btoa(JSON.stringify(filters))}&XDEBUG_SESSION_START`)

    let eventsHtml = ''

    if (!events.data)
        $('.container-agenda')[0].innerHTML = `<div class="content-without-information-event"><span>Sem informações...</span></div>`;

    events.data.forEach(item => {
        const dateFormat = new Date(`${item.DATA.split('/')[2]}/${item.DATA.split('/')[1]}/${item.DATA.split('/')[0]}`)

        let month = dateFormat
        month = monthNames[month.getMonth()]

        let year = getDateProperties(dateFormat).year
        let day = getDateProperties(dateFormat).day

        let dayWeek = dateFormat

        let week = dayWeek.getDay() - 1
        dayWeek = weekNames[week]

        let eventTitle = item.RESPONSAVEL
        let eventSubtitle = ''

        if (item.NUMERO_PROCESSO) {
            eventTitle = `${item.NOME_CLIENTE} X ${item.PARTE_CONTRARIA}`
            eventSubtitle = item.RESPONSAVEL
        }
            

        eventsHtml += `
            <div class="row row-striped">
                <div class="col-2 p-0 text-right">
                    <h3 class="display-4"><span class="badge badge-info">${day}</span></h3>
                    <h6>${month}/${year}</h6>
                </div>
                <div class="col-10">
                    <h5 class="text-uppercase"><strong>${eventTitle}</strong></h5>
                    <h6 class="text-uppercase"><strong>${eventSubtitle}</strong></h6>
                    <ul class="list-inline">
                        <li class="list-inline-item"><i class="fa fa-calendar-alt" aria-hidden="true"></i> ${dayWeek}</li>
                        <li class="list-inline-item"><i class="fa fa-clock" aria-hidden="true"></i> ${item.HORA}</li>
                        <li class="list-inline-item"><i class="fas fa-map-marker-alt" aria-hidden="true"></i> ${item.LOCAL}</li>
                    </ul>
                    <p>${item.ASSUNTO}</p>
                </div>
            </div>
        `
    })

    $('.container-agenda')[0].innerHTML = eventsHtml
}

async function searchInfoChat() {
    let result = await NajApi.getData(`mensagens/hasChat/${idUsuarioLogado}`);

    if(!result.chat.id_chat || !result.chat.id_usuario) {
        return;
    }

    id_chat_current = result.chat.id_chat;
}

async function onClickAgendarConsulta() {
    const message = `SOLICITAÇÃO DE AGENDAMENTO: "Agendamento de Consulta", aguardando datas disponíveis.`;
    const messageSucess = `Recebemos o seu pedido de "Agendamento de Consulta" e em breve retornaremos com as datas disponíveis, Muito Obrigado!`;
    const agendamentoRotina = `Agendamento de Consulta`;

    await sendMessageAgendamento(message, messageSucess, agendamentoRotina);
}

async function onClickAgendarReuniao() {
    const message = `SOLICITAÇÃO DE AGENDAMENTO: "Agendamento de Reunião", aguardando datas disponíveis.`;
    const messageSucess = `Recebemos o seu pedido de "Agendamento de Reunião" e em breve retornaremos com as datas disponíveis, Muito Obrigado!`;
    const agendamentoRotina = `Agendamento de Reunião`;

    await sendMessageAgendamento(message, messageSucess, agendamentoRotina);
}

async function onClickAgendarVisita() {
    const message = `SOLICITAÇÃO DE AGENDAMENTO: "Agendamento de Visita", aguardando datas disponíveis.`;
    const messageSucess = `Recebemos o seu pedido de "Agendamento de Visita" e em breve retornaremos com as datas disponíveis, Muito Obrigado!`;
    const agendamentoRotina = `Agendamento de Visita`;

    await sendMessageAgendamento(message, messageSucess, agendamentoRotina);
}

async function onClickOutroAgendamento() {
    const message = `SOLICITAÇÃO DE AGENDAMENTO: "Outro tipo de Agendamento", aguardando integração para maiores informações.`;
    const messageSucess = `Recebemos o seu pedido de "Agendamento" e em breve retornaremos solicitando mais informações, Muito Obrigado!`;
    const agendamentoRotina = `Outro tipo de Agendamento`;

    await sendMessageAgendamento(message, messageSucess, agendamentoRotina);
}

async function sendMessageAgendamento(message, messageSuccess, agendamentoRotina) {
    const data_hora = getDataHoraAtual();

    loadingStart('loading-agendamento');
    
    if(id_chat_current) {
        let data = {
            "id_chat"       : id_chat_current,
            "id_usuario"    : idUsuarioLogado,
            "conteudo"      : message,
            "tipo"          : 0,
            "data_hora"     : data_hora,
            "file_size"     : 0,
            "file_path"     : "",
            "id_atendimento": id_atendimento_current,
            agendamentoRotina
        };
    
        let result = await NajApi.postData(`chat/mensagem?XDEBUG_SESSION_START`, data);

        if(!result || !result.model) {
            NajAlert.toastError('Não foi possível enviar a mensagem, tente novamente mais tarde!');
            loadingDestroy('loading-agendamento');
            return;
        }

        loadingDestroy('loading-agendamento');
        Swal.fire({
            title: "Mensagem enviada!",
            text: messageSuccess,
            icon: "success",
            confirmButtonText: "Entendi!",
        });

        $('#modal-agendamentos').modal('hide');
    } else {
        let data = {
            "id_usuario"    : idUsuarioLogado,
            "conteudo"      : message,
            "tipo"          : 0,
            "data_hora"     : data_hora,
            "file_size"     : 0,
            "file_path"     : "",
            agendamentoRotina
        };
    
        let result = await NajApi.postData(`chat/novo/atendimento?XDEBUG_SESSION_START`, data);

        if(!result) {
            NajAlert.toastError('Não foi possível enviar a mensagem, tente novamente mais tarde!');
            loadingDestroy('loading-agendamento');
            return;
        }

        if(result.message) {
            loadingDestroy('loading-agendamento');
            Swal.fire({
                title: "Mensagem enviada!",
                text: messageSuccess,
                icon: "success",
                confirmButtonText: "Entendi!",
            });
            $('#modal-agendamentos').modal('hide');
        }
    }
}