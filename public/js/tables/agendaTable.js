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
            width: 20
        });
    }

}