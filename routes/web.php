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

    //Mensagem
    Route::get('mensagens'         , 'MensagemController@index')->name('mensagens.index');

    //Processo
    Route::get('processos'         , 'ProcessoController@index')->name('processos.index');

    //Agenda
    Route::get('agendaCompromissos', 'AgendaCompromissoController@index')->name('agendaCompromissos.index');
});