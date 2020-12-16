<?php

namespace App\Http\Controllers\AreaCliente;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\NajController;
use App\Http\Controllers\AreaCliente\UsuarioController;
use App\Http\Controllers\AreaCliente\HomeMonitoramentoController;

class HomeController extends NajController {

    public function onLoad() {
        $this->setMonitoramentoController(new HomeMonitoramentoController);
    }
    
    public function index() {
        $this->getMonitoramentoController()->storeMonitoramento(self::INDEX_ACTION);
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