/**
 * Classe do Chat de Atendimento
 */
class Chat {

    constructor() {
        this.messageContainer = document.getElementById('content-messages-chat');
    }

    async loadContacts(parameters = '') {
        //Busca todos os contatos
        const NajClass = new Naj();
        let responseContatos = await NajClass.getData(`chat/mensagens${(parameters != '') ? `?f=${parameters}&XDEBUG_SESSION_START` : '?XDEBUG_SESSION_START'}`);

        let sHtmlTodos     = '',
            sHtmlAndamento = '',
            sHtmlFila      = '';

        //TODOS
        for(var i = 0; i < responseContatos.data.todos.length; i++) {
            sHtmlTodos += this.newItemContact(responseContatos.data.todos[i], null);
        }

        //EM ANDAMENTO
        for(var i = 0; i < responseContatos.data.emAndamento.length; i++) {
            sHtmlAndamento += this.newItemContact(responseContatos.data.emAndamento[i], true);
        }

        //NA FILA
        for(var i = 0; i < responseContatos.data.naFila.length; i++) {
            sHtmlFila += this.newItemContact(responseContatos.data.naFila[i], false);
            $('#iconBellBlink').addClass('iconBlink');
        }

        this.appendContacts(sHtmlTodos, sHtmlAndamento, sHtmlFila);
    }

    appendContacts(sHtmlTodos, sHtmlAndamento, sHtmlFila) {
        let ativo = $('.customtab .active')[0].getAttribute('data-link-nav-chat');
        //Monta na tela
        $('#content-list-contatos')[0].innerHTML = '';
        $('#content-list-contatos')[0].innerHTML = `
            <div class="tab-pane ${(ativo == 'todos') ? 'active' : ''} data-table-content naj-scrollable" id="content-todos" role="tabpanel" style="height:calc(100vh - 160px);">
                <div class="row m-0 pt-1 pb-2 mb-dropdown-item-divider">
                    <div class="input-group pl-2 pr-1" style="width: 90% !important;">
                        <div class="input-group-prepend">
                            <div class="input-group-text" id="btnGroupAddon" onclick="onClickFilterUserChat();" style="cursor: pointer;"><i class="fas fa-search"></i></div>
                        </div>
                        <input type="text" id="filter-name-chat" class="form-control" placeholder="Pesquisar por cliente" title="Pesquisar por cliente">
                    </div>
                    <div class="btn-group dropright show pl-0"  style="width: 5% !important;">
                        <button type="button" class="btn btn-light btn-light-atendimento-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-filter" act="1"></i></button>
                        <div class="content-dropbox-filter-chat-time dropdown-menu pb-0">
                            <a class="dropdown-item mb-dropdown-item-divider" href="#" id="filter-data-atual" onclick="onClickFilterDataChat();" style="margin-top: -10px;"><i class="far fa-calendar-alt mr-2"></i>Mês Atual</a>
                            <a class="dropdown-item mb-dropdown-item-divider" href="#" id="filter-data-7" onclick="onClickFilterDataChat(7);"><i class="far fa-calendar-alt mr-2"></i>Últimos 7 Dias</a>
                            <a class="dropdown-item mb-dropdown-item-divider" href="#" id="filter-data-15" onclick="onClickFilterDataChat(15);"><i class="far fa-calendar-alt mr-2"></i>Últimos 15 Dias</a>
                            <a class="dropdown-item mb-dropdown-item-divider" href="#" id="filter-data-30" onclick="onClickFilterDataChat(30);"><i class="far fa-calendar-alt mr-2"></i>Últimos 30 Dias</a>
                            <a class="dropdown-item" href="#" id="filter-data-60" onclick="onClickFilterDataChat(60);"><i class="far fa-calendar-alt mr-2"></i>Últimos 60 Dias</a>
                        </div>
                    </div>
                </div>
                <ul class="mailbox list-style-none">
                    <li>
                        <div class="message-center chat-scroll">
                            ${sHtmlTodos}
                        </div>
                    </li>
                </ul>
            </div>
            <div class="tab-pane ${(ativo == 'andamento') ? 'active' : ''} data-table-content naj-scrollable" id="content-em-andamento" role="tabpanel">
                <ul class="mailbox list-style-none">
                    <li>
                        <div class="message-center chat-scroll">
                            ${sHtmlAndamento}
                        </div>
                    </li>
                </ul>
            </div>
            <div class="tab-pane ${(ativo == 'fila') ? 'active' : ''} data-table-content naj-scrollable" id="content-fila" role="tabpanel">
                <ul class="mailbox list-style-none">
                    <li>
                        <div class="message-center chat-scroll">
                            ${sHtmlFila}
                        </div>
                    </li>
                </ul>
            </div>
        `;

        $(`.dropdown-item`).removeClass('item-filter-data-chat-selected');
        $(`#filter-data-${filterDataChat.itemListSelected}`).addClass('item-filter-data-chat-selected');
    }

    async loadInfoUsuario(key) {
        await this.loadInfoUsuarioProcesso(key.id_usuario_cliente);
        await this.loadInfoUsuarioDocumentos(key.id_usuario_cliente);
        await this.loadInfoUsuarioFinanceiro(key.id_usuario_cliente);
    }

    async loadInfoUsuarioDocumentos(id_usuario_cliente) {
        if(!id_usuario_cliente) {
            this.appendInfoUsuarioDocumentos(`
                <div class="text-no-process-chat">
                    <p>Sem informações...</p>
                </div>
            `);
            return;
        }
        const NajClass = new Naj();

        let parameters = btoa(JSON.stringify({id_usuario_cliente}));
        let documentos  = await NajClass.getData(`documentos/show/${parameters}?XDEBUG_SESSION_START`),
            sHtml      = ``;

        if(documentos.data.length < 1) {
            sHtml = `
                <div class="text-no-process-chat">
                    <p>Sem informações...</p>
                </div>
            `;
        }

        for(var i = 0; i < documentos.data.length; i++) {
            sHtml += this.newInfoUsuarioDocumentos(documentos.data[i]);
        }
        
        this.appendInfoUsuarioDocumentos(sHtml);
    }

    async loadInfoUsuarioFinanceiro(id_usuario_cliente) {
        if(!id_usuario_cliente) {
            this.appendInfoUsuarioFinanceiro(`
                <div class="text-no-process-chat">
                    <p>Sem informações...</p>
                </div>
            `);
            return;
        }
        const NajClass = new Naj();

        let parameters = btoa(JSON.stringify({id_usuario_cliente}));
        let processos  = await NajClass.getData(`processos/paginate?f=${parameters}&XDEBUG_SESSION_START`),
            sHtml      = ``;

        if(processos.resultado.length < 1) {
            sHtml = `
                <div class="text-no-process-chat">
                    <p>Sem informações...</p>
                </div>
            `;
        }

        for(var i = 0; i < processos.resultado.length; i++) {
            sHtml += this.newInfoUsuarioFinanceiro(processos.resultado[i]);
        }
        
        this.appendInfoUsuarioFinanceiro(sHtml);
    }

    async loadInfoUsuarioProcesso(id_usuario_cliente) {
        if(!id_usuario_cliente) {
            this.appendInfoUsuarioProcesso(`
                <div class="text-no-process-chat">
                    <p>Sem informações...</p>
                </div>
            `);
            return;
        }
        const NajClass = new Naj();

        let parameters = btoa(JSON.stringify({id_usuario_cliente}));
        let processos  = await NajClass.getData(`processos/paginate?f=${parameters}&XDEBUG_SESSION_START`),
            sHtml      = ``;

        if(processos.resultado.length < 1) {
            sHtml = `
                <div class="text-no-process-chat">
                    <p>Sem informações...</p>
                </div>
            `;
        }

        for(var i = 0; i < processos.resultado.length; i++) {
            let anexosProcesso = await NajClass.getData(`processos/anexos/${processos.resultado[i].CODIGO_PROCESSO}?XDEBUG_SESSION_START`),
                sHtmlAnexos    = '';

            sHtmlAnexos = await this.newInfoUsuarioProcessoAnexo(processos.resultado[i], anexosProcesso);
            sHtml += this.newInfoUsuarioProcesso(processos.resultado[i], sHtmlAnexos);
        }
        
        this.appendInfoUsuarioProcesso(sHtml);
    }

    newInfoUsuarioFinanceiro(processo) {
        return `
            
        `;
    }

    newInfoUsuarioDocumentos(documento) {
        let sHtml = ``,
            nome  = '',
            id_anexo = 0,
            pessoaCodigo = 0;

        for(var indice = 0; indice < documento.length; indice++) {
            sHtml += `
                <div class="row-content-anexo-processo row row-zebra-memo">
                    <div class="custom-control custom-checkbox col-1" style="margin-right: -15px;">
                        <input key="${btoa(JSON.stringify({"id" : documento[indice].ID, "name": documento[indice].DESCRICAO}))}" type="checkbox" class="custom-control-input" id="processo-anexo-row-${documento[indice].CODIGO}-${indice}" onclick="onClickCheckDocumentos();">
                        <label class="custom-control-label" for="processo-anexo-row-${documento[indice].CODIGO}-${indice}">&nbsp;</label>
                    </div>
                    <div class="p-0 col-8">
                        ${(documento[indice].DESCRICAO.length > 25) ? `${documento[indice].DESCRICAO.substr(0, 22)}...` : `${documento[indice].DESCRICAO}`}
                        ${(documento[indice].DESCRICAO.length > 25) ? `<span style="margin-left: -9px !important;">
                            <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="${documento[indice].DESCRICAO}"></i>
                        </span>` : ``}
                    </div>
                    <div class="p-0 col-3">
                        ${this.formarterData(documento[indice].DATA_ARQUIVO, '/')}
                    </div>
                </div>
            `;
            nome = documento[indice].NOME;
            id_anexo = documento[indice].ID;
            pessoaCodigo = documento[indice].CODIGO;
        }

        return `
            <div class="m-0 p-1 d-flex flex-row comment-row row-anexo-documentos">
                <div class="pl-2 comment-text w-100">
                    <div class="font-12">
                        <span class="font-12">
                            <i class="font-18 mdi mdi-open-in-new cursor-pointer text-dark mr-1" title="Ver ficha completa da pessoa" data-toggle="tooltip" onclick="onClickFichaPessoa(${pessoaCodigo});" style="margin-top: 3px;"></i>
                            ${(nome.length > 25) ? `${nome.substr(0, 22)}...` : `${nome}`}
                            ${(nome.length > 25) ? `<span>
                                <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="${nome}"></i>
                            </span>` : ``}
                            ${(nome.length > 25) ? `<br>` : ``}
                            <span class="badge badge-secondary badge-rounded" title="${(documento.length) ? `+${documento.length} ANEXO(S)`  : ``}">${(documento.length) ? `+${documento.length} ANEXOS`  : ``}</span>
                            <span class="action-icons">
                                <a data-toggle="collapse" href="#documentos-chat-${pessoaCodigo}-${id_anexo}" aria-expanded="false" onclick="onClickAnexoDocumentoProcesso(this);">
                                    <i class="fas fa-chevron-circle-right" title="Clique para ver os documentos" data-toggle="tooltip"></i>
                                </a>
                            </span>
                        </span>
                        <div class="collapse mt-1 well" id="documentos-chat-${pessoaCodigo}-${id_anexo}" aria-expanded="false">
                            ${sHtml}
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    newInfoUsuarioProcesso(processo, sHtmlAnexos) {
        let sHtmlUltimaAtividade = `
            <div class="font-12 title-items-processo-chat">Última Atividade:</div>
            <span class="font-12 text-muted">Não há informações.</span>
        `;

        let sHtmlUltimoAndamento = `
            <div class="font-12 title-items-processo-chat">Último Andamento:</div>
            <span class="font-12 text-muted">Não há informações.</span>
        `;

        let sHtmlQtdeClientes = ``;
        let sHtmlEnvolvidos   = '';

        if(processo.ULTIMA_ATIVIDADE_DATA) {
            sHtmlUltimaAtividade = `
                <div class="font-12 title-items-processo-chat">Última Atividade:</div>
                <div class="font-12 text-muted">Data: ${processo.ULTIMA_ATIVIDADE_DATA}</div>
                <div class="font-12 text-muted">Descrição: ${processo.ULTIMA_ATIVIDADE_DESCRICAO}.</div>
            `;
        }

        if(processo.ULTIMO_ANDAMENTO_DESCRICAO) {
            sHtmlUltimoAndamento = `
                <div class="font-12 title-items-processo-chat">Último Andamento:</div>
                <div class="font-12 text-muted">Data: ${processo.ULTIMO_ANDAMENTO_DATA}</div>
                <div class="font-12 text-muted">Descrição: ${processo.ULTIMO_ANDAMENTO_DESCRICAO}.</div>
            `;
        }

        if(processo.QTDE_CLIENTES) {
            sHtmlQtdeClientes = `<span class="badge badge-secondary badge-rounded" title="+${processo.QTDE_CLIENTES} ENVOLVIDO(S)">+${processo.QTDE_CLIENTES} ENV.</span>`;
            sHtmlEnvolvidos   = `
                <span class="action-icons">
                    <a data-toggle="collapse" href="#partes-processo-${processo.CODIGO_PROCESSO}" data-key-processo="${processo.CODIGO_PROCESSO}" aria-expanded="false" onclick="onClickEnvolvidosProcesso(${processo.CODIGO_PROCESSO}, this);">
                        <i class="fas fa-chevron-circle-right" title="Clique para ver os envolvidos" data-toggle="tooltip"></i>
                    </a>
                </span>
            `;
        }

        return `
            <div class="m-0 p-1 d-flex flex-row comment-row row-anexo-processo">
                <div class="pl-2 comment-text w-100">
                    <div class="font-12 title-items-processo-chat">Informações do Processo:</div>
                    <div class="font-12 text-medium text-muted">
                        <div>
                            Código: ${processo.CODIGO_PROCESSO} <i class="font-18 mdi mdi-open-in-new cursor-pointer text-dark" title="Ver ficha do processo" data-toggle="tooltip" onclick="onClickFichaProcesso(${processo.CODIGO_PROCESSO});"></i>
                            ${(processo.SITUACAO == "ENCERRADO") ? `<span class="badge badge-danger badge-rounded" title="Baixado">Baixado</span>` : ``}
                        </div>
                        ${(processo.NUMERO_PROCESSO_NEW) ? `<div>Número: ${processo.NUMERO_PROCESSO_NEW}</div>` : ``}
                        ${(processo.CLASSE) ? `<div>${processo.CLASSE}</div>` : ``}
                        ${(processo.CARTORIO && processo.COMARCA && processo.COMARCA_UF) ? `<div>${processo.CARTORIO} - ${processo.COMARCA} (${processo.COMARCA_UF})</div>` : ``}                        
                    </div>
                    <div class="font-12 title-items-processo-chat">Envolvidos:</div>
                    <div class="font-12">
                        <i class="font-18 mdi mdi-open-in-new cursor-pointer text-dark" title="Ver ficha do envolvido" data-toggle="tooltip" onclick="onClickFichaEnvolvido(${processo.CODIGO_CLIENTE});"></i>
                        <span class="font-12">
                            ${(processo.NOME_CLIENTE.length > 25) ? `${processo.NOME_CLIENTE.substr(0, 20)}...` : `${processo.NOME_CLIENTE}`}
                            ${(processo.NOME_CLIENTE.length > 25) ? `<span>
                                <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="${processo.NOME_CLIENTE}"></i>
                            </span>` : ``}
                            <small><span class="text-muted">(${processo.QUALIFICA_CLIENTE}) </span></small>
                            ${(processo.NOME_CLIENTE.length > 25) ? `<br>` : ``}
                            ${sHtmlQtdeClientes}
                            ${sHtmlEnvolvidos}
                        </span>
                        <div class="collapse mt-1 well" id="partes-processo-${processo.CODIGO_PROCESSO}" aria-expanded="false"></div>
                        <div class="font-12">
                            <i class="font-18 mdi mdi-open-in-new cursor-pointer text-dark" title="Ver ficha do envolvido" data-toggle="tooltip" onclick="onClickFichaEnvolvido(${processo.CODIGO_ADVERSARIO});"></i>
                            ${processo.NOME_ADVERSARIO} 
                            <small><span class="text-muted">(${processo.QUALIFICA_ADVERSARIO})</span></small>
                        </div>
                    </div>
                    ${sHtmlUltimoAndamento}
                    ${sHtmlUltimaAtividade}
                    ${sHtmlAnexos}
                </div>
            </div>
        `;
    }

    async newInfoUsuarioProcessoAnexo(processo, anexos) {
        let sHtmlAnexos = '';
        let sHtmlAnexosAriaExpanded = '';
        let sHtmlAnexosSemInformacao = '<span class="font-12 text-muted">Não há informações.</span>';
        let contadorAnexos = 0;

        for(var indice = 0; indice < anexos.length; indice++) {

            if(anexos[indice].NOME_ARQUIVO == 'DIR') {
                continue;
            }

            contadorAnexos++;
            sHtmlAnexos += `
                <div class="row-content-anexo-processo row row-zebra-memo">
                    <div class="custom-control custom-checkbox col-1" style="margin-right: -15px;">
                        <input key="${btoa(JSON.stringify({"id" : anexos[indice].ID, "name": anexos[indice].DESCRICAO}))}" type="checkbox" class="custom-control-input" id="processo-anexo-${anexos[indice].CODIGO_PROCESSO}-row-${indice}" onclick="onClickCheckAnexoProcesso();">
                        <label class="custom-control-label" for="processo-anexo-${anexos[indice].CODIGO_PROCESSO}-row-${indice}">&nbsp;</label>
                    </div>
                    <div class="p-0 col-8 input-group">
                        <span>${(anexos[indice].DESCRICAO.length > 25) ? `${anexos[indice].DESCRICAO.substr(0, 22)}...` : `${anexos[indice].DESCRICAO}`}</span>&emsp;
                        ${(anexos[indice].DESCRICAO.length > 25) ? `<span style="margin-left: -9px !important;">
                            <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="${anexos[indice].DESCRICAO}"></i>
                        </span>` : ``}
                        
                    </div>
                    <div class="p-0 col-3">
                        ${this.formarterData(anexos[indice].DATA_ARQUIVO, '/')}
                    </div>
                </div>
            `;
        }

        if(anexos.length > 0) {
            sHtmlAnexosSemInformacao = '';
            sHtmlAnexosAriaExpanded = `
                <span class="action-icons">
                    <a data-toggle="collapse" href="#processo-${processo.CODIGO_PROCESSO}-anexo" data-key-processo-anexo="${processo.CODIGO_PROCESSO}" aria-expanded="false" onclick="onClickAnexoProcesso(this);">
                        <i class="fas fa-chevron-circle-right" title="Clique para ver os anexos" data-toggle="tooltip"></i>
                    </a>
                </span>
            `;
        }

        return `
            <div class="font-12 title-items-processo-chat">
                Documentos:
                <span class="badge badge-secondary badge-rounded" title="${(anexos.length) ? `+${contadorAnexos} ANEXO(S)`  : ``}">${(anexos.length) ? `+${contadorAnexos} ANEXO(S)`  : ``}</span>
                ${sHtmlAnexosAriaExpanded}
            </div>
            ${sHtmlAnexosSemInformacao}
            <div class="font-12 collapse well" id="processo-${processo.CODIGO_PROCESSO}-anexo">
                ${sHtmlAnexos}
            </div>
        `;
    }

    appendInfoUsuarioDocumentos(sHtml) {
        $('#info-documentos-user')[0].innerHTML = ``;
        $('#info-documentos-user')[0].innerHTML = `
            <div class="comment-widgets pb-0 mb-0">
                ${sHtml}
            </div>
            <div id="button-encaminhar-documento" style="display: none;">
                <button type="button" class="btn btn-info ml-1" style="width: 15vw !important; margin-left: 4px !important;" onclick="shareAnexos('row-anexo-documentos');"><i class="fas fa-share mr-2"></i>Encaminhar<span class="ml-1" id="contador-check-documentos">(0)</span></button>
                <button type="button" class="btn btn-danger" style="width: 8vw !important;" onclick="onClickCancelarEncaminharAnexos('row-anexo-documentos');"><i class="fas fa-times mr-2"></i>Cancelar </button>
            </div>
        `;
        $('.fa-info-circle').tooltip('update');
    }

    appendInfoUsuarioFinanceiro(sHtml) {
        $('#info-financeiro-user')[0].innerHTML = ``;
        $('#info-financeiro-user')[0].innerHTML = `
            <div class="comment-widgets pb-0 mb-0">
                ${sHtml}
            </div>
        `;
    }

    appendInfoUsuarioProcesso(sHtml) {
        $('#info-processos-user')[0].innerHTML = ``;
        $('#info-processos-user')[0].innerHTML = `
            <div class="comment-widgets pb-0 mb-0">
                ${sHtml}
            </div>
            <div id="button-encaminhar-anexo-processo" style="display: none;">
                <button type="button" id="" class="btn btn-info ml-1" style="width: 15vw !important; margin-left: 4px !important;" onclick="shareAnexos('row-anexo-processo');"><i class="fas fa-share mr-2"></i>Encaminhar<span class="ml-1" id="contador-check-processo">(0)</span></button>
                <button type="button" class="btn btn-danger" style="width: 8vw !important;" onclick="onClickCancelarEncaminharAnexos('row-anexo-processo');"><i class="fas fa-times mr-2"></i>Cancelar </button>
            </div>
        `;

        $('.fa-info-circle').tooltip('update');
    }

    async loadMessageInChat(key, moveScrollView = true, useLoading = true) {
        if(useLoading) {
            loadingStart('loading-message-chat');
        }

        const NajClass = new Naj();

        let parameters = btoa(JSON.stringify({"limit" : limitAtualChat}));
        let responseChat = await NajClass.getData(`chat/mensagem/publico/${key.id_chat}?f=${parameters}&XDEBUG_SESSION_START`);
        let sHtml = "";

        if(!responseChat.isLastPage) {
            sHtml = `
                <div class="row chat-item-mostrar-mais-messages" onclick="onClickButtonMaisMensagemChat();">
                    <p class="text-button-mais-mensagem-chat">Mostrar mais mensagem...</p>
                </div>
            `;
        }

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
        $('.content-message-select-user-chat').hide();

        if(moveScrollView) {
            chat.scrollToBottom();
        }

        //Carrega as informações do RASCUNHO da mensagem
        await this.createUpdateRascunhoMessage(key.id_chat, null);
        await this.loadMessageRascunhoChat(key.id_chat);

        //Carrega as informações do usuário, PROCESSOS, CADASTRO E RELACIONAMENTOS
        // await this.loadInfoUsuario(key);
        
        loadingDestroy('loading-message-chat');
    }

    newContentAnexo(fileUpload, isOdd) {
        let spanIconStatusMessage = `<span class="iconesStatusMessage"><i class="mdi mdi-check ml-1"></i></span>`;

        //Verificar o status que veio
        if(fileUpload.status == 1) {
            spanIconStatusMessage = `<span class="iconesStatusMessage"><i class="mdi mdi-check-all ml-1"></i></span>`;
        } else if(fileUpload.status == 2) {
            spanIconStatusMessage = `<span class="iconesStatusMessageSuccess"><i class="mdi mdi-check-all ml-1"></i></span>`;
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

        return `
            <li class="${(!isOdd) ? 'no-odd-chat-naj' : 'odd-chat-naj odd '} chat-item">
                <div class="chat-content">
                    <div class="box bg-light-success p-2 ${classOdd}">
                        <h5 class="font-medium m-0">${fileUpload.nome}</h5>
                        <div class="mt-2 content-info-anexo-chat">
                            <div class="m-0">
                                <button class="btn btn-sm btn-rounded btn-download-chat" onclick="onClickDownloadAnexoChat(${fileUpload.id_mensagem}, '${fileUpload.conteudo}');">
                                    <i class="far fa-arrow-alt-circle-down icon-download-chat"></i>
                                </button>
                            </div>
                            <p class="mb-0 text-chat-messages" style="margin-top: 4px;">${fileUpload.conteudo}</p>
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

        let spanIconStatusMessage = `<span class="iconesStatusMessage"><i class="mdi mdi-check ml-1"></i></span>`;
        //Verificar o status que veio
        if(message.status == 1) {
            spanIconStatusMessage = `<span class="iconesStatusMessage"><i class="mdi mdi-check-all ml-1"></i></span>`;
        } else if(message.status == 2) {
            spanIconStatusMessage = `<span class="iconesStatusMessageSuccess"><i class="mdi mdi-check-all ml-1"></i></span>`;
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

        return `
            <li class="${(!isOdd) ? 'no-odd-chat-naj' : 'odd-chat-naj odd '} chat-item">
                <div class="chat-content">
                    <div class="box bg-light-success p-2 ${classOdd}">
                        <h5 class="font-medium m-0">${message.nome}</h5>
                        <div class="yiyiyiyiyi">
                            <span class="mb-0 text-chat-messages">${message.conteudo}</span>                            
                        </div>
                        <div class="chat-time m-0">${data_hora}${(!isOdd) ? '' : spanIconStatusMessage}</div>
                    </div>
                </div>
            </li>
        `;
    }

    newItemContact(item, status_atendimento = null) {
        let classSelected = (item.id_chat == id_chat_current_selected) ? 'selected-conversa-chat' : '';
        let mensagem = item.ultima_mensagem;

        if(item.ultima_mensagem.length > 30) {
            mensagem = `${item.ultima_mensagem.substr(0, 28)} ...`;
        }
        return `
            <a key="${btoa(JSON.stringify({'id_chat' : item.id_chat, 'id_usuario_cliente': item.id_usuario_cliente}))}" href="javascript:void(0)" class="message-item ${classSelected}" data-chat-status="${status_atendimento}">
                <span class="user-img"> <img src="${appUrl}imagens/user.png" alt="user" class="rounded-circle"></span>
                <div class="mail-contnet">
                    <h5 class="message-title weight-500">${item.cliente}<span class="text-horario-chat">${this.formaterDataInHora(item.data_hora)}</span></h5>
                    <div class="d-flex">
                        <span class="mail-desc" style="margin-right: 8%;">${(item.id_usuario_cliente == idUsuarioLogado) ? `Você: ${mensagem}` : mensagem}</span>
                        ${(item.qtde_novas > 0) ? `<span class="badge badge-success text-white font-normal badge-pill float-right" style="right: 20px; position: absolute;">${item.qtde_novas}</span>` : ''}</div>
                </div>
            </a>
        `;
    }

    async createUpdateRascunhoMessage(id_chat, message, mensagem_digitada = false) {
        let rascunhoChat = JSON.parse(sessionStorage.getItem('@NAJWEB/rascunho_chat'));

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

        sessionStorage.setItem('@NAJWEB/rascunho_chat', JSON.stringify(rascunhoChat));
    }

    async loadMessageRascunhoChat(id_chat) {
        let rascunhoChat = JSON.parse(sessionStorage.getItem('@NAJWEB/rascunho_chat'));

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
        let rascunhoChat = JSON.parse(sessionStorage.getItem('@NAJWEB/rascunho_chat_editor'));

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

        sessionStorage.setItem('@NAJWEB/rascunho_chat_editor', JSON.stringify(rascunhoChat));
    }

    async loadMessageRascunhoEditorChat(id_chat) {
        let rascunhoChat = JSON.parse(sessionStorage.getItem('@NAJWEB/rascunho_chat_editor'));

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
		this.messageContainer
			.lastElementChild
			.scrollIntoView({
				behavior: 'smooth'
			});
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

}