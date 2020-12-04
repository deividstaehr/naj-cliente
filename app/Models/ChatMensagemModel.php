<?php

namespace App\Models;

use App\Models\NajModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AreaCliente\ChatMensagemStatusController;

/**
 * Modelo de boletos.
 *
 * @package    Models
 * @author     Roberto Oswaldo Klann
 * @since      13/07/2020
 */
class ChatMensagemModel extends NajModel {

    protected function loadTable() {
        $this->setTable('chat_mensagem');

        $this->addColumn('id', true);
        $this->addColumn('id_chat');
        $this->addColumn('id_usuario');
        $this->addColumn('conteudo');
        $this->addColumn('tipo');
        $this->addColumn('data_hora');
        $this->addColumn('file_size');
        $this->addColumn('file_path');
    }

    public function getAllMensagensChatPublico($id) {
        $queryFilters = request()->query('f');
        $filterParse  = json_decode(base64_decode($queryFilters));
        $limit        = $filterParse->limit;
        $offset       = ($this->getOffsetPage($id) - $limit);

        if($offset < 0) {
            $offset = 0;
        }

        $this->setStatusMensagemLida($id);

        $aData = DB::select("
          select c.id_mensagem,
                  c.id_chat,
                  c.id_usuario_mensagem,
                  c.conteudo,
                  c.tipo_conteudo,
                  c.data_hora,
                  c.file_size,
                  c.file_path,
                  c.status_atendimento,
                  c.id_usuario_atendimento,
                  c.data_hora_inicio,
                  c.data_hora_termino,
                  cms2.status_mensagem,
                  cms2.status,
                  c.nome,
                  c.usuario_tipo_id,
                  c.id_atendimento
            from (
                    select cm.id as id_mensagem,
                          cm.id_chat,
                          cm.id_usuario as id_usuario_mensagem,
                          cm.conteudo,
                          cm.tipo as tipo_conteudo,
                          cm.data_hora,
                          cm.file_size,
                          cm.file_path,
                          ca.status as status_atendimento,
                          ca.id_usuario as id_usuario_atendimento,
                          ca.data_hora_inicio,
                          ca.data_hora_termino,
                          ca.id as id_atendimento,
                          usuarios.nome,
                          usuarios.usuario_tipo_id
                      from chat_mensagem cm
                left join chat_atendimento_rel_mensagem cam 
                        on cam.id_mensagem = cm.id
                left join chat_atendimento ca 
                        on ca.id = cam.id_atendimento
                      join usuarios
                        on usuarios.id = cm.id_usuario
                  ) as c 
        left join # pegando o último id que deve ser com a última data de status da mensagem
                  (
                    select max(s.id) as id_status,
                          s.id_mensagem
                      from chat_mensagem_status s
                  group by s.id_mensagem
                  ) as cms on cms.id_mensagem = c.id_mensagem
        left join # relacionando o último id que possui a data e hora do último status com a mensagem
                  (
                    select id,
                          status_data_hora as status_mensagem,
                          status
                      from chat_mensagem_status
                  ) as cms2 on cms2.id = cms.id_status            
            where c.id_chat = {$id}
          order by c.id_chat DESC,
                  c.data_hora,
                  c.id_mensagem
            LIMIT {$limit}
            OFFSET {$offset}
        ");

        return ['data' => $aData, 'isLastPage' => ($offset == 0)];
    }

    public function getOffsetPage($id) {
        $aCount = DB::select("
          select c.id_mensagem,
                  c.id_chat,
                  c.id_usuario_mensagem,
                  c.conteudo,
                  c.tipo_conteudo,
                  c.data_hora,
                  c.file_size,
                  c.file_path,
                  c.status_atendimento,
                  c.id_usuario_atendimento,
                  c.data_hora_inicio,
                  c.data_hora_termino,
                  cms2.status_mensagem,
                  cms2.status,
                  c.nome,
                  c.id_atendimento
            from (
                    select cm.id as id_mensagem,
                          cm.id_chat,
                          cm.id_usuario as id_usuario_mensagem,
                          cm.conteudo,
                          cm.tipo as tipo_conteudo,
                          cm.data_hora,
                          cm.file_size,
                          cm.file_path,
                          ca.status as status_atendimento,
                          ca.id_usuario as id_usuario_atendimento,
                          ca.data_hora_inicio,
                          ca.data_hora_termino,
                          ca.id as id_atendimento,
                          usuarios.nome
                      from chat_mensagem cm
                left join chat_atendimento_rel_mensagem cam 
                        on cam.id_mensagem = cm.id
                left join chat_atendimento ca 
                        on ca.id = cam.id_atendimento
                      join usuarios
                        on usuarios.id = cm.id_usuario
                  ) as c 
        left join # pegando o último id que deve ser com a última data de status da mensagem
                  (
                     select max(s.id) as id_status,
                            s.id_mensagem
                       from chat_mensagem_status s
                   group by s.id_mensagem
                  ) as cms on cms.id_mensagem = c.id_mensagem
        left join # relacionando o último id que possui a data e hora do último status com a mensagem
                  (
                    select id,
                           status_data_hora as status_mensagem,
                           status
                      from chat_mensagem_status
                  ) as cms2 on cms2.id = cms.id_status            
            where c.id_chat = {$id}
         order by c.id_chat DESC,
                  c.data_hora,
                  c.id_mensagem
        ");
        
        return count($aCount);
    }

    public function getLastMessageByUserAndChat($id_usuario, $id_chat) {
      return DB::select("
            SELECT *
              FROM chat_mensagem
              WHERE TRUE
                AND id_chat    = {$id_chat}
                AND id_usuario =  {$id_usuario}
          ORDER BY id DESC
              LIMIT 1
      ");
    }

    public function getNotReadMessages($idChat, $idUsuario) {
      $result = DB::table('chat_mensagem')
          ->where('id_chat', $idChat)
          ->where('id_usuario', '<>', $idUsuario)
          ->whereNotExists(function($query) {
              $query->select(DB::raw(1))
                  ->from('chat_mensagem_status')
                  ->where('chat_mensagem_status.status', 2)
                  ->whereRaw('chat_mensagem_status.id_mensagem = chat_mensagem.id');
          })
          ->get();

      return $result;
    }

    private function getNotReadMessagesMyUser($idChat, $idUsuario) {
        $result = DB::table('chat_mensagem')
            ->where('id_chat', $idChat)
            ->where('id_usuario', '=', $idUsuario)
            ->whereNotExists(function($query) {
                $query->select(DB::raw(1))
                    ->from('chat_mensagem_status')
                    ->where('chat_mensagem_status.status', 2)
                    ->whereRaw('chat_mensagem_status.id_mensagem = chat_mensagem.id');
            })
            ->get();

        return $result;
    }

    private function setStatusMensagemLida($idChat) {
        $notRead = $this->getNotReadMessages($idChat, Auth::user()->id);

        $ChatMensagemStatusController = new ChatMensagemStatusController();

        foreach($notRead as $Mensagem) {
            $ChatMensagemStatusController->store([
                "id_mensagem"      => $Mensagem->id,
                "status"           => 2,
                "status_data_hora" => date("Y-m-d H:i:s")
            ]);
        }
    }
    
}