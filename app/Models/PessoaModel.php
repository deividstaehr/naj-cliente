<?php

namespace App\Models;

use App\Models\NajModel;
use Illuminate\Support\Facades\DB;

/**
 * Modelo de Pessoas.
 *
 * @package    Controllers
 * @subpackage NajWeb
 * @author     Roberto Oswaldo Klann
 * @since      23/01/2020
 */
class PessoaModel extends NajModel {
    
    protected function loadTable() {
        $this->setTable('pessoa');
        
        $this->addColumn('CODIGO', true);
        $this->addColumn('CODIGO_DIVISAO');
        $this->addColumn('CODIGO_GRUPO');
        $this->addColumn('NOME');
        $this->addColumn('CPF');
        $this->addColumn('CNPJ');
        $this->addColumn('DATA_NASCTO');
        $this->addColumn('SITUACAO');
        $this->addColumn('DATA_CADASTRO');
        $this->addColumn('TIPO');
        $this->addColumn('ENDERECO_TIPO');
        $this->addColumn('ENDERECO');
        $this->addColumn('NUMERO');
        $this->addColumn('BAIRRO');
        $this->addColumn('COMPLEMENTO');
        $this->addColumn('CIDADE');
        $this->addColumn('UF');
        
        $this->primaryKey = 'CODIGO';
        
    }

    /**
     * Obtêm todas as pessoas que contenham o filtro contido no nome 
     * 
     * @param string $filter
     * @return array
     */
    public function allPessoasInFilter($filter) {
        return DB::select(
            "SELECT codigo AS pessoa_codigo,
                    nome,
                    cpf,
                    cnpj,
                    cidade
               FROM pessoa
              WHERE TRUE
                AND nome LIKE'%{$filter}%'
            "
        );
    }
    
    public function getPessoaByNome($nome) {
        $result = DB::select(
            "SELECT codigo, nome,
                    REPLACE(
                    REPLACE(
                    REPLACE(
                    REPLACE(
                    REPLACE(
                    REPLACE(
                    REPLACE(
                    REPLACE(
                    REPLACE(
                    REPLACE(
                        nome,'Ä','A')
                        ,'ä','a')
                        ,'Ë','E')
                        ,'ë','e')
                        ,'Ï','I')
                        ,'ï','i')
                        ,'Ö','O')
                        ,'ö','o')
                        ,'Ü','u')
                        ,'ü','u'
                ) as nome_sem_formatacao
               FROM pessoa
              WHERE TRUE
                AND tipo = 'F'
                HAVING nome_sem_formatacao LIKE'%{$nome}%'
                LIMIT 1;"
        );
        if(count($result) > 0){
            return $result[0];
        } else {
            return null;
        }
    }

    public function getPessoasUsuarioInFilter($filter) {
        return DB::select(
            "SELECT codigo AS pessoa_codigo,
                    nome,
                    cpf,
                    cnpj,
                    cidade
               FROM pessoa
               JOIN pessoa_usuario
                 ON pessoa_usuario.codigo_pessoa = pessoa.codigo
              WHERE TRUE
                AND nome LIKE'%{$filter}%'
            "
        );
    }

    /**
     * Obtêm pessoa pelo seu cpf
     * 
     * @param string $cpf
     * @return array
     */
    public function getPessoaByCpf($cpf) {
        return DB::select(
            "SELECT *
               FROM pessoa
              WHERE TRUE
                AND cpf = '{$cpf}'
            "
        );
    }
    
    /**
     * Obtêm pessoa pelo seu cnpj
     * 
     * @param string $cpf
     * @return array
     */
    public function getPessoaByCnpj($cnpj) {
        return DB::select(
            "SELECT *
               FROM pessoa
              WHERE TRUE
                AND cpf = '{$cnpj}'
            "
        );
    }

    public function getPessoasFisicaByNome($nome) {
        return DB::select(
            "SELECT codigo AS pessoa_codigo,
                    nome,
                    cpf,
                    cnpj,
                    cidade
               FROM pessoa
              WHERE TRUE
                AND tipo = 'F'
                AND nome LIKE'%{$nome}%'
            "
        );
    }
    
}