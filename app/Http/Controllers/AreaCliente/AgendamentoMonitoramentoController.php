<?php

namespace App\Http\Controllers\AreaCliente;

use App\Models\MonitoramentoModel;
use App\Http\Controllers\AreaCliente\MonitoramentoController;

/**
 * Controller do monitoramento do sistema da rotina de agendamento.
 *
 * @package    Controllers
 * @subpackage AreaCliente
 * @author     Roberto Oswaldo Klann
 * @since      06/02/2021
 */
class AgendamentoMonitoramentoController extends MonitoramentoController {

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

        $this->nomeRotina = 'Agendamento';
        $this->nomeModulo = 'AcessoAreaClienteWEBAgenda';
    }

    protected function getDescriptionAction($action) {        
        $ipCliente = $_SERVER['REMOTE_ADDR'];

        return sprintf(
            'Clicou em %s. IP Cliente: %s. Navegador: %s',
            request()->get('agendamentoRotina'),
            $ipCliente,
            $this->getBrowserUsedByUser()
        );
    }

}