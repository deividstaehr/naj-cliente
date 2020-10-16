let processoCodigoFilter;
processoTable = new ProcessosTable();
const NajApi  = new Naj('Processos', processoTable);

$(document).ready(function() {
    
    processoTable.render();
    
});

function onClickExibirModalAnexoProcesso(codigo) {
    debugger;
    processoCodigoFilter = codigo;
    anexoProcessoTable = new AnexoProcessoTable();
    anexoProcessoTable.render();
    $('#modal-anexo-processo').modal('show');
}

function onClickExibirModalAtividadeProcesso(codigo) {
    debugger;
    processoCodigoFilter = codigo;
    atividadeProcessoTable = new AtividadeProcessoTable();
    atividadeProcessoTable.render();
    $('#modal-atividade-processo').modal('show');
}