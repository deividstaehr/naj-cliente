<?php

namespace App\Http\Controllers\AreaCliente;

use App\Models\SysConfigModel;
use App\Http\Controllers\NajController;

/**
 * Controller dos relacionamentos da Advocacia x Usuário.
 *
 * @package    Controllers
 * @subpackage NajWeb
 * @author     Roberto Oswaldo Klann
 * @since      16/03/2020
 */
class SysConfigController extends NajController {

    public function onLoad() {
        $this->setModel(new SysConfigModel);
    }

    protected function resolveWebContext($usuarios, $code) {}

    /**
     * Verifica se Empresa existe
     * 
     * @param array $data
     * @return StdClass
     */
    public function existsEmpresa($data){
        return $this->getModel()->existsEmpresa($data);
    } 

    /**
     * Deleta registro no Sys_Config
     * 
     * @param string $secao
     * @param string $chave
     * @return int
     */
    public function destroySysConfig($secao, $chave) {
        return $this->getModel()->destroySysConfig($secao, $chave);
    }
    
    /**
     * Busca registro no Sys_Config
     * 
     * @param string $secao
     * @param string $chave
     * @return StdClass
     */
    public function searchSysConfig($secao, $chave) {
        return $this->getModel()->searchSysConfig($secao, $chave);
    }
}