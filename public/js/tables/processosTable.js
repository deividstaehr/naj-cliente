class ProcessosTable extends Table {

    constructor() {
        super();
        
        this.target           = 'datatable-processos';
        this.name             = 'Processos';
        this.route            = `processos`;
        this.key              = ['codigo'];
        this.openLoaded       = true;
        this.isItEditable     = false;
        this.isItDestructible = false;
        this.showTitle        = false;
        this.defaultFilters   = false;

        this.addField({
            name: 'CODIGO_PROCESSO',
            title: 'Código',
            width: 5
        });
        
        this.addField({
            name: 'nome_partes',
            title: 'Nome das Partes',
            width: 35,
            onLoad: (data, row) =>  {
                let sHtmlQtdeClientes = '';
                let sHtmlEnvolvidos   = '';
                let sHtmlAdversarios   = '';
                let sHtmlEnvolvidosAdv = '';

                if(row.QTDE_CLIENTES) {
                    sHtmlQtdeClientes = `<span class="badge badge-secondary badge-rounded badge-nome-partes-processo" title="+${row.QTDE_CLIENTES} Envolvido(s)">+${row.QTDE_CLIENTES} Envolvido(s)</span>`;
                    sHtmlEnvolvidos   = `
                        <span class="action-icons">
                            <a data-toggle="collapse" href="#partes-processo-${row.CODIGO_PROCESSO}" data-key-processo="${row.CODIGO_PROCESSO}" aria-expanded="false" onclick="onClickEnvolvidosProcesso(${row.CODIGO_PROCESSO}, this);">
                                <i class="fas fa-chevron-circle-right icone-partes-processo-expanded" title="Clique para ver os envolvidos" data-toggle="tooltip"></i>
                            </a>
                        </span>
                    `;
                }

                if(row.QTDE_ADVERSARIOS) {
                    sHtmlAdversarios   = `<span class="badge badge-secondary badge-rounded badge-nome-partes-processo" title="+${row.QTDE_ADVERSARIOS} Envolvido(s)">+${row.QTDE_ADVERSARIOS} Envolvido(s)</span>`;
                    sHtmlEnvolvidosAdv = `
                        <span class="action-icons">
                            <a data-toggle="collapse" href="#partes-adv-processo-${row.CODIGO_PROCESSO}" data-key-processo="${row.CODIGO_PROCESSO}" aria-expanded="false" onclick="onClickEnvolvidosProcessoAdv(${row.CODIGO_PROCESSO}, this);">
                                <i class="fas fa-chevron-circle-right icone-partes-processo-expanded" title="Clique para ver os envolvidos" data-toggle="tooltip"></i>
                            </a>
                        </span>
                    `;
                }

                return `
                    <table class="w-100">
                        <tr>
                            <td class="td-nome-parte-cliente">${row.NOME_CLIENTE} (${row.QUALIFICA_CLIENTE})</td>
                        </tr>
                        <tr>
                            <td class="td-nome-parte-cliente">
                                <div class="row" style="width: 100% !important; margin-left: 1px !important;">
                                    ${sHtmlQtdeClientes}${sHtmlEnvolvidos}
                                </div>
                            </td>
                        </tr>
                        <tr class="collapse well" id="partes-processo-${row.CODIGO_PROCESSO}" aria-expanded="false"></tr>
                        ${(row.NOME_ADVERSARIO)
                            ?
                            `<tr>
                                <td>${row.NOME_ADVERSARIO} (${row.QUALIFICA_ADVERSARIO})</td>
                            </tr>
                            <tr>
                                <td class="td-nome-parte-cliente">
                                    <div class="row" style="width: 100% !important; margin-left: 1px !important;">
                                        ${sHtmlAdversarios}${sHtmlEnvolvidosAdv}
                                    </div>
                                </td>
                            </tr>
                            <tr class="collapse well" id="partes-adv-processo-${row.CODIGO_PROCESSO}" aria-expanded="false"></tr>
                            `
                            : ``
                        }                        
                    </table>
                `;
            }
        });
        
        this.addField({
            name: 'informacao_processo',
            title: 'Informações do Processo',
            width: 35,
            onLoad: (data, row) =>  {
                return `
                    <table>
                        <tr>
                            <td>${row.NUMERO_PROCESSO_NEW}</td>
                        </tr>                        
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
                `;
            }
        });
        
        this.addField({
            name: 'situacao',
            title: 'Situação',
            width: 10,
            onLoad: (data, row) =>  {
                let classeCss = (row.SITUACAO == "ENCERRADO") ? 'badge-danger' : 'badge-success';
                let situacao  = (row.SITUACAO == "ENCERRADO") ? 'Baixado' : 'Em andamento';
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
            name: 'outras_informacao',
            title: 'Outras Informações',
            width: 15,
            onLoad: (data, row) =>  {
                return `
                    <table class="row-informacoes-processo">
                        <tr>
                            <td>
                                <div class="row">
                                    <i class="fas fa-search icone-informaçoes-processo mr-4" onclick="onClickExibirModalAnexoProcesso(${row.CODIGO_PROCESSO});"></i><span class="ml-3 mb-2 badge badge-secondary badge-rounded badge-informacoes-processo ${(row.QTDE_ANEXOS_PROCESSO > 0) ? `weight-500` : ``}" onclick="onClickExibirModalAnexoProcesso(${row.CODIGO_PROCESSO});">${row.QTDE_ANEXOS_PROCESSO} Documento(s) Anexos</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="row">
                                    <i class="fas fa-search icone-informaçoes-processo mr-4" onclick="onClickExibirModalAtividadeProcesso(${row.CODIGO_PROCESSO});"></i><span class="ml-3 mb-2 badge badge-secondary badge-rounded badge-informacoes-processo ${(row.QTDE_ATIVIDADE_PROCESSO > 0) ? `weight-500` : ``}" onclick="onClickExibirModalAtividadeProcesso(${row.CODIGO_PROCESSO});">${row.QTDE_ATIVIDADE_PROCESSO} Atividade(s)</span>
                                </div>
                            </td>
                        </tr>
                    </table>
                `;
            }
        });

        this.addFixedFilter('codigo_usuario', 'I', idUsuarioLogado);
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
            let f = false;

            let filters = this.filtersForSearch.concat(this.fixedFilters);

            if (filters) f = '&f=' + this.toBase64(filters);

            const { data } = await api.get(`${this.route}/paginate?limit=${this.limit}&page=${this.page}${f || ''}&XDEBUG_SESSION_START`);

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

        loadingDestroy(loading);
    }

}