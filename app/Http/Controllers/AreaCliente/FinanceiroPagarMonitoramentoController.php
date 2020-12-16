<?php

namespace App\Http\Controllers\AreaCliente;

use App\Models\MonitoramentoModel;
use App\Http\Controllers\AreaCliente\MonitoramentoController;

/**
 * Controller do monitoramento do sistema da rotina de financeiro.
 *
 * @package    Controllers
 * @subpackage AreaCliente
 * @author     Roberto Oswaldo Klann
 * @since      16/12/2020
 */
class FinanceiroPagarMonitoramentoController extends MonitoramentoController {

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

        $this->nomeRotina = 'Financeiro A Pagar';
        $this->nomeModulo = 'AcessoAreaClienteWebFinanceiro';
    }   

}