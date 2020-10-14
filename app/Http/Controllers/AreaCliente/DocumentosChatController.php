<?php

namespace App\Http\Controllers\AreaCliente;

use App\Http\Controllers\NajController;
use App\Models\DocumentosChatModel;

/**
 * Controlador dos documentos do Chat.
 *
 * @since 2020-08-11
 */
class DocumentosChatController extends NajController {

    public function onLoad() {
        $this->setModel(new DocumentosChatModel);
    }

    public function documentos($key) {
        return response()->json(['data' => $this->getModel()->documentos($key)]);
    }

}