<div class="modal fade" id="modal-upload-logo-empresa" tabindex="-1" role="dialog" aria-hidden="true">
    <div id="loading-upload-anexo" class="loader loader-default" data-half></div>
    <div class="modal-dialog modal-extra-large" role="document" style="min-width: 50% !important; margin-top: 12%;">
        <div class="modal-content modal-content-shadow-naj">
            <div class="modal-header modal-header-naj">
                <p class="titulo-modal-naj">Upload Logo Advocacia</p>
                <button type="button" data-dismiss="modal" class="btn btn-info btn-rounded btnClose">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body-naj p-0" style="height: 30vh;">
                <div class="row">
                    <div class="col-12 mt-4 ml-2">
                        <div id="loading-anexo-chat" class="loader loader-default" data-half></div>                        
                        <div class="table table-striped files" id="previews">
                            <div id="template" class="file-row">
                                <div class="row" style="align-items: center;">
                                    <div class="col-7">
                                        <p class="name" data-dz-name></p>
                                        <strong class="error text-danger" data-dz-errormessage></strong>
                                    </div>
                                    <div class="col-2">
                                        <p class="size" data-dz-size></p>
                                    </div>
                                    <div class="col-3">
                                        <button data-dz-remove class="btn btn-danger cancel">
                                            <i class="fas fa-ban mr-1"></i><span>Cancelar</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="_method" value="POST">
                        <input type="hidden" id="token" name="_token" value="{{ csrf_token() }}">
                        <meta name="csrf-token" content="{{ csrf_token() }}" />
                    </div>
                    <div class="col-12" style="position: absolute; bottom: 2%; margin-left: 2%;">
                        <button type="button" class="btn btn-success" onclick="onClickSendLogo();"><i class="fas fa-paper-plane mr-1"></i>Gravar</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times mr-1"></i>Cancelar</button>
                        <button type="button" class="btn btn-info fileinput-button" style="position: absolute; right: 1%;"><i class="fas fa-paperclip mr-1"></i></i>Anexar Arquivos</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>