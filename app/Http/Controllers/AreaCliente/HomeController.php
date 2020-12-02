<?php

namespace App\Http\Controllers\AreaCliente;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\NajController;
use App\Http\Controllers\AreaCliente\UsuarioController;
use App\User;

class HomeController extends NajController {
    
    public function index() {
        return view('areaCliente.home');
    }

    public function indexUpdateSenha() {
        return view('areaCliente.updateSenha');
    }

    public function indexStoreUsuario() {
        return view('areaCliente.cadastroUsuarioLogin');
    }

    public function storeUsuario() {
        $UsuarioController = new UsuarioController();
        return response()->json($UsuarioController->store());
    }
    
}