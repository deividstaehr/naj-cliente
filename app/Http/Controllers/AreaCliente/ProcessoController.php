<?php

namespace App\Http\Controllers\AreaCliente;

use App\Http\Controllers\NajController;
use App\Models\ProcessoModel;

/**
 * Controller de processos.
 *
 * @package    Controllers
 * @subpackage NajWeb
 * @author     Roberto Klann
 * @since      20/07/2020
 */
class ProcessoController extends NajController {

    public function onLoad() {
        $this->setModel(new ProcessoModel);
    }

    public function index() {
        return view('areaCliente.consulta.ProcessoConsultaView');
    }
    
    /**
     * Retorna o max(id) no BD 
     * @return integer
     */
    public function proximo() {
        $proximo = $this->getModel()->max('codigo');
        if(!$proximo){
            $proximo = 0;
        }
        return response()->json($proximo)->content();
    }
    
    public function anexos($key) {
        return response()->json($this->getModel()->anexos($key));
    }

    public function getPartes($key) {
        return response()->json($this->getModel()->getPartes($key));
    }

    public function getParteCliente($key) {
        return response()->json($this->getModel()->getParteCliente($key));
    }

    public function getParteAdversaria($key) {
        return response()->json($this->getModel()->getParteAdversaria($key));
    }

    // public function getMovimentacao($key) {
    //     $key = $this->parseQueryFilter($key);

    //     $AppProcessoMovimentacaoModel = new AppProcessoMovimentacaoModel;
    //     $AppProcessoMovimentacaoModel->addFixedFilter('CODIGO_PROCESSO', $key->CODIGO);

    //     return $AppProcessoMovimentacaoModel->makePagination();
    // }

    // public function getAtividades($key) {
    //     $key = $this->parseQueryFilter($key);

    //     $AppProcessoAtividadesModel = new AppProcessoAtividadesModel;
    //     $AppProcessoAtividadesModel->addFixedFilter('CODIGO_PROCESSO', $key->CODIGO);

    //     return $AppProcessoAtividadesModel->makePagination();
    // }

    /**
     * Obtêm os registros de "prc_qualificacao"
     * 
     * @return JSON
     */
    public function getPrcQualificacao(){
        $result = $this->getModel()->getPrcQualificacao();
        return response()->json($result)->content();
    }
    
    /**
     * Obtêm os registros de "prc_orgao"
     * 
     * @return JSON
     */
    public function getPrcOrgao(){
        $result = $this->getModel()->getPrcOrgao();
        return response()->json($result)->content();
    }
    
    /**
     * Obtêm os registros de "prc_situacao"
     * 
     * @return JSON
     */
    public function getPrcSituacao(){
        $result = $this->getModel()->getPrcSituacao();
        return response()->json($result)->content();
    }

    public function getQtdeAtivoBaixado() {
        return response()->json(['data' => $this->getModel()->getQtdeAtivoBaixado()]);
    }
    
}