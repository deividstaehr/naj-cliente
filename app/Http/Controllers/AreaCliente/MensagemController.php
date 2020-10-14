<?php

namespace App\Http\Controllers\AreaCliente;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
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
    
}