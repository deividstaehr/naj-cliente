<?php

namespace App\Models;

use App\Models\NajModel;

/**
 * Modelo do anexo das atividades.
 *
 * @since 2020-10-16
 */
class AtividadeAnexoModel extends NajModel {

   protected function loadTable() {
      $this->setTable('atividade_anexos');

      $this->addColumn('id', true);
      $this->addColumn('codigo_atividade');
      $this->addColumn('descricao');
      $this->addColumn('data');

      $this->setOrder('data');
   }

}