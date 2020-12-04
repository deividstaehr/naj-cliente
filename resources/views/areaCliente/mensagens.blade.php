@extends('areaCliente.viewBase')

@section('title', 'NAJ | Mensagem')

@section('css')
    <link href="{{ env('APP_URL') }}ampleAdmin/assets/libs/select2/dist/css/select2.min.css" rel="stylesheet">
    <link href="{{ env('APP_URL') }}ampleAdmin/assets/libs/summernote/dist/summernote-bs4.css" rel="stylesheet">
    <link href="{{ env('APP_URL') }}ampleAdmin/assets/libs/dropzone/dist/min/dropzone.min.css" rel="stylesheet">

    <style>

        /* AJUSTES PARA TELAS PEQUENAS */
        @media only screen and (max-width: 766px) {
            .body-page-naj {
                height: 93vh !important;
                overflow-y: hidden !important;
            }
        }
        
    </style>
@endsection

@section('active-layer', 'mensagens')
@section('content')

<div class="row height-100 bg-content-messages">
    <div class="col-lg-6 col-md-6 col-sm-12 pr-0 pl-0 content-row-chat">
        <div class="chat-box data-table-content naj-scrollable content-chat-box-full" id="content-chat-box-full" style="overflow-x: hidden; border-top: none !important; padding-left: 5px !important;">
            <div id="loading-message-chat" class="loader loader-default" data-half></div>
            <div class="content-message-select-user-chat warning d-none">
                <p class="text-message-select-user-chat"></p>
            </div>
            <div class="mail-compose bg-white w-100 d-none" id="content-upload-anexos-chat" style="overflow: hidden !important; height: 100%;">
                <div class="card-header bg-info row">
                    <div class="col-lg-11 col-md-11 col-sm-8">
                        <h4 class="mb-0 text-white">Anexos</h4>
                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-4">
                        <button type="button" data-dismiss="modal" class="btn btn-info btn-rounded btn-cancelar-anexos-modal" onclick="onClickCancelarAnexos();">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body row naj-scrollable" style="overflow-x: hidden !important; height: 82%;">
                    <div id="loading-anexo-chat" class="loader loader-default" data-half></div>
                    <div class="col-12">
                        <div class="table table-striped files" id="previews">
                            <div id="template" class="file-row">
                                <div class="row" style="align-items: center;">
                                    <div class="col-lg-7 col-md-7 col-sm-12">
                                        <p class="name" data-dz-name></p>
                                        <strong class="error text-danger" data-dz-errormessage></strong>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-12">
                                        <p class="size" data-dz-size></p>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-12">
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
                        <button type="button" class="btn btn-info fileinput-button button-adicionar-anexo-modal"><i class="fas fa-paperclip mr-1"></i></i>Anexar Arquivos</button>
                    </div>
                </div>
            </div>
            <ul class="chat-list" id="content-messages-chat"></ul>
        </div>
        <div class="bg-light content-butons-chat">
            <div class="btn-group dropup show pl-0 content-input-mensagem-chat" id="content-input-tres-pontos-chat" style="width: 3% !important; left: 17px; position: absolute; background-color: #f1f1f1 !important;">
                <button type="button" class="btn btn-light btn-light-atendimento-dropdown" id="input-anexo"><i class="fas fa-paperclip"></i></button>
            </div>
            <textarea name="" id="input-text-chat-enviar" class="input-mensagem-chat content-input-mensagem-chat" wrap="hard" placeholder="Digite sua mensagem"></textarea>
            <div class="content-button-enviar-smartphone">
                <button type="button" class="btn btn-success btn-circle" id="button-enviar-smartphone"><i class="fas fa-paper-plane" style="margin-left: -5px;"></i></button>
            </div>
            <div id="content-button-rascunho-message-chat">
                <span class="font-10 badge badge-danger" title="Rascunho da mensagem">RASCUNHO</span><i class="fas fa-trash ml-1 cursor-pointer" id="icon-trash-rascunho-message-chat"></i>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-md-6 col-sm-12 mt-2 pr-0 pl-0" style="height: 100%;" id="content-logo-mensagem">
        <div class="row ml-0 mr-3">
            <div class="col-12">
                <div class="card" id="content-meus-processos">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-center">
                            <img src="{{ env('APP_URL') }}imagens/logo_escritorio/logo_escritorio.png" alt="logo-cliente" class="dark-logo"/>
                        </div>
                        <div class="mt-4 d-flex align-items-center justify-content-center">
                            <div class="ml-4">
                                <h3 class="font-medium" id="nomeEmpresa"></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row ml-0 mr-3">
            <div class="col-12">
                <div class="card" id="content-meus-processos">
                    <div class="pt-0 pb-4 card-body">
                        <div class="mt-4 d-flex align-items-center justify-content-center">
                            <div class="ml-4">
                                <h4 class="font-medium">Acesse no celular baixando o nosso APP EXCLUSIVO para clientes.</h4>
                                <h3 class="font-medium" style="text-align: center;">NAJ Desk.</h3>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-center">
                            <img src="{{ env('APP_URL') }}imagens/applestore.png" alt="logo-apple-store" class="dark-logo mr-4" style="height: 40px;"/>
                            <img src="{{ env('APP_URL') }}imagens/playstore.png" alt="logo-play-store" class="dark-logo"  style="height: 40px;"/>
                        </div>
                        <div class="mt-2 d-flex align-items-center justify-content-center">
                            <h5 class="font-medium">Baixe gratuitamente</h5>
                        </div>
                    </div>
                </div>
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