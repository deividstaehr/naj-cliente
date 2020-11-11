class FinanceiroPagarTable extends Table {

    constructor() {
        super();
        
        this.target           = 'datatable-financeiro-pagar';
        this.name             = 'Financeiro';
        this.route            = `financeiro/pagar`;
        this.key              = ['CODIGO_CONTA'];
        this.openLoaded       = true;
        this.isItEditable     = false;
        this.isItDestructible = false;
        this.showTitle        = false;
        this.defaultFilters   = false;

        this.addField({
            name: 'CODIGO_CONTA',
            title: 'Conta',
            width: 5
        });
        
        this.addField({
            name: 'nome_pessoas',
            title: 'Pessoa(s)',
            width: 25,
            onLoad: (data, row) =>  {
                return `
                    <table class="w-100">
                        ${(row.NOME_CLIENTE && row.NOME_ADVERSARIO)
                            ?
                            `<tr>
                                <td>${row.NOME_CLIENTE} X ${row.NOME_ADVERSARIO}</td>
                            </tr>
                            `
                            : 
                            `<tr>
                                <td>${row.NOME_CLIENTE}</td>
                            </tr>
                            `
                        }
                    </table>
                `;
            }
        });
        
        this.addField({
            name: 'DESCRICAO',
            title: 'Informações da Conta',
            width: 30,
            onLoad: (data, row) =>  {
                return `
                    <table class="w-100">
                        ${(row.DESCRICAO)
                            ?
                            `<tr>
                                <td>${row.DESCRICAO} (Parcela: ${row.PARCELA_ATUAL} de ${row.PARCELA_TOTAL})</td>
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
                        ${(row.COMARCA && row.UF)
                            ?
                            `<tr>
                                <td>${row.COMARCA} (${row.UF})</td>
                            </tr>
                            `
                            : ``
                        }
                    </table>
                `;
            }
        });
        
        this.addField({
            name: 'DATA_VENCIMENTO',
            title: 'Vencimento',
            width: 7.5
        });
        
        this.addField({
            name: 'VALOR_PARCELA',
            title: 'Valor Parcela',
            width: 7.5,
            onLoad: (data, row) =>  {
                return formatter.format(row.VALOR_PARCELA);
            }
        });

        this.addField({
            name: 'SITUACAO',
            title: 'Situação',
            width: 10,
            onLoad: (data, row) =>  {
                let classeCss = '';
                let situacao  = '';

                if(row.SITUACAO == 'P') {
                    classeCss = 'badge-success';
                    situacao  = 'Paga';
                } else if(row.SITUACAO == 'R') {
                    classeCss = 'badge-success';
                    situacao  = 'Recebida';
                } else if(row.SITUACAO == 'C') {
                    classeCss = 'badge-warning';
                    situacao  = 'Cancelada';
                } else if(row.SITUACAO == 'G') {
                    classeCss = 'badge-primary';
                    situacao  = 'Agrupada';
                } else if(row.SITUACAO == 'A' && dataVencimentoMenorDataAtual(row.DATA_VENCIMENTO)) {
                    classeCss = 'badge-danger';
                    situacao  = 'Vencida';
                } else if(row.SITUACAO == 'A' && !dataVencimentoMenorDataAtual(row.DATA_VENCIMENTO)) {
                    classeCss = 'badge-info';
                    situacao  = 'A Vencer';
                }

                return `
                    <table class="row-status-processo">
                        <tr>
                            <td><span class="badge ${classeCss} badge-rounded badge-status-processo">${situacao}</span></td>
                        </tr>
                    </table>
                `;
            }
        });

        this.addField({
            name: 'DATA_PAGAMENTO',
            title: 'Pagamento',
            width: 7.5,
            onLoad: (data, row) =>  {
                if(row.DATA_PAGAMENTO) {
                    return row.DATA_PAGAMENTO;
                }
                
                return '<span style="margin-left: 45%;">-</span>';
            }
        });

        this.addField({
            name: 'VALOR_PAGAMENTO',
            title: 'Valor Pago',
            width: 7.5,
            onLoad: (data, row) =>  {
                if(row.VALOR_PAGAMENTO) {
                    return formatter.format(row.VALOR_PAGAMENTO);
                }
                
                return '<span style="margin-left: 45%;">-</span>';
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
            let f2 = false;

            let filters2 = this.filtersForSearch.concat(this.fixedFilters);

            if (filters2) f2 = '&f=' + this.toBase64(filters2);

            let filterUser = btoa(JSON.stringify([{'val': idUsuarioLogado}]));

            const { data2 } = await api.get(`${this.route}/paginate?limit=${this.limit}&page=${this.page}${f2 || ''}&filterUser=${filterUser}&XDEBUG_SESSION_START`);

            let dataInicial = $('#filter-data-inicial-pagar').val();
            let dataFinal   = $('#filter-data-final-pagar').val();

            //limpa filtros 
            this.filtersForSearch = [];

            if(dataInicial && dataFinal){
                let filter2    = {};
                filter2.val    = formatDate(dataInicial, false);
                filter2.val2   = formatDate(dataFinal, false);
                filter2.op     = "CF";
                filter2.col    = "CP.DATA_VENCIMENTO";
                filter2.origin = btoa(filter2);
                this.filtersForSearch.push(filter2);
            }

            let f = false;

            let filters = this.filtersForSearch.concat(this.fixedFilters);

            if (filters) f = '&f=' + this.toBase64(filters);

            const { data } = await api.get(`${this.route}/paginate?limit=${this.limit}&page=${this.page}${f || ''}&filterUser=${filterUser}&XDEBUG_SESSION_START`);

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
        let data_inicial = `${$('#filter-data-inicial-pagar').val().split('/')[2]}-${$('#filter-data-inicial-pagar').val().split('/')[1]}-${$('#filter-data-inicial-pagar').val().split('/')[0]}`;
        let data_final   = `${$('#filter-data-final-pagar').val().split('/')[2]}-${$('#filter-data-final-pagar').val().split('/')[1]}-${$('#filter-data-final-pagar').val().split('/')[0]}`;

        let response = await api.get(`financeiro/pagar/indicador/${btoa(JSON.stringify({data_inicial, data_final}))}?filterUser=${filterUser}`);

        if(response.data) {
            let total_pago     = (response.data.total_pago.TOTAL_PAGO) ? `${formatter.format(response.data.total_pago.TOTAL_PAGO)}` : `R$ 0,00`;
            let total_pagar    = (response.data.total_aberto.TOTAL_EM_ABERTO) ? `${formatter.format(response.data.total_aberto.TOTAL_EM_ABERTO)}` : `R$ 0,00`;
            let total_atrasado = (response.data.total_atrasado.TOTAL_ATRASADO) ? `${formatter.format(response.data.total_atrasado.TOTAL_ATRASADO)}` : `R$ 0,00`;

            $('#total_pagar_pago')[0].innerHTML = total_pago;
            $('#total_pagar_pagar')[0].innerHTML  = total_pagar;
            $('#total_pagar_atrasado')[0].innerHTML = total_atrasado;
        }

        loadingDestroy(loading);
    }

}