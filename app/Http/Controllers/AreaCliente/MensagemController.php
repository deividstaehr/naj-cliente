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

        $ChatMensagemModel = new ChatMensagemModel();
        $novas             = $ChatMensagemModel->getNotReadMessages($chat->getOriginal()['id_chat'], Auth::user()->id);

        return response()->json(['todas' => $todas, 'novas' => count($novas)]);
    }
    
}