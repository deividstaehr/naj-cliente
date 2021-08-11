@extends('areaCliente.viewBase')

@section('title', 'NAJ - Cliente | Agenda')

@section('css')
    <link rel="stylesheet" href="{{ env('APP_URL') }}css/agenda.css">
@endsection

@section('active-layer', 'agendaCompromissos')
@section('content')

<div class="content-agenda">
    <div id="loading-agendamento" class="loader loader-default" data-half></div>
    <div class="row" style="height: 100%;">
        <div class="col-list-events col-lg-6 col-md-6 col-sm-12 pr-0" style="height: 100%;">
            <div class="container">
                <div class="card card-novo-agendamento">
                    <h2 class="weight-500">Novo Agendamento</h2>
                    <hr/>
                    <div class="content-agenda-buttons">                    
                        <button class="btn-agenda btn btn-primary" onclick="onClickAgendarConsulta()">Agendar uma Consulta</button>
                        <button class="btn-agenda btn btn-info" onclick="onClickAgendarReuniao()">Agendar uma Reunião</button>
                        <button class="text-white btn-agenda btn btn-warning" onclick="onClickAgendarVisita()">Agendar uma Visita</button>
                        <button class="btn-agenda btn btn-secondary" onclick="onClickOutroAgendamento()">Outro tipo de Agendamento</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-list-agendamento col-lg-6 col-md-6 col-sm-12 pl-0">
            <div class="container">
                <div class="card" style="width: 100%;">
                    <h2 class="weight-500">Próximos Eventos</h2>
                    <hr/>
                    <div class="container-agenda naj-scrollable"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@component('areaCliente.componentes.modalConsultaAnexoAtividade')
@endcomponent

@endsection

@section('scripts')
    <script src="{{ env('APP_URL') }}js/agenda.js"></script>
@endsection