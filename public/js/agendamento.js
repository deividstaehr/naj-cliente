let id_chat_current;
let id_atendimento_current;

$(document).ready(() => {
    // loadAgendamento();    
});

// async function loadAgendamento() {
//     let result = await NajApi.getData(`mensagens/hasChat/${idUsuarioLogado}`);

//     if(!result.chat.id_chat || !result.chat.id_usuario) {
//         return;
//     }

//     id_chat_current         = result.chat.id_chat;
// }

// async function onClickAgendarConsulta() {
//     const message = `SOLICITAÇÃO DE AGENDAMENTO: "Agendamento de Consulta", aguardando datas disponíveis.`;
//     const messageSucess = `Recebemos o seu pedido de "Agendamento de Consulta" e em breve retornaremos com as datas disponíveis, Muito Obrigado!`;
//     const agendamentoRotina = `Agendamento de Consulta`;

//     await sendMessageAgendamento(message, messageSucess, agendamentoRotina);
// }

// async function onClickAgendarReuniao() {
//     const message = `SOLICITAÇÃO DE AGENDAMENTO: "Agendamento de Reunião", aguardando datas disponíveis.`;
//     const messageSucess = `Recebemos o seu pedido de "Agendamento de Reunião" e em breve retornaremos com as datas disponíveis, Muito Obrigado!`;
//     const agendamentoRotina = `Agendamento de Reunião`;

//     await sendMessageAgendamento(message, messageSucess, agendamentoRotina);
// }

// async function onClickAgendarVisita() {
//     const message = `SOLICITAÇÃO DE AGENDAMENTO: "Agendamento de Visita", aguardando datas disponíveis.`;
//     const messageSucess = `Recebemos o seu pedido de "Agendamento de Visita" e em breve retornaremos com as datas disponíveis, Muito Obrigado!`;
//     const agendamentoRotina = `Agendamento de Visita`;

//     await sendMessageAgendamento(message, messageSucess, agendamentoRotina);
// }

// async function onClickOutroAgendamento() {
//     const message = `SOLICITAÇÃO DE AGENDAMENTO: "Outro tipo de Agendamento", aguardando integração para maiores informações.`;
//     const messageSucess = `Recebemos o seu pedido de "Agendamento" e em breve retornaremos solicitando mais informações, Muito Obrigado!`;
//     const agendamentoRotina = `Outro tipo de Agendamento`;

//     await sendMessageAgendamento(message, messageSucess, agendamentoRotina);
// }

// async function sendMessageAgendamento(message, messageSuccess, agendamentoRotina) {
//     const data_hora = getDataHoraAtual();

//     loadingStart('loading-agendamento');
    
//     if(id_chat_current) {
//         let data = {
//             "id_chat"       : id_chat_current,
//             "id_usuario"    : idUsuarioLogado,
//             "conteudo"      : message,
//             "tipo"          : 0,
//             "data_hora"     : data_hora,
//             "file_size"     : 0,
//             "file_path"     : "",
//             "id_atendimento": id_atendimento_current,
//             agendamentoRotina
//         };
    
//         let result = await NajApi.postData(`chat/mensagem?XDEBUG_SESSION_START`, data);

//         if(!result || !result.model) {
//             NajAlert.toastError('Não foi possível enviar a mensagem, tente novamente mais tarde!');
//             loadingDestroy('loading-agendamento');
//             return;
//         }

//         loadingDestroy('loading-agendamento');
//         Swal.fire({
//             title: "Mensagem enviada!",
//             text: messageSuccess,
//             icon: "success",
//             confirmButtonText: "Entendi!",
//         });

//         $('#modal-agendamentos').modal('hide');
//     } else {
//         let data = {
//             "id_usuario"    : idUsuarioLogado,
//             "conteudo"      : message,
//             "tipo"          : 0,
//             "data_hora"     : data_hora,
//             "file_size"     : 0,
//             "file_path"     : "",
//             agendamentoRotina
//         };
    
//         let result = await NajApi.postData(`chat/novo/atendimento?XDEBUG_SESSION_START`, data);

//         if(!result) {
//             NajAlert.toastError('Não foi possível enviar a mensagem, tente novamente mais tarde!');
//             loadingDestroy('loading-agendamento');
//             return;
//         }

//         if(result.message) {
//             loadingDestroy('loading-agendamento');
//             Swal.fire({
//                 title: "Mensagem enviada!",
//                 text: messageSuccess,
//                 icon: "success",
//                 confirmButtonText: "Entendi!",
//             });
//             $('#modal-agendamentos').modal('hide');
//         }
//     }
// }