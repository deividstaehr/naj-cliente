<?php

namespace App\Http\Controllers\AreaCliente;

use App\Models\FinanceiroModel;
use App\Http\Controllers\NajController;
use App\Http\Controllers\AreaCliente\FinanceiroMonitoramentoController;

/**
 * Controlador do Financeiro.
 *
 * @since 2020-08-10
 */
class FinanceiroController extends NajController {

    public function onLoad() {
        $this->setModel(new FinanceiroModel);
        $this->setMonitoramentoController(new FinanceiroMonitoramentoController);
    }

    public function indexFinanceiro($parametro) {
        return view('areaCliente.consulta.FinanceiroConsultaView')->with('tab_selected', ['tab' => $parametro]);
    }

    public function getTotalPagarTotalReceber() {
        return response()->json($this->getModel()->getTotalPagarTotalReceber());
    }

    public function getTotalRecebidoReceberAtrasado($parameters) {
        $parametros   = json_decode(base64_decode($parameters));

        return response()->json($this->getModel()->getTotalRecebidoReceberAtrasado($parametros));
    }

}