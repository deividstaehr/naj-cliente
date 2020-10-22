@extends('areaCliente.viewBase')

@section('title', 'NAJ | Mensagem')

@section('css')
    <link href="{{ env('APP_URL') }}css/acessoUsuario.css" rel="stylesheet">
    <link href="{{ env('APP_URL') }}ampleAdmin/assets/libs/select2/dist/css/select2.min.css" rel="stylesheet">
    <link href="{{ env('APP_URL') }}ampleAdmin/assets/libs/summernote/dist/summernote-bs4.css" rel="stylesheet">
    <link href="{{ env('APP_URL') }}ampleAdmin/assets/libs/dropzone/dist/min/dropzone.min.css" rel="stylesheet">
@endsection

@section('active-layer', 'mensagens')
@section('content')

<div class="row height-100 bg-content-messages">
    <div class="col-8 pr-0 pl-0" style="margin-left: 16%;">
        <div class="chat-box data-table-content naj-scrollable content-chat-box-full" style="overflow-x: hidden; border-top: none !important;" id="pololo">
            <div id="loading-message-chat" class="loader loader-default" data-half></div>
            <div class="mail-compose bg-white w-100" id="content-upload-anexos-chat" style="overflow: hidden !important; height: 100%;">
                <div class="card-header bg-info row">
                    <div class="col-11">
                        <h4 class="mb-0 text-white">Anexos</h4>
                    </div>
                    <div class="col-1">
                        <button type="button" data-dismiss="modal" class="btn btn-info btn-rounded" onclick="onClickCancelarAnexos();" style="right: 2%; position: absolute; cursor: pointer; border-color: #3695bf !important; margin-top: -8px;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body naj-scrollable" style="overflow-x: hidden !important; height: 82%;">
                    <div id="loading-anexo-chat" class="loader loader-default" data-half></div>
                    <div class="col-12">
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
                </div>
                <div class="card-footer-naj">
                    <div style="position: fixed; bottom: 10px; width: 100%;">
                        <button type="button" class="btn btn-success" onclick="onClickSendAnexoChat();"><i class="fas fa-paper-plane mr-1"></i>Enviar</button>
                        <button type="button" class="btn btn-danger" onclick="onClickCancelarAnexos();"><i class="fas fa-times mr-1"></i>Cancelar</button>
                        <button type="button" class="btn btn-info fileinput-button" style="position: absolute; right: 35%;"><i class="fas fa-paperclip mr-1"></i></i>Anexar Arquivos</button>
                    </div>
                </div>
            </div>
            <ul class="chat-list" id="content-messages-chat"></ul>
        </div>
        <div class="bg-light content-butons-chat">
            <div class="btn-group dropup show pl-0 content-input-mensagem-chat" style="width: 3% !important; left: 5px; position: absolute; background-color: #f1f1f1 !important;">
                <button type="button" class="btn btn-light btn-light-atendimento-dropdown" id="input-anexo"><i class="fas fa-paperclip"></i></button>
            </div>
            <textarea name="" id="input-text-chat-enviar" class="input-mensagem-chat content-input-mensagem-chat" wrap="hard" placeholder="Digete sua mensagem"></textarea>
            <div id="content-button-rascunho-message-chat">
                <span class="font-10 badge badge-danger" title="Rascunho da mensagem">RASCUNHO</span><i class="fas fa-trash ml-1 cursor-pointer" id="icon-trash-rascunho-message-chat"></i>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    <script src="{{ env('APP_URL') }}ampleAdmin/assets/libs/select2/dist/js/select2.full.min.js"></script>
    <script src="{{ env('APP_URL') }}ampleAdmin/assets/libs/select2/dist/js/select2.min.js"></script>
    <script src="{{ env('APP_URL') }}ampleAdmin/dist/js/pages/forms/select2/select2.init.js"></script>
    <script src="{{ env('APP_URL') }}ampleAdmin/assets/libs/summernote/dist/summernote-bs4.min.js"></script>
    <script src="{{ env('APP_URL') }}ampleAdmin/assets/libs/dropzone/dist/min/dropzone.min.js"></script>

    <script src="{{ env('APP_URL') }}js/Chat.js"></script>
    <script src="{{ env('APP_URL') }}js/mensagens.js"></script>

    <script>

        //Configuração do Editor de Texto
        $('#summernote-novoatendimento').summernote({
            tabsize: 4,
            height: 150,
            callbacks: {
                onPaste: function (e) {
                    var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                    e.preventDefault();
                    document.execCommand('insertText', false, bufferText);
                }
            }
        });

        //Configuração do UPLOAD
        Dropzone.autoDiscover = false;

        Dropzone.prototype.filesize = function(size) {
            var selectedSize = Math.round(size / 1024);
            return "<strong>" + selectedSize + "</strong> KB";
        };

        var previewNode = document.querySelector("#template");
        previewNode.id = "";
        var previewTemplate = previewNode.parentNode.innerHTML;
        previewNode.parentNode.removeChild(previewNode);

        var myDropzone = new Dropzone(document.body, {
            url: `${baseURL}chat/mensagem/anexo`,
            thumbnailWidth: 80,
            thumbnailHeight: 80,
            parallelUploads: 5,
            previewTemplate: previewTemplate,
            autoQueue: false,
            previewsContainer: "#previews",
            clickable: ".fileinput-button",
            dictFileSizeUnits: 'b'
        });

    </script>
@endsection