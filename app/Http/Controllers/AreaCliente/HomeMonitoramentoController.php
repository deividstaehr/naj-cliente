<?php

namespace App\Http\Controllers\AreaCliente;

use App\Models\MonitoramentoModel;
use App\Http\Controllers\AreaCliente\MonitoramentoController;

/**
 * Controller do monitoramento do sistema da rotina de mensagens.
 *
 * @package    Controllers
 * @subpackage AreaCliente
 * @author     Roberto Oswaldo Klann
 * @since      16/12/2020
 */
class HomeMonitoramentoController extends MonitoramentoController {

    /**
     * Nome da rotina.
     */
    public $nomeRotina;

    /**
     * Nome do modulo dessa rotina.
     */
    public $nomeModulo;

    public function onLoad() {
        $this->setModel(new MonitoramentoModel);

        $this->nomeRotina = 'Área do Cliente Web';
        $this->nomeModulo = 'AcessoAreaClienteWeb';
    }
 
    protected function getDescriptionAction($action) {        
        $ipCliente = $_SERVER['REMOTE_ADDR'];

        return sprintf(
            '%s ao Sistema %s. IP Cliente: %s. Navegador: %s',
            $this->getAcaoCurrent($action),
            $this->nomeRotina,
            $ipCliente,
            $this->getBrowserUsedByUser()
        );
    }

}