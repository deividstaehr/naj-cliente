<?php

namespace App\Http\Controllers\AreaCliente;

use App\Models\AtendimentoModel;
use App\Http\Controllers\NajController;

/**
 * Controller do atendimento.
 *
 * @package    Controllers
 * @subpackage NajWeb
 * @author     Roberto Oswaldo Klann
 * @since      08/07/2020
 */
class AtendimentoController extends NajController {

    public function onLoad() {
        $this->setModel(new AtendimentoModel);
    }

    protected function resolveWebContext($usuarios, $code) {}

    /**
     * Busca todas as mensagens da advocacia.
     */
    public function allMessages() {
        return response()->json(['data' => $this->getModel()->allMessages()]);
    }

    public function allMessagesChat($id) {
        return response()->json(['data' => $this->getModel()->allMessagesChat($id)]);
    }

}