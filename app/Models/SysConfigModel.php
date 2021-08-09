<?php

namespace App\Models;

use App\Models\NajModel;
use Illuminate\Support\Facades\DB;

/**
 * Modelo de relacionamento da Advocacia x Usuário.
 *
 * @package    Models
 * @author     Roberto Oswaldo Klann
 * @author     William Goebel
 * @since      16/03/2020
 */
class SysConfigModel extends NajModel {

    protected function loadTable() {
        $this->setTable('sys_config');

        $this->addColumn('ID', true);
        $this->addColumn('SECAO');
        $this->addColumn('CHAVE');
        $this->addColumn('VALOR');
    }

    /**
     * Verifica se Empresa existe
     * 
     * @param array $data
     * @return StdClass
     */
    public function existsEmpresa($data) {
        return DB::select("
            SELECT *
              FROM sys_config
             WHERE secao = '{$data['SECAO']}'
               AND chave = '{$data['CHAVE']}'
        ");
    }

    /**
     * Deleta registro no Sys_Config
     * 
     * @param string $secao
     * @param string $chave
     * @return int
     */
    public function destroySysConfig($secao, $chave) {
        return DB::table('sys_config')->where(['SECAO' => $secao, 'CHAVE' => $chave])->delete();
    }
    
    /**
     * Busca registro no Sys_Config
     * 
     * @param string $secao
     * @param string $chave
     * @return StdClass
     */
    public function searchSysConfig($secao, $chave) {
        $data = DB::table('sys_config')
            ->where(['SECAO' => $secao, 'CHAVE' => $chave])
            ->limit(1)
            ->first();

        if ($data)
            return $data->VALOR;

        return false;
    }


 // /**
    //  * Verifica se Empresa existe
    //  * 
    //  * @param array $data
    //  * @return StdClass
    //  */
    // public function existsEmpresa($data) {
    //     return DB::select("
    //         SELECT *
    //           FROM sys_config
    //          WHERE secao = '{$data['SECAO']}'
    //            AND chave = '{$data['CHAVE']}'
    //     ");
    // }
     // /**
    //  * Verifica se Empresa existe
    //  * 
    //  * @param array $data
    //  * @return StdClass
    //  */
    // public function existsEmpresa($data) {
    //     return DB::select("
    //         SELECT *
    //           FROM sys_config
    //          WHERE secao = '{$data['SECAO']}'
    //            AND chave = '{$data['CHAVE']}'
    //     ");
    // }




    // /**
    //  * Verifica se Empresa existe
    //  * 
    //  * @param array $data
    //  * @return StdClass
    //  */
    // public function existsEmpresa($data) {
    //     return DB::select("
    //         SELECT *
    //           FROM sys_config
    //          WHERE secao = '{$data['SECAO']}'
    //            AND chave = '{$data['CHAVE']}'
    //     ");
    // }

    // /**
    //  * Deleta registro no Sys_Config
    //  * 
    //  * @param string $secao
    //  * @param string $chave
    //  * @return int
    //  */
    // public function destroySysConfig($secao, $chave) {
    //     return DB::table('sys_config')->where(['SECAO' => $secao, 'CHAVE' => $chave])->delete();
    // }
    
    // /**
    //  * Busca registro no Sys_Config
    //  * 
    //  * @param string $secao
    //  * @param string $chave
    //  * @return StdClass
    //  */
    // public function searchSysConfig($secao, $chave) {
    //     $data = DB::table('sys_config')
    //         ->where(['SECAO' => $secao, 'CHAVE' => $chave])
    //         ->limit(1)
    //         ->first();

    //     if ($data)
    //         return $data->VALOR;

    //     return false;
    // }
}
