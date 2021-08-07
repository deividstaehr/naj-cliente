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
        <div class="col-6 pr-0 mr-0" style="height: 100%;">
            <div class="container">
                <div class="card" style="width: 100%;">
                    <h2 class="weight-500">Próximos Eventos</h2>
                    <hr/>
                    <div class="container-agenda naj-scrollable">
                        <!-- <div class="row row-striped">
                            <div class="col-2 p-0 text-right">
                                <h3 class="display-4"><span class="badge badge-info">05</span></h3>
                                <h6>Julho/2021</h6>
                            </div>
                            <div class="col-10">
                                <h5 class="text-uppercase"><strong>Reunião com cliente</strong></h5>
                                <ul class="list-inline">
                                    <li class="list-inline-item"><i class="fa fa-calendar-alt" aria-hidden="true"></i> Segunda</li>
                                    <li class="list-inline-item"><i class="fa fa-clock" aria-hidden="true"></i> 12:30 PM - 2:00 PM</li>
                                    <li class="list-inline-item"><i class="fas fa-map-marker-alt" aria-hidden="true"></i> 1º Vara do Trabalho Bal.Camboriú</li>
                                </ul>
                                <p>Pagamento de custas. (conforme publicação do dia 04/03/2020).</p>
                            </div>
                        </div>
                        <div class="row row-striped">
                            <div class="col-2 p-0 text-right">
                                <h3 class="display-4"><span class="badge badge-info">09</span></h3>
                                <h6>Julho/2021</h6>
                            </div>
                            <div class="col-10">
                                <h5 class="text-uppercase"><strong>Reunião</strong></h5>
                                <ul class="list-inline">
                                    <li class="list-inline-item"><i class="fa fa-calendar-alt" aria-hidden="true"></i> Terça</li>
                                    <li class="list-inline-item"><i class="fa fa-clock" aria-hidden="true"></i> 12:30 PM - 2:00 PM</li>
                                    <li class="list-inline-item"><i class="fas fa-map-marker-alt" aria-hidden="true"></i> 1º Vara do Trabalho Bal.Camboriú</li>
                                </ul>
                                <p>Pagamento de custas. (conforme publicação do dia 04/03/2020).</p>
                            </div>
                        </div>
                        <div class="row row-striped">
                            <div class="col-2 p-0 text-right">
                                <h3 class="display-4"><span class="badge badge-info">12</span></h3>
                                <h6>Julho/2021</h6>
                            </div>
                            <div class="col-10">
                                <h5 class="text-uppercase"><strong>Enviar relatório atendimentos ADM</strong></h5>
                                <ul class="list-inline">
                                    <li class="list-inline-item"><i class="fa fa-calendar-alt" aria-hidden="true"></i> Segunda</li>
                                    <li class="list-inline-item"><i class="fa fa-clock" aria-hidden="true"></i> 12:30 PM - 2:00 PM</li>
                                    <li class="list-inline-item"><i class="fas fa-map-marker-alt" aria-hidden="true"></i> Local do Evento</li>
                                </ul>
                                <p>Aqui vai qualqer que seja a descrição do evento apenas para ilustrar mesmo.</p>
                            </div>
                        </div>
                        <div class="row row-striped">
                            <div class="col-2 p-0 text-right">
                                <h3 class="display-4"><span class="badge badge-info">14</span></h3>
                                <h6>Julho/2021</h6>
                            </div>
                            <div class="col-10">
                                <h5 class="text-uppercase"><strong>Enviar relatório atendimentos ADM</strong></h5>
                                <ul class="list-inline">
                                    <li class="list-inline-item"><i class="fa fa-calendar-alt" aria-hidden="true"></i> Segunda</li>
                                    <li class="list-inline-item"><i class="fa fa-clock" aria-hidden="true"></i> 12:30 PM - 2:00 PM</li>
                                    <li class="list-inline-item"><i class="fas fa-map-marker-alt" aria-hidden="true"></i> Local do Evento</li>
                                </ul>
                                <p>Aqui vai qualqer que seja a descrição do evento apenas para ilustrar mesmo.</p>
                            </div>
                        </div>
                        <div class="row row-striped">
                            <div class="col-2 p-0 text-right">
                                <h3 class="display-4"><span class="badge badge-info">15</span></h3>
                                <h6>Julho/2021</h6>
                            </div>
                            <div class="col-10">
                                <h5 class="text-uppercase"><strong>Enviar relatório atendimentos ADM</strong></h5>
                                <ul class="list-inline">
                                    <li class="list-inline-item"><i class="fa fa-calendar-alt" aria-hidden="true"></i> Segunda</li>
                                    <li class="list-inline-item"><i class="fa fa-clock" aria-hidden="true"></i> 12:30 PM - 2:00 PM</li>
                                    <li class="list-inline-item"><i class="fas fa-map-marker-alt" aria-hidden="true"></i> 1º Vara do Trabalho Bal.Camboriú</li>
                                </ul>
                                <p>Aqui vai qualqer que seja a descrição do evento apenas para ilustrar mesmo.</p>
                            </div>
                        </div>
                        <div class="row row-striped">
                            <div class="col-2 p-0 text-right">
                                <h3 class="display-4"><span class="badge badge-info">15</span></h3>
                                <h6>Julho/2021</h6>
                            </div>
                            <div class="col-10">
                                <h5 class="text-uppercase"><strong>Enviar relatório atendimentos ADM</strong></h5>
                                <ul class="list-inline">
                                    <li class="list-inline-item"><i class="fa fa-calendar-alt" aria-hidden="true"></i> Segunda</li>
                                    <li class="list-inline-item"><i class="fa fa-clock" aria-hidden="true"></i> 12:30 PM - 2:00 PM</li>
                                    <li class="list-inline-item"><i class="fas fa-map-marker-alt" aria-hidden="true"></i> Local do Evento</li>
                                </ul>
                                <p>Aqui vai qualqer que seja a descrição do evento apenas para ilustrar mesmo.</p>
                            </div>
                        </div>
                        <div class="row row-striped">
                            <div class="col-2 p-0 text-right">
                                <h3 class="display-4"><span class="badge badge-info">23</span></h3>
                                <h6>Julho/2021</h6>
                            </div>
                            <div class="col-10">
                                <h5 class="text-uppercase"><strong>Enviar relatório atendimentos ADM</strong></h5>
                                <ul class="list-inline">
                                    <li class="list-inline-item"><i class="fa fa-calendar-alt" aria-hidden="true"></i> Segunda</li>
                                    <li class="list-inline-item"><i class="fa fa-clock" aria-hidden="true"></i> 12:30 PM - 2:00 PM</li>
                                    <li class="list-inline-item"><i class="fas fa-map-marker-alt" aria-hidden="true"></i> Local do Evento</li>
                                </ul>
                                <p>Aqui vai qualqer que seja a descrição do evento apenas para ilustrar mesmo.</p>
                            </div>
                        </div>
                        <div class="row row-striped">
                            <div class="col-2 p-0 text-right">
                                <h3 class="display-4"><span class="badge badge-info">01</span></h3>
                                <h6>Agosto/2021</h6>
                            </div>
                            <div class="col-10">
                                <h5 class="text-uppercase"><strong>Enviar relatório atendimentos ADM</strong></h5>
                                <ul class="list-inline">
                                    <li class="list-inline-item"><i class="fa fa-calendar-alt" aria-hidden="true"></i> Segunda</li>
                                    <li class="list-inline-item"><i class="fa fa-clock" aria-hidden="true"></i> 12:30 PM - 2:00 PM</li>
                                    <li class="list-inline-item"><i class="fas fa-map-marker-alt" aria-hidden="true"></i> 1º Vara do Trabalho Bal.Camboriú</li>
                                </ul>
                                <p>Aqui vai qualqer que seja a descrição do evento apenas para ilustrar mesmo.</p>
                            </div>
                        </div>
                        <div class="row row-striped">
                            <div class="col-2 p-0 text-right">
                                <h3 class="display-4"><span class="badge badge-info">11</span></h3>
                                <h6>Agosto/2021</h6>
                            </div>
                            <div class="col-10">
                                <h5 class="text-uppercase"><strong>Cliente: Cintia Feliski- Atendimento</strong></h5>
                                <ul class="list-inline">
                                    <li class="list-inline-item"><i class="fa fa-calendar-alt" aria-hidden="true"></i> Segunda</li>
                                    <li class="list-inline-item"><i class="fa fa-clock" aria-hidden="true"></i> 12:30 PM - 2:00 PM</li>
                                    <li class="list-inline-item"><i class="fas fa-map-marker-alt" aria-hidden="true"></i> Local do Evento</li>
                                </ul>
                                <p>Aqui vai qualqer que seja a descrição do evento apenas para ilustrar mesmo.</p>
                            </div>
                        </div>
                        <div class="row row-striped">
                            <div class="col-2 p-0 text-right">
                                <h3 class="display-4"><span class="badge badge-info">26</span></h3>
                                <h6>Agosto/2021</h6>
                            </div>
                            <div class="col-10">
                                <h5 class="text-uppercase"><strong>Atendimento Sra Desireé</strong></h5>
                                <ul class="list-inline">
                                    <li class="list-inline-item"><i class="fa fa-calendar-alt" aria-hidden="true"></i> Segunda</li>
                                    <li class="list-inline-item"><i class="fa fa-clock" aria-hidden="true"></i> 12:30 PM - 2:00 PM</li>
                                    <li class="list-inline-item"><i class="fas fa-map-marker-alt" aria-hidden="true"></i> Local do Evento</li>
                                </ul>
                                <p>Aqui vai qualqer que seja a descrição do evento apenas para ilustrar mesmo.</p>
                            </div>
                        </div>
                        <div class="row row-striped">
                            <div class="col-2 p-0 text-right">
                                <h1 class="display-4"><span class="badge badge-info">28</span></h1>
                                <h6>Agosto/2021</h6>
                            </div>
                            <div class="col-10">
                                <h5 class="text-uppercase"><strong>Reunião</strong></h5>
                                <ul class="list-inline">
                                    <li class="list-inline-item"><i class="fa fa-calendar-alt" aria-hidden="true"></i> Segunda</li>
                                    <li class="list-inline-item"><i class="fa fa-clock" aria-hidden="true"></i> 12:30 PM - 2:00 PM</li>
                                    <li class="list-inline-item"><i class="fas fa-map-marker-alt" aria-hidden="true"></i> Local do Evento</li>
                                </ul>
                                <p>Aqui vai qualqer que seja a descrição do evento apenas para ilustrar mesmo.</p>
                            </div>
                        </div>
                        <div class="row row-striped">
                            <div class="col-2 p-0 text-right">
                                <h1 class="display-4"><span class="badge badge-info">30</span></h1>
                                <h6>Agosto/2021</h6>
                            </div>
                            <div class="col-10">
                                <h5 class="text-uppercase"><strong>Reunião com sra. Aline</strong></h5>
                                <ul class="list-inline">
                                    <li class="list-inline-item"><i class="fa fa-calendar-alt" aria-hidden="true"></i> Segunda</li>
                                    <li class="list-inline-item"><i class="fa fa-clock" aria-hidden="true"></i> 12:30 PM - 2:00 PM</li>
                                    <li class="list-inline-item"><i class="fas fa-map-marker-alt" aria-hidden="true"></i> Local do Evento</li>
                                </ul>
                                <p>Aqui vai qualqer que seja a descrição do evento apenas para ilustrar mesmo.</p>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 pl-0">
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
    </div>
</div>

@component('areaCliente.componentes.modalConsultaAnexoAtividade')
@endcomponent

@endsection

@section('scripts')
    <script src="{{ env('APP_URL') }}js/agenda.js"></script>
@endsection