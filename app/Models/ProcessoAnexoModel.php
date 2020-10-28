<?php

namespace App\Models;

use App\Models\NajModel;
use Illuminate\Support\Facades\DB;

/**
 * Modelo do anexo dos processos.
 *
 * @since 2020-10-16
 */
class ProcessoAnexoModel extends NajModel {

   protected function loadTable() {
      $this->setTable('prc_anexos');

      $this->addColumn('id', true);
      $this->addColumn('codigo_processo');
      $this->addColumn('descricao');
      $this->addColumn('data_arquivo');
      $this->addColumn('file_size');

      $this->setOrder('data_arquivo', 'desc');
   }

   public function hasTextoVersao($codigo) {
      $anexo = DB::select("
         SELECT *
           FROM prc_anexos
          WHERE TRUE
            AND id = {$codigo}
      ");

      return $anexo[0]->CODIGO_TEXTO;
   }

}