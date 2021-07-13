class AtividadeTable extends Table {

    constructor() {
        super();
        
        this.target           = 'datatable-atividades';
        this.name             = 'Atividades';
        this.route            = `atividades`;
        this.key              = ['codigo'];
        this.openLoaded       = true;
        this.isItEditable     = false;
        this.isItDestructible = false;
        this.showTitle        = false;
        this.defaultFilters   = false;
        
        this.addField({
            name: 'data_hora_inicio',
            title: 'Data/Hora Inicio',
            width: 10,
            onLoad: (data, row) =>  {
                const data_atual = getDataAtual();
                let mesAtual           = data_atual.split('-')[1] - 1;
                let mesInicio          = row.DATA_INICIO.split('/')[1] - 1;
                let data_atual_moment  = moment([data_atual.split('-')[0], mesAtual, data_atual.split('-')[2]]);
                let data_inicio_moment = moment([row.DATA_INICIO.split('/')[2], mesInicio, row.DATA_INICIO.split('/')[0]]);
                
                const days_difference = data_atual_moment.diff(data_inicio_moment, 'days');

                if(days_difference > 30) 
                    return `
                        <table style="margin: 5px 0 0 20px;">
                            <tr>
                                <td>${row.DATA_INICIO} ${row.HORA_INICIO}</td>
                            </tr>
                        </table>
                    `;

                let string_days = 'Hoje';
                if(days_difference > 0) {
                    string_days = `Há ${days_difference} dias`;
                }

                return `
                    <table style="margin: 5px 0 0 20px;">
                        <tr>
                            <td>${row.DATA_INICIO} ${row.HORA_INICIO}</td>
                        </tr>
                        <tr>
                            <td><span class="mt-1 mb-2 badge badge-warning badge-rounded badge-informacoes-processo">${string_days}</span></td>
                        </tr>
                    </table>
                    
                `;
            }
        });
        
        this.addField({
            name: 'TEMPO',
            title: 'Tempo',
            width: 5,
            onLoad: (data, row) =>  {
                if (!row.TEMPO)
                    return `00:00:00`

                return `
                    <span>${row.TEMPO}</span>
                `
            }
        });
        
        this.addField({
            name: 'DESCRICAO',
            title: 'Histórico',
            width: 40,
            onLoad: (data, row) =>  {
                return `
                    <span style="word-break: break-word;">${row.DESCRICAO}</span>
                `;
            }
        });

        this.addField({
            name: 'outras_informacao',
            title: 'Outras Informações',
            width: 25,
            onLoad: (data, row) =>  {
                let html = ''

                if(!row.NUMERO_PROCESSO_NEW && !row.CARTORIO && !row.COMARCA) {
                    html = `
                        <table>
                            <tr>
                                <td class="td-nome-parte-cliente">${row.PESSOA_CLIENTE_NOME} (Cliente)</td>
                            </tr>
                        </table>
                    `
                } else {
                    html = `
                        <table style="width: 100%;">
                            <tr>
                                <td>
                                    <table style="width: 100%;">
                                        <tr>
                                            <td class="td-nome-parte-cliente">${row.NOME_CLIENTE} (${row.QUALIFICA_CLIENTE})</td>
                                        </tr>
                                        ${(row.NOME_ADVERSARIO)
                                            ?
                                            `<tr>
                                                <td>${row.NOME_ADVERSARIO} (${row.QUALIFICA_ADVERSARIO})</td>
                                            </tr>
                                            `
                                            : ``
                                        }
                                        ${(row.NUMERO_PROCESSO_NEW)
                                            ?
                                            `<tr>
                                                <td>${row.NUMERO_PROCESSO_NEW}</td>
                                            </tr>
                                            `
                                            : ``
                                        }
                                        ${(row.CLASSE)
                                            ?
                                            `<tr>
                                                <td>${row.CLASSE}</td>
                                            </tr>
                                            `
                                            : ``
                                        }
                                        ${(row.CARTORIO && row.COMARCA && row.COMARCA_UF)
                                            ?
                                            `<tr>
                                                <td>${row.CARTORIO} - ${row.COMARCA} (${row.COMARCA_UF})</td>
                                            </tr>
                                            `
                                            : ``
                                        }
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <table style="width: 100%;">
                                        <tr>
                                            <td style="width: 50%;">
                                                ${(row.OBSERVACAO)
                                                    ?
                                                    `<span style="position: relative;">Observações <i class="fas fa-search icone-informaçoes-processo ml-2 mr-4 cursos-pointer" onclick="onClickObservacaoProcesso('${row.CODIGO_PROCESSO}');"></i></span>`
                                                    :
                                                    `<span style="position: relative;">Observações: - </span>`
                                                }
                                                
                                            </td>
                                            <td>
                                            ${(row.VALOR_CAUSA > 0)
                                                ?
                                                `<span>Valor Ação: <span class="weight-700">${convertIntToMoney(row.VALOR_CAUSA)}</span></span>`
                                                :
                                                `<span>Valor Ação: <span class="">0,00</span></span>`
                                            }
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width: 50%;">
                                                <span>Grau de Risco: <span class="${row.DESCRICAO_RISCO ? 'weight-700' : ''}">${row.DESCRICAO_RISCO || '-'}</span></span>
                                            </td>
                                            <td>
                                                ${(row.VALOR_RISCO > 0)
                                                    ?
                                                    `<span>Valor Risco: <span class="weight-700">${convertIntToMoney(row.VALOR_RISCO)}</span></span>`
                                                    :
                                                    `<span>Valor Risco: <span class="">0,00</span></span>`
                                                }
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    `
                }

                return html
            }
        });

        this.addField({
            name: 'outras_informacao',
            title: '',
            width: 20,
            onLoad: (data, row) =>  {
                return `
                    <table class="row-informacoes-processo">
                        <tr>
                            <td class="weight-500 text-dark">Responsável:</td>
                        </tr>
                        <tr>
                            <td>${row.NOME_USUARIO}</td>
                        </tr>
                        <tr>
                            <td>
                                <div class="row">
                                    <i class="fas fa-search icone-informaçoes-processo mr-2" onclick="onClickExibirModalAnexoAtividade(${row.CODIGO});"></i><span class="ml-3 mb-2 badge badge-secondary badge-rounded badge-informacoes-processo ${(row.QTDE_ANEXOS_ATIVIDADE > 0) ? `weight-500` : ``}" onclick="onClickExibirModalAnexoAtividade(${row.CODIGO});">${row.QTDE_ANEXOS_ATIVIDADE} Documento(s) Anexos</span>
                                </div>
                            </td>
                        </tr>
                    </table>
                `;
            }
        });
    }

    makeSkeleton() {
        const actionsIn = this.getActionsIn();
        const actionsOut = this.getActionsOut();
        let defaultFilters = '';

        if(this.defaultFilters){
            defaultFilters = `<div style="display: flex;">
                        <div style="display: flex; align-items: center;">
                            <span>Pesquisar em&nbsp;</span>
                        </div>
                        <select id="${this.ids.filterColumn}">
                            ${this.getFilterColumns()}
                        </select>
                        <div style="display: flex; align-items: center;">
                            <span>&nbsp;que&nbsp;</span>&nbsp;
                        </div>
                        <select id="${this.ids.filterOption}"></select>&nbsp;

                        <div id="${this.ids.filterValueContainer}" style="display: flex;"></div>

                        <div id="${this.ids.filterValue2Container}" style="display: flex;">
                            <div style="display: flex; align-items: center;">&nbsp;&nbsp;e&nbsp;&nbsp;</div>
                            <input id="${this.ids.filterValue2}" type="text" />
                        </div>

                        &nbsp;&nbsp;

                        <button id=${this.ids.filterButton} title="Adicionar filtro" class="btn btnCustom" style="background: #2f323e !important;">
                            <i class="fas fa-plus btn-icon"></i>
                        </button>

                        <div style="display: flex; align-items: center;">
                            <div class="y-separator"></div>
                        </div>

                        <button id="${this.ids.searchButton}" class="btn btnCustom" style="background: #2f323e !important;">
                            <i class="fas fa-search btn-icon"></i>&nbsp;&nbsp;
                            Pesquisar
                        </button>
                    </div>

                            <div class="data-table-filter-list" id="${this.ids.filterList}"></div>`;
        }

        fillById(this.target, `
            ${this.showTitle && `
                <div class="page-breadcrumb border-bottom">
                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-xs-12 align-self-center">
                            <h5 class="font-medium text-uppercase mb-0">${this.name}</h5>
                </div>
                        <div class="col-lg-9 col-md-8 col-xs-12 align-self-center">
                            <nav aria-label="breadcrumb" class="mt-2 float-md-right float-left">
                                <ol class="breadcrumb mb-0 justify-content-end p-0">
                                    <li class="breadcrumb-item">&nbsp;</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            ` || ''}

            <div class="datatable-body" id="${this.ids.container}">
                <div id="${this.ids.loading}" class="loader loader-default" data-half></div>

                <div class="data-table-filter">
                    
                    ${defaultFilters}
                    
                </div>

                <div class="data-table-footer">
                    <div>
                        <div class="actions-container" act="1">
                            <div class="actions-in" act="1">
                                <button id="${this.ids.actionsInButton}" class="btn btnCustom action-in-button btn-action-default" act="1">
                                    <i class="fas fa-ellipsis-v btn-icon" act="1"></i>
                                </button>
                                <ul act="1" id="list-actions-default" class="actions-in-list" style="display: none;">${actionsIn}</ul>
                            </div>
                            <div class="actions-out">
                                ${actionsOut}
                            </div>

                            <div class="y-separator"></div>
                        </div>

                        <div class="pagination-container">
                            <button id="${this.ids.fisrtPage}" title="Primeira página" class="btn btnCustom action-in-button">
                                <i class="fas fa-caret-left fa-2x btn-icon"></i>
                            </button>

                            <button id="${this.ids.previousPage}" title="Página anterior" class="btn btnCustom action-in-button">
                                <i class="fas fa-angle-left fa-2x btn-icon"></i>
                            </button>

                            <div id="${this.ids.paginationPages}"></div>

                            <button id="${this.ids.nextPage}" title="Próxima página" class="btn btnCustom action-in-button">
                                <i class="fas fa-angle-right fa-2x btn-icon"></i>
                            </button>

                            <button id="${this.ids.lastPage}" title="Última página" class="btn btnCustom action-in-button">
                                <i class="fas fa-caret-right fa-2x btn-icon"></i>
                            </button>
                        </div>

                        <div class="y-separator"></div>

                        <div class="pages-container">
                            Página&nbsp;
                            <span id="${this.ids.currentPage}">1</span>
                            &nbsp; de &nbsp;
                            <span id="${this.ids.totalPages}">1</span>
                        </div>
                    </div>
                    <div>
                        Exibir
                        &nbsp;
                        <select id="${this.ids.perPage}">
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="100" selected>100</option>
                        </select>
                        &nbsp;
                        registros de
                        &nbsp;
                        <span id="${this.ids.total}">0</span>
                        &nbsp;&nbsp;
                    </div>
                </div>

                <div class="data-table-header">
                    ${this.getHeaders()}
                </div>
                <div class="data-table-content naj-scrollable" id="${this.id}">
                    <div id="${this.ids.notSearch}" class="not-search">Nenhuma busca realizada</div>
                </div>
            </div>
        `);

        this.verifyHasAction(actionsIn, actionsOut);

        if(this.defaultFilters){
            this.loadFilterOptions();
            this.loadFilterValue();
            this.verifyBetweenFilter();

            byId(this.id).style.overflow = 'overlay';
        }
        
        this.notifyActions();
        
        if(this.defaultFilters){

            addEventById(this.ids.filterColumn, 'change', () => this.onChangeFilterColumn());
            addEventById(this.ids.filterOption, 'change', () => this.onChangeFilterOption());
            addEventById(this.ids.filterButton, 'click', () => {
                this.applyMasksEvents();

                this.addValidateFilter();
            });
        
            addEventById(this.ids.searchButton, 'click', () => this.onSearch());
            addEventById(this.ids.container, 'click', e => e.target.getAttribute('act') != 1 && this.closeActions());
            addEventById(this.ids.actionsInButton, 'click', () => {
                const list = document.querySelector(`#${this.ids.container} .actions-in-list`);

                list.classList.toggle('action-in-open');
            });
        } else if(this.forceButtonsInTreePoints) {
            addEventById(this.ids.actionsInButton, 'click', () => {
                const list = document.querySelector(`#${this.ids.container} .actions-in-list`);
    
                list.classList.toggle('action-in-open');
            });
        }
    }
    
    //Sobreescreve o método
    async load() {
        const { loading, notSearch, totalPages, totalCounter } = this.ids;

        loadingStart(loading);

        this.closeActions();

        this.resetSelectedRow();

        const oldLimit = this.limit;

        this.loadLimit();

        if (oldLimit !== this.limit) this.page = 1;

        try {
            
            let filterUser  = btoa(JSON.stringify([{'val': idUsuarioLogado}]));
            let dataInicial = $('#filter-data-inicial').val();
            let dataFinal   = $('#filter-data-final').val();

            //limpa filtros 
            this.filtersForSearch = [];

            if(dataInicial && dataFinal){
                let filter2        = {};
                filter2.val    = formatDate(dataInicial, false);
                filter2.val2   = formatDate(dataFinal, false);
                filter2.op     = "B";
                filter2.col    = "A.DATA";
                filter2.origin = btoa(filter2);
                this.filtersForSearch.push(filter2);
            } else {

                //Mês atual
                let month = new Date().getMonth();

                if(month < 10) month = '0' + month;

                dataInicial = '0001-01-01';
                dataFinal   = '9999-01-01';

                let filter2    = {};
                filter2.val    = dataInicial;
                filter2.val2   = dataFinal;
                filter2.op     = "B";
                filter2.col    = "A.DATA";
                filter2.origin = btoa(filter2);
                this.filtersForSearch.push(filter2);
            }

            let f = false;

            let filters = this.filtersForSearch.concat(this.fixedFilters);

            if (filters) f = '&f=' + this.toBase64(filters);

            const { data } = await api.get(`${this.route}/paginate?limit=${this.limit}&page=${this.page}${f || ''}&filterUser=${filterUser}`);

            this.data = data;

            this.totalPages = Math.ceil(data.total / data.limite);

            this.notifyPaginator(
                this.page > 1,
                this.page < this.totalPages
            );

            if (data.resultado.length > 0) {
                this.fillDataTable(data.resultado);
            } else {
                fillById(this.id, `
                    <div id="${notSearch}" class="not-search">
                        Nenhum registro encontrado
                    </div>
                `);
            }

            Array.from(
                document.querySelectorAll(`#${this.id} .data-table-row`)
            ).forEach(item => (
                item.addEventListener('click', e => this.onClickRow(item, e))
            ));

            fillById(totalCounter, numberWithCommas(data.total));
            fillById(totalPages, this.totalPages);
        } catch(e) {
            NajAlert.toastError('Erro ao efetuar a requisição!');
        }

        this.notifyActions();

        let filterUser   = btoa(JSON.stringify([{'val': idUsuarioLogado}]));
        let data_inicial = `${$('#filter-data-inicial').val().split('/')[2]}-${$('#filter-data-inicial').val().split('/')[1]}-${$('#filter-data-inicial').val().split('/')[0]}`;
        let data_final   = `${$('#filter-data-final').val().split('/')[2]}-${$('#filter-data-final').val().split('/')[1]}-${$('#filter-data-final').val().split('/')[0]}`;

        let responseTotalHoras = await api.get(`atividades/totalHoras/${btoa(JSON.stringify({data_inicial, data_final}))}?filterUser=${filterUser}`);

        if(responseTotalHoras.data.total_horas) {
            if(responseTotalHoras.data.total_horas[0].total_horas == null) {
                $('#total_horas')[0].innerHTML = `00:00:00`;
            } else {
                $('#total_horas')[0].innerHTML = `${responseTotalHoras.data.total_horas[0].total_horas}`;
            }
        }

        loadingDestroy(loading);
    }

}