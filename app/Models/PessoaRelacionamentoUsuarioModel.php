<?php

namespace App\Models;

use App\Models\NajModel;
use Illuminate\Support\Facades\DB;

class PessoaRelacionamentoUsuarioModel extends NajModel {

    /* Constantes do BANCO DE DADOS */
    const DB_SIM = 'S';
    const DB_NAO = 'N';

    protected function loadTable() {
        $this->setTable('pessoa_rel_usuarios');
        
        $this->addColumn('pessoa_codigo', true);
        $this->addColumn('usuario_id'   , true);
    }

    public function getRelacionamentosUsuario($codigo) {
        $pessoa_usuario = DB::select("
            SELECT *
              FROM pessoa_rel_usuarios
             WHERE TRUE 
               AND usuario_id = {$codigo}
        ");

        if($pessoa_usuario) {
            return $pessoa_usuario;
        }

        return DB::select("
            SELECT *
              FROM pessoa_rel_clientes
             WHERE TRUE 
               AND usuario_id = {$codigo}
        ");
    }

    public function getRelacionamentosUsuarioModuloFinanceiroContasReceber($codigo) {
        return DB::select("
            SELECT *
              FROM pessoa_rel_clientes
             WHERE TRUE 
               AND usuario_id     = {$codigo}
               AND contas_receber = '" . self::DB_SIM . "'
        ");
    }

    public function getRelacionamentosUsuarioModuloFinanceiroContasPagar($codigo) {
        return DB::select("
            SELECT *
              FROM pessoa_rel_clientes
             WHERE TRUE 
               AND usuario_id  = {$codigo}
               AND contas_pagar = '" . self::DB_SIM . "'
        ");
    }

    public function getRelacionamentosUsuarioModuloProcessos($codigo) {
        return DB::select("
            SELECT *
              FROM pessoa_rel_clientes
             WHERE TRUE 
               AND usuario_id = {$codigo}
               AND processos  = '" . self::DB_SIM . "'
        ");
    }

    public function getRelacionamentosUsuarioModuloAtividades($codigo) {
        return DB::select("
            SELECT *
              FROM pessoa_rel_clientes
             WHERE TRUE 
               AND usuario_id = {$codigo}
               AND atividades = '" . self::DB_SIM . "'
        ");
    }

}