<?php

namespace App\Models;

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
      $rota = request()->route()->getName();

      $codigoCliente = "-9999";
      if($rota == 'atividades.paginate') {
         $codigoCliente = implode(',', $this->getRelacionamentoClientes());
      }

      $this->setTable('atividade');
      $this->addColumn('CODIGO', true)->setHidden();

      $this->setOrder('A.DATA DESC, A.HORA_INICIO', 'DESC');

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
      return [1, 2, 3];
      $PessoaRelUsuarioModel = new PessoaRelacionamentoUsuarioModel();
      $relacionamentos       = $PessoaRelUsuarioModel->getRelacionamentosUsuario(1);
      $aCodigo = [];

      foreach($relacionamentos as $relacionamento) {
         $aCodigo[] = $relacionamento->pessoa_codigo;
      }

      return $aCodigo;
      // return [1, 2, 3];
   }

   public function addAllColumns() {
      $this->addRawColumn("DATE_FORMAT(A.DATA,'%d/%m/%Y') AS DATA_INICIO")
         ->addRawColumn("A.CODIGO AS CODIGO")
         ->addRawColumn("A.ENVIAR AS ENVIAR")
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
         ->addRawColumn("CO.UF AS COMARCA_UF")
         ->addRawColumn("PC.VALOR_CAUSA")
         ->addRawColumn("PC.DATA_CADASTRO")
         ->addRawColumn("PC.DATA_DISTRIBUICAO")
         ->addRawColumn("
            (
                SELECT COUNT(0) 
                FROM ATIVIDADE_ANEXOS ATV_ANEXO
                JOIN ATIVIDADE
                  ON ATIVIDADE.CODIGO = ATV_ANEXO.CODIGO_ATIVIDADE
                WHERE ATV_ANEXO.CODIGO_ATIVIDADE = A.CODIGO
                  AND ENVIAR = 'S'
            ) AS QTDE_ANEXOS_ATIVIDADE
         ");
   }

    public function getTotalHoras($data_inicial, $data_final) {
        $codigoCliente = implode(',', $this->getRelacionamentoClientes());

        $total_horas = DB::select("
            SELECT time_format( SEC_TO_TIME( SUM( TIME_TO_SEC( tempo ) ) ),'%H:%i:%s')  AS total_horas 
              FROM atividade 
             WHERE CODIGO_CLIENTE IN({$codigoCliente})
               AND data BETWEEN '{$data_inicial}' AND '{$data_final}'
               AND enviar = 'S'
        ");

        return $total_horas;
    }

    public function getQtdeUltimas30DiasAndTodas($parametro) {
      $codigoCliente = implode(',', $this->getRelacionamentoClientes());

      $trinta_dias = DB::select("
          SELECT COUNT(0) qtde_30_dias
            FROM atividade 
           WHERE CODIGO_CLIENTE IN({$codigoCliente})
             AND data BETWEEN '{$parametro->data_inicial}' AND '{$parametro->data_final}'
             AND enviar = 'S'
      ");

      $todas = DB::select("
          SELECT COUNT(0) todas
            FROM atividade 
           WHERE CODIGO_CLIENTE IN({$codigoCliente})
             AND enviar = 'S'
      ");

      return ['trinta_dias' => $trinta_dias, 'todas' => $todas];
    }
}