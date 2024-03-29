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
    if (isMobile()) {
        $('#row-content-agendamento')[0].innerHTML = `
            <div class="col-list-agendamento col-lg-6 col-md-6 col-sm-12 pl-0">
                <div class="container">
                    <div class="card" style="width: 100%; height: 100%;">
                        <div class="d-flex justify-content-center align-items-center">
                            <h2 style="font-size: 16px !important;" class="weight-500" id="title-next-events">Próximos Eventos</h2>
                            <button class="ml-4 btn btn-info btn-rounded" style="margin-top: -10px !important;" onclick="showModalNewEvent()"><i class="fas fa-plus-circle"></i></button>
                        </div>
                        <hr/>
                        <div class="container-agenda naj-scrollable"></div>
                    </div>
                </div>
            </div>
        `;
    }

    let filters = {
        user_id: idUsuarioLogado
    }

    const events = await NajApi.getData(`agenda/all?filters=${btoa(JSON.stringify(filters))}&XDEBUG_SESSION_START`)

    let eventsHtml = ''
    console.log(events.data.length)
    if (events.data.length == 0) {
        $('#title-next-events')[0].innerHTML = `Próximos Eventos (${events.total_events[0].quantidade_eventos})`
        $('.container-agenda')[0].innerHTML = `<div class="content-without-information-event"><span>Sem informações...</span></div>`
        return
    }

    events.data.forEach(item => {
        const dateFormat = new Date(`${item.DATA.split('/')[2]}/${item.DATA.split('/')[1]}/${item.DATA.split('/')[0]}`)

        let month = dateFormat
        month = monthNames[month.getMonth()]

        let year = getDateProperties(dateFormat).year
        let day = getDateProperties(dateFormat).day

        let dayWeek = dateFormat

        let week = dayWeek.getDay()

        if (week == 0)
            dayWeek = weekNames[week]
        else
            dayWeek = weekNames[week - 1]

        let eventTitle = `
            <h5 class="text-uppercase"><strong><i class="fa fa-user" aria-hidden="true"></i> ${item.RESPONSAVEL}</strong></h5>
        `
        let eventSubtitle = ''

        if (item.NUMERO_PROCESSO) {
            eventTitle = `<h6 class="text-uppercase"><strong>${item.NOME_CLIENTE} X ${item.PARTE_CONTRARIA}</strong></h6>`
            
            eventSubtitle = `<h5 class="text-uppercase"><strong><i class="fa fa-user" aria-hidden="true"></i> ${item.RESPONSAVEL}</strong></h5>`
        }

        let assunt = `<p>${item.ASSUNTO}</p>`

        if (item.ASSUNTO.length > 500) {
            assunt = `
                ${item.ASSUNTO.substr(0, 500)}
                <span class="action-icons">
                    <a data-toggle="collapse" href="#item-agenda-${item.ID_COMPROMISSO}" data-key-processo="${item.ID_COMPROMISSO}" aria-expanded="false" onclick="onClickItemAgenda(${item.ID_COMPROMISSO}, this);">
                        <i class="fas fa-chevron-circle-right icone-partes-processo-expanded" title="Clique para ver o assunto completo" data-toggle="tooltip"></i>
                    </a>
                </span>
            `
        }

        eventsHtml += `
            <div class="row row-striped">
                <div class="col-day-calendar col-lg-2 col-md-2 col-sm-12 p-0 text-right">
                    <h3 class="display-4"><span class="badge badge-info">${day}</span></h3>
                    <h6 class="text-month-year">${month}/${year}</h6>
                </div>
                <div class="col-infos-calendar col-lg-10 col-md-10 col-sm-12">
                    ${eventSubtitle}
                    ${eventTitle}
                    <ul class="list-inline">
                        <li class="list-inline-item"><i class="fa fa-calendar-alt" aria-hidden="true"></i> ${dayWeek}</li>
                        <li class="list-inline-item"><i class="fa fa-clock" aria-hidden="true"></i> ${item.HORA}</li>
                        <li class="list-inline-item"><i class="fas fa-map-marker-alt" aria-hidden="true"></i> ${item.LOCAL}</li>
                    </ul>
                    <p id="item-agenda-hide-${item.ID_COMPROMISSO}">${assunt}</p>
                </div>
            </div>
        `
    })

    $('.container-agenda')[0].innerHTML = eventsHtml
    $('#title-next-events')[0].innerHTML = `Próximos Eventos (${events.total_events[0].quantidade_eventos})`
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
    
        let result = await NajApi.postData(`chat/mensagem`, data);

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
    
        let result = await NajApi.postData(`chat/novo/atendimento`, data);

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

    $('#modal-novo-agendamento').modal('hide');
}

async function onClickItemAgenda(codigo, el) {
    const parameters = btoa(JSON.stringify({codigo}))
    const event = await NajApi.getData(`agenda/show/${parameters}`)

    if (!event.data) return

    let classItem = ''

    if(el.children) {
        let className = el.children.item(0).className

        classItem = 'fas fa-chevron-circle-right icone-partes-processo-expanded'

        if(className == 'fas fa-chevron-circle-down icone-partes-processo-expanded') {
            $(`#item-agenda-hide-${codigo}`)[0].innerHTML = `
                ${event.data[0].assunto.substr(0, 500)}
                <span class="action-icons">
                    <a data-toggle="collapse" href="#item-agenda-${codigo}" data-key-processo="${codigo}" aria-expanded="false" onclick="onClickItemAgenda(${codigo}, this);">
                        <i class="${classItem}" title="Clique para ver o assunto completo" data-toggle="tooltip"></i>
                    </a>
                </span>
            `;
        } else {
            classItem = 'fas fa-chevron-circle-down icone-partes-processo-expanded'
            $(`#item-agenda-hide-${codigo}`)[0].innerHTML = `
                ${event.data[0].assunto}
                <span class="action-icons">
                    <a data-toggle="collapse" href="#item-agenda-${codigo}" data-key-processo="${codigo}" aria-expanded="false" onclick="onClickItemAgenda(${codigo}, this);">
                        <i class="${classItem}" title="Clique para ver o assunto completo" data-toggle="tooltip"></i>
                    </a>
                </span>
            `;
        }   
    }
}

function isMobile() {
    const toMatch = [
        /Android/i,
        /webOS/i,
        /iPhone/i,
        /iPad/i,
        /iPod/i,
        /BlackBerry/i,
        /Windows Phone/i
    ];

    return toMatch.some((toMatchItem) => {
        return navigator.userAgent.match(toMatchItem);
    });
}

function showModalNewEvent() {
    $('#modal-novo-agendamento').modal('show');
}