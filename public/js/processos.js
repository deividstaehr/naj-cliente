let processoCodigoFilter;
processoTable = new ProcessosTable();
const NajApi  = new Naj('Processos', processoTable);

$(document).ready(function() {
    
    processoTable.render();

    //Ao esconder o modal de '#modal-manutencao-pessoa' remove a classe 'z-index-100' do modal '#modal-upload-anexo-ficha-pessoa-chat'
    $('#modal-anexo-atividade').on('hidden.bs.modal', function(){
        $('#modal-atividade-processo').removeClass('z-index-100');
    });
    
});

function onClickExibirModalAnexoProcesso(codigo) {
    processoCodigoFilter = codigo;
    anexoProcessoTable = new AnexoProcessoTable();
    anexoProcessoTable.render();
    $('#modal-anexo-processo').modal('show');
}

function onClickExibirModalAtividadeProcesso(codigo) {
    processoCodigoFilter = codigo;
    atividadeProcessoTable = new AtividadeProcessoTable();
    atividadeProcessoTable.render();
    
    $('#modal-atividade-processo').modal('show');
}

async function onClickDownloadAnexoProcesso(codigo, arquivoName) {
    if(!codigo) {
        NajAlert.toastError('Não foi possível fazer o download, recarregue a página e tente novamente!');
        return;
    }

    loadingStart('loading-download-anexo-processo');
    let identificador = sessionStorage.getItem('@NAJ_CLIENTE/identificadorEmpresa');
    let parametros    = JSON.stringify({codigo, identificador, 'original_name' : arquivoName});
    let result        = await NajApi.getData(`anexos/processos/download/${parametros}?XDEBUG_SESSION_START`, true);

    if(result && result.size > 0) {
        const url = URL.createObjectURL(result);
  
        const a = document.createElement('a');
        
        a.href = url;
        a.download = arquivoName || 'download';
        const clickHandler = () => {
            setTimeout(() => {
                URL.revokeObjectURL(url);
                this.removeEventListener('click', clickHandler);
            }, 150);
        };

        a.addEventListener('click', clickHandler, false);
        a.click();
        loadingDestroy('loading-download-anexo-processo');
    } else {
        NajAlert.toastError('Não foi possível fazer o download, o anexo não foi encontrado!');
        loadingDestroy('loading-download-anexo-processo');
    }    
}

async function onClickEnvolvidosProcesso(codigo, el) {
    let parameters = btoa(JSON.stringify({codigo})),
        envolvidos = await NajApi.getData(`processos/partes/cliente/${parameters}`),
        sHtml      = '';

    if(el.children) {
        let className = el.children.item(0).className;

        if(className == 'fas fa-chevron-circle-down icone-partes-processo-expanded') {
            el.children.item(0).className = 'fas fa-chevron-circle-right icone-partes-processo-expanded';
            return;
        }
        el.children.item(0).className = 'fas fa-chevron-circle-down icone-partes-processo-expanded';
    }

    for(var indice = 0; indice < envolvidos.length; indice++) {
        sHtml += `
            <div class="row" style="width: 100%; height: 20px !important;">
                <div class="col-12" style="margin-left: 3% !important;">
                    ${(envolvidos[indice].NOME.length > 55) 
                    ?
                    `${envolvidos[indice].NOME.substr(0, 50)}...
                        <span class="ml-1">
                            <i class="fas fa-info-circle" style="font-size: 14px;" data-toggle="tooltip" data-placement="top" title="${envolvidos[indice].NOME}"></i>
                        </span>
                    `
                    :
                    `${envolvidos[indice].NOME}`} (${envolvidos[indice].QUALIFICACAO})
                </div>
            </div>
        `;
    }

    $(`#partes-processo-${codigo}`)[0].innerHTML = sHtml;
    $('.fa-info-circle').tooltip('update');
}

async function onClickEnvolvidosProcessoAdv(codigo, el) {
    let parameters = btoa(JSON.stringify({codigo})),
        envolvidos = await NajApi.getData(`processos/partes/adversaria/${parameters}`),
        sHtml      = '';

    if(el.children) {
        let className = el.children.item(0).className;

        if(className == 'fas fa-chevron-circle-down icone-partes-processo-expanded') {
            el.children.item(0).className = 'fas fa-chevron-circle-right icone-partes-processo-expanded';
            return;
        }
        el.children.item(0).className = 'fas fa-chevron-circle-down icone-partes-processo-expanded';
    }

    for(var indice = 0; indice < envolvidos.length; indice++) {
        sHtml += `
            <div class="row" style="width: 100%; height: 20px !important;">
                <div class="col-12" style="margin-left: 3% !important;">
                    ${(envolvidos[indice].NOME.length > 55) 
                    ?
                    `${envolvidos[indice].NOME.substr(0, 50)}...
                        <span class="ml-1">
                            <i class="fas fa-info-circle" style="font-size: 14px;" data-toggle="tooltip" data-placement="top" title="${envolvidos[indice].NOME}"></i>
                        </span>
                    `
                    :
                    `${envolvidos[indice].NOME}`} (${envolvidos[indice].QUALIFICACAO})
                </div>
            </div>
        `;
    }

    $(`#partes-adv-processo-${codigo}`)[0].innerHTML = sHtml;
    $('.fa-info-circle').tooltip('update');
}

async function onClickExibirModalAndamentoProcesso(codigo_processo) {
    andamentoProcessoTable = new AndamentoProcessoTable(codigo_processo);
    andamentoProcessoTable.render();

    $('#modal-consulta-andamento-processo').modal('show');
}

function dataIsBetweenTrintaDias(data) {
    if(!data) return false;

    const splitDate  = data.split('-');
    const dataTrinta = getDateProperties(new Date(new Date().getTime() - (31 * 86400000))).fullDate

    return moment(`${splitDate[0]}-${splitDate[1]}-${splitDate[2].split(' ')[0]}`).isAfter(dataTrinta);
}

function onClickExibirModalAnexoAtividade(codigo) {
    atividadeCodigoFilter = codigo;
    anexoAtividadesTable = new AnexoAtividadeTable();
    anexoAtividadesTable.render();

    $('#modal-atividade-processo').addClass('z-index-100');
    $('#modal-anexo-atividade').modal('show');
}

async function onClickDownloadAnexoAtividade(codigo, arquivoName) {
    if(!codigo) {
        NajAlert.toastError('Não foi possível fazer o download, recarregue a página e tente novamente!');
        return;
    }

    loadingStart('loading-download-anexo-atividade');
    let identificador = sessionStorage.getItem('@NAJ_CLIENTE/identificadorEmpresa');
    let parametros    = JSON.stringify({codigo, identificador, 'original_name' : arquivoName});
    let result        = await NajApi.getData(`atividade/download/${parametros}?XDEBUG_SESSION_START`, true);

    if(result && result.size > 0) {
        const url = URL.createObjectURL(result);
  
        const a = document.createElement('a');
        
        a.href = url;
        a.download = arquivoName || 'download';
        
        const clickHandler = () => {
            setTimeout(() => {
                URL.revokeObjectURL(url);
                this.removeEventListener('click', clickHandler);
            }, 150);
        };
        
        a.addEventListener('click', clickHandler, false);
        a.click();
        loadingDestroy('loading-download-anexo-atividade');
    } else {
        NajAlert.toastError('Não foi possível fazer o download, o anexo não foi encontrado!');
        loadingDestroy('loading-download-anexo-atividade');
    }
}

async function onClickObservacaoProcesso(processoCodigo) {
    const result = await NajApi.getData(`processos/observacao/${processoCodigo}`);
    $('#modal-consulta-observacao').modal('show')
    $('#header-obersavao')[0].innerHTML = `Observações do Processo: ${processoCodigo}`

    $('#content-observation')[0].innerHTML = ``

    if (!result.data) return $('#content-observation')[0].innerHTML = `Não foi possível buscar as observações`

    let text = ``

    if (result.data[0].pedidos_processo && result.data[0].observacao) {
        text += `${result.data[0].pedidos_processo}`
        text += `<br><hr>`
        text += `${result.data[0].observacao}`
    } else if (result.data[0].pedidos_processo) {
        text += `${result.data[0].pedidos_processo}`
    } else if (result.data[0].observacao) {
        text += `${result.data[0].observacao}`
    }

    $('#content-observation')[0].innerHTML = text
}