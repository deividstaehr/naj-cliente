<?php

namespace App\Http\Controllers\AreaCliente;

use App\Models\AtividadeModel;
use App\Http\Controllers\NajController;
use App\Http\Controllers\AreaCliente\AtividadeMonitoramentoController;

/**
 * Controller de processos.
 *
 * @package    Controllers
 * @subpackage NajWeb
 * @author     Roberto Klann
 * @since      20/07/2020
 */
class AtividadeController extends NajController {

    public function onLoad() {
        $this->setModel(new AtividadeModel);
        $this->setMonitoramentoController(new AtividadeMonitoramentoController);
    }

    public function index() {
        return view('areaCliente.consulta.AtividadeConsultaView');
    }

    public function totalHoras($parameters) {
        $parametros   = json_decode(base64_decode($parameters));

        return response()->json(['total_horas' => $this->getModel()->getTotalHoras($parametros->data_inicial, $parametros->data_final)]);
    }

    public function getQtdeUltimas30DiasAndTodas($parameters) {
        $parametros   = json_decode(base64_decode($parameters));

        return response()->json($this->getModel()->getQtdeUltimas30DiasAndTodas($parametros));
    }
    
}