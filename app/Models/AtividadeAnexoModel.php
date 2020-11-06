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
      $this->addColumn('data_arquivo');
      $this->addColumn('file_size');

      $this->setOrder('data_arquivo', 'desc');

      $this->addAllColumns();
      $this->addRawFilter("enviar = 'S'");
      $this->setRawBaseSelect("
          SELECT [COLUMNS]
            FROM atividade_anexos
            JOIN atividade
              ON atividade.codigo = atividade_anexos.codigo_atividade
      ");
   }

   public function addAllColumns() {
      $this->addRawColumn("atividade.enviar");
   }

}