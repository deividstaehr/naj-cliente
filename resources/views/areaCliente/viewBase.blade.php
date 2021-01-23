<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>    
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="{{ env('APP_URL') }}imagens/logo-naj-2020_N - Cópia.png" rel="shortcut icon" type="image/vnd.microsoft.icon" />

    <link href="{{ env('APP_URL') }}ampleAdmin/dist/css/style.min.css" rel="stylesheet">
    <link href="{{ env('APP_URL') }}css/app.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ env('APP_URL') }}naj-datatables/styles/alert.css">
    <link rel="stylesheet" href="{{ env('APP_URL') }}naj-datatables/styles/loading.css">
    <link rel="stylesheet" href="{{ env('APP_URL') }}naj-datatables/styles/modal.css">
    <link rel="stylesheet" href="{{ env('APP_URL') }}naj-datatables/styles/index.css">
    <link rel="stylesheet" href="{{ env('APP_URL') }}naj-datatables/styles/scrollbar.css">

    <title>@yield('title')</title>

    @yield('css')
</head>

<body class="body-page-naj">    
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <div id="main-wrapper">
        <header class="topbar">
            <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                <div class="navbar-header">
                    <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)">
                        <i class="ti-menu ti-close"></i>
                    </a>                        
                    <b class="logo-icon ml-5">
                        <img src="{{ env('APP_URL') }}imagens/logo-naj-125x50px.png" alt="homepage" class="dark-logo" />
                    </b>
                    <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i class="ti-more"></i></a>
                </div>
                <div class="navbar-collapse collapse mb-0 ">
                    &emsp;<span class="font-weight-bolder">Licenciado:</span>&nbsp;
                    <span id="nomeEmpresaLicenciada"></span>
                </div>
                <div class="navbar-collapse collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav float-right mr-auto"><li class="nav-item dropdown"></li></ul>
                    <ul class="navbar-nav float-right">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle waves-effect waves-dark pro-pic" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                @if (Auth::check())
                                    <span class="ml-2 user-text font-medium">{{ Auth::user()->nome }}</span><span class="fas fa-angle-down ml-2 user-text"></span>
                                @else
                                    <span class="ml-2 user-text font-medium">Usuário</span><span class="fas fa-angle-down ml-2 user-text"></span>
                                @endif
                            </a>
                            <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY">
                                <div class="d-flex no-block align-items-center p-3 mb-2 border-bottom">
                                    <div class="ml-2">
                                        <h4 class="mb-0">{{ Auth::user()->nome }}</h4>
                                        <p class=" mb-0 text-muted">{{ Auth::user()->email_recuperacao }}</p>
                                    </div>
                                </div>
                                <a class="dropdown-item"  href="{{ url(env('APP_ALIAS') . '/usuarios/perfil') }}"><i class="ti-user mr-1 ml-1"></i> Alterar dados do Usuário</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="javascript:void(0)" id="logout"><i class="fa fa-power-off mr-1 ml-1"></i> Sair</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

        <aside class="left-sidebar">
            <div class="scroll-sidebar">
                <nav class="sidebar-nav">
                    <ul id="sidebarnav" style="background-color: #20222a !important;">
                        <li class="sidebar-item"> <a class="sidebar-link" href="{{ url('ac/home') }}" aria-expanded="false"><i class="fas fa-home"></i>
							<span class="hide-menu">Início</span></a>
						</li>
                        <li class="sidebar-item"> <a class="sidebar-link" href="{{ url('ac/mensagens') }}" aria-expanded="false"><i class="far fa-comments"></i>
							<span class="hide-menu">Minhas Mensagens</span></a>
						</li>
                        <li class="sidebar-item"> <a class="sidebar-link" href="{{ url('ac/processos') }}" aria-expanded="false"><i class="fas fa-balance-scale"></i>
							<span class="hide-menu">Meus Processos</span></a>
						</li>
						<li class="sidebar-item"> <a class="sidebar-link" href="{{ url('ac/atividades') }}" aria-expanded="false"><i class="fas fa-tasks"></i>
							<span class="hide-menu">Atividades</span></a>
						</li>
                        <li class="sidebar-item" id="sidebar-item-financeiro"> <a class="sidebar-link" href="{{ url('ac/financeiro/index/receber') }}" aria-expanded="false" id="sidebar-link-financeiro"><i class="fas fa-donate"></i>
							<span class="hide-menu">Financeiro</span></a>
						</li>
                    </ul>
                </nav>
            </div>
        </aside>
        <!--loader do najFunctions é aplicado sobre o "page-wrapper" por default-->
        <div class="page-wrapper" style="display: block; height: 100vh;">
            @yield('content')
        </div>
    </div>
    <div style="display: none" id="active-layer">@yield('active-layer')</div>

    <script src="{{ env('APP_URL') }}ampleAdmin/assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="{{ env('APP_URL') }}ampleAdmin/assets/libs/popper.js/dist/umd/popper.min.js"></script>
    <script src="{{ env('APP_URL') }}ampleAdmin/assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="{{ env('APP_URL') }}ampleAdmin/dist/js/app.init.mini-sidebar.js"></script>
	<script src="{{ env('APP_URL') }}ampleAdmin/dist/js/app.init.horizontal-fullwidth.js"></script>	
	<script src="{{ env('APP_URL') }}ampleAdmin/dist/js/app.js"></script>	
    <script src="{{ env('APP_URL') }}ampleAdmin/dist/js/app-style-switcher.js"></script>
    <script src="{{ env('APP_URL') }}ampleAdmin/assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <script src="{{ env('APP_URL') }}ampleAdmin/assets/extra-libs/sparkline/sparkline.js"></script>
    <script src="{{ env('APP_URL') }}ampleAdmin/dist/js/waves.js"></script>
    <script src="{{ env('APP_URL') }}ampleAdmin/dist/js/sidebarmenu.js"></script>
    <script src="{{ env('APP_URL') }}ampleAdmin/dist/js/custom.min.js"></script>

    <script src="{{ env('APP_URL') }}js/input-mask/jquery.inputmask.js"></script>
    <script src="{{ env('APP_URL') }}js/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="{{ env('APP_URL') }}js/input-mask/jquery.inputmask.extensions.js"></script>
    <script src="{{ env('APP_URL') }}js/jQuery-Mask-Plugin/jquery.mask.min.js"></script>

    <script src="{{ env('APP_URL') }}js/axios.js"></script>
    <script src="{{ env('APP_URL') }}js/Naj.js"></script>
    <script src="{{ env('APP_URL') }}js/NajFunctions.js"></script>

    <script>
        const baseURL           = "{{ env('APP_URL') }}" + "{{ env('APP_ALIAS') }}" + "/";
        const baseURLCpanel     = "{{ env('CPANEL_URL') }}";
        const appAlias          = "{{ env('APP_ALIAS') }}";
        const appUrl            = "{{ env('APP_URL') }}";
        const nomeUsuarioLogado = "{{ Auth::user()->nome }}";
        const tipoUsuarioLogado = "{{ Auth::user()->usuario_tipo_id }}";
        const idUsuarioLogado   = "{{ Auth::user()->id }}";

        $(window).on('load', () => {
            identificador = sessionStorage.getItem('@NAJ_CLIENTE/identificadorEmpresa');
            if(!identificador) {
                //Busca o identificador
                axios({
                    method: 'get',
                    url: `${baseURL}empresas/identificador`
                }).then(response => {
                    if(!response.data) return;

                    sessionStorage.setItem('@NAJ_CLIENTE/identificadorEmpresa', response.data);
                });

                //Busca os dados da empresa
                axios({
                    method: 'get',
                    url: `${baseURL}empresas/getNomeFirstEmpresa`
                }).then(response => {
                    if (!response.data) return;
                    sessionStorage.setItem('@NAJ_CLIENTE/nomeEmpresa', response.data);
                    $('#nomeEmpresaLicenciada')[0].innerHTML = `${response.data}`;

                    if($('#nomeEmpresa')[0]) {
                        $('#nomeEmpresa')[0].innerHTML = `${response.data}`;
                    }
                });
            }

            $('#nomeEmpresaLicenciada')[0].innerHTML = `${sessionStorage.getItem('@NAJ_CLIENTE/nomeEmpresa')}`;
            if($('#nomeEmpresa')[0]) {
                $('#nomeEmpresa')[0].innerHTML = `${sessionStorage.getItem('@NAJ_CLIENTE/nomeEmpresa')}`;
            }
            $('#logout').on('click', onClickLogout);
        });

        function onClickLogout() {
            localStorage.clear();
            sessionStorage.clear();
            window.location.href = "{{ url('auth/logout') }}";
        }

    </script>
    <script src="{{ env('APP_URL') }}js/datatable/api.js"></script>
    <script src="{{ env('APP_URL') }}naj-datatables/src/sweetalert2.min.js"></script>
    <script src="{{ env('APP_URL') }}naj-datatables/src/functions.js"></script>
    <script src="{{ env('APP_URL') }}naj-datatables/src/TableModel.js"></script>
    <script src="{{ env('APP_URL') }}naj-datatables/src/Table.js"></script>
    <script src="{{ env('APP_URL') }}naj-datatables/src/alerts.js"></script>
    <script src="{{ env('APP_URL') }}naj-datatables/src/masks.js"></script>
    <script src='https://momentjs.com/downloads/moment.min.js'></script>

    @yield('scripts')

    </body>
</html>