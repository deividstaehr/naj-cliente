<div class="modal fade" id="modal-novo-agendamento" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-extra-large" role="document" style="min-width: 60% !important;">
        <div class="modal-content modal-content-shadow-naj">
            <div class="modal-header modal-header-naj">
                <p class="titulo-modal-naj">Novo Agendamento</p>
                <button type="button" data-dismiss="modal" class="btn btn-info btn-rounded btnClose">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body-naj p-0" style="height: 65vh !important;">                
                <div id="loading-novo-agendamento" class="loader loader-default" data-half></div>
                <div class="content-agenda-buttons">
                    <h6 class="text-center weight-500">Escolha uma das opções de AGENDAMENTOS abaixo:</h6>
                    <button class="btn-agenda btn btn-primary" onclick="onClickAgendarConsulta()">Agendar uma Consulta</button>
                    <button class="btn-agenda btn btn-info" onclick="onClickAgendarReuniao()">Agendar uma Reunião</button>
                    <button class="text-white btn-agenda btn btn-warning" onclick="onClickAgendarVisita()">Agendar uma Visita</button>
                    <button class="btn-agenda btn btn-secondary" onclick="onClickOutroAgendamento()">Outro tipo de Agendamento</button>
                </div>
            </div>
        </div>
    </div>
</div>