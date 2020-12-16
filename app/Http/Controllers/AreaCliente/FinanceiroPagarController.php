<?php

namespace App\Http\Controllers\AreaCliente;

use App\Models\FinanceiroPagarModel;
use App\Http\Controllers\NajController;
use App\Http\Controllers\AreaCliente\FinanceiroPagarMonitoramentoController;

/**
 * Controlador do Financeiro. OBS: ISSO AQUI É GAMBIARRA
 *
 * @since 2020-08-10
 */
class FinanceiroPagarController extends NajController {

    public function onLoad() {
        $this->setModel(new FinanceiroPagarModel);
        $this->setMonitoramentoController(new FinanceiroPagarMonitoramentoController);
    }

    public function index() {
        return view('areaCliente.consulta.FinanceiroConsultaView');
    }

    public function getTotalPagoPagarAtrasado($parameters) {
        $parametros   = json_decode(base64_decode($parameters));

        return response()->json($this->getModel()->getTotalPagoPagarAtrasado($parametros));
    }

}