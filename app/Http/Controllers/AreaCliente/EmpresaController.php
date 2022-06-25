<?php

namespace App\Http\Controllers\AreaCliente;

use App\Http\Controllers\NajController;
use App\Http\Controllers\AreaCliente\SysConfigController;
use Google\Cloud\Storage\StorageClient;
use Illuminate\Support\Facades\Storage;
use App\Models\EmpresaModel;

/**
 * Controller de empresas.
 *
 * @package    Controllers
 * @subpackage NajWeb
 * @author     Roberto Oswaldo Klann
 * @since      30/01/2020
 */
class EmpresaController extends NajController {

    public function __construct() {
        $base = rtrim(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '/');
        @list($dir,) = explode('/public', $base);

        $this->laravelStorageDir = $dir . '/storage/app/logo_advocacia/';

        parent::__construct();
    }

    public function onLoad() {
        $this->setModel(new EmpresaModel);
    }

    protected function resolveWebContext($pessoas, $code) {
        return view('najWeb.empresa');
    }

    public function proximo() {
        $proximo = $this->getModel()->max('codigo');

        return $proximo;
    }

    public function handleItems($model = null) {}

    public function validateStore($data) {
        $SysConfigController = new SysConfigController();

        //Valida se a empresa já não foi cadastrada
        if($this->validateEmpresaHasStore($data, $SysConfigController->getModel())) {
            //Exclui o relacionamento entre ADVOCACIA X USUARIO na tabela SYS_CONFIG
            $result = $SysConfigController->destroySysConfig(request()->get('SECAO'), request()->get('CHAVE'));
        }

        //Relacionamento ADVOCACIA X USUARIO na tabela SYS_CONFIG
        $SysConfigController->store();

        $data['codigo'] = $this->getModel()->max('codigo') + 1;

        return $data;
    }

    private function validateEmpresaHasStore($data, $SysConfigModel) {
        $empresa = $SysConfigModel->existsEmpresa(['SECAO' => request()->get('SECAO'), 'CHAVE' => request()->get('CHAVE')]);

        if(is_array($empresa) && count($empresa) > 0) return true;
    }

    public function getIdentificadorEmpresa() {
        $SysConfigController = new SysConfigController();
        $SysConfigModel      = $SysConfigController->getModel();
        $empresa             = $SysConfigModel->existsEmpresa(['SECAO' => 'CPANEL', 'CHAVE' => 'CLIENTE_ID']);

        return response()->json($empresa[0]->VALOR);
    }

    public function findEmpresaByCodigo($codigo) {
        return $this->getModel()->find($codigo);
    }

    public function getNomeFirstEmpresa() {
        $empresa = $this->getModel()->getFirstEmpresa();
        return response()->json($empresa[0]->NOME);
    }

    public function getLogoEmpresa() {
        $empresa = $this->getModel()->getFirstEmpresa();

        return response()->json(base64_encode($empresa[0]->LOGO));
    }

    public function storeLogo() {
        $file = request()->get('file');
        
        @list($type, $fileData) = explode(';', $file);
        @list(, $fileData)      = explode(',', $fileData);

        Storage::disk('local')->put("/logo_advocacia/logo_escritorio.png", base64_decode($fileData));

        $temporaryFile = $this->laravelStorageDir . "logo_escritorio.png";
        $destinationFile = env('PATH_LOGO') . "logo_escritorio.png";

        $result = rename($temporaryFile, $destinationFile);

        Storage::disk('local')->delete("/logo_advocacia/logo_escritorio.png");

        return response()->json($result);
    }

}