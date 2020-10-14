@extends('areaCliente.viewBase')

@section('title', 'NAJ - Cliente | Processos')

@section('css')
@endsection

@section('active-layer', 'processo')
@section('content')

<div id="datatable-processos" class="naj-datatable" style="height: 100%;"></div>

@endsection

@section('scripts')
    <script src="{{ env('APP_URL') }}js/tables/processosTable.js"></script>
    <script src="{{ env('APP_URL') }}js/processos.js"></script>
@endsection