<?php

/**
 * Rotas da Aplicação.
 *
 * @package routes
 * @author  Roberto Klann
 * @since   09/12/2019
 */

Route::get('/', function() {
    return redirect('auth/login');
});

/*
 | Install
 |
 */
Route::group([
    'namespace' => 'AreaCliente',
    'prefix'    => 'empresaLogin'
], function($router) {
    //EMPRESA
    Route::get('empresas/getNomeFirstEmpresa', 'EmpresaController@getNomeFirstEmpresa')->name('empresa.first-empresa');
});

/*
 | Cadastro Usuário Login
 |
 */
Route::group([
    'namespace' => 'AreaCliente',
    'prefix'    => 'usuario'
], function($router) {
    //CADASTRO USUARIO NO LOGIN
    Route::get('login/store' , 'HomeController@indexStoreUsuario')->name('login.usuario.store.index');
    Route::post('login/store', 'HomeController@storeUsuario')->name('login.usuario.store');

    Route::get('empresas/identificador'      , 'EmpresaController@getIdentificadorEmpresa')->name('empresa.identificador-empresa');
});

/*
 | Auth
 |
 */
Route::group([
    'namespace' => 'Auth',
    'prefix'    => 'auth'
], function($router) {
    Route::get('login' , 'LoginController@index')->name('auth.index');
    Route::post('login', 'LoginController@login')->name('auth.login');
    Route::get('logout', 'LoginController@logout')->name('auth.logout');
});

/*
 | Dashboard
 |
 */
Route::group([
    'namespace'  => 'AreaCliente',
    'prefix'     => 'ac',
    'middleware' => 'auth:web'
], function($router) {
    Route::get('{route}', 'HomeController@index')->where('route', 'index|home');

    //ATUALIZAR SENHA USUARIO
    Route::get('password/update', 'HomeController@indexUpdateSenha')->name('password.update');

    //EMPRESA
    Route::get('empresas/identificador'      , 'EmpresaController@getIdentificadorEmpresa')->name('empresa.identificador-empresa');
    Route::get('empresas/getNomeFirstEmpresa', 'EmpresaController@getNomeFirstEmpresa')->name('empresa.first-empresa');
    Route::get('empresas/getLogoEmpresa'     , 'EmpresaController@getLogoEmpresa')->name('empresa.logo');
    Route::post('empresas/logo'              , 'EmpresaController@storeLogo')->name('empresa.store.logo');
    
    //Mensagem
    Route::get('mensagens'             , 'MensagemController@index')->name('mensagens.index');
    Route::get('mensagens/hasChat/{id}', 'MensagemController@hasChat')->name('mensagens.has-chat');
    Route::get('mensagens/hasMensagemFromChat/{id}', 'MensagemController@hasMensagemFromChat')->name('mensagens.has-chat-mensagem');
    Route::get('mensagens/indicador'   , 'MensagemController@getNewMessagesAndTodas')->name('mensagens.indicador');

    //CHAT - MENSAGEM
    Route::get('chat/mensagens'            , 'AtendimentoController@allMessages')->name('atendimento.all-messages');
    Route::get('chat/mensagem/publico/{id}', 'ChatMensagemController@getAllMensagensChatPublico')->name('atendimento.all-messages-chat-publico');
    Route::post('chat/mensagem'            , 'ChatMensagemController@store')->name('atendimento.chat.mensagem.store');

    //CHAT - ANEXO
    Route::post('chat/mensagem/anexo'               , 'AnexoChatStorageController@uploadAnexoChat')->name('anexo-chat.upload');
    Route::post('chat/mensagem/shareAnexo'          , 'AnexoChatStorageController@shareAnexoChat')->name('anexo-chat.share');
    Route::get('chat/mensagem/download/{parameters}', 'AnexoChatStorageController@downloadAnexoChat')->name('anexo-chat.download');

    //CHAT - ATENDIMENTO
    Route::post('chat/atendimento'     , 'ChatAtendimentoController@store')->name('chat.atendimento.store');
    Route::post('chat/novo/atendimento', 'ChatAtendimentoController@novoAtendimento')->name('chat.novo-atendimento.store');
    Route::put('chat/atendimento/{id}' , 'ChatAtendimentoController@update')->name('chat.atendimento.update');

    //PROCESSOS
    Route::get('processos'                   , 'ProcessoController@index')->name('processos.index');
    Route::get('processos/create'            , 'ProcessoController@create')->name('processo.create');
    Route::get('processos/paginate'          , 'ProcessoController@paginate')->name('processos.paginate');
    Route::get('processos/proximo'           , 'ProcessoController@proximo')->name('processos.proximo');
    Route::get('processos/show/{key}'        , 'ProcessoController@show')->name('processos.show');
    Route::post('processos'                  , 'ProcessoController@store')->name('processos.store');
    Route::put('processos'                   , 'ProcessoController@update')->name('processos.update');
    Route::get('processos/anexos/{key}'      , 'ProcessoController@anexos')->name('processos.anexos');
    Route::get('processos/partes/{key}'      , 'ProcessoController@getPartes')->name('processos.partes');
    Route::get('processos/partes/cliente/{key}'   , 'ProcessoController@getParteCliente')->name('processos.partes.clinte');
    Route::get('processos/partes/adversaria/{key}', 'ProcessoController@getParteAdversaria')->name('processos.partes.adversaria');
    Route::get('processos/prcqualificacao'   , 'ProcessoController@getPrcQualificacao')->name('processos.prcqualificacao');
    Route::get('processos/prcorgao'          , 'ProcessoController@getPrcOrgao')->name('processos.prcorgao');
    Route::get('processos/prcsituacao'       , 'ProcessoController@getPrcSituacao')->name('processos.prcsituacao');
    Route::get('processos/indicador/{parametro}', 'ProcessoController@getQtdeAtivoBaixado')->name('processos.indicador');
    Route::get('processos/observacao/{codigo}', 'ProcessoController@getObservation')->name('processos.observation');

    //PROCESSOS ANDAMENTO
    Route::get('processos/andamento/paginate', 'AndamentoProcessoController@paginate')->name('processos.andamento.paginate');

    //PROCESSOS ANEXOS
    Route::get('anexos/processos/paginate'             , 'ProcessoAnexoController@paginate')->name('processos.anexo.paginate');
    Route::get('anexos/processos/download/{parameters}', 'AnexoChatStorageController@downloadAnexoProcesso')->name('processos.download.anexo');

    //ATIVIDADES
    Route::get('atividades'                        , 'AtividadeController@index')->name('atividades.index');
    Route::get('atividades/paginate'               , 'AtividadeController@paginate')->name('atividades.paginate');
    Route::get('atividades/totalHoras/{parameters}', 'AtividadeController@totalHoras')->name('atividades.total-horas');
    Route::get('atividades/indicador/{parameters}' , 'AtividadeController@getQtdeUltimas30DiasAndTodas')->name('atividades.indicador');

    //ATIVIDADES ANEXO
    Route::get('atividades/anexos/paginate'     , 'AtividadeAnexoController@paginate')->name('atividades.anexo.paginate');
    Route::get('atividade/download/{parameters}', 'AnexoChatStorageController@downloadAnexoAtividade')->name('atividades.download.anexo');

    //ATIVIDADE PROCESSO
    Route::get('atividades/processo/paginate', 'AtividadeProcessoController@paginate')->name('atividades.processo.paginate');

    //Agenda
    Route::get('agendaCompromissos', 'AgendaCompromissoController@index')->name('agendaCompromissos.index');

    //DOCUMENTOS
    Route::get('documentos/show/{key}', 'DocumentosChatController@documentos')->name('documentos-show');

    //USUÁRIO
    Route::get('usuarios/perfil'             , 'UsuarioController@perfil')->name('usuario.perfil');
    Route::get('usuarios/show/{id}'          , 'UsuarioController@show')->name('usuario.show');
    Route::get('usuarios/cpf/{cpf}'          , 'UsuarioController@getUserByCpfInCpanel')->name('usuario.show');
    Route::put('usuarios/{id}'               , 'UsuarioController@update')->name('usuario.update');    
    Route::put('usuarios/password/{id}'      , 'UsuarioController@updatePassword')->name('usuario.update-password');
    Route::put('usuarios/atualizarDados/{id}', 'UsuarioController@atualizarDados')->name('usuario.atualizar-dados');
    Route::put('usuarios/acesso/{id}'        , 'UsuarioController@updateUltimoAcessoSistema')->name('usuario.update-ultimo-acesso');

    //FINANCEIRO
    Route::get('financeiro/index/{parametros}'     , 'FinanceiroController@indexFinanceiro')->name('financeiro.index');    
    Route::get('financeiro/indicador'        , 'FinanceiroController@getTotalPagarTotalReceber')->name('financeiro.indicador');
    Route::get('financeiro/receber/paginate' , 'FinanceiroController@paginate')->name('financeiro.receber.paginate');
    Route::get('financeiro/pagar/paginate'   , 'FinanceiroPagarController@paginate')->name('financeiro.pagar.paginate');
    Route::get('financeiro/receber/indicador/{parametros}', 'FinanceiroController@getTotalRecebidoReceberAtrasado')->name('financeiro.receber.indicador');
    Route::get('financeiro/pagar/indicador/{parametros}'  , 'FinanceiroPagarController@getTotalPagoPagarAtrasado')->name('financeiro.pagar.indicador');

    //PESQUISA NPS    
    Route::get('pesquisa/nps/pendentes', 'PesquisaNpsUsuarioController@searchsNotReadByUser')->name('pesquisa-nps-pendentes.searchsNotReadByUser');
    Route::post('pesquisa/nps/resposta', 'PesquisaNpsUsuarioController@saveAnswer')->name('pesquisa-nps-resposta.saveAnswer');
    Route::post('pesquisa/nps/naoresponder', 'PesquisaNpsUsuarioController@saveNotAnswer')->name('pesquisa-nps-resposta.saveNotAnswer');
});