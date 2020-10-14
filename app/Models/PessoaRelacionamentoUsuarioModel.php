<?php

namespace App\Models;

use App\Models\NajModel;
use Illuminate\Support\Facades\DB;

class PessoaRelacionamentoUsuarioModel extends NajModel {

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

}