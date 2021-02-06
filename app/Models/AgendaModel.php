<?php

namespace App\Models;

use App\Models\NajModel;

/**
 * Modelo da agenda.
 *
 * @since 2021-02-06
 */
class AgendaModel extends NajModel {

   protected function loadTable() {
      $this->setTable('agenda');
   }
}