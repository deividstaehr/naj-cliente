@extends('areaCliente.viewBase')

@section('title', 'NAJ | Área do Cliente')

@section('css')
@endsection

@section('active-layer', 'home')

@section('content')
<div class="page-content container-fluid p-2" style="min-height: calc(100vh - 110px) !important;">
    <div class="row">
        <div class="col-6">
            <div class="row">
                <div class="col-md-6 col-lg-6">
                    <div class="card card-hover cursorActive" id="content-minhas-mensagens">
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

                <div class="col-md-6 col-lg-6">
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
                <div class="col-md-12 col-lg-12">
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
                <div class="col-md-12 col-lg-12">
                    <div class="card card-hover cursorActive" style="height: 103%;">
                        <div class="card-body">
                            <h5 class="card-title text-uppercase">FINANCEIRO</h5>
                            <div class="d-flex no-block align-items-center">
                                <h2 class="mb-0 display-5"><i class="fas fa-donate text-primary"></i></h2>
                                <div class="ml-auto">
                                    <h4 class="font-medium bold"><i class="fas fa-dollar-sign text-danger"></i> A PAGAR</h4>
                                    <h5 class="text-dark mb-0"><span class="mr-2 align-right">Pago</span>  <span class="text-danger float-right" id="qtde_pagar_pago"></span> </h5>
                                    <h5 class="text-dark mb-0"><span class="mr-2">Em Aberto</span> <span class="text-danger float-right" id="qtde_pagar_aberto"></span> </h5>
                                </div>
                                <div class="ml-auto">
                                <h4 class="font-medium bold"><i class="fas fa-dollar-sign text-success"></i> A RECEBER</h4>
                                    <h5 class="text-dark mb-0"><span class="mr-2 align-right">Recebido</span>  <span class="text-info float-right" id="qtde_receber_recebido"></span> </h5>
                                    <h5 class="text-dark mb-0"><span class="mr-2">Em Aberto</span> <span class="text-info float-right" id="qtde_receber_aberto"></span> </h5>
                                </div>
                            </div>
                            <i class="fas fa-search text-info" style="bottom: 19px; position: absolute; right: 22px;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6">
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div class="card" id="content-meus-processos">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-center">
                                <img src="{{ env('APP_URL') }}imagens/logo_escritorio.png" alt="logo-cliente" class="dark-logo" style="height: 212px; width: 250px;"/>
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

            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div class="card" id="content-meus-processos">
                        <div class="pt-0 pb-4 card-body">
                            <div class="mt-4 d-flex align-items-center justify-content-center">
                                <div class="ml-4">
                                    <h3 class="font-medium">Acesse no seu celular baixando o nosso APP EXCLUSIVO para clientes do escritório</h3>
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
@endsection
@section('scripts')
    <script src="{{ env('APP_URL') }}js/home.js"></script>
@endsection