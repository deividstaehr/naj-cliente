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
                return `
                    <table>
                        <tr>
                            <td class="td-nome-parte-cliente">${row.NOME_CLIENTE} (${row.QUALIFICA_CLIENTE}) <i class="ml-2 fas fa-users icone-parte-processo"></i></td>
                        </tr>
                        ${(row.NOME_ADVERSARIO)
                            ?
                            `<tr>
                                <td>${row.NOME_ADVERSARIO} (${row.QUALIFICA_ADVERSARIO})</td>
                            </tr>
                            `
                            : ``
                        }
                        ${(row.NOME_ADVOGADO)
                            ?
                            `<tr>
                                <td>${row.NOME_ADVOGADO}</td>
                            </tr>
                            `
                            : ``
                        }
                        ${(row.NOME_RESPONSAVEL) 
                          ?
                          `<tr>
                               <td>${row.NOME_RESPONSAVEL}</td>
                           </tr>
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
                        ${(row.CARTORIO && row.COMARCA && row.COMARCA_UF)
                            ?
                            `<tr>
                                <td>${row.CARTORIO} - ${row.COMARCA} (${row.COMARCA_UF})</td>
                            </tr>
                            `
                            : ``
                        }
                        ${(row.ULTIMO_ANDAMENTO_DESCRICAO)
                            ?
                            `<tr>
                                <td>${row.ULTIMO_ANDAMENTO_DESCRICAO}</td>
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
                            <td><i class="fas fa-search icone-informaçoes-processo mr-2" onclick="onClickExibirModalAnexoProcesso(${row.CODIGO_PROCESSO});"></i><span class="mb-2 badge badge-secondary badge-rounded badge-informacoes-processo">${row.QTDE_ANEXOS_PROCESSO} Documento(s) Anexos</span></td>
                        </tr>
                        <tr>
                            <td><i class="fas fa-search icone-informaçoes-processo mr-2" onclick="onClickExibirModalAtividadeProcesso(${row.CODIGO_PROCESSO});"></i><span class="mb-2 badge badge-secondary badge-rounded badge-informacoes-processo">${row.QTDE_ATIVIDADE_PROCESSO} Atividade(s)</span></td>
                        </tr>
                    </table>
                `;
            }
        });
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