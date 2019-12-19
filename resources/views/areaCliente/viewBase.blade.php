<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>    
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <link href="/css/app.css" rel="stylesheet">
    <link href="/ampleAdmin/dist/css/style.min.css" rel="stylesheet">
    <link href="/ampleAdmin/assets/images/logos/logoNajMin.png" rel="shortcut icon" type="image/vnd.microsoft.icon" />
    <title>@yield('title')</title>

    @yield('css')
</head>

<body>    
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
                    <a class="navbar-brand" href=" {{url('ac/index')}} ">
                        <b class="logo-icon">
                            <img src="/ampleAdmin/assets/images/logos/logoNaj.png" alt="homepage" class="dark-logo" />
                        </b>
                    </a>
                    <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i class="ti-more"></i></a>
                </div>
                <div class="navbar-collapse collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav float-right mr-auto">
                        <li class="nav-item d-none d-md-block"><a class="nav-link sidebartoggler waves-effect waves-light" href="javascript:void(0)" data-sidebartype="mini-sidebar"><i class="mdi mdi-menu font-24"></i></a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" id="2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="font-18 far fa-comments">
								</i>
                                <div class="notify">
                                    <span class="heartbit"></span>
                                    <span class="point"></span>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-left mailbox animated bounceInDown" aria-labelledby="2">
                                <ul class="list-style-none">
                                    <li>
                                        <div class="drop-title border-bottom">Você tem 2 mensagens novas</div>
                                    </li>
                                    <li>
                                        <div class="message-center message-body">
                                            <!-- Message -->
                                            <a href="javascript:void(0)" class="message-item">
                                                <span class="user-img"> <img src="/ampleAdmin/assets/images/users/1.jpg" alt="user" class="rounded-circle"> <span class="profile-status online pull-right"></span> </span>
                                                <span class="mail-contnet">
                                                    <h5 class="message-title">Dr. João Advogado</h5> <span class="mail-desc">Olá, aqui é o dr. João, estou passando para fazer um resumo dos...</span> <span class="time">9:30 AM</span> </span>
                                            </a>
                                            <!-- Message -->
                                            <a href="javascript:void(0)" class="message-item">
                                                <span class="user-img"> <img src="/ampleAdmin/assets/images/users/2.jpg" alt="user" class="rounded-circle"> <span class="profile-status busy pull-right"></span> </span>
                                                <span class="mail-contnet">
                                                    <h5 class="message-title">Estagiário do Escritório BBB</h5> <span class="mail-desc">Olá, boa tarde, estou passando para informar que....</span> <span class="time">9:10 AM</span> </span>
                                            </a>
                                            
                                        </div>
                                    </li>
                                    <li>
                                        <a class="nav-link text-center link text-dark" href="javascript:void(0);"> <b>Ver todas as Mensagens</b> <i class="fa fa-angle-right"></i> </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="mdi mdi-bell-ring-outline font-18"></i>
                                <div class="notify">
                                    <span class="heartbit"></span>
                                    <span class="point"></span>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-left mailbox animated bounceInDown">
                                <span class="with-arrow"><span class="bg-primary"></span></span>
                                <ul class="list-style-none">
                                    <li>
                                        <div class="drop-title border-bottom">Você tem 4 Notificações Novas</div>
                                    </li>
                                    <li>
                                        <div class="message-center notifications">
                                            <!-- Message -->
                                            <a href="javascript:void(0)" class="message-item">
                                                <span class="btn btn-danger btn-circle"><i class="fa fa-link"></i></span>
                                                <span class="mail-contnet">
                                                    <h5 class="message-title">Nova Mensagem</h5> <span class="mail-desc">Ocorreram novas movimentações no seu processo</span> <span class="time">9:30 AM</span> </span>
                                            </a>
                                            <!-- Message -->
                                            <a href="javascript:void(0)" class="message-item">
                                                <span class="btn btn-success btn-circle"><i class="ti-calendar"></i></span>
                                                <span class="mail-contnet">
                                                    <h5 class="message-title">Novo Compromisso</h5> <span class="mail-desc">Você possui novas mensagens não lidas</span> <span class="time">9:10 AM</span> </span>
                                            </a>
                                            <!-- Message -->
                                            <a href="javascript:void(0)" class="message-item">
                                                <span class="btn btn-info btn-circle"><i class="ti-settings"></i></span>
                                                <span class="mail-contnet">
                                                    <h5 class="message-title">Nova Mensagem</h5> <span class="mail-desc">Você tem uma nova mensagem do escritório xxx</span> <span class="time">9:08 AM</span> </span>
                                            </a>
                                            <!-- Message -->
                                            <a href="javascript:void(0)" class="message-item">
                                                <span class="btn btn-primary btn-circle"><i class="ti-user"></i></span>
                                                <span class="mail-contnet">
                                                    <h5 class="message-title">Informativo</h5> <span class="mail-desc">Olá, detectamos novos atividades no seu processo</span> <span class="time">9:02 AM</span> </span>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <a class="nav-link text-center mb-1 text-dark" href="javascript:void(0);"> <strong>Ver todas as Notificações</strong> <i class="fa fa-angle-right"></i> </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>

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
                                <a class="dropdown-item" href="javascript:void(0)"><i class="ti-user mr-1 ml-1"></i> Perfil</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="javascript:void(0)" id="logout"><i class="fa fa-power-off mr-1 ml-1"></i> Logout</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

        <aside class="left-sidebar">
            <div class="scroll-sidebar">
                <nav class="sidebar-nav">
                    <ul id="sidebarnav">
                        <li class="sidebar-item"> <a class="sidebar-link" href="{{ url('ac/home') }}" aria-expanded="false"><i class="fas fa-chart-line"></i>
							<span class="hide-menu">Início</span></a>
						</li>
                        <li class="sidebar-item"> <a class="sidebar-link" href="{{ url('ac/mensagens') }}" aria-expanded="false"><i class="far fa-comments"></i>
							<span class="hide-menu">Minhas Mensagens</span></a>
						</li>
                        <li class="sidebar-item"> <a class="sidebar-link" href="{{ url('ac/processos') }}" aria-expanded="false"><i class="fas fa-balance-scale"></i>
							<span class="hide-menu">Meus Processos</span></a>
						</li>
						<li class="sidebar-item"> <a class="sidebar-link" href="{{ url('ac/agendaCompromissos') }}" aria-expanded="false"><i class="icon-calender"></i>
							<span class="hide-menu">Agenda de Compromissos</span></a>
						</li>
                    </ul>
                </nav>
            </div>
        </aside>
        <div class="page-wrapper">
            @yield('content')
        </div>
    </div>
    <div style="display: none" id="active-layer">@yield('active-layer')</div>

    <script src="/ampleAdmin/assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="/ampleAdmin/assets/libs/popper.js/dist/umd/popper.min.js"></script>
    <script src="/ampleAdmin/assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="/ampleAdmin/dist/js/app.init.mini-sidebar.js"></script>
	<script src="/ampleAdmin/dist/js/app.init.horizontal-fullwidth.js"></script>	
	<script src="/ampleAdmin/dist/js/app.js"></script>	
    <script src="/ampleAdmin/dist/js/app-style-switcher.js"></script>
    <script src="/ampleAdmin/assets/libs/perfect-scrollbar/dist/perfect-scrollbar.jquery.min.js"></script>
    <script src="/ampleAdmin/assets/extra-libs/sparkline/sparkline.js"></script>
    <script src="/ampleAdmin/dist/js/waves.js"></script>
    <script src="/ampleAdmin/dist/js/sidebarmenu.js"></script>
    <script src="/ampleAdmin/dist/js/custom.min.js"></script>

    <script src="/js/input-mask/jquery.inputmask.js"></script>
    <script src="/js/input-mask/jquery.inputmask.date.extensions.js"></script>
    <script src="/js/input-mask/jquery.inputmask.extensions.js"></script>
    <script src="/js/jQuery-Mask-Plugin/jquery.mask.min.js"></script>

    <script>

        $(window).on('load', () => {
            $('#logout').on('click', onClickLogout);
        });

        function onClickLogout() {
            window.location.href = "{{ url('auth/logout') }}";
        }

    </script>

    @yield('scripts')

    </body>
</html>