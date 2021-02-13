<div class="modal fade" id="modal-agendamentos" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modalAgendamento modal-dialog" role="document">
        <div class="modal-content modal-content-shadow-naj">
            <div class="modal-header modal-header-naj">
                <p class="titulo-modal-naj">Agenda</p>
                <button type="button" data-dismiss="modal" class="btn btn-info btn-rounded btnClose">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body-naj p-0">
                <div id="loading-agendamento" class="loader loader-default" data-half></div>
                <h5 class="mt-4 weight-700 text-center">Escolha uma das opções de AGENDAMENTOS abaixo:</h5>
                <div class="content-agenda-buttons">                    
                    <button class="btn btn-primary" onclick="onClickAgendarConsulta()">Agendar uma Consulta</button>
                    <button class="btn btn-info" onclick="onClickAgendarReuniao()">Agendar uma Reunião</button>
                    <button class="btn btn-warning" onclick="onClickAgendarVisita()">Agendar uma Visita</button>
                    <button class="btn btn-secondary" onclick="onClickOutroAgendamento()">Outro tipo de Agendamento</button>
                </div>
            </div>
        </div>
    </div>
</div>