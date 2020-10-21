<?php

namespace App\Models;

use App\Models\NajModel;

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

      $this->setOrder('data_arquivo');
   }

}