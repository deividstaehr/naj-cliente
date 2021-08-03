agendaTable = new AgendaTable()
const NajApi    = new Naj('Eventos', agendaTable)

$(document).ready(function() {
    
    agendaTable.render()
    
});