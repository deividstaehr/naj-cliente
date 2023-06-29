<?php

namespace App\Http\Controllers\AreaCliente;

use App\Models\AnexoChatStorageModel;
use App\Http\Controllers\NajController;
use App\Http\Controllers\AreaCliente\ChatMensagemController;
use App\Http\Controllers\AreaCliente\GoogleCloudStorageController;
use Google\Cloud\Storage\StorageClient;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\ProcessoAnexoModel;

/**
 * Controllador do Google Cloud Storage.
 *
 * @package    Controllers
 * @subpackage NajWeb
 * @author     Roberto Oswaldo Klann
 * @since      04/08/2020
 */
class AnexoChatStorageController extends NajController {

    private $laravelStorageDir;

    public function __construct() {
        $base = rtrim(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '/');
        @list($dir,) = explode('/public', $base);

        $this->laravelStorageDir = $dir . '/storage/app/chat_files/';

        parent::__construct();
    }

    public function onLoad() {
        $this->setModel(new AnexoChatStorageModel);
    }

    protected function resolveWebContext($usuarios, $code) {}

	public function uploadAnexoChat() {
        $files = request()->get('files');
        $response = [];

        $oChatMensagemController = new ChatMensagemController();
        try {
            $oChatMensagemController->setCurrentAction(self::STORE_ACTION);
            $oChatMensagemController->begin();
            foreach($files as $oFile) {
                $model = $oChatMensagemController->storeMessageAnexo($oFile, true);

                $response[] = $model->original['model'];

                $this->callStoreFile($oFile, $model->original['model']['file_path']);
            }
        } catch(Exception $e) {
            $oChatMensagemController->rollback();
            return response()->json(['status_code' => 400, 'mensagem' => 'Não foi possível enviar os anexos, tente novamente mais tarde.']);
        }

        $oChatMensagemController->commit();
        return response()->json(['status_code' => 200, 'data' => $response, 'mensagem' => 'Anexo(s) enviado com sucesso!']);
    }
    
    public function callStoreFile($oFile, $nameFile) {
        $file       = $oFile['arquivo'];
        $name       = $oFile['name_file'];
        $id_cliente = $oFile['id_cliente'];
        
        @list($type, $fileData)    = explode(';', $file);
        @list(, $fileData)         = explode(',', $fileData);
        @list($fileName, $fileExt) = explode('.', $name);

        $pathStorage = $this->getModel()->getPathStorage();

        //Verificando se é para subir pro GCP
        if($this->getModel()->isSyncGoogleStorage()) {
            $GCSController = new GoogleCloudStorageController($this->getModel()->getKeyFileGoogleStorage(), $pathStorage);

            return $GCSController->storeFile(base64_decode($fileData), $nameFile);
        }

        Storage::disk('local')->put("/chat_files/" . $nameFile, base64_decode($fileData));

        $temporaryFile = $this->laravelStorageDir . $nameFile;
        $destinationFile = $pathStorage . "/" . $nameFile;

        $result = rename($temporaryFile, $destinationFile);

        Storage::disk('local')->delete("/chat_files/" . $nameFile);
    }

    public function downloadAnexoChat($parameters) {
        $parametros   = json_decode(base64_decode($parameters));
        $pathStorage  = $this->getModel()->getPathStorage();
        $originalName = $this->getModel()->getOriginalNameForDownload($parametros->id_message);

        if(!$originalName) {
            return response()->json(['status_code' => 400, 'mensagem' => 'Anexo não encontrado!']);
        }

        $nameFile = $parametros->identificador . "/chat_files/" . $parametros->id_message;
        //Verificando se é para subir pro GCP
        if($this->getModel()->isSyncGoogleStorage()) {
            $GCSController = new GoogleCloudStorageController($this->getModel()->getKeyFileGoogleStorage(), $pathStorage);

            return $GCSController->downloadFile($nameFile);
        }

        return file_get_contents($pathStorage . "/" . $nameFile);
    }

    public function downloadAnexoAtividade($parameters) {
        $parametros   = json_decode($parameters);
        $pathStorage  = $this->getModel()->getPathStorage();
        $originalName = $parametros->original_name;

        if(!$originalName) {
            return response()->json(['status_code' => 400, 'mensagem' => 'Anexo não encontrado!']);
        }

        $nameFile = $parametros->identificador . "/atividade_anexos/" . $parametros->codigo;
        //Verificando se é para subir pro GCP
        if($this->getModel()->isSyncGoogleStorage()) {
            $GCSController = new GoogleCloudStorageController($this->getModel()->getKeyFileGoogleStorage(), $pathStorage);

            return $GCSController->downloadFile($nameFile);
        }

        return file_get_contents($pathStorage . "/" . $nameFile);
    }

    public function downloadAnexoProcesso($parameters) {
        $parametros   = json_decode($parameters);
        $pathStorage  = $this->getModel()->getPathStorage();
        $originalName = $parametros->original_name;

        if(!$originalName) {
            return response()->json(['status_code' => 400, 'mensagem' => 'Anexo não encontrado!']);
        }

        $nameFile = $parametros->identificador . "/prc_anexos/" . $parametros->codigo;

        //VERIFICANDO SE É TEXTO VERSAO
        $codigoTextoVersao = $this->hasTextoVersao($parametros->codigo);

        if($codigoTextoVersao) {
            $versao   = $this->getLastVersaoTexto($codigoTextoVersao);
            $nameFile = $parametros->identificador . "/textos/txt" . $codigoTextoVersao . "_vs" . $versao . ".docx";
        }

        //Verificando se é para subir pro GCP
        if($this->getModel()->isSyncGoogleStorage()) {
            $GCSController = new GoogleCloudStorageController($this->getModel()->getKeyFileGoogleStorage(), $pathStorage);

            return $GCSController->downloadFile($nameFile);
        }

        return file_get_contents($pathStorage . "/" . $nameFile);
    }

    public function shareAnexoChat() {
        $files       = request()->get('files');
        $pathStorage = $this->getModel()->getPathStorage();

        $oChatMensagemController = new ChatMensagemController();
        try {
            $oChatMensagemController->setCurrentAction(self::STORE_ACTION);
            $oChatMensagemController->begin();
            foreach($files as $oFile) {
                $sizeFile = $this->getSizeFile($oFile, $pathStorage);

                if(!$sizeFile) return response()->json(['status_code' => 400, 'mensagem' => 'Não foi possível encaminhar o(s) anexo(s), comunique o suporte.']);

                $oFile['file_size'] = $sizeFile;
                $model = $oChatMensagemController->storeMessageAnexo($oFile);

                if(!$this->callShareFile($oFile, $model->original['model']['file_path'], $pathStorage)) {
                    $oChatMensagemController->rollback();
                    return response()->json(['status_code' => 400, 'mensagem' => 'Não foi possível encaminhar os anexos, tente novamente mais tarde.']);
                }
            }
        } catch(Exception $e) {
            $oChatMensagemController->rollback();
            return response()->json(['status_code' => 400, 'mensagem' => 'Não foi possível enviar os anexos, tente novamente mais tarde.']);
        }

        $oChatMensagemController->commit();

        return response()->json(['status_code' => 200, 'mensagem' => 'Anexo(s) enviado com sucesso!']);
    }

    private function callShareFile($oFile, $nameFile, $pathStorage) {
        $originalName = $oFile['id_cliente'] . "/" . $oFile['pasta'] . "/" . $oFile['id_file'];
        //Verificando se é para subir pro GCP
        if($this->getModel()->isSyncGoogleStorage()) {
            $GCSController = new GoogleCloudStorageController($this->getModel()->getKeyFileGoogleStorage(), $pathStorage);

            return $GCSController->copyFile($originalName, $nameFile);
        }

        return copy($pathStorage . "/" . $originalName, $pathStorage . "/" . $oFile['id_cliente'] . "/chat_files/" . $nameFile);
    }

    private function getSizeFile($oFile, $pathStorage) {
        $nameFile = $oFile['id_cliente'] . "/" . $oFile['pasta'] . "/" . $oFile['id_file'];
        if($this->getModel()->isSyncGoogleStorage()) {
            $GCSController = new GoogleCloudStorageController($this->getModel()->getKeyFileGoogleStorage(), $pathStorage);

            return $GCSController->getSizeFile($nameFile);
        }

        return filesize($pathStorage . "/" . $nameFile);
    }

    private function hasTextoVersao($codigo) {
        $ProcessoAnexoModel = new ProcessoAnexoModel();

        return $ProcessoAnexoModel->hasTextoVersao($codigo);
    }

    private function getLastVersaoTexto($codigo) {
        $versao = DB::select("
            SELECT VERSAO
              FROM texto_versao
             WHERE TRUE
               AND codigo_texto = {$codigo}
          ORDER BY versao DESC
             LIMIT 1
        ");

        return $versao[0]->VERSAO;
    }

}