/**
 * Classe do Chat de Atendimento
 */
class Chat {

    constructor() {
        this.messageContainer = document.getElementById('content-messages-chat');
    }

    // async loadMessageInChat(key, moveScrollView = true, useLoading = true, loadRascunho = true) {
    //     if(useLoading) {
    //         loadingStart('loading-message-chat');
    //     }

    //     const NajClass = new Naj();

    //     let parameters = btoa(JSON.stringify({"limit" : limitAtualChat}));
    //     let responseChat = await NajClass.getData(`chat/mensagem/publico/${key.id_chat}?f=${parameters}&XDEBUG_SESSION_START`);
    //     let sHtml = "";

    //     if(!responseChat.isLastPage) {
    //         sHtml = `
    //             <div class="row chat-item-mostrar-mais-messages" onclick="onClickButtonMaisMensagemChat();">
    //                 <p class="text-button-mais-mensagem-chat">Mostrar mais mensagem...</p>
    //             </div>
    //         `;
    //     }

    //     this.hideContentAnexoChat();

    //     for(var i = 0; i < responseChat.data.length; i++) {
    //         if(!this.isBeginConversation(responseChat.data[i].conteudo) && !this.isFinishConversation(responseChat.data[i].conteudo) && !this.isTransferConversation(responseChat.data[i].conteudo) && responseChat.data[i].tipo_conteudo == '0') {
    //             let isEu = idUsuarioLogado == responseChat.data[i].id_usuario_mensagem;
    //             sHtml += this.newContentNewMessage(responseChat.data[i], isEu);
    //         } else if(responseChat.data[i].tipo_conteudo == 1) {
    //             let isEu = idUsuarioLogado == responseChat.data[i].id_usuario_mensagem;
    //             sHtml += this.newContentAnexo(responseChat.data[i], isEu);
    //         } else if(this.isBeginConversation(responseChat.data[i].conteudo)) {
    //             sHtml += this.newContentStartMessage(responseChat.data[i]);
    //         } else if(this.isTransferConversation(responseChat.data[i].conteudo)) {
    //             sHtml += this.newContentTransferConversation(responseChat.data[i]);
    //         } else {
    //             sHtml += this.newContentFinishMessage(responseChat.data[i]);
    //         }
    //         id_atendimento_current = responseChat.data[i].id_atendimento;
    //     }

    //     this.appendMessagesInChat(sHtml);
    //     $('.content-message-select-user-chat').hide();

    //     if(moveScrollView) {
    //         this.scrollToBottom();
    //     }

    //     //Carrega as informações do RASCUNHO da mensagem
    //     if(loadRascunho) {
    //         await this.createUpdateRascunhoMessage(key.id_chat, null);
    //         await this.loadMessageRascunhoChat(key.id_chat);
    //     }

    //     //Carrega as informações do usuário, PROCESSOS, CADASTRO E RELACIONAMENTOS
    //     // await this.loadInfoUsuario(key);
        
    //     loadingDestroy('loading-message-chat');
    // }

    async startChat(key, moveScrollView = true, useLoading = true, loadInfoUser = true, loadRascunho = true) {
        if(useLoading) {
            loadingStart('loading-message-chat');
        }

        const NajClass = new Naj();

        let parameters = btoa(JSON.stringify({"limit" : limitAtualChat, "id_usuario_chat": key.id_usuario_cliente}));
        let responseChat = await NajClass.getData(`chat/mensagem/publico/${key.id_chat}?f=${parameters}`);
        let sHtml = "";

        if(!responseChat.isLastPage) {
            sHtml = `
                <div class="row chat-item-mostrar-mais-messages" onclick="onClickButtonMaisMensagemChat();">
                    <p class="text-button-mais-mensagem-chat">Mostrar mais mensagem...</p>
                </div>
            `;
        }

        //se veio dados do dispositivo, utilizado para enviar push para o dispositivo
        if(responseChat.dados_dispositivos && responseChat.dados_dispositivos.naj.length > 0) {
            sessionStorage.setItem('@NAJ_WEB/dados_dispositivo_usuario_chat', btoa(JSON.stringify({'dados': responseChat.dados_dispositivos.naj})));
        } else {
            sessionStorage.removeItem('@NAJ_WEB/dados_dispositivo_usuario_chat');
        }

        this.hideContentEditorMensagemChat();
        this.hideContentAnexoChat();

        for(var i = 0; i < responseChat.data.length; i++) {
            if(!this.isBeginConversation(responseChat.data[i].conteudo) && !this.isFinishConversation(responseChat.data[i].conteudo) && !this.isTransferConversation(responseChat.data[i].conteudo) && responseChat.data[i].tipo_conteudo == '0') {
                let isEu = idUsuarioLogado == responseChat.data[i].id_usuario_mensagem;
                sHtml += this.newContentNewMessage(responseChat.data[i], isEu);
            } else if(responseChat.data[i].tipo_conteudo == 1) {
                let isEu = idUsuarioLogado == responseChat.data[i].id_usuario_mensagem;
                sHtml += this.newContentAnexo(responseChat.data[i], isEu);
            } else if(this.isBeginConversation(responseChat.data[i].conteudo)) {
                sHtml += this.newContentStartMessage(responseChat.data[i]);
            } else if(this.isTransferConversation(responseChat.data[i].conteudo)) {
                sHtml += this.newContentTransferConversation(responseChat.data[i]);
            } else {
                sHtml += this.newContentFinishMessage(responseChat.data[i]);
            }
            id_atendimento_current = responseChat.data[i].id_atendimento;
        }

        this.appendMessagesInChat(sHtml);

        if(moveScrollView)
            this.scrollToBottom();

        //Carrega as informações do RASCUNHO da mensagem
        if(loadRascunho) {
            await this.createUpdateRascunhoMessage(key.id_chat, null);
            await this.loadMessageRascunhoChat(key.id_chat);
        }

        loadingDestroy('loading-message-chat');
        // this.loadOthersInfoChat(key, moveScrollView, loadRascunho, useLoading, loadInfoUser);
    }

    async loadNewMessages(key, moveScrollView = true, useLoading = true, loadInfoUser = true, loadRascunho = true) {
        if(useLoading) {
            loadingStart('loading-message-chat');
        }

        const NajClass = new Naj();
        const parameters = btoa(JSON.stringify({"limit" : limitAtualChat, "id_usuario_chat": key.id_usuario_cliente}));
        const responseChat = await NajClass.getData(`chat/mensagem/new/${key.id_chat}?f=${parameters}`);

        //se veio dados do dispositivo, utilizado para enviar push para o dispositivo
        if(responseChat.dados_dispositivos && responseChat.dados_dispositivos.naj.length > 0) {
            sessionStorage.setItem('@NAJ_WEB/dados_dispositivo_usuario_chat', btoa(JSON.stringify({'dados': responseChat.dados_dispositivos.naj})));
        } else {
            sessionStorage.removeItem('@NAJ_WEB/dados_dispositivo_usuario_chat');
        }

        this.hideContentEditorMensagemChat();
        this.hideContentAnexoChat();
        this.updateStatusMessagesChat(responseChat.messagesReadCurrentChat);

        for(var i = 0; i < responseChat.data.length; i++) {

            if(!this.isBeginConversation(responseChat.data[i].conteudo) && !this.isFinishConversation(responseChat.data[i].conteudo) && !this.isTransferConversation(responseChat.data[i].conteudo) && responseChat.data[i].tipo_conteudo == '0') {
                let isEu = idUsuarioLogado == responseChat.data[i].id_usuario_mensagem;
                $('#content-messages-chat').append(this.newContentNewMessage(responseChat.data[i], isEu));
            } else if(responseChat.data[i].tipo_conteudo == 1) {
                let isEu = idUsuarioLogado == responseChat.data[i].id_usuario_mensagem;
                $('#content-messages-chat').append(this.newContentAnexo(responseChat.data[i], isEu));
            } else if(this.isBeginConversation(responseChat.data[i].conteudo)) {
                $('#content-messages-chat').append(this.newContentStartMessage(responseChat.data[i]));
            } else if(this.isTransferConversation(responseChat.data[i].conteudo)) {
                $('#content-messages-chat').append(this.newContentTransferConversation(responseChat.data[i]));
            } else {
                $('#content-messages-chat').append(this.newContentFinishMessage(responseChat.data[i]));
            }
            
            id_atendimento_current = responseChat.data[i].id_atendimento;
        }

        loadingDestroy('loading-message-chat');

        if(moveScrollView)
            this.scrollToBottom();

        // this.loadOthersInfoChat(key, moveScrollView, loadRascunho, useLoading, loadInfoUser);
    }

    async moreMessagesOld(key, moveScrollView = false, useLoading = true, loadInfoUser = true, loadRascunho = true) {
        if(useLoading) {
            loadingStart('loading-message-chat');
        }

        const NajClass = new Naj();
        const parameters = btoa(JSON.stringify({"offset" : offsetOldMessages, "id_usuario_chat": key.id_usuario_cliente}));
        const responseChat = await NajClass.getData(`chat/mensagem/old/${key.id_chat}?f=${parameters}`);

        //se veio dados do dispositivo, utilizado para enviar push para o dispositivo
        if(responseChat.dados_dispositivos && responseChat.dados_dispositivos.naj.length > 0) {
            sessionStorage.setItem('@NAJ_WEB/dados_dispositivo_usuario_chat', btoa(JSON.stringify({'dados': responseChat.dados_dispositivos.naj})));
        } else {
            sessionStorage.removeItem('@NAJ_WEB/dados_dispositivo_usuario_chat');
        }

        let totalHeight = 0;

        this.hideContentEditorMensagemChat();
        this.hideContentAnexoChat();

        for(var i = 0; i < responseChat.data.length; i++) {
            totalHeight += $('#content-messages-chat').children('li').first()[0].offsetHeight;
            if(!this.isBeginConversation(responseChat.data[i].conteudo) && !this.isFinishConversation(responseChat.data[i].conteudo) && !this.isTransferConversation(responseChat.data[i].conteudo) && responseChat.data[i].tipo_conteudo == '0') {
                let isEu = idUsuarioLogado == responseChat.data[i].id_usuario_mensagem;
                $(this.newContentNewMessage(responseChat.data[i], isEu)).insertBefore($('#content-messages-chat').children('li').first());
            } else if(responseChat.data[i].tipo_conteudo == 1) {
                let isEu = idUsuarioLogado == responseChat.data[i].id_usuario_mensagem;
                $(this.newContentAnexo(responseChat.data[i], isEu)).insertBefore($('#content-messages-chat').children('li').first());
            } else if(this.isBeginConversation(responseChat.data[i].conteudo)) {
                $(this.newContentStartMessage(responseChat.data[i])).insertBefore($('#content-messages-chat').children('li').first());
            } else if(this.isTransferConversation(responseChat.data[i].conteudo)) {
                $(this.newContentTransferConversation(responseChat.data[i])).insertBefore($('#content-messages-chat').children('li').first());
            } else {
                $(this.newContentFinishMessage(responseChat.data[i])).insertBefore($('#content-messages-chat').children('li').first());
            }

            id_atendimento_current = responseChat.data[i].id_atendimento;
        }

        document.getElementById('content-chat-box-full').scrollTop = totalHeight;

        loadingDestroy('loading-message-chat');

        // this.loadOthersInfoChat(key, moveScrollView, loadRascunho, useLoading, loadInfoUser);
    }

    newContentAnexo(fileUpload, isOdd) {
        let spanIconStatusMessage = `<span class="iconesStatusMessage" id="status-message-${fileUpload.id_mensagem}"><i class="mdi mdi-check-all ml-1"></i></span>`;

        //Verificar o status que veio
        if(fileUpload.status == 1) {
            spanIconStatusMessage = `<span class="iconesStatusMessage" id="status-message-${fileUpload.id_mensagem}"><i class="mdi mdi-check-all ml-1"></i></span>`;
        } else if(fileUpload.status == 2) {
            spanIconStatusMessage = `<span class="iconesStatusMessageSuccess" id="status-message-${fileUpload.id_mensagem}"><i class="mdi mdi-check-all ml-1"></i></span>`;
        } else if(fileUpload.status == 0) {
            spanIconStatusMessage = `<span class="iconesStatusMessage" id="status-message-${fileUpload.id_mensagem}"><i class="mdi mdi-check ml-1"></i></span>`;
        }

        let data_hora = '';

        if(fileUpload.data_hora.split(' ')[0] == getDataAtual()) {
            data_hora = `Hoje ${this.formaterDataInHora(fileUpload.data_hora)}`;
        } else {
            data_hora = `${this.convertDataHora(fileUpload.data_hora)}`;
        }

        let classOdd = 'color-odd-naj';

        if(!isOdd && (fileUpload.usuario_tipo_id != 3)) {
            classOdd = 'color-no-odd-naj-no-usuario-cliente-message';
        } else if(!isOdd && (fileUpload.usuario_tipo_id == 3)) {
            classOdd = 'color-no-odd-naj-usuario-cliente-message';
        }

        let extensao = fileUpload.conteudo.split('.')[1];
        let titleIconDownload = fileUpload.file_type == 2 ? 'Baixar áudio para ouvir' : 'Baixar Arquivo';
        let fileNameAttachment = ''

        if (fileUpload.file_type != 2) {
            const icon = (fileUpload.file_type == 0) ? '<i class="icon-anexo-chat fas fa-image"></i>' : '<i class="icon-anexo-chat fas fa-file"></i>'

            fileNameAttachment = `<p class="mb-0 text-chat-messages" style="margin-top: 4px; word-break: break-word;">${icon} ${fileUpload.conteudo}</p>`
        }

        return `
            <li class="${(!isOdd) ? 'no-odd-chat-naj' : 'odd-chat-naj odd '} chat-item" style="${(fileUpload.file_type == 2 && isMobile() ? 'width: 100% !important;' : '')}">
                <div class="chat-content" style="${(fileUpload.file_type == 2 && isMobile() ? 'width: 95% !important;' : '')}">
                    <div class="box bg-light-success p-2 ${classOdd}" style="max-width: 100%; ${(fileUpload.file_type == 2  && isMobile() ? 'width: 100%' : '')}">
                        <h5 class="font-medium m-0">${fileUpload.nome}</h5>
                        <div class="mt-2 content-info-anexo-chat">
                            <div class="m-0 d-flex" style="max-height: 30px;">
                                ${fileNameAttachment}
                                <i id="btn-download-${fileUpload.id_mensagem}" onclick="onClickDownloadAnexoChat(${fileUpload.id_mensagem}, '${fileUpload.conteudo}', ${fileUpload.file_type});" class="fas fa-download icon-download-chat" data-toggle="tooltip" title="${titleIconDownload}"></i>
                                ${
                                    (fileUpload.file_type == 2) ?
                                    `<audio id="audio-${fileUpload.id_mensagem}" controls style="height: 30px;">
                                        <source id="source-${fileUpload.id_mensagem}" src="" type="audio/${extensao}"/>
                                    </audio>`
                                    : ''
                                }
                            </div>
                        </div>
                        <div class="m-0">
                            <div class="m-0 content-size-anexo-chat">${this.formaterSizeAnexo(fileUpload.file_size)}</div>
                            <div class="chat-time m-0 ${(!isOdd) ? ' ajuste-hora-anexo-chat' : ''}">${data_hora}${(!isOdd) ? '' : spanIconStatusMessage}</div>
                        </div>
                    </div>
                </div>
            </li>
        `;
    }

    appendMessagesInChat(sHtml) {
        $('#content-messages-chat')[0].innerHTML = `${sHtml}`;
    }

    newContentStartMessage(message) {
        if(!message) return;
        return `
            <div class="row chat-item-inicio-fim-conversa">
                <p class="text-header-inicio-atendimento">${message.nome} - Iniciou atendimento</p>
                <p class="text-info-inicio-atendimento">${this.convertDataHora(message.data_hora)}</p>
            </div>
        `;
    }

    newContentFinishMessage(message) {
        if(!message) return;
        return `
            <div class="row mt-4 chat-item-inicio-fim-conversa">
                <p class="text-header-fim-atendimento">${message.nome} - Encerrou atendimento</p>
                <p class="text-info-fim-atendimento">${this.convertDataHora(message.data_hora)}</p>
            </div>
        `;
    }

    newContentTransferConversation(message) {
        if(!message) return;
        return `
            <div class="row mt-4 chat-item-inicio-fim-conversa">
                <p class="text-header-transfer-atendimento">${message.conteudo}</p>
                <p class="text-info-transfer-atendimento">${this.convertDataHora(message.data_hora)}</p>
            </div>
        `;
    }

    newContentNewMessage(message, isOdd) {
        if(!message) return;

        let spanIconStatusMessage = `<span class="iconesStatusMessage"><i class="mdi mdi-check-all ml-1"></i></span>`;
        //Verificar o status que veio
        if(message.status == 1) {
            spanIconStatusMessage = `<span class="iconesStatusMessage"><i class="mdi mdi-check-all ml-1"></i></span>`;
        } else if(message.status == 2) {
            spanIconStatusMessage = `<span class="iconesStatusMessageSuccess"><i class="mdi mdi-check-all ml-1"></i></span>`;
        } else if(message.status == 0) {
            spanIconStatusMessage = `<span class="iconesStatusMessage"><i class="mdi mdi-check-all ml-1"></i></span>`;
        }

        let data_hora = '';

        if(message.data_hora.split(' ')[0] == getDataAtual()) {
            data_hora = `Hoje ${this.formaterDataInHora(message.data_hora)}`;
        } else {
            data_hora = `${this.convertDataHora(message.data_hora)}`;
        }

        let classOdd = 'color-odd-naj';

        if(!isOdd && (message.usuario_tipo_id != 3)) {
            classOdd = 'color-no-odd-naj-no-usuario-cliente-message';
        } else if(!isOdd && (message.usuario_tipo_id == 3)) {
            classOdd = 'color-no-odd-naj-usuario-cliente-message';
        }

        let conteudo;
        if(message.conteudo.search('http') > -1) {
            if(message.conteudo.search('<a href="') > -1) {
                conteudo = message.conteudo;
                //conteudo = message.conteudo.replace(/((http:|https:)[^\s]+[\w])/g, '<a href="$1" target="_blank" style="word-wrap: break-word;"><i class="icone-link-chat fas fa-link mr-1"></i> $1</a>');
            } else {
                conteudo = message.conteudo.replace(/((http:|https:)[^\s]+[\w])/g, '<a href="$1" target="_blank" style="word-wrap: break-word;"><i class="icone-link-chat fas fa-link mr-1"></i> $1</a>');
            }
        } else {
            conteudo = message.conteudo.replace(/((http:|https:)[^\s]+[\w])/g, '<a href="$1" target="_blank" style="word-wrap: break-word;"><i class="icone-link-chat fas fa-link mr-1"></i>$1</a>');
        }

        return `
            <li class="${(!isOdd) ? 'no-odd-chat-naj' : 'odd-chat-naj odd '} chat-item">
                <div class="chat-content">
                    <div class="box bg-light-success p-2 ${classOdd}" style="max-width: 100%;">
                        <h5 class="font-medium m-0">${message.nome}</h5>
                        <div class="yiyiyiyiyi">
                            <span class="mb-0 text-chat-messages" style="word-wrap: break-word;">${conteudo}</span>
                        </div>
                        <div class="chat-time m-0">${data_hora}${(!isOdd) ? '' : spanIconStatusMessage}</div>
                    </div>
                </div>
            </li>
        `;
    }

    async createUpdateRascunhoMessage(id_chat, message, mensagem_digitada = false) {
        let rascunhoChat = JSON.parse(localStorage.getItem('@NAJCLIENTE/rascunho_chat'));

        if(!rascunhoChat) {
            rascunhoChat = [];
            rascunhoChat.push({id_chat, message});
        } else {
            let newChat = true;
            for(var i = 0; i < rascunhoChat.length; i++) {
                if(rascunhoChat[i].id_chat == id_chat) {
                    newChat = false;

                    //Se for digitada no chat atualiza o que ta no rascunho
                    if(mensagem_digitada) {
                        rascunhoChat[i].message = message;
                    } else {
                        rascunhoChat[i].message = rascunhoChat[i].message;
                    }
                    break;
                }
            }

            if(newChat) {
                rascunhoChat.push({id_chat, message});
            }            
        }

        localStorage.setItem('@NAJCLIENTE/rascunho_chat', JSON.stringify(rascunhoChat));
    }

    async loadMessageRascunhoChat(id_chat) {
        let rascunhoChat = JSON.parse(localStorage.getItem('@NAJCLIENTE/rascunho_chat'));

        for(var i = 0; i < rascunhoChat.length; i++) {
            if(rascunhoChat[i].id_chat == id_chat) {
                $('#input-text-chat-enviar').val(rascunhoChat[i].message);

                //Se tiver mensagem exibe o BADGE do rascunho
                if(rascunhoChat[i].message) {
                    $('#content-button-rascunho-message-chat').show();
                } else {
                    $('#content-button-rascunho-message-chat').hide();
                }
                
                break;
            } else {
                $('#content-button-rascunho-message-chat').hide();
            }
        }
    }

    async createUpdateRascunhoEditorMessage(id_chat, message, mensagem_digitada = false) {
        let rascunhoChat = JSON.parse(localStorage.getItem('@NAJCLIENTE/rascunho_chat_editor'));

        if(!rascunhoChat) {
            rascunhoChat = [];
            rascunhoChat.push({id_chat, message});
        } else {
            let newChat = true;
            for(var i = 0; i < rascunhoChat.length; i++) {
                if(rascunhoChat[i].id_chat == id_chat) {
                    newChat = false;

                    //Se for digitada no chat atualiza o que ta no rascunho
                    if(mensagem_digitada) {
                        rascunhoChat[i].message = message;
                    } else {
                        rascunhoChat[i].message = rascunhoChat[i].message;
                    }
                    break;
                }
            }

            if(newChat) {
                rascunhoChat.push({id_chat, message});
            }            
        }

        localStorage.setItem('@NAJCLIENTE/rascunho_chat_editor', JSON.stringify(rascunhoChat));
    }

    async loadMessageRascunhoEditorChat(id_chat) {
        let rascunhoChat = JSON.parse(localStorage.getItem('@NAJCLIENTE/rascunho_chat_editor'));

        for(var i = 0; i < rascunhoChat.length; i++) {
            if(rascunhoChat[i].id_chat == id_chat) {
                $('.card-body .note-editable')[0].innerHTML = rascunhoChat[i].message;

                //Se tiver mensagem exibe o BADGE do rascunho
                if(rascunhoChat[i].message) {
                    $('#content-button-rascunho-editor-message-chat').show();
                } else {
                    $('#content-button-rascunho-editor-message-chat').hide();
                }
                
                break;
            } else {
                $('#content-button-rascunho-editor-message-chat').hide();
            }
        }
    }

    updateStatusMessagesChat(messagesId) {
        for(var i = 0; i < messagesId.length; i++) {
            let element = $(`#status-message-${messagesId[i].id_mensagem}`);

            element.removeClass('iconesStatusMessage');
            element.addClass('iconesStatusMessageSuccess');
        }
    }

    showButtonsChat(status_atendimento) {
        if(status_atendimento == "null") {
            $('.content-input-mensagem-chat').hide();
            $('.text-header-historico-mensagem').show();
            $('.content-butons-chat').hide();

            $('.chat-box').removeClass('content-chat-box-no-full');
            $('.chat-box').addClass('content-chat-box-full');
        } else if(status_atendimento == "true") {
            $('.content-input-mensagem-chat').show();
            $('.text-header-historico-mensagem').hide();
            $('.content-butons-chat').show();

            $('.chat-box').removeClass('content-chat-box-full');
            $('.chat-box').addClass('content-chat-box-no-full');
        } else {
            $('.content-input-mensagem-chat').hide();
            $('.text-header-historico-mensagem').hide();
            $('.content-butons-chat').hide();
            $('.chat-box').addClass('content-chat-box-no-full');
            $('.chat-box').removeClass('content-chat-box-full');
        }
    }

    cleanInputMessage() {
        $('.input-mensagem-chat').val("");
    }

    isBeginConversation(message) {
        if(!message) return false;
        return message.search('Iniciou') > -1;
    }

    isFinishConversation(message) {
        if(!message) return false;
        return message.search('Encerrou') > -1;
    }

    isTransferConversation(message) {
        if(!message) return false;
        return message.search('Transferiu') > -1;
    }

    scrollToBottom() {
        if(!this.messageContainer.lastElementChild) return;

		this.messageContainer
			.lastElementChild
			.scrollIntoView({block: "end", behavior: "smooth"});
	}

    formaterDataInHora(value) {
        let hora = value.split(' ');

        if(!hora[1]) return '';

        return hora[1].substr(0, 5);
    }

    formarterData(value, typeDivisor = '-') {
        let data = value.split('-');

        if(!data[1]) return '';

        return `${data[2]}${typeDivisor}${data[1]}${typeDivisor}${data[0]}`;
    }

    convertDataHora(value) {
        let hora = value.split(' ');

        if(!hora[1]) return '';

        hora = hora[1].substr(0, 5);

        let data = value.split(' ')[0];
        let dia  = data.substr(8);
        let mes  = data.substr(5, 2);
        let ano  = data.substr(0, 4);

        return `${dia} ${this.convertMes(mes)}, ${ano}, ${hora}`;
    }

    convertMes(mes) {
        switch(mes) {
            case '01':
                return 'Janeiro';
            case '02':
                return 'Fevereiro';
            case '03':
                return 'Março';
            case '04':
                return 'Abril';
            case '05':
                return 'Maio';
            case '06':
                return 'Junho';
            case '07':
                return 'Julho';
            case '08':
                return 'Agosto';
            case '09':
                return 'Setembro';
            case '10':
                return 'Outubro';
            case '11':
                return 'Novembro';
            default: 
                return 'Dezembro';
        }
    }

    formaterSizeAnexo(size) {
        return `${Math.round(size / 1024)}KB` ;
    }

    hideContentAnexoChat() {
        $('#content-upload-anexos-chat').hide();
        $('.content-butons-chat').show();
        $('.chat-box').removeClass('content-chat-box-full');
        $('.chat-box').addClass('content-chat-box-no-full');
        $('#content-messages-chat').show();
        $('#input-text-chat-enviar').show();
    }

    hideContentEditorMensagemChat() {
        $('#content-editor-upload').hide();
        $('.content-butons-chat').show()
        $('#content-messages-chat').show();
        // $('#input-text-chat-enviar').show();
        $('.content-buttons-atendimento').show();
        $('.chat-box').removeClass('content-chat-box-full');
        $('.chat-box').addClass('content-chat-box-no-full');
    }

}