<?php

namespace App\Models;

use Auth;
use App\Models\NajModel;
use Illuminate\Support\Facades\DB;

/**
 * Modelo de atendimento.
 *
 * @package    Models
 * @author     Roberto Oswaldo Klann
 * @since      08/07/2020
 */
class AtendimentoModel extends NajModel {

    protected function loadTable() {
         $this->setTable('chat_mensagem');
    }

    public function allMessages() {
        return [
            'todos'       => $this->getMessagesFinished(),
            'emAndamento' => $this->getMessagesInAtendimento(),
            'naFila'      => $this->getMessagesInFila()
        ];
    }

    private function getMessagesFinished() {
        $queryFilters = request()->query('f');
        $filters      = "HAVING status_atendimento = '1'";

        //Se foi informado algum filtro
        if($queryFilters) {
            $filterParse = json_decode(base64_decode($queryFilters));
  
            if(isset($filterParse->nome_usuario_cliente)) {
                $filters = " AND u.cliente like '%{$filterParse->nome_usuario_cliente}%'";
            }

            if(isset($filterParse->data_inicial, $filterParse->data_final)) {
               $filters = $filters . " AND cm.data_hora BETWEEN '{$filterParse->data_inicial}' AND '$filterParse->data_final'";
           }
        }

        return DB::select("
            select cru.id_chat,
                   u.id_usuario_cliente,
                   u.cliente,
                   cm.ultima_mensagem,
                   cm.data_hora,
                   if(cms.qtde_novas is null,0,cms.qtde_novas) as qtde_novas,
                   cat.id_usuario_atendimento,
                   cat.data_hora_inicio,
                   cat.data_hora_termino,
                   cat.status_atendimento
              from (
                      select id   as id_usuario_cliente,
                             nome as cliente
                        from usuarios
                       where usuario_tipo_id ='3' #usuários do tipo cliente
                   ) as u
                   inner join (
                    select id_usuario,
                           id_chat 
                      from chat_rel_usuarios
                   ) as cru on cru.id_usuario = u.id_usuario_cliente
       left join  (
                    select max(id) as id_ultima_mensagem,
                           id_chat
                      from chat_mensagem
                  group by id_chat
                   ) as cm2 on cm2.id_chat = cru.id_chat
        inner join (
                    select id,
                           conteudo as ultima_mensagem,
                           id_chat,
                           data_hora
                      from chat_mensagem
                  order by data_hora
                   ) as cm on cm.id = cm2.id_ultima_mensagem
        inner join (
                      select id,
                             tipo
                        from chat
                   ) as c on c.id = cm.id_chat
         left join (
                      select count(0) as qtde_novas,
                             cm.id_chat
                        from chat_mensagem_status s
                  inner join chat_mensagem cm on cm.id = s.id_mensagem
                       where s.status='1' # mensagens com status = Entregue
                    group by cm.id_chat
                   ) as cms on cms.id_chat = c.id
         left join (
                      select id_chat,
                             data_hora_inicio,
                             data_hora_termino,
                             id_usuario as id_usuario_atendimento,
                             status as status_atendimento
                        from chat_atendimento
                    order by data_hora_inicio desc
                   ) as cat on cat.id_chat = cru.id_chat
              where c.tipo = '0'                              
           group by u.id_usuario_cliente
           {$filters}
           order by cm.data_hora desc
        ");
    }

    private function getMessagesInFila() {
        return DB::select("
            select cru.id_chat,
                   u.id_usuario_cliente,
                   u.cliente,
                   cm.ultima_mensagem,
                   cm.data_hora,
                   if(cms.qtde_novas is null,0,cms.qtde_novas) as qtde_novas,
                   cat.id_usuario_atendimento,
                   cat.data_hora_inicio,
                   cat.data_hora_termino,
                   cat.status_atendimento
              from (
                      select id   as id_usuario_cliente,
                             nome as cliente
                        from usuarios
                       where usuario_tipo_id ='3'
                   ) as u
        inner join (
                      select id_usuario,
                             id_chat 
                        from chat_rel_usuarios
                   ) as cru on cru.id_usuario = u.id_usuario_cliente
       left join  (
                      select max(id) as id_ultima_mensagem,
                             id_chat
                        from chat_mensagem
                    group by id_chat
                  ) as cm2 on cm2.id_chat = cru.id_chat
        inner join (
                      select id,
                             conteudo as ultima_mensagem,
                             id_chat,
                             data_hora
                        from chat_mensagem
                    order by data_hora
                   ) as cm on cm.id = cm2.id_ultima_mensagem
        inner join (
                      select id,
                             tipo
                        from chat
                   ) as c on c.id = cm.id_chat
         left join (
                      select count(0) as qtde_novas,
                             cm.id_chat
                        from chat_mensagem_status s
                  inner join chat_mensagem cm on cm.id = s.id_mensagem
                       where s.status='1'
                    group by cm.id_chat
                   ) as cms on cms.id_chat = c.id
         left join (
                      select id_chat,
                             data_hora_inicio,
                             data_hora_termino, 
                             id_usuario as id_usuario_atendimento,
                             status as status_atendimento
                        from chat_atendimento
                    order by data_hora_inicio desc
                   ) as cat on cat.id_chat = cru.id_chat
             where c.tipo='0' # chats tipo público (com clientes)
               and status_atendimento is null
          group by u.id_usuario_cliente
          order by cm.data_hora desc
        ");
    }

    private function getMessagesInAtendimento() {
          $id_usuario = Auth::user()->id;
          return DB::select("
               select cru.id_chat,
                    u.id_usuario_cliente,
                    u.cliente,
                    cm.ultima_mensagem,
                    cm.data_hora,
                    if(cms.qtde_novas is null,0,cms.qtde_novas) as qtde_novas,
                    cat.id_usuario_atendimento,
                    cat.data_hora_inicio,
                    cat.data_hora_termino,
                    cat.status_atendimento
               from (
                         select id   as id_usuario_cliente,
                              nome as cliente
                         from usuarios
                         where usuario_tipo_id ='3'
                    ) as u
                    inner join (
                         select id_usuario,
                                id_chat 
                           from chat_rel_usuarios
                      ) as cru on cru.id_usuario = u.id_usuario_cliente
          left join  (
                         select max(id) as id_ultima_mensagem,
                                id_chat
                           from chat_mensagem
                       group by id_chat
                     ) as cm2 on cm2.id_chat = cru.id_chat
           inner join (
                         select id,
                                conteudo as ultima_mensagem,
                                id_chat,
                                data_hora
                           from chat_mensagem
                       order by data_hora
                      ) as cm on cm.id = cm2.id_ultima_mensagem
          inner join (
                         select id,
                              tipo
                         from chat
                    ) as c on c.id = cm.id_chat
          left join (
                         select count(0) as qtde_novas,
                              cm.id_chat
                         from chat_mensagem_status s
                    inner join chat_mensagem cm on cm.id = s.id_mensagem
                         where s.status='1'
                         group by cm.id_chat
                    ) as cms on cms.id_chat = c.id
          left join (
                         select id_chat,
                              data_hora_inicio,
                              data_hora_termino, 
                              id_usuario as id_usuario_atendimento,
                              status as status_atendimento
                         from chat_atendimento
                         order by data_hora_inicio desc
                    ) as cat on cat.id_chat = cru.id_chat
               where c.tipo='0'
                    and status_atendimento = '0'
                    and id_usuario_cliente = {$id_usuario}
               group by u.id_usuario_cliente
               order by cm.data_hora desc
        ");
    }

    public function allMessagesChat($id) {
          return DB::select("
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
                    usuarios.nome,
                    ca.id as id_atendimento
               from chat_mensagem cm
          left join chat_atendimento_rel_mensagem cam on cam.id_mensagem = cm.id
          left join chat_atendimento ca on ca.id = cam.id_atendimento
               join usuarios
                 on usuarios.id = cm.id_usuario
               where cm.id_chat = {$id} -- ID_CHAT para setar o CHAT desejado e carregar todo o histórico de mensagems
               order by cm.id_chat,
                        cm.data_hora,
                        cm.id
          ");
    }
    
}