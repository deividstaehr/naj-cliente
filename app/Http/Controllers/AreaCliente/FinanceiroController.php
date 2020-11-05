<?php

namespace App\Http\Controllers\AreaCliente;

use App\Http\Controllers\NajController;
use App\Models\FinanceiroModel;

/**
 * Controlador do Financeiro.
 *
 * @since 2020-08-10
 */
class FinanceiroController extends NajController {

    public function onLoad() {
        $this->setModel(new FinanceiroModel);
    }

    public function index() {
        return view('areaCliente.consulta.FinanceiroConsultaView');
    }

    public function getTotalPagarTotalReceber() {
        return response()->json($this->getModel()->getTotalPagarTotalReceber());
    }

    public function getTotalRecebidoReceberAtrasado($parameters) {
        $parametros   = json_decode(base64_decode($parameters));

        return response()->json($this->getModel()->getTotalRecebidoReceberAtrasado($parametros));
    }

}