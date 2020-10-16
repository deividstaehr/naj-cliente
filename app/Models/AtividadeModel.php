<?php

namespace App\Models;

use Auth;
use App\Models\NajModel;
use Illuminate\Support\Facades\DB;
use App\Models\PessoaRelacionamentoUsuarioModel;

/**
 * Modelo das atividades.
 *
 * @since 2020-10-16
 */
class AtividadeModel extends NajModel {

   protected function loadTable() {
      $codigoCliente = implode(',', $this->getRelacionamentoClientes());

      $this->setTable('atividade');
      $this->addColumn('CODIGO', true)->setHidden();
      $this->setOrder('A.DATA');
      $this->addAllColumns();
      $this->addRawFilter("A.CODIGO_CLIENTE IN ({$codigoCliente})");
      $this->setRawBaseSelect("
               SELECT [COLUMNS]
                 FROM ATIVIDADE A
           INNER JOIN PESSOA P1 
                   ON P1.CODIGO = A.CODIGO_USUARIO
            LEFT JOIN PRC PC 
                   ON PC.CODIGO = A.CODIGO_PROCESSO
            LEFT JOIN PRC_COMARCA CO 
                   ON CO.CODIGO = PC.CODIGO_COMARCA
            LEFT JOIN PRC_CARTORIO CA 
                   ON CA.CODIGO = PC.CODIGO_CARTORIO
            LEFT JOIN PRC_CLASSE CL 
                   ON CL.CODIGO = PC.CODIGO_CLASSE
      ");
   }

   public function getRelacionamentoClientes() {
      // $PessoaRelUsuarioModel = new PessoaRelacionamentoUsuarioModel();
      // $relacionamentos       = $PessoaRelUsuarioModel->getRelacionamentosUsuario(Auth::user()->id);
      // $aCodigo = [];

      // foreach($relacionamentos as $relacionamento) {
      //    $aCodigo[] = $relacionamento->pessoa_codigo;
      // }

      request()->request->remove('f');

      // return $aCodigo;
      return [1, 2, 3];
   }

   public function addAllColumns() {
      $this->addRawColumn("DATE_FORMAT(A.DATA,'%d/%m/%Y') AS DATA_INICIO")
         ->addRawColumn("A.CODIGO AS CODIGO")
         ->addRawColumn("A.CODIGO_PROCESSO AS CODIGO_PROCESSO")
         ->addRawColumn("DATE_FORMAT(A.DATA_TERMINO,'%d/%m/%Y') AS DATA_TERMINO")
         ->addRawColumn("DATE_FORMAT(A.HORA_INICIO,'%H:%m:%s') AS HORA_INICIO")
         ->addRawColumn("DATE_FORMAT(A.HORA_TERMINO,'%H:%m:%s') AS HORA_TERMINO")
         ->addRawColumn("DATE_FORMAT(A.TEMPO,'%H:%m:%s') AS TEMPO")
         ->addRawColumn("A.HISTORICO AS DESCRICAO")
         ->addRawColumn("P1.NOME AS NOME_USUARIO")
         ->addRawColumn("PC.NUMERO_PROCESSO")
         ->addRawColumn("PC.NUMERO_PROCESSO_NEW")
         ->addRawColumn("CL.CLASSE")
         ->addRawColumn("CA.CARTORIO")
         ->addRawColumn("CO.COMARCA")
         ->addRawColumn("PC.VALOR_CAUSA")
         ->addRawColumn("PC.DATA_CADASTRO")
         ->addRawColumn("PC.DATA_DISTRIBUICAO")
         ->addRawColumn("
            (
                SELECT COUNT(0) 
                FROM ATIVIDADE_ANEXOS ATV_ANEXO
                WHERE ATV_ANEXO.CODIGO_ATIVIDADE = A.CODIGO
            ) AS QTDE_ANEXOS_ATIVIDADE
         ");
   }

    public function getTotalHoras() {
        $codigoCliente = implode(',', $this->getRelacionamentoClientes());

        $total_horas = DB::select("
            SELECT time_format( SEC_TO_TIME( SUM( TIME_TO_SEC( tempo ) ) ),'%H:%i:%s')  AS total_horas 
              FROM atividade 
             WHERE CODIGO_CLIENTE IN({$codigoCliente})
        ");

        return $total_horas;
    }
}