<?php

namespace App\Http\Controllers\AreaCliente;

use App\Http\Controllers\NajController;
use App\Models\AtividadeProcessoModel;

/**
 * Controller de processos.
 *
 * @package    Controllers
 * @subpackage NajWeb
 * @author     Roberto Klann
 * @since      20/07/2020
 */
class AtividadeProcessoController extends NajController {

    public function onLoad() {
        $this->setModel(new AtividadeProcessoModel);
    }
    
}