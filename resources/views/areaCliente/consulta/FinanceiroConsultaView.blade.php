@extends('areaCliente.viewBase')

@section('title', 'NAJ - Cliente | Financeiro')

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

@section('active-layer', 'financeiro')
@section('content')

<div class="page-content container-fluid note-has-grid p-2 mb-2" style="height: 89%; min-height: 89% !important;">
    <ul class="nav nav-pills p-2 bg-white rounded-pill align-items-center">
        <li class="nav-item">
            <a href="#receber" id="link-receber" class="nav-link active rounded-pill note-link d-flex align-items-center px-2 px-md-3 mr-0 mr-md-2" onclick="onClickTabReceber();">
                <i class="fas fa-dollar-sign mr-1"></i>
                <span class="d-none d-md-block">A RECEBER</span>
            </a> 
        </li>
        <li class="nav-item">
            <a href="#pagar" id="link-pagar" class="nav-link rounded-pill note-link d-flex align-items-center px-2 px-md-3 mr-0 mr-md-2" onclick="onClickTabPagar();">
                <i class="fas fa-dollar-sign mr-1"></i>
                <span class="d-none d-md-block">A PAGAR</span>
            </a>
        </li>
    </ul>
    <div class="tab-content bg-transparent" style="height: 93%;">
        <div id="note-full-container" class="note-has-grid row" style="height: 100%;">
            <div class="tab-pane active content-full" id="receber" role="tabpanel" style="width: 100%; height: 100%;">
                <div class="content-full single-note-item" style="height: 100%;">
                    <div id="datatable-financeiro-receber" class="naj-datatable"></div>
                </div>
            </div>
            <div class="tab-pane content-full" id="pagar" role="tabpanel" style="width: 100%; height: 100%;">
                <div class="content-full single-note-item" style="height: 100%;">
                    <div id="datatable-financeiro-pagar" class="naj-datatable"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="content-bottom-pagar" class="row datatable-body mt-0" style="height: 8%; margin: 8px !important;">
    <div class="col-6 p-0">
        <span>
            <span class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="Valor total que já PAGUEI."></span>
            <b>Total PAGO:</b> <span class="badge badge-success badge-rounded" id='total_pagar_pago' style="margin-top: 1px; position: absolute;"></span>&emsp;

            <span class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="Valor total que FALTA PAGAR." style="margin-left: 10%;"></span>
            <b>Total A PAGAR:</b> <span class="badge badge-info badge-rounded" id='total_pagar_pagar' style="margin-top: 1px; position: absolute;"></span>&emsp;

            <span class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="Valor total que falta receber e esta ATRASADO." style="margin-left: 10%;"></span>
            <b>Total ATRASADO:</b> <span class="badge badge-danger badge-rounded" id='total_pagar_atrasado' style="margin-top: 1px; position: absolute;"></span>&emsp;
        </span>
    </div>
</div>

<div id="content-bottom-receber" class="row datatable-body mt-0" style="height: 8%; margin: 8px !important;">
    <div class="col-6 p-0">
        <span>
            <span class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="Valor total que já PAGUEI."></span>
            <b>Total RECEBIDO:</b> <span class="badge badge-success badge-rounded" id='total_receber_recebido' style="margin-top: 1px; position: absolute;"></span>&emsp;

            <span class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="Valor total que FALTA PAGAR." style="margin-left: 10%;"></span>
            <b>Total A RECEBER:</b> <span class="badge badge-info badge-rounded" id='total_receber_receber' style="margin-top: 1px; position: absolute;"></span>&emsp;

            <span class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="Valor total que falta receber e esta ATRASADO." style="margin-left: 10%;"></span>
            <b>Total ATRASADO:</b> <span class="badge badge-danger badge-rounded" id='total_receber_atrasado' style="margin-top: 1px; position: absolute;"></span>&emsp;
        </span>
    </div>
</div>

@endsection

@section('scripts')
    <script src="{{ env('APP_URL') }}js/gijgo.min.js"></script>
    <script src="{{ env('APP_URL') }}js/messages.pt-br.js"></script>
    <script src="{{ env('APP_URL') }}js/tables/financeiroPagarTable.js"></script>
    <script src="{{ env('APP_URL') }}js/tables/financeiroReceberTable.js"></script>
    <script src="{{ env('APP_URL') }}js/financeiro.js"></script>
@endsection