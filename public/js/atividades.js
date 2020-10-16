let atividadeCodigoFilter;
atividadesTable = new AtividadeTable();
const NajApi    = new Naj('Atividades', atividadesTable);

$(document).ready(function() {
    
    atividadesTable.render();
    
});

function onClickExibirModalAnexoAtividade(codigo) {
    atividadeCodigoFilter = codigo;
    anexoAtividadesTable = new AnexoAtividadeTable();
    anexoAtividadesTable.render();
    $('#modal-anexo-atividade').modal('show');
}