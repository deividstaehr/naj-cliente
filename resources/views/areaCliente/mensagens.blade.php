@extends('areaCliente.viewBase')

@section('title', 'NAJWebAC | Mensagem')

@section('css')
    <link href="/css/acessoUsuario.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/ampleAdmin/assets/libs/select2/dist/css/select2.min.css">
@endsection

@section('active-layer', 'mensagens')
@section('content')
    <div id="loading" class="loader loader-default" is-active data-half></div>
    <div class="page-content container-fluid p-4" style="min-height: calc(100vh - 111px); !important">
        <div class="card mb-0" style="box-shadow: 0 1px 4px 0 rgba(0, 0, 0, .1) !important;">
            <ul class="nav nav-tabs manage-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#recuperacao" role="tab">
                        <span class="hidden-sm-up">
                            <h4><i class="ti-lock"></i></h4>
                        </span>
                        <span class="d-none d-md-block">Código de Acesso</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#cadastro" role="tab">
                        <span class="hidden-sm-up">
                            <h4><i class="icon-notebook"></i></h4>
                        </span>
                        <span class="d-none d-md-block">Dados cadastrais</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#dispositivos" role="tab">
                        <span class="hidden-sm-up">
                            <h4><i class="ti-receipt"></i></h4>
                        </span>
                        <span class="d-none d-md-block">Dispositivos</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#finalizar" role="tab">
                        <span class="hidden-sm-up">
                            <h4><i class="ti-check-box"></i></h4>
                        </span>
                        <span class="d-none d-md-block">Relacionamento</span>
                    </a>
                </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content ">
                <div class="tab-pane active" id="recuperacao" role="tabpanel">
                    <div class="bg-light">
                        <div class="user-box-wrapper p-4 d-flex no-block justify-content-center align-items-center">
                            <div class="user-box">
                                <div id="loginform">
                                    <div class="logo">
                                        <h5 class="font-medium mb-3">Informe o código de acesso do usuário</h5>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <form class="form-horizontal mt-3" id="formCodigoAcesso" method="post">
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1"><i id="iconCodigoAcesso" class="fas fa-check"></i></span>
                                                    </div>
                                                    <input type="text" name="codigo_acesso" id="codigo_acesso" class="form-control form-control-lg" aria-describedby="basic-addon1">
                                                </div>
                                                <div class="form-group text-center">
                                                    <div class="col-xs-12 pb-3">
                                                        <button class="btn btn-block btn-lg btn-info" type="submit">Validar</button>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="_method" value="POST">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            </form>
                                        </div>
                                        <div class="col-12" id="divResultadoUsuario">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center p-2">
                            <div class="ml-auto">
                                <button class="btn btn-info text-white btn-rounded py-2 px-3" id="proximoAcesso" disabled onclick="onClickAvancar('#recuperacao', '#cadastro');">Próximo <i class="ti-arrow-right ml-2"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="cadastro" role="tabpanel">
                    <div class="bg-light">
                        <form class="form-horizontal tab-content-custom" id="formCadastroPessoa">
                            <div class="row ">
                                <div class="col-sm-12 col-md-6 col-lg-6 p-4" style="padding-right: 0px !important; padding-bottom: 0px !important">
                                    <div class="form-group row">
                                        <label for="codigo" class="col-sm-3 control-label label-center">Código</label>
                                        <div class="col-sm-4">
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="codigo" name="codigo">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="nome" class="col-sm-3 control-label label-center">Nome</label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="nome" name="nome" placeholder="Informe seu nome">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="tipo" class="col-sm-3 control-label label-center">Tipo</label>
                                        <div class="col-sm-4">
                                            <div class="input-group">
                                                <select class="form-control" id="tipopessoa" name="tipo" onchange="onChangeTipoPessoa();">
                                                    <option value="F" selected="selected">Física</option>
                                                    <option value="J">Jurídica</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row" id="divcpf">
                                        <label for="cpf" class="col-sm-3 control-label label-center">CPF</label>
                                        <div class="col-sm-4">
                                            <div class="input-group">
                                                <input type="text" class="form-control mascaracpf" name="cpf" id="cpf">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row" style="display: none" id="divcnpj">
                                        <label for="cnpj" class="col-sm-3 control-label label-center">CNPJ</label>
                                        <div class="col-sm-4">
                                            <div class="input-group">
                                                <input type="text" class="form-control mascaracnpj" name="cnpj" id="cnpj">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="status" class="col-sm-3 control-label label-center">Status</label>
                                        <div class="col-sm-4">
                                            <div class="input-group">
                                                <select class="form-control" name="status" id="status" onchange="onChangeStatus();">
                                                    <option value="A">Ativo</option>
                                                    <option value="B">Baixado</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="data_inclusao" class="col-sm-3 control-label label-center">Data de Inclusão</label>
                                        <div class="col-sm-4">
                                            <div class="input-group">
                                                <input type="date" class="form-control" id="data_inclusao" name="data_inclusao">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row" id="div-databaixa" style="display: none;">
                                        <label for="email4" class="col-sm-3 control-label label-center">Data de Baixa</label>
                                        <div class="col-sm-4">
                                            <div class="input-group">
                                                <input type="date" class="form-control" id="databaixa" name="databaixa">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="endereco_tipo" class="col-sm-3 control-label label-center">Tipo de Endereço</label>
                                        <div class="col-sm-5">
                                            <div class="input-group">
                                                <select name="endereco_tipo" id="tipoEnderecoAlteracao" class="form-control select2">
                                                    <option value="Rua">Rua</option>
                                                    <option value="Avenida">Avenida</option>
                                                    <option value="Passarela">Passarela</option>
                                                    <option value="Travessa">Travessa</option>
                                                    <option value="Localidade">Localidade</option>
                                                    <option value="Rotatória">Rotatória</option>
                                                    <option value="Condominio Residencial">Condomínio Residencial</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="endereco" class="col-sm-3 control-label label-center">Endereço</label>
                                        <div class="col-sm-6">
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="endereco" name="endereco" placeholder="Informe seu endereço">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6 p-4" style="padding-left: 0px !important; padding-bottom: 0px !important">
                                    <div class="form-group row">
                                        <label for="numero" class="col-sm-3 control-label label-center">Número</label>
                                        <div class="col-sm-4">
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="numero" name="numero" onpaste="return onlynumber();" onkeypress="return onlynumber();">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="complemento" class="col-sm-3 control-label label-center">Complemento</label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="complemento" name="complemento" placeholder="Informe um complemento">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="bairro" class="col-sm-3 control-label label-center">Bairro</label>
                                        <div class="col-sm-4">
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="bairro" name="bairro" placeholder="Informe seu bairro">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="cep" class="col-sm-3 control-label label-center">CEP</label>
                                        <div class="col-sm-4">
                                            <div class="input-group">
                                                <input type="text" class="form-control mascaracep" id="cep" name="cep">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="cidade" class="col-sm-3 control-label label-center">Cidade</label>
                                        <div class="col-sm-6">
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="cidade" name="cidade" placeholder="Informe sua cidade">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="uf" class="col-sm-3 control-label label-center">Estado</label>
                                        <div class="col-sm-6">
                                            <div class="input-group">
                                                <select name="uf" id="estado" class="form-control select2">
                                                    <option value="AC">Acre (AC)</option>
                                                    <option value="AL">Alagoas (AL)</option>
                                                    <option value="AP">Amapá (AP)</option>
                                                    <option value="AM">Amazonas (AM)</option>
                                                    <option value="BA">Bahia (BA)</option>
                                                    <option value="CE">Ceará (CE)</option>
                                                    <option value="DF">Distrito Federal (DF)</option>
                                                    <option value="ES">Espírito Santo (ES)</option>
                                                    <option value="GO">Goiás (GO)</option>
                                                    <option value="MA">Maranhão (MA)</option>
                                                    <option value="MT">Mato Grosso (MT)</option>
                                                    <option value="MS">Mato Grosso do Sul (MS)</option>
                                                    <option value="MG">Minas Gerais (MG)</option>
                                                    <option value="PA">Pará (PA)</option>
                                                    <option value="PB">Paraíba (PB)</option>
                                                    <option value="PR">Paraná (PR)</option>
                                                    <option value="PE">Pernambuco (PE)</option>
                                                    <option value="PI">Piauí (PI)</option>
                                                    <option value="RJ">Rio de Janeiro (RJ)</option>
                                                    <option value="RN">Rio Grande do Norte (RN)</option>
                                                    <option value="RS">Rio Grande do Sul (RS)</option>
                                                    <option value="RO">Rondônia (RO)</option>
                                                    <option value="RR">Roraima (RR)</option>
                                                    <option value="SC">Santa Catarina (SC)</option>
                                                    <option value="SP">São Paulo (SP)</option>
                                                    <option value="SE">Sergipe (SE)</option>
                                                    <option value="TO">Tocantins (TO)</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="observacao" class="col-sm-3 control-label label-center">Observação</label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <textarea type="text" class="form-control" id="observacao" name="observacao" placeholder="Informe uma observação"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="_method" value="POST">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            </div>
                        </form>
                        <div class="d-flex align-items-center p-2 content-footer-custom">
                            <p class="mt-2" style="font-weight: 500;">Usuário já cadastrado, atualize os dados se necessário.</p>
                            <div class="ml-auto">                                
                                <button class="btn btn-info text-white btn-rounded py-2 px-3" onclick="onClickAvancar('#cadastro', '#dispositivos');">Próximo <i class="ti-arrow-right ml-2"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="dispositivos" role="tabpanel">
                    <div class="bg-light">
                        <div class="p-3 tab-content-custom">
                            <div class="table-responsive">
                                <table class="table text-muted mb-0 no-wrap recent-table font-light">
                                    <thead>
                                        <tr class="text-uppercase">
                                            <th class="border-0">#</th>
                                            <th class="border-0">Name</th>
                                            <th class="border-0">Status</th>
                                            <th class="border-0">Date</th>
                                            <th class="border-0">Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td class="txt-oflo">Elite admin</td>
                                            <td><span class="badge badge-pill text-uppercase text-white font-medium badge-success label-rouded">SALE</span> </td>
                                            <td class="txt-oflo">April 18, 2017</td>
                                            <td><span class="text-success">$24</span></td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td class="txt-oflo">Real Homes WP Theme</td>
                                            <td><span class="badge badge-pill text-uppercase text-white font-medium badge-info label-rouded">EXTENDED</span></td>
                                            <td class="txt-oflo">April 19, 2017</td>
                                            <td><span class="text-info">$1250</span></td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td class="txt-oflo">Ample Admin</td>
                                            <td><span class="badge badge-pill text-uppercase text-white font-medium badge-info label-rouded">EXTENDED</span></td>
                                            <td class="txt-oflo">April 19, 2017</td>
                                            <td><span class="text-info">$1250</span></td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td class="txt-oflo">Real Homes WP Theme</td>
                                            <td><span class="badge badge-pill text-uppercase text-white font-medium badge-info label-rouded">EXTENDED</span></td>
                                            <td class="txt-oflo">April 19, 2017</td>
                                            <td><span class="text-info">$1250</span></td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td class="txt-oflo">Ample Admin</td>
                                            <td><span class="badge badge-pill text-uppercase text-white font-medium badge-info label-rouded">EXTENDED</span></td>
                                            <td class="txt-oflo">April 19, 2017</td>
                                            <td><span class="text-info">$1250</span></td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td class="txt-oflo">Real Homes WP Theme</td>
                                            <td><span class="badge badge-pill text-uppercase text-white font-medium badge-info label-rouded">EXTENDED</span></td>
                                            <td class="txt-oflo">April 19, 2017</td>
                                            <td><span class="text-info">$1250</span></td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td class="txt-oflo">Ample Admin</td>
                                            <td><span class="badge badge-pill text-uppercase text-white font-medium badge-info label-rouded">EXTENDED</span></td>
                                            <td class="txt-oflo">April 19, 2017</td>
                                            <td><span class="text-info">$1250</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="d-flex align-items-center p-2 content-footer-custom">
                            <div class="ml-auto">                                
                                <button class="btn btn-info text-white btn-rounded py-2 px-3" onclick="onClickAvancar('#dispositivos', '#finalizar');">Próximo <i class="ti-arrow-right ml-2"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="finalizar" role="tabpanel">
                    <div class="bg-light ">
                        <div class="tab-pane-header-custom-fixed">
                            <div class="form-group row p-3">
                                <label for="pessoa" class="col-sm-1 control-label label-center">Pesquisar</label>
                                <div class="col-sm-5">
                                    <div class="input-group">
                                        <select class="select2-data-ajax form-control" name="pessoa" style="width: 540px !important;"></select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="p-3 tab-content-custom-finalizar">
                            <div class="table-responsive">
                                <table class="table text-muted mb-0 no-wrap recent-table font-light">
                                    <thead>
                                        <tr class="text-uppercase">
                                            <th class="border-0">#</th>
                                            <th class="border-0">Name</th>
                                            <th class="border-0">Status</th>
                                            <th class="border-0">Date</th>
                                            <th class="border-0">Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td class="txt-oflo">Elite admin</td>
                                            <td><span class="badge badge-pill text-uppercase text-white font-medium badge-success label-rouded">SALE</span> </td>
                                            <td class="txt-oflo">April 18, 2017</td>
                                            <td><span class="text-success">$24</span></td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td class="txt-oflo">Real Homes WP Theme</td>
                                            <td><span class="badge badge-pill text-uppercase text-white font-medium badge-info label-rouded">EXTENDED</span></td>
                                            <td class="txt-oflo">April 19, 2017</td>
                                            <td><span class="text-info">$1250</span></td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td class="txt-oflo">Ample Admin</td>
                                            <td><span class="badge badge-pill text-uppercase text-white font-medium badge-info label-rouded">EXTENDED</span></td>
                                            <td class="txt-oflo">April 19, 2017</td>
                                            <td><span class="text-info">$1250</span></td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td class="txt-oflo">Real Homes WP Theme</td>
                                            <td><span class="badge badge-pill text-uppercase text-white font-medium badge-info label-rouded">EXTENDED</span></td>
                                            <td class="txt-oflo">April 19, 2017</td>
                                            <td><span class="text-info">$1250</span></td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td class="txt-oflo">Ample Admin</td>
                                            <td><span class="badge badge-pill text-uppercase text-white font-medium badge-info label-rouded">EXTENDED</span></td>
                                            <td class="txt-oflo">April 19, 2017</td>
                                            <td><span class="text-info">$1250</span></td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td class="txt-oflo">Real Homes WP Theme</td>
                                            <td><span class="badge badge-pill text-uppercase text-white font-medium badge-info label-rouded">EXTENDED</span></td>
                                            <td class="txt-oflo">April 19, 2017</td>
                                            <td><span class="text-info">$1250</span></td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td class="txt-oflo">Ample Admin</td>
                                            <td><span class="badge badge-pill text-uppercase text-white font-medium badge-info label-rouded">EXTENDED</span></td>
                                            <td class="txt-oflo">April 19, 2017</td>
                                            <td><span class="text-info">$1250</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="d-flex align-items-center p-2 content-footer-custom">
                            <div class="ml-auto">
                                <button class="btn btn-info text-white btn-rounded py-2 px-3">Finalizar <i class="far fa-paper-plane"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')    
    <script src="/js/acessoUsuario.js"></script>
    <script src="/ampleAdmin/assets/libs/select2/dist/js/select2.full.min.js"></script>
    <script src="/ampleAdmin/assets/libs/select2/dist/js/select2.min.js"></script>
    <script src="/ampleAdmin/dist/js/pages/forms/select2/select2.init.js"></script>
@endsection