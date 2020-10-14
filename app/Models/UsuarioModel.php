<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use App\Models\NajModel;

class UsuarioModel extends NajModel implements
    JWTSubject,
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract {
    
    use Notifiable, Authenticatable, Authorizable, CanResetPassword, MustVerifyEmail;
    
    protected function loadTable() {
        $this->setTable('usuarios');

        $this->addColumn('id', true);
        $this->addColumn('usuario_tipo_id')->addJoin('usuarios_tipo');
        $this->addColumn('login');
        $this->addColumn('password')->setHidden();
        $this->addColumn('status');
        $this->addColumn('data_inclusao');
        $this->addColumn('data_baixa');
        $this->addColumn('email_recuperacao');
        $this->addColumn('mobile_recuperacao');
        $this->addColumn('nome');
        $this->addColumn('apelido');
        $this->addColumn('cpf');
        $this->addColumn('senha_provisoria');
        $this->addColumn('smtp_host');
        $this->addColumn('smtp_login');
        $this->addColumn('smtp_senha');
        $this->addColumn('smtp_porta');
        $this->addColumn('smtp_ssl');

        $this->addColumnFrom('usuarios_tipo', 'tipo', 'usuario_type_nome');
        
        $this->setOrder('usuarios.status, usuarios.nome');
        
        $this->primaryKey = 'id';
    }

    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims() {
        return [];
    }

    public function setPasswordAttribute($password) {
        if($password !== null && $password !== "" && !(request()->get('tokenInstall')) && !(request()->get('usuarioVeioDoCpanel'))) {
            $this->attributes['password'] = bcrypt($password);
        } else {
            $this->attributes['password'] = $password;
        }
    }

    public function getCodigoGrupoFromPessoa($grupo) {
        return DB::select("
            SELECT codigo
              FROM pessoa_grupo
             WHERE TRUE
               AND grupo LIKE'%{$grupo}%';
        ");
    }
    
}