<?php

namespace App\Http\Controllers\AreaCliente;

use Hash;
use App\Models\UsuarioModel;
use App\Http\Controllers\NajController;
use App\Http\Controllers\AreaCliente\PessoaController;
use App\Http\Controllers\AreaCliente\PessoaClienteController;
use App\Http\Controllers\AreaCliente\PessoaUsuarioController;
use App\Http\Controllers\AreaCliente\PessoaRelacionamentoUsuario;
use App\Http\Controllers\AreaCliente\PessoaUsuario;
use App\Http\Controllers\AreaCliente\GrupoPessoaController;
use App\Http\Controllers\Api\UsuarioApiController;

/**
 * Controller dos Usuários.
 *
 * @package    Controllers
 * @subpackage AreaCliente
 * @author     Roberto Oswaldo Klann
 * @since      09/01/2020
 */
class UsuarioController extends NajController {

    const USER_TYPE_SUPERVISOR = 0;
    const USER_TYPE_ADMIN      = 1;
    const USER_TYPE_USER       = 2;
    const USER_TYPE_CLIENT     = 3;
    const USER_TYPE_PARTNER    = 4;

    public function onLoad() {
        $this->setModel(new UsuarioModel);
    }

    protected function resolveWebContext($usuarios, $code) {
        return view('najWeb.usuario');
    }

    /**
     * Index da rota de usuários.
     */
    public function index() {
        return view('najWeb.consulta.UsuarioConsultaView')->with('is_usuarios', true);
    }

    public function perfil() {
        return view('areaCliente.manutencao.PerfilUsuarioManutencaoView');
    }

    public function smtp() {
        return view('areaCliente.manutencao.SmtpUsuarioManutencaoView')->with('is_usuarios', true);
    }

    public function proximo() {
        $proximo = $this->getModel()->max('id');

        return response()->json($proximo);
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
        switch($model['usuario_tipo_id']) {
            case self::USER_TYPE_SUPERVISOR:
            case self::USER_TYPE_ADMIN:
            case self::USER_TYPE_USER:
            case self::USER_TYPE_PARTNER:
                $this->afterStoreUserByType($model);
                break;
            case self::USER_TYPE_CLIENT:
                $this->afterStoreUserTypeClient($model);
                break;
        }
    }

    public function updateItems($model) {}

    public function destroyItems($model) {}

    public function update($key) {
        $toUpdate = $this->resolveValidate(
            $this->getModel()->getFilledAttributes()
        );

        $codigoPessoa = request()->all()['items'][0]['pessoa_codigo'];
        $toUpdate['najWeb'] = 1;
        $toUpdate['codigo_pessoa'] = request()->all()['codigo_pessoa'];
        $toUpdate['pessoa_codigo'] = $codigoPessoa;

        $UsuarioApiController = new UsuarioApiController();
        $result               = $UsuarioApiController->update($toUpdate, $key);
        $response             = json_decode($result->getBody()->getContents());

        if (!isset($response->status_code) || $response->status_code != '200') {
            return $this->resolveResponse(['mensagem' => $response->naj->mensagem], 400);
        }

        if(request()->get('password')) {
            request()->merge(['senha_provisoria' => 'S']);
        }

        return parent::update($key);
    }

    public function validateStore($data) {
        $this->validaSeExisteUsuarioComCpfInformado($data);

        $codigoPessoa   = request()->all()['items'][0]['pessoa_codigo'];
        $data['najWeb'] = 1;
        $data['usuarioVeioDoCpanel'] = isset(request()->all()['usuarioVeioDoCpanel']);
        $data['codigo_pessoa'] = request()->all()['codigo_pessoa'];
        $data['pessoa_codigo'] = request()->all()['codigo_pessoa'];
        $data['items'] = 
        [        
            [
                'pessoa_codigo' => $codigoPessoa,
                'usuario_id'    => (isset($data['id'])) ? $data['id'] : 0
            ]
        ];

        $UsuarioApiController = new UsuarioApiController();
        $result = '';

        //Se for a rotina de INSTALL do sistema, então chama um método diferente na inclusão.
        if(request()->get('tokenInstall')) {
            $result = $UsuarioApiController->storeUserByInstall(request()->get('tokenInstall'), $data);
        } else {
            $result = $UsuarioApiController->store($data);
        }
        
        $response = json_decode($result->getBody()->getContents());

        if(!isset($response->status_code) || $response->status_code != '200') {
            $this->throwException($response->naj->mensagem);
        }

        $data['id'] = $response->naj->model->id;

        if(request()->get('tokenInstall') || isset(request()->all()['usuarioVeioDoCpanel'])) {
            $data['password'] = $response->naj->model->password;
        }

        return $data;
    }

    private function afterStoreUserByType($model) {
        $data = [];
        if($model['cpf']) $data = $this->getModel()->getDataByColumn('cpf', $model['cpf'], 'pessoa');

        if(is_array($data) && count($data) > 1) {
            $this->throwException('Registro não inserido, existe duas pessoas com o mesmo CPF.');
        }

        if(!$data) {
            $model['codigo_divisao'] = '1';
            $model['codigo_grupo']   = $this->getCodigoGrupoFromPessoa(($model['usuario_tipo_id'] == 3) ? 'teste' : 'usuario');

            $pessoa = $this->storePessoa($model);
            $model['pessoa_codigo'] = $pessoa->original['model']['CODIGO'];
            $model['codigo_pessoa'] = $pessoa->original['model']['CODIGO'];
        } else {
            $model['pessoa_codigo'] = $data[0]->CODIGO;
            $model['codigo_pessoa'] = $data[0]->CODIGO;
        }

        $this->storePessoaRelUsers($model, $data);
        $this->storePessoaUser($model, $data);
    }

    private function afterStoreUserTypeClient($model) {
        $data = $this->getModel()->getDataByColumn('cpf', $model['cpf'], 'pessoa');

        if(!$data) {
            $model['codigo_divisao'] = '1';
            $model['codigo_grupo']   = $this->getCodigoGrupoFromPessoa('cliente');

            $pessoa = $this->storePessoa($model);
            $model['pessoa_codigo'] = $pessoa->original['model']['CODIGO'];
        } else {
            $model['pessoa_codigo'] = $data[0]->CODIGO;
        }

        $this->storePessoaClient($model, $data);
    }

    private function storePessoa($model) {
        $PessoaController = new PessoaController();

        $atributos = [
            'CODIGO'         => ($PessoaController->proximo() + 1),
            'DATA_CADASTRO'  => $model['data_inclusao'],
            'TIPO'           => 'F',
            'SITUACAO'       => 'A',
            'NOME'           => $model['nome'],
            'CODIGO_DIVISAO' => $model['codigo_divisao'],
            'CODIGO_GRUPO'   => $model['codigo_grupo'],
            'CPF'            => $model['cpf']
        ];

        return $PessoaController->store($atributos);
    }

    private function storePessoaRelUsers($model, $data) {
        $PessoaRelUsuarioController = new PessoaRelacionamentoUsuarioController();
        $response = $PessoaRelUsuarioController->store(['pessoa_codigo' => $model['pessoa_codigo'], 'usuario_id' => $model['id']]);

        if(!isset($response->original['model'])) {
            $this->throwException('Registro não inserido, tente novamente.');
        }
    }

    private function storePessoaUser($model, $data) {        
        $PessoaUsuarioController = new PessoaUsuarioController();
        $response = $PessoaUsuarioController->getModel()->getDataByColumn('codigo_pessoa', $model['codigo_pessoa'], 'pessoa_usuario');

        //Se já existe o relacionamento não faz nada.
        if($response) {
            return;
        }

        $response = $PessoaUsuarioController->store(
            [
                'codigo_pessoa' => $model['codigo_pessoa'],
                'perfil'        => $this->getConvertTypeUser($model['usuario_tipo_id']),
                'externo'       => 'S',
                'situacao'      => 'A',
                'email_origem'  => $model['email_recuperacao']
            ]
        );

        if(!isset($response->original['model'])) {
            $this->throwException('Registro não inserido, tente novamente.');
        }
    }

    private function storePessoaClient($model) {
        $PessoaClienteController = new PessoaClienteController();
        $response = $PessoaClienteController->store(
            [
                'pessoa_codigo' => $model['pessoa_codigo'],
                'usuario_id'    => $model['id']
            ]
        );

        if(!isset($response->original['model'])) {
            $this->throwException('Registro não inserido, tente novamente.');
        }
    }

    private function getCodigoGrupoFromPessoa($nomeGrupo) {
        $grupo = $this->getModel()->getCodigoGrupoFromPessoa($nomeGrupo);

        if(!$grupo) {
            $GrupoPessoaController = new GrupoPessoaController();
            $GrupoPessoa           = $GrupoPessoaController->store(
                [
                    'codigo'    => ($GrupoPessoaController->proximo() + 1),
                    'grupo'     => $nomeGrupo,
                    'principal' => 'S'
                ]
            );

            return $GrupoPessoa->original['model']['codigo'];
        }

        return $grupo[0]->codigo;
    }

    public function getUserByCpfInCpanel($cpf) {
        $UsuarioApiController = new UsuarioApiController();

        $result   = $UsuarioApiController->getUserByCpf($cpf);
        $response = json_decode($result->getBody()->getContents());

        if($response) {
            return response()->json($response);
        }
    }

    protected function getConvertTypeUser($tipo_usuario) {
        switch($tipo_usuario) {
            case self::USER_TYPE_ADMIN:
            case self::USER_TYPE_USER:
                return 'U';
            case self::USER_TYPE_PARTNER:
                return 'P';
            case self::USER_TYPE_CLIENT:
                return 'C';
            default:
                return 'S';
        }
    }

    private function validaSeExisteUsuarioComCpfInformado($model) {
        $data = [];
        if($model['cpf']) $data = $this->getModel()->getDataByColumn('cpf', $model['cpf'], 'usuarios');

        if(is_array($data) && count($data) > 0) {
            $this->throwException('Registro não inserido, este CPF já está sendo usado!');
        }
    }

    public function updatePassword($id) {
        $UsuarioApiController = new UsuarioApiController();
        $result   = $UsuarioApiController->updatePassword($id, request()->all());
        $response = json_decode($result->getBody()->getContents());

        if(!isset($response->status_code) || $response->status_code != '200') {
            if($response->mensagem == 'Senha antiga incorreta') {
                return $this->resolveResponse(['mensagem' => 'Senha antiga incorreta!'], 200);
            }
            $this->resolveResponse(['mensagem' => $response->naj->mensagem], 200);
        }

        $parameters        = json_decode(base64_decode($id));
        $usuario           = $this->getModel()->find($parameters->id);
        $usuario->password = $response->password;
        $result = $usuario->save();

        if($result) {
            return $this->resolveResponse(['mensagem' => 'Senha alterada com sucesso!'], 200);
        }

        return $this->resolveResponse(['mensagem' => 'Não foi possível alterar a senha, tente novamente mais tarde!'], 200);
    }

    public function updateSenhaProvisora($id) {
        $parameters = json_decode(base64_decode($id));
        $usuario    = $this->getModel()->find($parameters->id);

        $usuario->senha_provisoria = 'N';
        $result = $usuario->save();

        if($result) {
            return $this->resolveResponse(['mensagem' => 'Status da senha provisoria alterado com sucesso!'], 200);
        }

        return $this->resolveResponse(['mensagem' => 'Não foi possível alterar o status da senha provisória, tente novamente mais tarde!'], 200);
    }

    public function smtpUpdate($id) {
        $parameters = json_decode(base64_decode($id));
        $usuario    = $this->getModel()->find($parameters->id);

        $usuario->smtp_host  = request()->get('smtp_host');
        $usuario->smtp_login = request()->get('smtp_login');
        $usuario->smtp_senha = request()->get('smtp_senha');
        $usuario->smtp_porta = request()->get('smtp_porta');
        $usuario->smtp_ssl   = request()->get('smtp_ssl');
        $result = $usuario->save();

        if($result) {
            return $this->resolveResponse(['mensagem' => 'E-mail configurado com sucesso!', 'status_code' => 200], 200);
        }

        return $this->resolveResponse(['mensagem' => 'Não foi possível realizar a configuração do E-mail, tente novamente mais tarde!', 'status_code' => 400], 200);
    }

    public function atualizarDados($key) {
        $parameters = json_decode(base64_decode($key));
        $usuario    = $this->getModel()->find($parameters->id);

        $UsuarioApiController = new UsuarioApiController();
        $result   = $UsuarioApiController->atualizarDados($key, request()->all());
        $response = json_decode($result->getBody()->getContents());

        if(!isset($response->status_code) || $response->status_code != '200') {
            $this->resolveResponse(['mensagem' => $response->naj->mensagem], 200);
        }

        if(!isset($response->naj->is_update)) {
            request()->merge(['usuarioVeioDoCpanel' => false]);
        }

        $usuario->id                 = $response->naj->model->id;
        $usuario->password           = $response->naj->model->password;
        $usuario->nome               = request()->get('nome');
        $usuario->cpf                = request()->get('cpf');
        $usuario->apelido            = request()->get('apelido');
        $usuario->login              = request()->get('login');
        $usuario->email_recuperacao  = request()->get('email_recuperacao');
        $usuario->mobile_recuperacao = request()->get('mobile_recuperacao');
        $usuario->senha_provisoria = 'N';

        $result = $usuario->save();

        if($result) {
            return $this->resolveResponse(['mensagem' => 'Atualização realizada com sucesso!', 'status_code' => 200], 200);
        }

        return $this->resolveResponse(['mensagem' => 'Não foi possível alterar os dados, tente novamente mais tarde!', 'status_code' => 400], 200);
    }

}