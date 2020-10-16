<?php

namespace App\Http\Controllers\AreaCliente;

use App\Http\Controllers\NajController;
use App\Models\AtividadeModel;

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
    }

    public function index() {
        return view('areaCliente.consulta.AtividadeConsultaView');
    }

    public function totalHoras() {
        return response()->json(['total_horas' => $this->getModel()->getTotalHoras()]);
    }
    
}