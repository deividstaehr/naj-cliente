<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="{{ env('APP_URL') }}naj-datatables/styles/alert.css">
        <link href="{{ env('APP_URL') }}ampleAdmin/dist/css/style.min.css" rel="stylesheet">
        <link href="{{ env('APP_URL') }}imagens/logo-naj-2020_N - Cópia.png" rel="shortcut icon" type="image/vnd.microsoft.icon" />

        <script src="{{ env('APP_URL') }}naj-datatables/src/sweetalert2.min.js"></script>
        <script src="{{ env('APP_URL') }}naj-datatables/src/alerts.js"></script>

        <style>
            .btn-login-cpf-livre {
                background-color: transparent !important;
                border: none;
                margin-top: 5px;
            }

            .btn-login-cpf-livre:focus {
                outline: none !important;
            }
        </style>

        <title>NAJ Cliente - Login</title>
    </head>
    <body>
        <div class="main-wrapper">
            <div class="preloader">
                <div class="lds-ripple">
                    <div class="lds-pos"></div>
                    <div class="lds-pos"></div>
                </div>
            </div>

            <div id="main-atualizar-dados" class="auth-wrapper d-flex no-block justify-content-center align-items-center" style="background:url({{ env('APP_URL') }}ampleAdmin/assets/images/big/auth-bg.jpg) no-repeat center center;">
                <div class="auth-wrapper d-flex no-block justify-content-center align-items-center">
                    <div class="auth-box" style="width: 500px;">
                        <div id="loginform">
                            <div class="logo">
                                <img src="{{ env('APP_URL') }}imagens/logo_escritorio.png" alt="logo-cliente" class="dark-logo" style="height: 212px; width: 250px;"/>
                                <h4 class="font-medium mb-3 mt-2" id="nomeEmpresaLicenciada"></h4>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <form class="form-horizontal mt-2" id="loginform" method="post" action="login">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="ti-user"></i></span>
                                            </div>
                                            <input type="text" name="login" id="login" class="form-control form-control-lg mascaracpf" aria-label="Login" aria-describedby="basic-addon1">
                                            <div class="dropright mt-1">
                                                <button type="button" class="ml-2 btn-login-cpf-livre" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v" act="1"></i></button>
                                                <div class="dropdown-menu pb-0" style="position: absolute; transform: translate3d(99px, 0px, 0px); top: 0px; left: 0px; will-change: transform;">
                                                    <a class="dropdown-item mb-dropdown-item-divider" id="login-cpf" href="#">Login com CPF</a>
                                                    <a class="dropdown-item mb-dropdown-item-divider" id="login-livre" href="#">Login Livre</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon2"><i class="ti-pencil"></i></span>
                                            </div>
                                            <input type="password" name="password" id="password" class="form-control form-control-lg" placeholder="Senha" aria-label="Password" aria-describedby="basic-addon1">
                                        </div>
                                        <div class="form-group text-center">
                                            <div class="col-xs-12 pb-3">
                                                <button class="btn btn-block btn-lg btn-info" type="submit">Entrar</button>
                                            </div>
                                        </div>

                                        <span style="cursor: pointer; margin-left: 22%;" onclick="onClickModalEsqueceuLogin();"><i class="fas fa-lock"></i> Esqueceu o login ou a senha?</span>

                                        <input type="hidden" name="_method" value="POST">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    </form>
                                </div>

                                <br>
                                @if ($errors->has('login'))
                                <script>
                                    NajAlert.toastError("Verifique se os dados informados estão corretos e se o seu usuário está ativo!");
                                </script>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="{{ env('APP_URL') }}js/jquery.js"></script>
        <script src="{{ env('APP_URL') }}js/axios.js"></script>
        <script src="{{ env('APP_URL') }}ampleAdmin/assets/libs/jquery/dist/jquery.min.js"></script>        
        <script src="{{ env('APP_URL') }}ampleAdmin/assets/libs/popper.js/dist/umd/popper.min.js"></script>
        <script src="{{ env('APP_URL') }}ampleAdmin/assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>        
        <script src="{{ env('APP_URL') }}/js/input-mask/jquery.inputmask.js"></script>
        <script src="{{ env('APP_URL') }}js/input-mask/jquery.inputmask.date.extensions.js"></script>
        <script src="{{ env('APP_URL') }}js/input-mask/jquery.inputmask.extensions.js"></script>
        <script src="{{ env('APP_URL') }}js/jQuery-Mask-Plugin/jquery.mask.min.js"></script>
        <script src="{{ env('APP_URL') }}ampleadmin/assets/libs/sweetalert2/dist/sweetalert2.all.min.js"></script>
        <script src="{{ env('APP_URL') }}ampleadmin/assets/libs/sweetalert2/sweet-alert.init.js"></script>
        <script>
            $('[data-toggle="tooltip"]').tooltip();
            $(".preloader").fadeOut();
            $('#to-recover').on("click", function() {
                $("#loginform").slideUp();
                $("#recoverform").fadeIn();
            });

            $(document).ready(function() {
                $('#login-cpf').on('click', function() {
                    $('#login').addClass('mascaracpf');
                    $('#login').mask('000.000.000-00', {placeholder: "___.___.___-__"});
                });

                $('#login-livre').on('click', function() {
                    $('#login').removeClass('mascaracpf');
                    $('#login').unmask();
                    $('#login')[0].placeholder = 'Login';
                    $('#login').removeAttr('maxlength');
                });

                axios({
                    method : 'get',
                    url    : `/empresaLogin/empresas/getNomeFirstEmpresa`,
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'Access-Control-Allow-Origin': '*',
                        'Access-Control-Allow-Credentials': true
                    }
                }).then(response => {
                    if(!response.data) return;

                    sessionStorage.setItem('@NAJ_CLIENTE/nomeEmpresa', response.data);
                    $('#nomeEmpresaLicenciada')[0].innerHTML = `${response.data}`;

                    if($('#nomeEmpresa')[0]) {
                        $('#nomeEmpresa')[0].innerHTML = `${response.data}`;
                    }
                }).catch(error => {
                    NajAlert.toastError('Não foi possível buscar o nome do escritório!');
                });
            });

            $('.mascaracpf').mask('000.000.000-00', {placeholder: "___.___.___-__"});

            function onClickModalEsqueceuLogin() {
                Swal.fire({
                    title: "Esqueceu seus dados de acesso?",
                    text: 'Procure o administrador e solicite que gere uma NOVA SENHA provisória, em seguida, você poderá fazer LOGIN e TROCAR sua senha!',
                    type: "warning",
                });
            }

        </script>
    </body>
</html>