processoTable = new ProcessosTable();
const NajApi  = new Naj('Processos', processoTable);

$(document).ready(function() {
    
    processoTable.render();
    
});