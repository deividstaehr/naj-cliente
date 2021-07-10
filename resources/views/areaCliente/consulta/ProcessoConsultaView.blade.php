@extends('areaCliente.viewBase')

@section('title', 'NAJ - Cliente | Processos')

@section('css')
    <style>
        /* AJUSTES PARA TELAS PEQUENAS */
        @media only screen and (max-width: 766px) {
            #datatable-processos {
                height: 90% !important;
            }
        }
    </style>
@endsection

@section('active-layer', 'processo')
@section('content')

<div id="datatable-processos" class="naj-datatable" style="height: 100%;"></div>

@component('areaCliente.componentes.modalConsultaProcessoAnexo')
@endcomponent

@component('areaCliente.componentes.modalConsultaAtividadeProcesso')
@endcomponent

@component('areaCliente.componentes.modalConsultaAndamentoProcesso')
@endcomponent

@component('areaCliente.componentes.modalConsultaAnexoAtividade')
@endcomponent

@component('areaCliente.componentes.modalConsultaObservacao')
@endcomponent

@endsection

@section('scripts')
    <script src="{{ env('APP_URL') }}js/tables/anexoProcessoTable.js"></script>
    <script src="{{ env('APP_URL') }}js/tables/atividadeProcessoTable.js"></script>
    <script src="{{ env('APP_URL') }}js/tables/processosTable.js"></script>
    <script src="{{ env('APP_URL') }}js/tables/andamentoProcessoTable.js"></script>
    <script src="{{ env('APP_URL') }}js/tables/anexoAtividadeTable.js"></script>
    <script src="{{ env('APP_URL') }}js/processos.js"></script>
@endsection