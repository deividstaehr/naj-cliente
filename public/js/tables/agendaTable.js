class AgendaTable extends Table {

    constructor() {
        super();
        
        this.target           = 'datatable-agenda';
        this.name             = 'Eventos';
        this.route            = `agenda`;
        this.key              = ['codigo'];
        this.openLoaded       = true;
        this.isItEditable     = false;
        this.isItDestructible = false;
        this.showTitle        = false;
        this.defaultFilters   = false;
        
        this.addField({
            name: 'data_hora_inclusao',
            title: 'Data/Hora',
            width: 10
        });
        
        this.addField({
            name: 'descricaoTipo',
            title: 'Tipo',
            width: 20
        });
        
        this.addField({
            name: 'data_hora_compromisso',
            title: 'Data/Hora Compromisso',
            width: 10
        });

        this.addField({
            name: 'local',
            title: 'Local',
            width: 20
        });

        this.addField({
            name: 'assunto',
            title: 'Assunto',
            width: 30
        });

        this.addField({
            name: 'situacao',
            title: 'Status',
            width: 20,
            onLoad: (data, row) =>  {
                if (row.situacao == 'R') {
                    return `<span>Recebido</span>`;
                } else if(row.situacao == 'A') {
                    return `<span>Aberto</span>`;
                } else if (row.situacao == 'C') {
                    return `<span>Cancelado</span>`;
                }
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

            // let filters = this.filtersForSearch.concat(this.fixedFilters);

            // if (filters) f = '&f=' + this.toBase64(filters);

            const { data } = await api.get(`${this.route}/paginate?limit=${this.limit}&page=${this.page}&codigo_usuario=${idUsuarioLogado}&XDEBUG_SESSION_START`);
            // const { data } = await api.get(`${this.route}/paginate?limit=${this.limit}&page=${this.page}${f || ''}`);

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