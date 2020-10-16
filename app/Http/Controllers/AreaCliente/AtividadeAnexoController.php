<?php

namespace App\Http\Controllers\AreaCliente;

use App\Http\Controllers\NajController;
use App\Models\AtividadeAnexoModel;

/**
 * Controller dos anexos da atividade.
 *
 * @package    Controllers
 * @subpackage NajWeb
 * @author     Roberto Klann
 * @since      16/10/2020
 */
class AtividadeAnexoController extends NajController {

    public function onLoad() {
        $this->setModel(new AtividadeAnexoModel);
    }
    
}