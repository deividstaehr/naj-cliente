<?php

namespace App\Http\Controllers\AreaCliente;

use App\Models\ChatMensagemStatusModel;
use App\Http\Controllers\NajController;

/**
 * Controller do status da mensagem.
 *
 * @package    Controllers
 * @subpackage NajWeb
 * @author     Roberto Oswaldo Klann
 * @since      13/07/2020
 */
class ChatMensagemStatusController extends NajController {

    public function onLoad() {
        $this->setModel(new ChatMensagemStatusModel);
    }

    protected function resolveWebContext($usuarios, $code) {}

}