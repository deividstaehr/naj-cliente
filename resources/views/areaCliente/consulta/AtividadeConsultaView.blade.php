@extends('areaCliente.viewBase')

@section('title', 'NAJ - Cliente | Atividades')

@section('css')
@endsection

@section('active-layer', 'processo')
@section('content')

<div id="datatable-atividades" class="naj-datatable" style="height: 91%;"></div>
<div id='saldoAnterior' class="row datatable-body mt-0" style="height: 7%;">
    <div class="col-6 p-0">
        <span>
            <span class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="Total de Horas das Atividades."></span>
            <b>Total de Horas:</b> <span id='total_horas'></span>&emsp;
        </span>
    </div>
</div>

@component('areaCliente.componentes.modalConsultaAnexoAtividade')
@endcomponent

@endsection

@section('scripts')
    <script src="{{ env('APP_URL') }}js/tables/anexoAtividadeTable.js"></script>
    <script src="{{ env('APP_URL') }}js/tables/atividadeTable.js"></script>
    <script src="{{ env('APP_URL') }}js/atividades.js"></script>
@endsection