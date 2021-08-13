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
        $filters = json_decode(base64_decode(request()->get('filters')));

        return response()->json(['data' => $this->getModel()->allEvents(), 'total_events' => $this->getModel()->amountEventsByUser($filters->user_id)]);
    }

    public function showEvent($filter) {
        $filter = json_decode(base64_decode($filter));

        return response()->json(['data' => $this->getModel()->showEvent($filter)]);
    }

    public function amountEventsByUser($filter) {
        $filter = json_decode(base64_decode($filter));

        return response()->json(['data' => $this->getModel()->amountEventsByUser($filter[0]->val)]);
    }

}