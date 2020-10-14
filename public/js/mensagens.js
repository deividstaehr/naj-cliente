const chat = new Chat();
const NajApi  = new Naj();

let limitAtualChat = 20;
let id_chat_current;
let id_atendimento_current;
let id_chat_current_selected;
let id_usuario_current_chat;
let filterDataChat;
let usersNewAtendimento = [];

//---------------------- Functions -----------------------//
$(document).ready(function() {
    onLoadAtendimento();

    //Evento do click de exibir o modal anexo do chat
    $('#input-anexo').on('click', function() {
        $('#previews')[0].innerHTML = '';
        $('#content-upload-anexos-chat').show();
        $('.content-butons-chat').hide();
        $('.chat-box').removeClass('content-chat-box-no-full');
        $('.chat-box').addClass('content-chat-box-full');
        $('#content-messages-chat').hide();
        $('#input-text-chat-enviar').hide();
    });

    $('#input-text-chat-enviar').keyup(function(event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        
        if(keycode == '13') {
            event.preventDefault();
            if(!$('#input-text-chat-enviar').val()) {
                return;
            }

            chat.createUpdateRascunhoMessage(id_chat_current, null, true);
            sendMessage();
        } else {
            chat.createUpdateRascunhoMessage(id_chat_current, $('#input-text-chat-enviar').val(), true);
        }
    });

    $('.card-body .note-editable').keyup(function(event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        
        if(keycode == '13') {
            event.preventDefault();
            if(!$('#input-text-chat-enviar').val()) {
                return;
            }

            chat.createUpdateRascunhoEditorMessage(id_chat_current, null, true);
            sendMessage();
        } else {
            chat.createUpdateRascunhoEditorMessage(id_chat_current, $('.card-body .note-editable')[0].innerHTML, true);
        }
    });

    $('#icon-trash-rascunho-message-chat').on('click', function() {
        chat.createUpdateRascunhoMessage(id_chat_current, null, true);
        $('#input-text-chat-enviar').val('');
        $('#content-button-rascunho-message-chat').hide();
    });

    $('#icon-trash-rascunho-editor-message-chat').on('click', function() {
        chat.createUpdateRascunhoEditorMessage(id_chat_current, null, true);
        $('.card-body .note-editable')[0].innerHTML = '';
        $('#content-button-rascunho-editor-message-chat').hide();
    });

    setInterval(() => {
        loadMessageChat();
    }, 15000);
});

async function loadMessageChat() {
    let result = await NajApi.getData(`mensagens/hasChat/${idUsuarioLogado}?XDEBUG_SESSION_START`);
    id_chat_current         = result.chat.id_chat;
    id_usuario_current_chat = result.chat.id_usuario;

    if(result.chat.id_chat && !$('#content-upload-anexos-chat').is(":visible")) {
        let moveScroll = $('#pololo').scrollTop() + $('#pololo').innerHeight() == $('#pololo')[0].scrollHeight;
        await chat.loadMessageInChat({"id_chat" : id_chat_current, "id_usuario_cliente" : id_usuario_current_chat}, moveScroll, false);
    }
}

async function onLoadAtendimento() {
    let result = await NajApi.getData(`mensagens/hasChat/${idUsuarioLogado}?XDEBUG_SESSION_START`);

    if(!result.chat) {
        $('#content-upload-anexos-chat').hide();
        $('#content-button-rascunho-message-chat').hide();
        $('#content-button-rascunho-editor-message-chat').hide();
        return;
    }

    await chat.loadMessageInChat({"id_chat" : result.chat.id_chat, "id_usuario_cliente" : result.chat.id_usuario}, true, false);
    id_chat_current         = result.chat.id_chat;
    id_usuario_current_chat = result.chat.id_usuario;
    limitAtualChat          = 20;
}

async function sendMessage(mensagem = false) {
    let message = $('#input-text-chat-enviar').val();
    let data_hora = getDataHoraAtual();

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
        };
    
        let result = await NajApi.postData(`chat/mensagem`, data);

        if(!result || !result.model) {
            NajAlert.toastError('Não foi possível enviar a mensagem, tente novamente mais tarde!');
            return;
        }
        if(result.model) {
            let sHtmlMessage = chat.newContentNewMessage({"nome" : nomeUsuarioLogado, "conteudo" : message, "data_hora" : data_hora}, true);
            $(`#content-messages-chat`).append(sHtmlMessage);
            chat.scrollToBottom();
            chat.cleanInputMessage();
        }
    } else {
        let data = {
            "id_usuario"    : idUsuarioLogado,
            "conteudo"      : message,
            "tipo"          : 0,
            "data_hora"     : data_hora,
            "file_size"     : 0,
            "file_path"     : ""
        };
    
        let result = await NajApi.postData(`chat/novo/atendimento?XDEBUG_SESSION_START`, data);

        if(!result) {
            NajAlert.toastError('Não foi possível enviar a mensagem, tente novamente mais tarde!');
            return;
        }

        if(result.message) {
            let sHtmlMessage = chat.newContentNewMessage({"nome" : nomeUsuarioLogado, "conteudo" : message, "data_hora" : data_hora}, true);
            $(`#content-messages-chat`).append(sHtmlMessage);
            chat.scrollToBottom();
            chat.cleanInputMessage();
        }
    }
}

function onClickCancelarEditorTexto() {
    $('.content-butons-chat').show()
    $('#content-messages-chat').show();
    $('#input-text-chat-enviar').show();
    $('.chat-box').removeClass('content-chat-box-full');
    $('.chat-box').addClass('content-chat-box-no-full');

    chat.scrollToBottom();
}

function onClickCancelarAnexos() {
    $('#content-upload-anexos-chat').hide();
    $('.content-butons-chat').show();
    $('.chat-box').removeClass('content-chat-box-full');
    $('.chat-box').addClass('content-chat-box-no-full');
    $('#content-messages-chat').show();
    $('#input-text-chat-enviar').show();

    chat.scrollToBottom();
}

async function onClickButtonMaisMensagemChat() {
    limitAtualChat = limitAtualChat + 20;
    await chat.loadMessageInChat({"id_chat" : id_chat_current, "id_usuario_cliente" : id_usuario_current_chat}, false);
}

async function onClickSendAnexoEditor() {
    loadingStart('loading-anexo-chat-editor');

    //Se foi escrito algo
    if($("#summernote").summernote('code')) {
        await sendMessage($("#summernote").summernote('code'));
    }
    
    await sendAnexos(myDropzoneEditor);

    $('.content-butons-chat').show()
    $('#content-messages-chat').show();
    $('#input-text-chat-enviar').show();
    $('.chat-box').removeClass('content-chat-box-full');
    $('.chat-box').addClass('content-chat-box-no-full');
    $('#previews-file-editor')[0].innerHTML = '';
    myDropzoneEditor.files = [];

    chat.createUpdateRascunhoEditorMessage(id_chat_current, null, true);
    chat.scrollToBottom();

    loadingDestroy('loading-anexo-chat-editor');
}

async function onClickSendAnexoChat() {
    loadingStart('loading-anexo-chat');
    await sendAnexos(myDropzone);

    $('#content-upload-anexos-chat').hide();
    $('.content-butons-chat').show();
    $('.chat-box').removeClass('content-chat-box-full');
    $('.chat-box').addClass('content-chat-box-no-full');
    $('#content-messages-chat').show();
    $('#input-text-chat-enviar').show();
    $('#previews')[0].innerHTML = '';
    myDropzone.files = [];

    chat.scrollToBottom();
    loadingDestroy('loading-anexo-chat');
}

async function sendAnexos(dropzone) {
    let filesUpload = [];
    let data_hora = getDataHoraAtual();
    let identificador = sessionStorage.getItem('@NAJ_CLIENTE/identificadorEmpresa');

    if(dropzone.files.length < 1) {
        return;
    }

    for(var i = 0; i < dropzone.files.length; i++) {
        let parseFile = await toBase64(dropzone.files[i]);

        filesUpload.push({
            'name_file'   : dropzone.files[i].name,
            'arquivo'     : parseFile,
            'id_cliente'  : identificador,
            'nome'        : nomeUsuarioLogado,
            'data_hora'   : data_hora,
            'tipo'        : 1,
            'conteudo'    : dropzone.files[i].name,
            'id_usuario'  : idUsuarioLogado,
            'id_chat'     : id_chat_current,
            'file_size'   : dropzone.files[i].size,
            'file_path'   : '',
            'id_atendimento': id_atendimento_current
        });
    }

    result = await NajApi.postData(`chat/mensagem/anexo?XDEBUG_SESSION_START`, {'files': filesUpload});

    if(result.status_code == 200) {
        await chat.loadMessageInChat({"id_chat" : id_chat_current, "id_usuario_cliente" : id_usuario_current_chat}, false);
    } else {
        loadingDestroy('loading-anexo-chat');
        NajAlert.toastWarning(result.mensagem);
    }
}

async function onClickDownloadAnexoChat(id_message, arquivoName) {
    loadingStart('loading-message-chat');
    let parametros = btoa(JSON.stringify({id_message, identificador}));
    let result     = await NajApi.getData(`chat/mensagem/download/${parametros}?XDEBUG_SESSION_START`, true);
    let name       = arquivoName.split('.')[0];
    let ext        = arquivoName.split('.')[1];

    if(result) {
        var element = document.createElement('a');
        var reader  = new FileReader();

        reader.onloadend = function () {
            element.setAttribute('href', reader.result);
            element.setAttribute('download', `${name}.${ext}`);
            element.style = 'none';
            document.body.appendChild(element);
            element.click();
            document.body.removeChild(element);
        }

        reader.readAsDataURL(result);
    }
    loadingDestroy('loading-message-chat');
}

function toBase64(file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();

        reader.readAsDataURL(file);
        reader.onload = () => resolve(reader.result),
        reader.onerror = error => reject(error)
    });
}