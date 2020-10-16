class AnexoProcessoTable extends Table {

    constructor() {
        super();
        
        this.target           = 'datatable-anexos-processo';
        this.name             = 'Anexos do Processo';
        this.route            = `anexos/processos`;
        this.key              = ['id'];
        this.openLoaded       = true;
        this.isItEditable     = false;
        this.isItDestructible = false;
        this.showTitle        = false;
        this.defaultFilters   = false;

        this.addField({
            name: 'download',
            title: '',
            width: 5,
            onLoad: (data, row) =>  {
                return `
                    <table class="row-informacoes-processo">
                        <tr>
                            <td><i class="fas fa-download icone-download-processo-atividade" title="Baixar Anexo"></i></td>
                        </tr>
                    </table>
                `;
            }
        });

        this.addField({
            name: 'id',
            title: 'Código',
            width: 10
        });
        
        this.addField({
            name: 'descricao',
            title: 'Nome Arquivo',
            width: 60
        });
        
        this.addField({
            name: 'data_arquivo',
            title: 'Data',
            width: 25
        });

        debugger;
        this.addFixedFilter('codigo_processo', 'I', processoCodigoFilter);
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