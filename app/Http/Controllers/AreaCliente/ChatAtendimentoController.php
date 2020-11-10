<?php

namespace App\Http\Controllers\AreaCliente;

use Auth;
use App\Models\ChatAtendimentoModel;
use App\Models\UsuarioModel;
use App\Models\ChatRelacionamentoUsuarioModel;
use App\Http\Controllers\NajController;
use App\Http\Controllers\AreaCliente\ChatController;
use App\Http\Controllers\AreaCliente\AnexoChatStorageController;

/**
 * Controller do chat atendimento.
 *
 * @package    Controllers
 * @subpackage NajWeb
 * @author     Roberto Oswaldo Klann
 * @since      13/07/2020
 */
class ChatAtendimentoController extends NajController {

    public function onLoad() {
        $this->setModel(new ChatAtendimentoModel);
    }

    protected function resolveWebContext($usuarios, $code) {}

    /**
     *
     * @return type
     */
    public function store($attrs = null) {
        $this->setCurrentAction(self::STORE_ACTION);

        $this->begin();

        $code = 200;

        $data = ['mensagem' => 'Registro inserido com sucesso.', 'model' => null];

        try {
            $toStore = $this->resolveValidate(
                $this->getModel()->getFilledAttributes($attrs)
            );

            $data['model'] = $toStore;

            $model = $this->getModel()->newInstance();

            $model->fill($toStore);

            $result = $model->save();

            if (is_string($result)) {
                $this->throwException('Erro ao inserir o registro. ' . $result);
            }

            $this->handleItems($model);

            //GEITINHO BRASILEIRO ESSA LINHA DE CODIGO ABAIXO :D KAKAKAKAKAKAKAK OBS: RINDO DE MEDO
            $toStore['id'] = $this->getModel()->getLastAtendimentoByUserAndChat($model['id_usuario'], $model['id_chat'])[0]->id;
            $data['model'] = $toStore;

            $this->commit();
        } catch (Exception $e) {
            $code = 400;

            $data = ['mensagem' =>
                $this->extractMessageFromException($e)
            ];

            $this->rollback();
        }

        return $this->resolveResponse($data, $code);
    }

    public function handleItems($model = null) {
        $action = $this->getCurrentAction();
        
        if ($action === NajController::DESTROY_ACTION) {
            $this->destroyItems($model);
            
            return;
        }
        
        $this->{"{$action}Items"}($model);
    }

    public function storeItems($model) {
        //GEITINHO BRASILEIRO ESSA LINHA DE CODIGO ABAIXO :D KAKAKAKAKAKAKAK
        $this->model->id = $this->getModel()->getLastAtendimentoByUserAndChat($model['id_usuario'], $model['id_chat']);
    }

    public function updateItems($model) {}

    public function novoAtendimento() {
        $mensagem   = request()->get('conteudo');
        $data_hora  = request()->get('data_hora');
        $chat_store = $this->storeChat();

        if($mensagem) {
            //INCLUINDO A MENSAGEM QUE O CARA DIGITOU
            $this->storeChatMensagem([
                'id_chat'    => $chat_store['model']['id'],
                'id_usuario' => Auth::user()->id,
                'conteudo'   => $mensagem,
                'tipo'       => 0,
                'data_hora'  => $data_hora
            ]); 
        }

        if(request()->get('files')) {
            //INCLUINDO OS ANEXOS
            $this->callStoreAnexos(Auth::user()->id, $chat_store['model']['id']);
        }

        return response()->json(['message' => 'Mensagem enviada com sucesso!', 'status_code' => 200], 200);
    }

    /**
     * Retorna se tem chat para o usuário.
     * 
     * @return boolean
     */
    private function hasChat($id) {
        $ChatRelUsuarioModel = new ChatRelacionamentoUsuarioModel();
        $chat = $ChatRelUsuarioModel->where('id_usuario', $id)->first();

        if(is_null($chat)) {
            return false;
        }

        return $chat->getOriginal();
    }

    private function storeChat() {
        $ChatController = new ChatController();
        $max = $ChatController->getModel()->max('id') + 1;
        $nome = '#PUBLICO_' . $max;

        $chat = $ChatController->store([
            'data_inclusao' => request()->get('data_hora'),
            'tipo'          => 0,
            'nome'          => $nome
        ]);

        $chat->original['model']['id'] = $ChatController->getModel()->max('id');
        return $chat->original;
    }

    private function storeChatMensagem($parametros) {
        $ChatMensagemController = new ChatMensagemController();
        $ChatMensagemController->store($parametros);
    }

    /**
     * Retorn se tem um atendimento aberto para o chat.
     * 
     * @return boolean
     */
    private function hasAtendimentoOpen($id) {
        $atendimento = $this->getModel()->hasAtendimentoOpen($id);

        return isset($atendimento[0]->id);
    }

    private function callStoreAnexos($id_usuario, $id_chat) {
        $ChatMensagemController     = new ChatMensagemController();
        $AnexoChatStorageController = new AnexoChatStorageController();
        $files                      = request()->get('files');

        try {
            $ChatMensagemController->setCurrentAction(self::STORE_ACTION);
            $ChatMensagemController->begin();
            foreach($files as $oFile) {
                $oFile['id_chat'] = $id_chat;
                $model = $ChatMensagemController->storeMessageAnexo($oFile);

                $AnexoChatStorageController->callStoreFile($oFile, $model->original['model']['file_path']);
            }
        } catch(Exception $e) {
            $ChatMensagemController->rollback();

            return response()->json(['status_code' => 400, 'mensagem' => 'Não foi possível enviar os anexos, tente novamente mais tarde.']);
        }

        $ChatMensagemController->commit();
    }

}