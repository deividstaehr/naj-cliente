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
      $codigoCliente = implode(',', $this->getRelacionamentoClientes());

      if($codigoCliente == "") {
         $codigoCliente = "-1";
      }

      $this->setTable('atividade');
      $this->addColumn('CODIGO', true)->setHidden();

      $this->setOrder('A.DATA', 'DESC');

      $this->addAllColumns();
      $this->addRawFilter("ENVIAR = 'S'");
      $this->addRawFilter("A.CODIGO_CLIENTE IN ({$codigoCliente})");
      $this->setRawBaseSelect("
               SELECT [COLUMNS]
                 FROM ATIVIDADE A
           INNER JOIN PESSOA P1 
                   ON P1.CODIGO = A.CODIGO_USUARIO            
            LEFT JOIN PRC PC
                   ON PC.CODIGO = A.CODIGO_PROCESSO
            LEFT JOIN PESSOA P3
                  ON P3.CODIGO = PC.CODIGO_CLIENTE
            LEFT JOIN PESSOA P2
                   ON P2.CODIGO = PC.CODIGO_ADVERSARIO
            LEFT JOIN PRC_COMARCA CO 
                   ON CO.CODIGO = PC.CODIGO_COMARCA
            LEFT JOIN PRC_CARTORIO CA 
                   ON CA.CODIGO = PC.CODIGO_CARTORIO
            LEFT JOIN PRC_CLASSE CL 
                   ON CL.CODIGO = PC.CODIGO_CLASSE
      ");
   }

   public function getRelacionamentoClientes() {
      $codigo_usuario = request()->get('filterUser');

      if($codigo_usuario) {
         $codigo_usuario = json_decode(base64_decode($codigo_usuario));
      } else {
         return [];
      }

      $PessoaRelUsuarioModel = new PessoaRelacionamentoUsuarioModel();
      $relacionamentos       = $PessoaRelUsuarioModel->getRelacionamentosUsuarioModuloAtividades($codigo_usuario[0]->val);
      $aCodigo = [];

      foreach($relacionamentos as $relacionamento) {
         $aCodigo[] = $relacionamento->pessoa_codigo;
      }

      $rota = request()->route()->getName();

      //Se for paginate remove o filtro para não add duas vezes
      if($rota == 'atividades.paginate') {
         request()->request->remove('filterUser');
      }

      return $aCodigo;
   }

   public function addAllColumns() {
      $this->addRawColumn("DATE_FORMAT(A.DATA,'%d/%m/%Y') AS DATA_INICIO")
         ->addRawColumn("A.CODIGO AS CODIGO")
         ->addRawColumn("A.ENVIAR AS ENVIAR")
         ->addRawColumn("A.CODIGO_PROCESSO AS CODIGO_PROCESSO")
         ->addRawColumn("DATE_FORMAT(A.DATA_TERMINO,'%d/%m/%Y') AS DATA_TERMINO")
         ->addRawColumn("TIME_FORMAT(A.HORA_INICIO,'%H:%i:%s') AS HORA_INICIO")
         ->addRawColumn("TIME_FORMAT(A.HORA_TERMINO,'%H:%i:%s') AS HORA_TERMINO")
         ->addRawColumn("TIME_FORMAT(A.TEMPO,'%H:%i:%s') AS TEMPO")
         ->addRawColumn("A.HISTORICO AS DESCRICAO")
         ->addRawColumn("P1.NOME AS NOME_USUARIO")
         ->addRawColumn("P3.NOME AS NOME_CLIENTE")
         ->addRawColumn("P2.NOME AS NOME_ADVERSARIO")
         ->addRawColumn("PC.QUALIFICA_ADVERSARIO")
         ->addRawColumn("PC.QUALIFICA_CLIENTE")
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

        if($codigoCliente == "") {
            $codigoCliente = "-1";
         }

        $total_horas = DB::select("
            SELECT time_format(
                      SEC_TO_TIME(
                        SUM(
                           TIME_TO_SEC(tempo)
                        )
                     ),
                   '%H:%i:%s') AS total_horas 
              FROM atividade
             WHERE CODIGO_CLIENTE IN({$codigoCliente})
               AND data BETWEEN '{$data_inicial}' AND '{$data_final}'
               AND enviar = 'S'
        ");

        return $total_horas;
    }

    public function getQtdeUltimas30DiasAndTodas($parametro) {
      $codigoCliente = implode(',', $this->getRelacionamentoClientes());

      if($codigoCliente == "") {
         $codigoCliente = "-1";
      }

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