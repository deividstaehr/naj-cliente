<?php

namespace App\Http\Controllers\AreaCliente;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\ChatMensagemModel;
use App\Models\ChatRelacionamentoUsuarioModel;

class MensagemController extends Controller {
    
    public function index() {
        return view('areaCliente.mensagens');
    }

    /**
     * Retorna se tem chat para o usuário.
     * 
     * @return boolean
     */
    public function hasChat($id) {
        $ChatRelUsuarioModel = new ChatRelacionamentoUsuarioModel();
        $chat = $ChatRelUsuarioModel->where('id_usuario', $id)->first();

        if(is_null($chat)) {
            return response()->json(['chat' => false]);
        }

        return response()->json(['chat' => $chat->getOriginal()]);
    }

    public function hasMensagemFromChat($id) {
        $ChatMensagemModel = new ChatMensagemModel();
        $chat              = $ChatMensagemModel->where('id_chat', $id)->first();

        if(is_null($chat)) {
            return response()->json(['chat' => false]);
        }

        return response()->json(['chat' => $chat->getOriginal()]);
    }

    public function getNewMessagesAndTodas() {
        $ChatRelUsuarioModel = new ChatRelacionamentoUsuarioModel();
        $chat = $ChatRelUsuarioModel->where('id_usuario', Auth::user()->id)->first();

        if(is_null($chat)) {
            return response()->json(['sem_chat' => true]);
        }

        $todas = DB::select("
            SELECT COUNT(0) todas
              FROM chat_mensagem
             WHERE TRUE
               AND id_chat = {$chat->getOriginal()['id_chat']}
        ");

        $novas = DB::select("
            select  cru.id_chat,
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
                        where s.status = '1'
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
              and cru.id_chat = {$chat->getOriginal()['id_chat']}
            group by u.id_usuario_cliente
            order by cm.data_hora desc;
        ");

        return response()->json(['todas' => $todas, 'novas' => $novas]);
    }
    
}