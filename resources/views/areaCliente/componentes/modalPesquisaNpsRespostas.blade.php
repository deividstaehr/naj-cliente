<div class="modal fade" id="modal-pesquisa-nps-respostas" tabindex="-1" role="dialog" aria-hidden="true">
    <div id="loading-pesquisa-nps-respostas" class="loader loader-default" data-half></div>
    <div class="modal-dialog modal-extra-large" role="document" style="min-width: 60% !important; margin-top: 12%;">
        <div class="modal-content modal-content-shadow-naj">
            <div class="modal-header modal-header-naj">
                <p class="titulo-modal-naj">Pesquisa de Satisfação</p>
                <button type="button" data-dismiss="modal" class="btn btn-info btn-rounded btnClose">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body-naj p-0" style="height: 52vh;">
                <div class="content-resposta-nps">
                    <div class="row">
                        <input type="hidden" id="id_pesquisa_nps">
                        <input type="hidden" id="id_resposta_nps">
                        <input type="hidden" id="amount_open">

                        <div class="col-lg-12 col-md-12 col-sm-12 content-title-pergunta-nps"></div>

                        <div class="col-lg-12 col-md-12 col-sm-12 mt-4 content-notes-respostas-nps"></div>

                        <div class="col-lg-12 col-md-12 col-sm-12 mt-4 mb-0">
                            <div class="form-group row">
                                <div class="col-12">
                                    <div class="input-group">
                                        <textarea rows="5" class="form-control" name="motivo" id="motivo" placeholder="Qual o principal motivo para a sua nota?"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <button class="btn btn-success" onclick="saveSearchNps()">Confirmar</button>
                            <button class="btn btn-secondary" onclick="saveNotAnswerSearchNps()">Não Responder</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>