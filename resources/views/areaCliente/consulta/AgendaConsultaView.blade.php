@extends('areaCliente.viewBase')

@section('title', 'NAJ - Cliente | Agenda')

@section('css')
    <link rel="stylesheet" href="{{ env('APP_URL') }}css/gijgo.min.css">
    <style>
        button {
            background: #fff !important;
        }
        .naj-datatable i {
            color: rgba(47, 50, 62, .75);
            cursor: pointer;
            font-size: 14px;
        }
    </style>
@endsection

@section('active-layer', 'agendaCompromissos')
@section('content')

<div id="datatable-agenda" class="naj-datatable" style="height: 100%;"></div>

@component('areaCliente.componentes.modalConsultaAnexoAtividade')
@endcomponent

@endsection

@section('scripts')
    <script src="{{ env('APP_URL') }}js/tables/agendaTable.js"></script>
    <script src="{{ env('APP_URL') }}js/agenda.js"></script>
@endsection