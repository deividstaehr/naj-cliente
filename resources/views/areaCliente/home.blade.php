@extends('areaCliente.viewBase')

@section('title', 'NAJ | Área do Cliente')

@section('css')
    <link href="{{ env('APP_URL') }}ampleAdmin/assets/libs/dropzone/dist/min/dropzone.min.css" rel="stylesheet">

    <style>
        .page-content-home {
            height: 105% !important;
        }

        /* AJUSTES PARA TELAS PEQUENAS */
        @media only screen and (max-width: 766px) {
            .page-content-home {
                height: 99% !important;
            }
        }

        .pulse-naj {
            animation: pulse 0.7s infinite;
            border: 1px solid #007b0066;
            animation-direction: alternate;
            -webkit-animation-name: pulse;
            animation-name: pulse;
        }

        @-webkit-keyframes pulse {
            0% {
                -webkit-transform: scale(1);
            }
            100% {
                -webkit-transform: scale(1.1);
            }
            }

            @keyframes pulse {
            0% {
                transform: scale(1);
            }
            100% {
                transform: scale(1.1);
            }
        }
        
    </style>
@endsection

@section('active-layer', 'home')

@section('content')
<div class="page-content container-fluid pt-2 pb-2 scrollable page-content-home">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12 content-right-home">
            <div class="row">
                <div class="col-md-6 col-lg-6 col-sm-12">
                    <div class="card card-hover cursorActive pulse-naj" id="content-minhas-mensagens">
                        <div class="card-body">
                            <h5 class="card-title text-uppercase">MINHAS MENSAGENS</h5>
                            <div class="d-flex align-items-center mb-2 mt-4">
                                <h2 class="mb-0 display-7"><i class="fas fa-comments text-info"></i></h2>
                                <div class="cursorActive" style="margin-left: 30% !important;">
                                    <h3 class="ml-3 font-medium" id="qtde_mensagens_novas"></h3>
                                    <h5 class="text-info mb-0">Novas</h5>
                                </div>
                                <div class="ml-4 cursorActive" style="margin-left: 15% !important;">
                                    <h3 class="ml-3 font-medium" id="qtde_mensagens_todas"></h3>
                                    <h5 class="text-info mb-0">Todas</h5>
                                </div>
                            </div>
                            <i class="fas fa-search text-info icone-search-home-cards"></i>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-6 col-sm-12">
                    <div class="card card-hover cursorActive" id="content-atividades">
                        <div class="card-body">
                            <h5 class="card-title text-uppercase">ATIVIDADES</h5>
                            <div class="d-flex align-items-center mb-2 mt-4">
                                <h2 class="mb-0 display-7"><i class="fas fa-tasks text-info"></i></h2>
                                <div class="cursorActive" style="margin-left: 15% !important;">
                                    <h3 class="ml-3 font-medium" id="qtde_atividade_trinta_dias"></h3>
                                    <h5 class="text-info mb-0">Últimos 30 Dias</h5>
                                </div>
                                <div class="ml-4 cursorActive" style="margin-left: 15% !important;">
                                    <h3 class="ml-3 font-medium" id="qtde_atividade_todas"></h3>
                                    <h5 class="text-info mb-0">Todas</h5>
                                </div>
                            </div>
                            <i class="fas fa-search text-info icone-search-home-cards"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 col-lg-12 col-sm-12">
                    <div class="card card-hover cursorActive" id="content-meus-processos">
                        <div class="card-body">
                            <h5 class="card-title text-uppercase">Meus Processos</h5>
                            <div class="d-flex align-items-center">
                                <h2 class="mb-0 display-5"><i class="fas fa-balance-scale text-primary"></i></h2>
                                <div class="cursorActive" style="margin-left: 30% !important;">
                                    <h3 class="ml-3 font-medium" id="qtde_processo_ativos"></h3>
                                    <h5 class="text-info mb-0">Ativos</h5>
                                </div>
                                <div class="ml-4 cursorActive" style="margin-left: 15% !important;">
                                    <h3 class="ml-3 font-medium" id="qtde_processo_baixado"></h3>
                                    <h5 class="text-info mb-0">Baixados</h5>
                                </div>
                            </div>
                            <i class="fas fa-search text-info icone-search-home-cards"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-sm-12">
                    <div class="card" style="height: 83%;">
                        <div class="card-body">
                            <h5 class="card-title text-uppercase">FINANCEIRO</h5>
                            <div class="d-flex no-block align-items-center row">
                                <h2 class="mb-0 display-5"><i class="fas fa-donate text-primary"></i></h2>
                                <div class="col-lg-4 col-md-4 col-sm-12 ml-auto card-hover cursor-pointer p-2" id="content-financeiro-receber">
                                    <h4 class="font-medium bold"><i class="fas fa-dollar-sign text-success"></i> A RECEBER</h4>
                                    <h5 class="text-dark mb-0"><span class="mr-2 align-right">Recebido</span>  <span class="text-info float-right" id="qtde_receber_recebido"></span> </h5>
                                    <h5 class="text-dark mb-0"><span class="mr-2">Em Aberto</span> <span class="text-info float-right" id="qtde_receber_aberto"></span> </h5>
                                    <i class="fas fa-search text-info" style="margin-top: 10px; margin-left: 90%;"></i>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-12 ml-auto card-hover cursor-pointer p-2" id="content-financeiro-pagar">
                                    <h4 class="font-medium bold"><i class="fas fa-dollar-sign text-danger"></i> A PAGAR</h4>
                                    <h5 class="text-dark mb-0"><span class="mr-2 align-right">Pago</span>  <span class="text-danger float-right" id="qtde_pagar_pago"></span> </h5>
                                    <h5 class="text-dark mb-0"><span class="mr-2">Em Aberto</span> <span class="text-danger float-right" id="qtde_pagar_aberto"></span> </h5>
                                    <i class="fas fa-search text-info" style="margin-left: 90%; margin-top: 10px;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 col-md-6">
            <div class="row">
                <div class="col-md-12 col-lg-12 col-sm-12">
                    <div class="card">
                        <div class="card-body pt-0 pb-3">
                            <div class="d-flex align-items-center justify-content-center">
                                <img src="{{ env('APP_URL') }}imagens/logo_escritorio/logo_escritorio.png" alt="logo-cliente" class="dark-logo"/>
                            </div>
                            <div class="mt-4 d-flex align-items-center justify-content-center">
                                <div class="ml-4">
                                    <h3 class="font-medium" id="nomeEmpresa"></h3>
                                </div>                                
                            </div>
                            @if (Auth::user()->usuario_tipo_id == 0 || Auth::user()->usuario_tipo_id == 1)
                            <div style="margin-left: 40%;">
                                <button type="button" class="btn btn-info" onclick="onClickExibirModalLogo();"><i class="fas fa-paperclip mr-1"></i>Carregar Logo</button>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 col-lg-12 col-sm-12">
                    <div class="card">
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
</div>

@component('areaCliente.componentes.modalManutencaoLogoEmpresa')
@endcomponent

@endsection
@section('scripts')
    <script src="{{ env('APP_URL') }}ampleAdmin/assets/libs/dropzone/dist/min/dropzone.min.js"></script>
    <script src="{{ env('APP_URL') }}js/home.js"></script>
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
            dictFileSizeUnits: 'b',
            init: function() {
            this.on("addedfile", function() {
                if(this.files.length > 1) {
                    this.removeFile(this.files[1]);
                    NajAlert.toastError('Não é possível adicionar mais de um arquivo!');
                }
            });
        }
        });

    </script>
@endsection