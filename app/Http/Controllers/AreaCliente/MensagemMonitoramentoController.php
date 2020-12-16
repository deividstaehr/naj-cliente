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
class MensagemMonitoramentoController extends MonitoramentoController {

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

        $this->nomeRotina = 'Mensagem';
        $this->nomeModulo = 'AcessoAreaClienteWebMensagens';
    }

    protected function getDescriptionAction($action) {
        return sprintf(
            '%s a rotina %s',
            $this->getAcaoCurrent($action),
            $this->nomeRotina
        );
    }

    protected function getAcaoCurrent($action) {
        switch($action) {
            case self::STORE_ACTION:
                return 'Incluiu';

            case self::UPDATE_ACTION:
                return 'Alterou';

            case self::DESTROY_ACTION:
                return 'Excluiu';

            case self::PAGINATE_ACTION:
                return 'Pesquisou por';

            case self::INDEX_ACTION:
                return 'Acessou';

            default:
                break;
        }
    }

}