let processoCodigoFilter;
processoTable = new ProcessosTable();
const NajApi  = new Naj('Processos', processoTable);

$(document).ready(function() {
    
    processoTable.render();
    
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
    let name          = arquivoName.split('.')[0];
    let ext           = arquivoName.split('.')[1];

    if(result && result.size > 0) {
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
    } else {
        NajAlert.toastError('Não foi possível fazer o download, o anexo não foi encontrado!');
    }

    loadingDestroy('loading-download-anexo-processo');
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
                    ${envolvidos[indice].NOME} (${envolvidos[indice].QUALIFICACAO})
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
                    ${envolvidos[indice].NOME} (${envolvidos[indice].QUALIFICACAO})
                </div>
            </div>
        `;
    }

    $(`#partes-adv-processo-${codigo}`)[0].innerHTML = sHtml;
    $('.fa-info-circle').tooltip('update');
}