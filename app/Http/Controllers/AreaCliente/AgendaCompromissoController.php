<?php

namespace App\Http\Controllers\AreaCliente;

use App\Http\Controllers\NajController;
use App\Models\AgendaCompromissoModel;

/**
 * Controller da agenda do cliente.
 *
 * @package    Controllers
 * @subpackage AreaCliente
 * @author     Roberto Oswaldo Klann
 * @since      02/08/2021
 */
class AgendaCompromissoController extends NajController {

    public function onLoad() {
        $this->setModel(new AgendaCompromissoModel);
    }

    public function index() {
        return view('areaCliente.consulta.AgendaConsultaView');
    }

    public function all() {
        return response()->json(['data' => $this->getModel()->allEvents()]);
    }

}