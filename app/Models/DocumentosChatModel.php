<?php

namespace App\Models;

use App\Models\NajModel;
use Illuminate\Support\Facades\DB;
use App\Models\PessoaRelacionamentoUsuarioModel;

/**
 * Modelo de documentos.
 *
 * @since 2020-08-11
 */
class DocumentosChatModel extends NajModel {

   protected function loadTable() {
      $this->setTable('pessoa_anexo');

      $this->addColumn('id', true);
      $this->addColumn('id_dir');
      $this->addColumn('codigo_pessoa');
   }

   public function getRelacionamentoClientes() {
      // $queryFilters = request()->query('f');
      // $filterParse  = json_decode(base64_decode($queryFilters));
      $PessoaRelUsuarioModel = new PessoaRelacionamentoUsuarioModel();
      $relacionamentos       = $PessoaRelUsuarioModel->getRelacionamentosUsuario($filterParse->id_usuario_cliente);
      // $aCodigo = [];

      // foreach($relacionamentos as $relacionamento) {
      //    $aCodigo[] = $relacionamento->pessoa_codigo;
      // }

      request()->request->remove('f');

      // return $aCodigo;
      return [1, 2, 3];
   }

   public function documentos($key) {
      $filterParse  = json_decode(base64_decode($key));
      $PessoaRelUsuarioModel = new PessoaRelacionamentoUsuarioModel();
      $relacionamentos       = $PessoaRelUsuarioModel->getRelacionamentosUsuario($filterParse->id_usuario_cliente);
      $relacionamentos       = [276, 274];
      $aDocumentos           = [];

      foreach($relacionamentos as $relacionamento) {
         $aDoc = $this->getDocumentosByPessoa($relacionamento);
         if(count($aDoc) > 0) {
            $aDocumentos[] = $aDoc;
         }
      }

      return $aDocumentos;
   }

   private function getDocumentosByPessoa($codigo) {
      $sql = "
         SELECT *
           FROM pessoa
           JOIN pessoa_anexos
             ON pessoa_anexos.codigo_pessoa = pessoa.codigo
          WHERE TRUE
            AND pessoa.codigo = {$codigo}
      ";

      return DB::select($sql);
   }

}