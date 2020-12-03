<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link href="{{ env('APP_URL') }}ampleAdmin/dist/css/style.min.css" rel="stylesheet">
        <link href="{{ env('APP_URL') }}ampleAdmin/assets/libs/jquery-steps/jquery.steps.css" rel="stylesheet">
        <link href="{{ env('APP_URL') }}ampleAdmin/assets/libs/jquery-steps/steps.css" rel="stylesheet">
        <link href="{{ env('APP_URL') }}imagens/logo-naj-2020_N - Cópia.png" rel="shortcut icon" type="image/vnd.microsoft.icon" />        
        <link rel="stylesheet" href="{{ env('APP_URL') }}naj-datatables/styles/alert.css">
        <link rel="stylesheet" href="{{ env('APP_URL') }}naj-datatables/styles/loading.css">
        <link rel="stylesheet" href="{{ env('APP_URL') }}naj-datatables/styles/scrollbar.css">
        <link href="{{ env('APP_URL') }}css/app.css" rel="stylesheet">

        <script src="{{ env('APP_URL') }}naj-datatables/src/sweetalert2.min.js"></script>
        <script src="{{ env('APP_URL') }}naj-datatables/src/alerts.js"></script>
        <title>Cadastro de usuário</title>
    </head>
    <body>
        <div class="main-wrapper">
            <div class="preloader">
                <div class="lds-ripple">
                    <div class="lds-pos"></div>
                    <div class="lds-pos"></div>
                </div>
            </div>

            <div id="bloqueio-cadastro-usuario" class="loader loader-default" data-half></div>
            <div id="main-atualizar-dados" class="auth-wrapper d-flex no-block justify-content-center align-items-center" style="background:url({{ env('APP_URL') }}ampleAdmin/assets/images/big/auth-bg.jpg) no-repeat center center;">
                <div class="card-atualizar-dados shadow-lg">
                    <div class="p-0 wizard-content" style="height: 100%;">
                        <div class="tab-wizard wizard-circle naj-scrollable" style="height: 100%;">
                            <!-- Step 1 -->
                            <h6>Informe seus dados</h6>
                            <section>
                                <div class="col-12">
                                    <div class="row pl-4">
                                        <div class="col-12">
                                            <form class="form-horizontal needs-validation" id="form-usuario-perfil" novalidate="">
                                                <input class="d-none" type="text" name="id">
                                                <input class="d-none" type="text" name="usuario_tipo_id" id="usuario_tipo_id">
                                                <input class="d-none" type="text" name="status" id="status">
                                                <input class="d-none" type="text" name="data_inclusao" id="data_inclusao">

                                                <div class="form-group row">
                                                    <label for="cpf" class="col-sm-2 control-label label-center pr-2">CPF</label>
                                                    <div class="col-sm-3 input-alterar-dados">
                                                        <div class="input-group">
                                                            <input type="text" id="cpf" name="cpf" class="form-control mascaracpf" onkeypress="return onlynumber();" onchange="onChangeCpf();" required="">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="nome" class="col-sm-2 control-label label-center pr-2">Nome</label>
                                                    <div class="col-sm-9 input-alterar-dados">
                                                        <div class="input-group">
                                                            <input type="text" name="nome" class="form-control" required="" onchange="onChangeNome();">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="apelido" class="col-sm-2 control-label label-center pr-2">Apelido</label>
                                                    <div class="col-sm-9 input-alterar-dados">
                                                        <div class="input-group">
                                                            <input type="text" name="apelido" class="form-control"  required="">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="login" class="col-sm-2 control-label label-center pr-2">Login</label>
                                                    <div class="col-sm-9 input-alterar-dados">
                                                        <div class="input-group">
                                                            <input type="text" name="login" id="login" class="form-control mascaracpf"  required="">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="email_recuperacao" class="col-sm-2 control-label label-center pr-2">E-mail</label>
                                                    <div class="col-sm-9 input-alterar-dados">
                                                        <div class="input-group">
                                                            <input type="email" name="email_recuperacao" class="form-control" placeholder="example@gmail.com" required="">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group row">
                                                    <label for="mobile_recuperacao" class="col-sm-2 control-label label-center pr-2">Número Móvel</label>
                                                    <div class="col-sm-3 input-alterar-dados">
                                                        <div class="input-group">
                                                            <input type="text" name="mobile_recuperacao" class="form-control mascaracelular" maxlength="16" onkeypress="return onlynumber();" required="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <!-- Step 2 -->
                            <h6>Informe uma senha</h6>
                            <section>
                                <div class="row mt-4" id="content-update-senha-atualizacao-dados">
                                    <form class="form-horizontal mt-3" id="loginform" style="width: 65%; margin-top: -4% !important;">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="ti-pencil"></i></span>
                                            </div>
                                            <input type="password" id="password_nova" name="password_nova" class="form-control form-control-lg" placeholder="Digite a sua senha" aria-describedby="basic-addon1">
                                        </div>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon2"><i class="ti-pencil"></i></span>
                                            </div>
                                            <input type="password" id="confirmar_senha" name="confirmar_senha" class="form-control form-control-lg" placeholder="Confirme a sua senha" aria-describedby="basic-addon1">
                                        </div>

                                        <div id="content-avisos-atualizar-dados">
                                            <h5 style="font-weight: 500 !important;">Dicas para criar uma senha mais segura:</h5>
                                            <p class="m-0">* Combine letras maiúsculas e minúsculas, símbolos e números.</p>
                                            <p class="m-0">* Não use informações pessoais como data de nascimento ou seu nome.</p>
                                        </div>

                                        <input type="hidden" name="_method" value="POST">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    </form>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="{{ env('APP_URL') }}js/jquery.js"></script>
        <script src="{{ env('APP_URL') }}js/axios.js"></script>
        <script src="{{ env('APP_URL') }}js/NajFunctions.js"></script>
        <script src="{{ env('APP_URL') }}naj-datatables/src/functions.js"></script>
        <script src="{{ env('APP_URL') }}ampleAdmin/assets/libs/jquery/dist/jquery.min.js"></script>
        <script src="{{ env('APP_URL') }}ampleAdmin/assets/libs/popper.js/dist/umd/popper.min.js"></script>
        <script src="{{ env('APP_URL') }}ampleAdmin/assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="{{ env('APP_URL') }}ampleAdmin/assets/libs/jquery-steps/build/jquery.steps.js"></script>
        <script src="{{ env('APP_URL') }}ampleAdmin/assets/libs/jquery-validation/dist/jquery.validate.min.js"></script>
        <script src="{{ env('APP_URL') }}/js/input-mask/jquery.inputmask.js"></script>
        <script src="{{ env('APP_URL') }}js/input-mask/jquery.inputmask.date.extensions.js"></script>
        <script src="{{ env('APP_URL') }}js/input-mask/jquery.inputmask.extensions.js"></script>
        <script src="{{ env('APP_URL') }}js/jQuery-Mask-Plugin/jquery.mask.min.js"></script>
        <script>
            const baseURL = "{{ env('APP_URL') }}";

            $(window).on('load', () => {
                identificador = sessionStorage.getItem('@NAJ_CLIENTE/identificadorEmpresa');
                if(!identificador) {
                    //Busca o identificador
                    axios({
                        method: 'get',
                        url: `${baseURL}usuario/empresas/identificador`
                    }).then(response => {
                        if(!response.data) return;

                        sessionStorage.setItem('@NAJ_CLIENTE/identificadorEmpresa', response.data);
                    });
                }
            });

            $('[data-toggle="tooltip"]').tooltip();
            $(".preloader").fadeOut();
            $('#to-recover').on("click", function() {
                $("#loginform").slideUp();
                $("#recoverform").fadeIn();
            });

            //Configurando os steps do novo atendimento
            $(".tab-wizard").steps({
                headerTag: "h6",
                bodyTag: "section",
                transitionEffect: "fade",
                titleTemplate: '<span class="step">#index#</span> #title#',
                labels: {
                    previous: "Anterior",
                    next: "Próximo",
                    finish: "Confirmar"
                },
                onFinished: async function(event, currentIndex) {
                    loadingStart('bloqueio-cadastro-usuario');

                    let empresa = sessionStorage.getItem('@NAJ_CLIENTE/identificadorEmpresa');
                    let dados = {
                        'password'           : $('[name=password_nova]').val(),
                        'najWeb'             : 1,
                        'codigo_pessoa'      : empresa,
                        'pessoa_codigo'      : empresa,
                        'senha_provisoria'   : 'N',
                        'id'                 : $('[name=id]').val(),
                        'usuario_tipo_id'    : 3,
                        'nome'               : $('[name=nome]').val(),
                        'cpf'                : $('[name=cpf]').val(),
                        'apelido'            : $('[name=apelido]').val(),
                        'login'              : $('[name=login]').val(),
                        'email_recuperacao'  : $('[name=email_recuperacao]').val(),
                        'mobile_recuperacao' : $('[name=mobile_recuperacao]').val().replace(/\D+/g, ''),
                        'status'             : 'A',
                        'data_inclusao'      : getDataAtual(),
                        'senha_provisoria'   : 'N',
                        'auto_cadastro_naj_adv_web': true,
                        'items' : [
                            {
                                "pessoa_codigo": empresa,
                                "usuario_id"   : 0
                            }
                        ]
                    };

                    if(!dados.nome || !dados.cpf || !dados.apelido || !dados.login || !dados.email_recuperacao || !dados.mobile_recuperacao) {
                        NajAlert.toastWarning("Você deve preencher todos os campos para poder confirmar!");
                        loadingDestroy('bloqueio-cadastro-usuario');
                        return;
                    }

                    //Validando o formulário
                    if(!$('#confirmar_senha').val() || !dados.password)  {
                        NajAlert.toastWarning("É necessário informar as duas senhas para realizar o cadastro!");
                        loadingDestroy('bloqueio-cadastro-usuario');
                        return;
                    }

                    //Validando se as senhas são diferentes
                    if($('#confirmar_senha').val() != dados.password)  {
                        NajAlert.toastWarning("Confirme a senha corretamente!");
                        loadingDestroy('bloqueio-cadastro-usuario');
                        return;
                    }

                    let r = /^(?=.*\d)(?=.*[a-z])(?:([0-9a-z$*&@#])){4,}$/;

                    if(!r.test(dados.password)) {
                        NajAlert.toastWarning("A senha deve conter no minimo 4 digitos, sendo números e letras!");
                        loadingDestroy('bloqueio-cadastro-usuario');
                        return;
                    }

                    let v = /^(?=.*\d)(?=.*[a-z])(?:([0-9a-z$*&@#])(?!\1)){4,}$/;

                    if(!v.test(dados.password)) {
                        NajAlert.toastWarning("A senha não pode conter números ou letras repetidas!");
                        loadingDestroy('bloqueio-cadastro-usuario');
                        return;
                    }

                    if(!validaCampoLogin()) {
                        loadingDestroy('bloqueio-cadastro-usuario');
                        NajAlert.toastWarning('O campo login deve ser igual ao campo CPF!');
                        return;
                    }

                    if(!validaCampoEmail()) {
                        loadingDestroy('bloqueio-cadastro-usuario');
                        NajAlert.toastWarning('O campo E-mail deve ser um email válido!');
                        return;
                    }

                    axios({
                        method: 'post',
                        url   : `${baseURL}usuario/login/store?XDEBUG_SESSION_START`,
                        data  : dados
                    })
                    .then(response => {
                        if(!response.data.original.model && response.data.original.mensagem) {
                            loadingDestroy('bloqueio-cadastro-usuario');
                            NajAlert.toastWarning(response.data.original.mensagem);
                            return;
                        }

                        //Faz a autenticação local, para entrar no sistema
                        axios({
                            method  : 'post',
                            url     : `${baseURL}auth/login`,
                            data    : {'login' : $('[name=login]').val(), 'password': $('[name=password_nova]').val(), 'status': 'A'}
                        })
                        .then(response => {
                            sessionStorage.clear();
                            window.location.href = `${baseURL}ac/home`;
                        }).catch(error => {
                            NajAlert.toastError('A senha informada não confere com a senha desse mesmo usuário no CPANEL.');
                        });
                        
                    }).catch(e => {
                        NajAlert.toast("Erro ao fazer o cadastro, tente novamente mais tarde!");
                    });
                    
                    loadingDestroy('bloqueio-cadastro-usuario');
                }
            });

            $('.actions .disabled').addClass('d-none')
            $('.actions .disabled').addClass('first-step');
            $('.actions').addClass('footer-steps-naj');
            $('.actions ul').addClass('ul-footer-steps-naj');
            $('.content')[0].style.height = '62%';

            $('.mascaracelular').mask("(000) 0 0000-0000", {placeholder: "(000) 0 0000-0000"});
            $('.mascaracpf').mask('000.000.000-00', {placeholder: "___.___.___-__"});
        </script>
        <script src="{{ env('APP_URL') }}js/cadastroUsuarioLogin.js"></script>
    </body>
</html>