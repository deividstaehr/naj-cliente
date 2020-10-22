<?php

namespace App\Http\Middleware;

use Closure;

/**
 * Middleware de validação da senha do usuário.
 *
 * @package    Middleware
 * @author     Roberto Oswaldo Klann
 * @since      17/01/2020
 */
class CheckPasswordProvisoria {

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $senha_provisoria = auth()->user()->senha_provisoria;

        $rota = $request->route()->getName();

        if($rota == 'password.update' || $rota == 'usuario.update-password' || $rota == 'usuario.update-senha-provisoria' || $rota == 'empresa.identificador-empresa' || $rota == 'usuario.atualizar-dados') {
            return $next($request);
        }

        if($senha_provisoria == 'S' && $rota != 'password.update') {
            return redirect('/ac/password/update');
        }

        return $next($request);
    }

}