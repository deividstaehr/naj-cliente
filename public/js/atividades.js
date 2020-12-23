let atividadeCodigoFilter;
atividadesTable = new AtividadeTable();
const NajApi    = new Naj('Atividades', atividadesTable);

$(document).ready(function() {
    
    atividadesTable.render();
    getCustomFilters();

    //Ao clicar em pesquisar...
    $(document).on("click", '#search-button', function () {
        buscaPersonalizada();
    });

    //Abre ou fecha drop das data rápidas ao clicar no botão das datas rápidas
    $(document).on('click', '#dropDatasRapidas', function() {
        if($('#listDatasRapidas')[0].attributes.class.value.search("action-in-open") > 0){
            removeClassCss('action-in-open', '#listDatasRapidas');
        } else {
            addClassCss('action-in-open', '#listDatasRapidas');
        }
    });
    
    //Fecha o drop down das data rápidas ao clicar fora do drop down das datas rápidas 
    $(document).on('click', function (e) {
        if(e.target.attributes['class'] != undefined){
            if(e.target.attributes.class.value.search('componenteDatasRapidas') == -1){
                removeClassCss('action-in-open', '#listDatasRapidas');
            }
        }
    });

    //Executa a busca ao presionar enter com um campo focado
    $(document).keypress(function(e){
        if(e.keyCode === 13){
            if($("#filter-data-inicial").is(":focus") || $("#filter-data-final").is(":focus")){
                buscaPersonalizada();
            }
        }
    });
    
});

function onClickExibirModalAnexoAtividade(codigo) {
    atividadeCodigoFilter = codigo;
    anexoAtividadesTable = new AnexoAtividadeTable();
    anexoAtividadesTable.render();
    $('#modal-anexo-atividade').modal('show');
}

async function getCustomFilters() {
    content = `
        <div style="display: flex;" class="font-12">
            <div style="display: flex; align-items: center;" class="m-1">
                <span>Período Entre</span>
            </div>
            <input type="text" id="filter-data-inicial" width="150" class="form-control" placeholder="__/__/____  ">
            <div style="display: flex; align-items: center;" class="m-1">
                <span>E</span>
            </div>
            <input type="text" id="filter-data-final" width="150" class="form-control" placeholder="__/__/____">
            <div class="actions-in m-1">
                <button id="dropDatasRapidas" class="btn btnCustom action-in-button componenteDatasRapidas">
                    <i id="iconDropDatasRapidas" class="fas fa-filter btn-icon componenteDatasRapidas"></i>
                </button>
                <ul id="listDatasRapidas" class="actions-in-list" style="display:none;">
                    <li class="action-in-item componenteDatasRapidas" onclick="setaDataRapida(1,this)">
                        <span class="componenteDatasRapidas">Mês Atual</span>
                    </li>
                    <li class="action-in-item componenteDatasRapidas" onclick="setaDataRapida(2,this)">
                        <span class="componenteDatasRapidas">Últimos 30 dias</span>
                    </li>
                    <li class="action-in-item componenteDatasRapidas" onclick="setaDataRapida(3,this)">
                        <span class="componenteDatasRapidas">Últimos 60 dias</span>
                    </li>
                    <li class="action-in-item componenteDatasRapidas" onclick="setaDataRapida(4,this)">
                        <span class="componenteDatasRapidas">Últimos 90 dias</span>
                    </li>
                    <li class="action-in-item componenteDatasRapidas" onclick="setaDataRapida(5,this)">
                        <span class="componenteDatasRapidas">Últimos 6 Meses</span>
                    </li>
                    <li class="action-in-item componenteDatasRapidas" onclick="setaDataRapida(6,this)">
                        <span class="componenteDatasRapidas">Último Ano</span>
                    </li>
                    <li class="action-in-item componenteDatasRapidas dropItemSelected" onclick="setaDataRapida(7,this)">
                        <span class="componenteDatasRapidas">Todas</span>
                    </li>
                </ul>
            </div>
            <button id="search-button" class="btn btnCustom action-in-button m-1">
                <i class="fas fa-search btn-icon"></i>&nbsp;&nbsp;
                Pesquisar
            </button>
        </div>`;

    //Seta os filtros personalizados no cabeçalho do datatable
    $('.data-table-filter').html(content);
    //Seta o "datepicker" no campo de "data_distribuicao"
    $('#filter-data-inicial').datepicker({
        uiLibrary: 'bootstrap4',
        locale: 'pt-br',
        format: 'dd/mm/yyyy'
    });

    $('#filter-data-final').datepicker({
        uiLibrary: 'bootstrap4',
        locale: 'pt-br',
        format: 'dd/mm/yyyy'
    });

    //Seta máscaras
    $('#filter-data-inicial').mask('00/00/0000');
    $('#filter-data-final').mask('00/00/0000');

    //Seta margens e paddings 
    addClassCss('m-1',$('#filter-data-inicial').parent());
    addClassCss('m-1',$('#filter-data-final').parent());
    addClassCss('p-0',$('.btn-outline-secondary'));
    removeClassCss('border-left-0',$('.btn-outline-secondary'));

    //Remove icone de calendário do datepicker e seta icone calendário do fontwelsome
    $('.gj-icon').html("");
    addClassCss('far fa-calendar-alt',$('.gj-icon'));
    removeClassCss('gj-icon',$('.gj-icon'));

    //Seta data nos campos de data
    setaDataRange(7);
}

async function setaDataRange(opcaoDataRapida) {
    let dataFinal;
    switch(parseInt(opcaoDataRapida)) {
        case 1:
            //Mês atual
            month = new Date().getMonth();
            if(month < 10) month = '0' + month;
            dataInicial = getDateProperties(new Date(new Date().getFullYear(), month)).fullDate;
            break;
        case 2:
            //Últimos 30 dias
            dataInicial = getDateProperties(new Date(new Date().getTime() - (30 * 86400000))).fullDate;
            break;
        case 3:
            //Últimos 60 dias
            dataInicial = getDateProperties(new Date(new Date().getTime() - (60 * 86400000))).fullDate;
            break;
        case 4:
            //Últimos 90 dias
            dataInicial = getDateProperties(new Date(new Date().getTime() - (90 * 86400000))).fullDate;
            break;
        case 5:
            //Últimos 6 meses
            dataInicial = getDateProperties(new Date(new Date().getTime() - (180 * 86400000))).fullDate;
            break;
        case 6:
            //Último ano
            dataInicial = getDateProperties(new Date(new Date().getTime() - (365 * 86400000))).fullDate;
            break;
        case 7:
            //Último ano
            dataInicial = '0001-01-01';
            dataFinal   = '9999-01-01';
            break;
    }

    if(dataFinal != '9999-01-01' && dataInicial != '0001-01-01') {
        dataFinal = getDateProperties(new Date()).fullDate;
    }
    
    $('#filter-data-inicial').val(formatDate(dataInicial));
    $('#filter-data-final').val(formatDate(dataFinal));
}

/**
 * Seta a data personalizada selecionada e executa a busca personalizada 
 * @param integer dataRapida
 * @param element el
 */
async function setaDataRapida(opcaoDataRapida, el){
    setaDataRange(opcaoDataRapida); 
    buscaPersonalizada();
    removeClassCss('dropItemSelected', $('.componenteDatasRapidas'));
    el.attributes.class.value += " dropItemSelected";
    removeClassCss('action-in-open', '#listDatasRapidas');
}

async function buscaPersonalizada() {
    let dataInicial = $('#filter-data-inicial').val();
    let dataFinal   = $('#filter-data-final').val();

    //limpa filtros 
    atividadesTable.filtersForSearch = [];

    if(dataInicial && dataFinal){
        filter2        = {}; 
        filter2.val    = formatDate(dataInicial, false);
        filter2.val2   = formatDate(dataFinal, false);
        filter2.op     = "B";
        filter2.col    = "A.DATA";
        filter2.origin = btoa(filter2);
        atividadesTable.filtersForSearch.push(filter2);
    }

    await atividadesTable.load();
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
  
        // Create a new anchor element
        const a = document.createElement('a');
        
        // Set the href and download attributes for the anchor element
        // You can optionally set other attributes like `title`, etc
        // Especially, if the anchor element will be attached to the DOM
        a.href = url;
        a.download = arquivoName || 'download';
        
        // Click handler that releases the object URL after the element has been clicked
        // This is required for one-off downloads of the blob content
        const clickHandler = () => {
            setTimeout(() => {
                URL.revokeObjectURL(url);
                this.removeEventListener('click', clickHandler);
            }, 150);
        };
        
        // Add the click event listener on the anchor element
        // Comment out this line if you don't want a one-off download of the blob content
        a.addEventListener('click', clickHandler, false);
        
        // Programmatically trigger a click on the anchor element
        // Useful if you want the download to happen automatically
        // Without attaching the anchor element to the DOM
        // Comment out this line if you don't want an automatic download of the blob content
        a.click();
        
        // Return the anchor element
        // Useful if you want a reference to the element
        // in order to attach it to the DOM or use it in some other way
        loadingDestroy('loading-download-anexo-atividade');
    } else {
        NajAlert.toastError('Não foi possível fazer o download, o anexo não foi encontrado!');
        loadingDestroy('loading-download-anexo-atividade');
    }
}