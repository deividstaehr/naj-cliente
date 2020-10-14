@extends('areaCliente.viewBase')

@section('title', 'NAJWebAC | Área do Cliente')

@section('css')
@endsection

@section('active-layer', 'home')

@section('content')
<div class="page-content container-fluid p-2" style="min-height: calc(100vh - 110px) !important;">
    <div class="row">
        <div class="col-md-3 col-lg-3">
            <div class="card card-hover cursorActive">
                <div class="card-body">
                    <h5 class="card-title text-uppercase">NOVAS MENSAGENS</h5>
                    <div class="d-flex align-items-center mb-2 mt-4">
                        <h2 class="mb-0 display-7"><i class="fas fa-comments text-info"></i></h2>
                        <div class="ml-auto">
                            <h2 class="mb-0 display-7"><span class="font-normal">2</span></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-lg-3">
            <div class="card card-hover cursorActive">
                <div class="card-body">
                    <h5 class="card-title text-uppercase">NOVAS NOTIFICAÇÕES</h5>
                    <div class="d-flex align-items-center mb-2 mt-4">
                        <h2 class="mb-0 display-7"><i class="mdi mdi-bell-ring-outline text-info"></i></h2>
                        <div class="ml-auto">
                            <h2 class="mb-0 display-7"><span class="font-normal">4</span></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-6">
            <div class="card card-hover">
                <div class="card-body">
                    <h5 class="card-title text-uppercase">AGENDA DE COMPROMISSOS</h5>
                    <div class="d-flex no-block align-items-center">
                        <h2 class="mb-0 display-5"><i class="icon-calender text-primary"></i></h2>
                        <div class="ml-auto cursorActive">
                            <h3 class="font-medium">2</h3>
                            <h5 class="text-info mb-0"><i class="fas fa-search text-info"></i> Próximos Eventos</h5>
                        </div>
                        <div class="ml-auto cursorActive">
                            <h3 class="font-medium">7</h3>
                            <h5 class="text-info mb-0"><i class="fas fa-search text-info"></i> Todos os Compromissos</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card card-hover">
                <div class="card-body">
                    <h5 class="card-title text-uppercase">Meus Processos</h5>
                    <div class="d-flex no-block align-items-center">
                        <h2 class="mb-0 display-5"><i class="fas fa-balance-scale text-primary"></i></h2>
                        <div class="ml-auto cursorActive">
                            <h3 class="font-medium">3</h3>
                            <h5 class="text-info mb-0"><i class="fas fa-search text-info"></i> Ativos</h5>
                        </div>
                        <div class="ml-auto cursorActive">
                            <h3 class="font-medium">7</h3>
                            <h5 class="text-info mb-0"><i class="fas fa-search text-info"></i> Todos</h5>
                        </div>
                        <div class="ml-auto cursorActive">
                            <h3 class="font-medium">2</h3>
                            <h5 class="text-info mb-0"><i class="fas fa-search text-info"></i> Judicial</h5>
                        </div>
                        <div class="ml-auto cursorActive">
                            <h3 class="font-medium">1</h3>
                            <h5 class="text-info mb-0"><i class="fas fa-search text-info"></i> Extrajudicial</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 col-lg-4">
            <div class="card card-hover cursorActive">
                <div class="card-body">
                    <h5 class="card-title text-uppercase">EM ABERTO</h5>
                    <div class="d-flex no-block align-items-center">
                        <h2 class="mb-0 display-5"><i class="fas fa-donate text-danger"></i></h2>
                        <div class="ml-auto">
                            <h2 class="mb-0 display-8"><span class="font-normal">R$ 500,00</span></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8 col-lg-8">
            <div class="card card-hover cursorActive">
                <div class="card-body">
                    <h5 class="card-title text-uppercase">FINANCEIRO</h5>
                    <div class="d-flex no-block align-items-center">
                        <h2 class="mb-0 display-5"><i class="fas fa-donate text-primary"></i></h2>
                        <div class="ml-auto">
                            <h3 class="font-medium">R$ 500,00</h3>
                            <h5 class="text-info mb-0">Contas em Aberto</h5>
                        </div>
                        <div class="ml-auto">
                            <h3 class="font-medium">R$ 12.000,00</h3>
                            <h5 class="text-info mb-0">Total Pago</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection