<?php

namespace App\Http\Controllers\AreaCliente;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\NajController;
use App\User;

class HomeController extends NajController {
    
    public function index() {
        return view('areaCliente.home');
    }

    public function indexUpdateSenha() {
        return view('areaCliente.updateSenha');
    }
    
}