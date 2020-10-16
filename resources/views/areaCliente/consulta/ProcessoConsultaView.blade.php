@extends('areaCliente.viewBase')

@section('title', 'NAJ - Cliente | Processos')

@section('css')
@endsection

@section('active-layer', 'processo')
@section('content')

<div id="datatable-processos" class="naj-datatable" style="height: 100%;"></div>

@component('areaCliente.componentes.modalConsultaProcessoAnexo')
@endcomponent

@component('areaCliente.componentes.modalConsultaAtividadeProcesso')
@endcomponent

@endsection

@section('scripts')
    <script src="{{ env('APP_URL') }}js/tables/anexoProcessoTable.js"></script>
    <script src="{{ env('APP_URL') }}js/tables/atividadeProcessoTable.js"></script>
    <script src="{{ env('APP_URL') }}js/tables/processosTable.js"></script>
    <script src="{{ env('APP_URL') }}js/processos.js"></script>
@endsection