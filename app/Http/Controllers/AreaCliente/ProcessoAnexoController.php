<?php

namespace App\Http\Controllers\AreaCliente;

use App\Http\Controllers\NajController;
use App\Models\ProcessoAnexoModel;

/**
 * Controller dos anexos do processo.
 *
 * @package    Controllers
 * @subpackage NajWeb
 * @author     Roberto Klann
 * @since      16/10/2020
 */
class ProcessoAnexoController extends NajController {

    public function onLoad() {
        $this->setModel(new ProcessoAnexoModel);
    }
    
}