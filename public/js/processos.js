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
    let result        = await NajApi.getData(`anexos/processos/download/${parametros}`, true);
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
    debugger;
    let parameters = btoa(JSON.stringify({codigo})),
        envolvidos = await NajApi.getData(`processos/partes/${parameters}`),
        sHtml      = '';

    if(el.children) {
        let className = el.children.item(0).className;

        if(className == 'fas fa-chevron-circle-down') {
            el.children.item(0).className = 'fas fa-chevron-circle-right';
            return;
        }
        el.children.item(0).className = 'fas fa-chevron-circle-down';
    }

    for(var indice = 0; indice < envolvidos.length; indice++) {
        sHtml += `
            <div class="row" style="width: 100%;">
                <div class="row" style="width: 100%;">
                    <span><i class="font-18 mdi mdi-open-in-new cursor-pointer text-dark" title="Ver ficha do envolvido" data-toggle="tooltip" onclick="onClickFichaEnvolvido(${envolvidos[indice].CODIGO});"></i></span>
                    ${envolvidos[indice].NOME}
                    <small><span class="text-muted">(${envolvidos[indice].QUALIFICACAO}) </span></small>
                </div>
            </div>
        `;
    }

    $(`#partes-processo-${codigo}`)[0].innerHTML = sHtml;
    $('.fa-info-circle').tooltip('update');
}