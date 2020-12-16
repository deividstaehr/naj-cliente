<?php

namespace App\Http\Controllers\AreaCliente;

use App\Models\MonitoramentoModel;
use App\Http\Controllers\NajController;

/**
 * Controller do monitoramento do sistema.
 *
 * @package    Controllers
 * @subpackage AreaCliente
 * @author     Roberto Oswaldo Klann
 * @since      16/12/2020
 */
class MonitoramentoController extends NajController {

    public function onLoad() {
        $this->setModel(new MonitoramentoModel);
    }

    /**
     * Aqui faz toda magica necessário para fazer o store
     */
    public function storeMonitoramento($action) {
        //montando os atributos para o INSERT
        $atributos = [
            'id_modulo'      => $this->getModel()->getIdModulo($this->nomeModulo),
            'codigo_divisao' => 1,
            'codigo_usuario' => $this->getModel()->getCodigoPessoa(),
            'data_hora'      => date('Y-m-d H:i:s'),
            'acao'           => $this->getDescriptionAction($action)
        ];

        $this->store($atributos);
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
                return 'Acesso';

            default:
                break;
        }
    }

    /**
     * Monta a descrição da coluna ACAO, ou seja, monta a descrição do que aconteceu.
     * 
     * OBS: Se for necessário fazer alguma alteração especifica na descrição da coluna ACAO é só sobreescrever esse cara.
     */
    protected function getDescriptionAction($action) {
        return sprintf(
            '%s dados na rotina %s',
            $this->getAcaoCurrent($action),
            $this->nomeRotina
        );
    }

    protected function getBrowserUsedByUser() {
        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        $navegador = 'Não identificado.';

        if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent)) {
            $navegador = 'Internet Explorer';
        } else if(preg_match('/Firefox/i',$u_agent)) {
            $navegador = 'Mozilla Firefox';
        } else if(preg_match('/Chrome/i',$u_agent)) {
            $navegador = 'Google Chrome';
        } else if(preg_match('/Safari/i',$u_agent)) {
            $navegador = 'Apple Safari';
        } else if(preg_match('/Opera/i',$u_agent)) {
            $navegador = 'Opera';
        } else if(preg_match('/Netscape/i',$u_agent)) {
            $navegador = 'Netscape';
        }

        return $navegador;
    }

}