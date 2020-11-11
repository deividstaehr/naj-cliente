financeiroReceberTable = new FinanceiroReceberTable();
financeiroPagarTable = new FinanceiroPagarTable();
const NajApi  = new Naj('Receber', financeiroReceberTable);

$(document).ready(function() {
    
    $('#sidebar-link-financeiro').addClass('active');
    $('#sidebar-item-financeiro').addClass('selected');

    financeiroReceberTable.render();
    financeiroPagarTable.render();
    getCustomFiltersReceber();
    getCustomFiltersPagar();

    //Ao clicar em pesquisar...
    $(document).on("click", '#search-button-receber', function () {
        buscaPersonalizada('receber');
    });

    //Ao clicar em pesquisar...
    $(document).on("click", '#search-button-pagar', function () {
        buscaPersonalizada('pagar');
    });

    //Abre ou fecha drop das data rápidas ao clicar no botão das datas rápidas
    $(document).on('click', '#dropDatasRapidas-pagar', function() {
        if($('#listDatasRapidas-pagar')[0].attributes.class.value.search("action-in-open") > 0){
            removeClassCss('action-in-open', '#listDatasRapidas-pagar');
        } else {
            addClassCss('action-in-open', '#listDatasRapidas-pagar');
        }
    });

    //Abre ou fecha drop das data rápidas ao clicar no botão das datas rápidas
    $(document).on('click', '#dropDatasRapidas-receber', function() {
        if($('#listDatasRapidas-receber')[0].attributes.class.value.search("action-in-open") > 0){
            removeClassCss('action-in-open', '#listDatasRapidas-receber');
        } else {
            addClassCss('action-in-open', '#listDatasRapidas-receber');
        }
    });
    
    //Fecha o drop down das data rápidas ao clicar fora do drop down das datas rápidas 
    $(document).on('click', function (e) {
        if(e.target.attributes['class'] != undefined){
            if(e.target.attributes.class.value.search('componenteDatasRapidas') == -1){
                removeClassCss('action-in-open', '#listDatasRapidas-pagar');
            }
        }
    });

    //Fecha o drop down das data rápidas ao clicar fora do drop down das datas rápidas 
    $(document).on('click', function (e) {
        if(e.target.attributes['class'] != undefined){
            if(e.target.attributes.class.value.search('componenteDatasRapidas') == -1){
                removeClassCss('action-in-open', '#listDatasRapidas-receber');
            }
        }
    });

    //Executa a busca ao presionar enter com um campo focado
    $(document).keypress(function(e){
        if(e.keyCode === 13){
            if($("#filter-data-inicial-pagar").is(":focus") || $("#filter-data-final-pagar").is(":focus")){
                buscaPersonalizada('pagar');
            }
        }
    });

    //Executa a busca ao presionar enter com um campo focado
    $(document).keypress(function(e){
        if(e.keyCode === 13){
            if($("#filter-data-inicial-receber").is(":focus") || $("#filter-data-final-receber").is(":focus")){
                buscaPersonalizada('receber');
            }
        }
    });

    if(tab_selected == 'receber') {
        onClickTabReceber();
    } else {
        onClickTabPagar();
    }
});

function onClickTabReceber() {
    $('#link-pagar').removeClass('active');
    $('#link-receber').addClass('active');

    $('#pagar').hide();
    $('#receber').show();

    $('#content-bottom-pagar').hide();
    $('#content-bottom-receber').show();
}

function onClickTabPagar() {
    $('#link-receber').removeClass('active');
    $('#link-pagar').addClass('active');

    $('#receber').hide();
    $('#pagar').show();

    $('#content-bottom-pagar').show();
    $('#content-bottom-receber').hide();
}

async function getCustomFiltersPagar() {
    content = `
        <div style="display: flex;" class="font-12">
            <div style="display: flex; align-items: center;" class="m-1">
                <span>Período Entre</span>
            </div>
            <input type="text" id="filter-data-inicial-pagar" width="150" class="form-control" placeholder="__/__/____  ">
            <div style="display: flex; align-items: center;" class="m-1">
                <span>E</span>
            </div>
            <input type="text" id="filter-data-final-pagar" width="150" class="form-control" placeholder="__/__/____">
            <div class="actions-in m-1">
                <button id="dropDatasRapidas-pagar" class="btn btnCustom action-in-button componenteDatasRapidas">
                    <i id="iconDropDatasRapidas" class="fas fa-filter btn-icon componenteDatasRapidas"></i>
                </button>
                <ul id="listDatasRapidas-pagar" class="actions-in-list" style="display:none;">
                    <li class="action-in-item componenteDatasRapidas dropItemSelected" onclick="setaDataRapida(1,this, 'pagar')">
                        <span class="componenteDatasRapidas">Mês Atual</span>
                    </li>
                    <li class="action-in-item componenteDatasRapidas" onclick="setaDataRapida(2,this, 'pagar')">
                        <span class="componenteDatasRapidas">Últimos 30 dias</span>
                    </li>
                    <li class="action-in-item componenteDatasRapidas" onclick="setaDataRapida(3,this, 'pagar')">
                        <span class="componenteDatasRapidas">Últimos 60 dias</span>
                    </li>
                    <li class="action-in-item componenteDatasRapidas" onclick="setaDataRapida(4,this, 'pagar')">
                        <span class="componenteDatasRapidas">Últimos 90 dias</span>
                    </li>
                    <li class="action-in-item componenteDatasRapidas" onclick="setaDataRapida(5,this, 'pagar')">
                        <span class="componenteDatasRapidas">Últimos 6 Meses</span>
                    </li>
                    <li class="action-in-item componenteDatasRapidas" onclick="setaDataRapida(6,this, 'pagar')">
                        <span class="componenteDatasRapidas">Último Ano</span>
                    </li>
                    <li class="action-in-item componenteDatasRapidas" onclick="setaDataRapida(7,this, 'pagar')">
                        <span class="componenteDatasRapidas">Todas</span>
                    </li>
                </ul>
            </div>
            <button id="search-button-pagar" class="btn btnCustom action-in-button m-1">
                <i class="fas fa-search btn-icon"></i>&nbsp;&nbsp;
                Pesquisar
            </button>
        </div>`;

    //Seta os filtros personalizados no cabeçalho do datatable
    $('.data-table-filter')[1].innerHTML = content;
    //Seta o "datepicker" no campo de "data_distribuicao"
    $('#filter-data-inicial-pagar').datepicker({
        uiLibrary: 'bootstrap4',
        locale: 'pt-br',
        format: 'dd/mm/yyyy'
    });

    $('#filter-data-final-pagar').datepicker({
        uiLibrary: 'bootstrap4',
        locale: 'pt-br',
        format: 'dd/mm/yyyy'
    });

    //Seta máscaras
    $('#filter-data-inicial-pagar').mask('00/00/0000');
    $('#filter-data-final-pagar').mask('00/00/0000');

    //Seta margens e paddings 
    addClassCss('m-1',$('#filter-data-inicial-pagar').parent());
    addClassCss('m-1',$('#filter-data-final-pagar').parent());
    addClassCss('p-0',$('.btn-outline-secondary'));
    removeClassCss('border-left-0',$('.btn-outline-secondary'));

    //Remove icone de calendário do datepicker e seta icone calendário do fontwelsome
    $('.gj-icon').html("");
    addClassCss('far fa-calendar-alt',$('.gj-icon'));
    removeClassCss('gj-icon',$('.gj-icon'));

    //Seta data nos campos de data
    setaDataRange(1, 'pagar');
}

async function getCustomFiltersReceber() {
    content = `
        <div style="display: flex;" class="font-12">
            <div style="display: flex; align-items: center;" class="m-1">
                <span>Período Entre</span>
            </div>
            <input type="text" id="filter-data-inicial-receber" width="150" class="form-control" placeholder="__/__/____  ">
            <div style="display: flex; align-items: center;" class="m-1">
                <span>E</span>
            </div>
            <input type="text" id="filter-data-final-receber" width="150" class="form-control" placeholder="__/__/____">
            <div class="actions-in m-1">
                <button id="dropDatasRapidas-receber" class="btn btnCustom action-in-button componenteDatasRapidas">
                    <i id="iconDropDatasRapidas" class="fas fa-filter btn-icon componenteDatasRapidas"></i>
                </button>
                <ul id="listDatasRapidas-receber" class="actions-in-list" style="display:none;">
                    <li class="action-in-item componenteDatasRapidas dropItemSelected" onclick="setaDataRapida(1,this, 'receber')">
                        <span class="componenteDatasRapidas">Mês Atual</span>
                    </li>
                    <li class="action-in-item componenteDatasRapidas" onclick="setaDataRapida(2,this, 'receber')">
                        <span class="componenteDatasRapidas">Últimos 30 dias</span>
                    </li>
                    <li class="action-in-item componenteDatasRapidas" onclick="setaDataRapida(3,this, 'receber')">
                        <span class="componenteDatasRapidas">Últimos 60 dias</span>
                    </li>
                    <li class="action-in-item componenteDatasRapidas" onclick="setaDataRapida(4,this, 'receber')">
                        <span class="componenteDatasRapidas">Últimos 90 dias</span>
                    </li>
                    <li class="action-in-item componenteDatasRapidas" onclick="setaDataRapida(5,this, 'receber')">
                        <span class="componenteDatasRapidas">Últimos 6 Meses</span>
                    </li>
                    <li class="action-in-item componenteDatasRapidas" onclick="setaDataRapida(6,this, 'receber')">
                        <span class="componenteDatasRapidas">Último Ano</span>
                    </li>
                    <li class="action-in-item componenteDatasRapidas" onclick="setaDataRapida(7,this, 'receber')">
                        <span class="componenteDatasRapidas">Todas</span>
                    </li>
                </ul>
            </div>
            <button id="search-button-receber" class="btn btnCustom action-in-button m-1">
                <i class="fas fa-search btn-icon"></i>&nbsp;&nbsp;
                Pesquisar
            </button>
        </div>`;

    //Seta os filtros personalizados no cabeçalho do datatable
    $('.data-table-filter')[0].innerHTML = content;
    //Seta o "datepicker" no campo de "data_distribuicao"
    $('#filter-data-inicial-receber').datepicker({
        uiLibrary: 'bootstrap4',
        locale: 'pt-br',
        format: 'dd/mm/yyyy'
    });

    $('#filter-data-final-receber').datepicker({
        uiLibrary: 'bootstrap4',
        locale: 'pt-br',
        format: 'dd/mm/yyyy'
    });

    //Seta máscaras
    $('#filter-data-inicial-receber').mask('00/00/0000');
    $('#filter-data-final-receber').mask('00/00/0000');

    //Seta margens e paddings 
    addClassCss('m-1',$('#filter-data-inicial-receber').parent());
    addClassCss('m-1',$('#filter-data-final-receber').parent());
    addClassCss('p-0',$('.btn-outline-secondary'));
    removeClassCss('border-left-0',$('.btn-outline-secondary'));

    //Remove icone de calendário do datepicker e seta icone calendário do fontwelsome
    $('.gj-icon').html("");
    addClassCss('far fa-calendar-alt',$('.gj-icon'));
    removeClassCss('gj-icon',$('.gj-icon'));

    //Seta data nos campos de data
    setaDataRange(1, 'receber');
}

async function setaDataRange(opcaoDataRapida, content) {
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
    
    $(`#filter-data-inicial-${content}`).val(formatDate(dataInicial));
    $(`#filter-data-final-${content}`).val(formatDate(dataFinal));
}

/**
 * Seta a data personalizada selecionada e executa a busca personalizada 
 * @param integer dataRapida
 * @param element el
 */
async function setaDataRapida(opcaoDataRapida, el, content) {
    setaDataRange(opcaoDataRapida, content); 
    buscaPersonalizada(content);
    removeClassCss('dropItemSelected', $('.componenteDatasRapidas'));
    el.attributes.class.value += " dropItemSelected";
    removeClassCss('action-in-open', '#listDatasRapidas');
}

async function buscaPersonalizada(content) {
    let dataInicial = $(`#filter-data-inicial-${content}`).val();
    let dataFinal   = $(`#filter-data-final-${content}`).val();

    if(content == 'pagar') {
        financeiroPagarTable.filtersForSearch = [];

        if(dataInicial && dataFinal) {
            let filter2    = {};
                filter2.val    = formatDate(dataInicial, false);
                filter2.val2   = formatDate(dataFinal, false);
                filter2.op     = "CF";
                filter2.col    = "CP.DATA_VENCIMENTO";
                filter2.origin = btoa(filter2);
                financeiroPagarTable.filtersForSearch.push(filter2);
        }
    
        await financeiroPagarTable.load();
    } else {
        //limpa filtros 
        financeiroReceberTable.filtersForSearch = [];

        if(dataInicial && dataFinal) {
            let filter2    = {};
                filter2.val    = formatDate(dataInicial, false);
                filter2.val2   = formatDate(dataFinal, false);
                filter2.op     = "CF";
                filter2.col    = "CP.DATA_VENCIMENTO";
                filter2.origin = btoa(filter2);
                financeiroReceberTable.filtersForSearch.push(filter2);
        }

        await financeiroReceberTable.load();
    }
}

function dataVencimentoMenorDataAtual(data_venc) {
    let anoAtual = getDataAtual().split('-')[2],
        mesAtual = getDataAtual().split('-')[1],
        diaAtual = getDataAtual().split('-')[0],
        diaVenc = data_venc.split('-')[2],
        mesVenc = data_venc.split('-')[1],
        anoVenc = data_venc.split('-')[0];

    if(anoAtual > anoVenc) {
        return true;
    } else if(anoAtual == anoVenc && mesAtual > mesVenc) {
        return true;
    } else if(anoAtual == anoVenc && mesAtual == mesVenc && diaAtual > diaVenc) {
        return true;
    } else if(anoAtual == anoVenc && mesAtual == mesVenc && diaAtual == diaVenc) {
        return true;
    }

    return false;
}