class ProcessosTable extends Table {

    constructor() {
        super();
        
        this.target       = 'datatable-processos';
        this.name         = 'Processos';
        this.route        = `processos`;
        this.key          = ['codigo'];
        this.openLoaded   = true;
        this.isItEditable = false;
        this.showTitle    = false;
        var countLinha    = 0;

        this.addField({
            name: 'codigo',
            title: 'Código',
            width: 10,
            onLoad:(data) => formatDate(data)
            
        });
        
        this.addField({
            name: 'nome_partes',
            title: 'Nome das Partes',
            width: 20,
            onLoad:(data) => formatDate(data)
            
        });
        
        this.addField({
            name: 'informacao_processo',
            title: 'Informações do Processo',
            width: 40
        });
        
        this.addField({
            name: 'situacao',
            title: 'Situação',
            width: 10,
            onLoad: (data,linha) =>  {
                let result = '';
                if(data != null){
                    //Verifica quais atributos serão apresentados
                    let tipoRegistro = linha.id_processo != null ? `` : `<span class="badge badge-pill badge-secondary">Citação</span>`;
                    let linha1 = `<span class="font-medium ">${linha.secao ? linha.secao : linha.diario_nome}</span><br>`;
                    let linha2 = `<span class="text-muted">${linha.tipo  ? linha.tipo  : linha.diario_competencia}</span><br>`;
                    let linha3 = `<button type="button" class="btnLeiaNaIntegra btn btn-sm waves-effect waves-light btn-rounded btn-outline-dark" data-id-movimentacao="${linha.id}" data-index-linha="${countLinha}" data-toggle="modal" data-target="#intimacao_content1"><i class="fas fa-search"></i> Leia na Íntegra</button>&emsp;`;
                        linha3 += `<span class="font-medium">Página: ${linha.pagina}</span>&emsp;${tipoRegistro} &emsp;`;
                        linha3 += linha.lido == "N"  ? `<span id="tag-new-${linha.id}" class="badge text-white font-normal badge-pill badge-warning blue-grey-text text-darken-4 mr-2">Nova</span>`: "";
                        result +=  `
                            <table>
                                <tbody>
                                    <tr>
                                        <td>
                                            ${linha1}
                                            ${linha2}
                                            ${linha3}
                                        </td>
                                    </tr>
                                <tbody>
                            </table>`;
                }
                //verifica se a linha corrente é inferior ao total de linhas por página
                if(countLinha < tableDiario.data.resultado.length - 1){
                    //Incrementa o contador de linha
                    countLinha++;
                } else {
                    //Reseta o contador de linha
                    countLinha = 0;
                }
                return result;
            }
        });
        
        this.addField({
            name: 'outras_informacao',
            title: 'Outras Informações',
            width: 20,
            onLoad: (data,linha) =>  {
                let result = '';
                let linha1 = '';
                let linha2 = '';
                let linha3 = '';
                //Verifica se tem processo relacionado a esta movimentação (FK da tb monitora_termo_processo)
                if(linha.id_processo != null){
                    //Verifica se tem o número novo do processo, essa informação vem da Escavador 
                    if(linha.processo.numero_novo != null){
                        //Verifica se tem código de processo (FK ta tb PRC), se tiver significa que o processo já está cadastrado no BD
                        if(linha.processo.codigo_processo != null){
                            linha1 = `<span class="font-medium">Código: ${linha.processo.codigo_processo}<img src="${appUrl}imagens/external-link.png" alt="link externo"/></span><br>`;
                            linha3 = `<span class="badge badge-success">Cadastrado</span>`;
                        } else{
                            linha3 = `<span class="badge badge-pill badge-danger">Pendente</span>`;
                        }
                        //tags                 = `<span class="badge badge-success">Monitorado</span>`;
                        linha2 = `<span>Processo: <span class="font-medium">${linha.processo.numero_novo}</span></span><br>`;
                    } else {
                        linha2 = `<span class="text-muted">Não conseguimos identificar o processo </span><i class="icon-info" data-toggle="tooltip" data-placement="top" title="" data-original-title="Localizamos o termo de pesquisa mas não foi possível identificar o processo"></i><br>`;
                        linha3 = `<span class="badge badge-secondary">Descartado</span>`;
                    }
                } else if(linha.id_processo == null){
                    linha1 = `<span class="text-muted">Não conseguimos identificar o processo </span><br>`;
                    linha2 = `<span class="badge badge-pill badge-danger">Pendente</span>`;
                }
                result +=  `
                    <table>
                        <tbody>
                            <tr>
                                <td> 
                                    ${linha1}
                                    ${linha2}
                                    ${linha3}
                                </td>
                            </tr>
                        <tbody>
                    </table>`;
                return result;
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
        
        // carregaBadgeNovasPublicacoes();
        // carregaBadgePendentes();
        
    }

}