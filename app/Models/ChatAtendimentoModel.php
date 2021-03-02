<?php

namespace App\Models;

use App\Models\NajModel;
use Illuminate\Support\Facades\DB;

/**
 * Modelo de boletos.
 *
 * @package    Models
 * @author     Roberto Oswaldo Klann
 * @since      13/07/2020
 */
class ChatAtendimentoModel extends NajModel {
    
    protected function loadTable() {
        $this->setTable('chat_atendimento');
        
        $this->addColumn('id', true);
        $this->addColumn('id_chat');
        $this->addColumn('id_usuario');
        $this->addColumn('data_hora_inicio');
        $this->addColumn('data_hora_termino');
        $this->addColumn('status');
    }

    public function getLastAtendimentoByUserAndChat($id_usuario, $id_chat) {
        return DB::select("
              SELECT *
                FROM chat_atendimento
               WHERE TRUE
                 AND id_chat    = {$id_chat}
                 AND id_usuario =  {$id_usuario}
            ORDER BY id DESC
               LIMIT 1
        ");
    }

    public function hasAtendimentoOpen($idChat) {
        return DB::select("
            SELECT cat.id as id_ultimo_atendimento,
                   cat.id_chat,
                   cat.data_hora_inicio,
                   cat.data_hora_termino,
                   cat.status
              FROM ( 
                     SELECT MAX(id) as id_ultimo_atendimento
                       FROM chat_atendimento	 
                   GROUP BY id_chat
                   ) as cat2
        INNER JOIN (
                     SELECT id,
                            id_chat,
                            data_hora_inicio,
                            data_hora_termino,
                            status
                       FROM chat_atendimento	 
                   ORDER BY id_chat, data_hora_inicio 
                  ) as cat on cat.id = cat2.id_ultimo_atendimento
             WHERE TRUE 
               AND id_chat = {$idChat}
               AND status = 0
        ");
    }
    
}