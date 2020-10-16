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
            name: 'NOME_USUARIO',
            title: 'Usuário Responsável',
            width: 20
        });
        
        this.addField({
            name: 'data_hora_inicio',
            title: 'Data/Hora Inicio',
            width: 10,
            onLoad: (data, row) =>  {
                return `${row.DATA_INICIO} ${row.HORA_INICIO}`;
            }
        });
        
        this.addField({
            name: 'data_hora_termino',
            title: 'Data/Hora Termino',
            width: 10,
            onLoad: (data, row) =>  {
                return `${row.DATA_TERMINO} ${row.HORA_TERMINO}`;
            }
        });
        
        this.addField({
            name: 'TEMPO',
            title: 'Tempo',
            width: 5
        });
        
        this.addField({
            name: 'DESCRICAO',
            title: 'Histórico',
            width: 20
        });

        this.addField({
            name: 'informacoes_processo',
            title: 'Informações do Processo',
            width: 20,
            onLoad: (data, row) =>  {
                if(!row.NUMERO_PROCESSO_NEW && !row.CARTORIO && !row.COMARCA && !row.VALOR_CAUSA) {
                    return 'Sem informações'
                }

                return `
                    <table>
                        ${(row.NUMERO_PROCESSO_NEW)
                            ?
                            `<tr>
                                <td>${row.NUMERO_PROCESSO_NEW}</td>
                            </tr>
                            `
                            : ``
                        }                     
                        ${(row.CARTORIO && row.COMARCA)
                            ?
                            `<tr>
                                <td>${row.CARTORIO} - ${row.COMARCA}</td>
                            </tr>
                            `
                            : ``
                        }
                        ${(row.VALOR_CAUSA)
                            ?
                            `<tr>
                                <td>R$${row.VALOR_CAUSA}</td>
                            </tr>
                            `
                            : ``
                        }
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
                            <td><i class="fas fa-search icone-informaçoes-processo mr-2" onclick="onClickExibirModalAnexoAtividade(${row.CODIGO});"></i><span class="mb-2 badge badge-secondary badge-rounded badge-informacoes-processo">${row.QTDE_ANEXOS_ATIVIDADE} Documento(s) Anexos</span></td>
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

        let responseTotalHoras = await api.get(`atividades/totalHoras`);

        if(responseTotalHoras.data.total_horas) {
            $('#total_horas')[0].innerHTML = `${responseTotalHoras.data.total_horas[0].total_horas}`;
        }

        loadingDestroy(loading);
    }

}