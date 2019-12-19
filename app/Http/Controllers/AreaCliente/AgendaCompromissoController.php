<?php

namespace App\Http\Controllers\AreaCliente;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\User;

class AgendaCompromissoController extends Controller {

    public function index() {
        return view('areaCliente/agendaCompromissos');
    }

}