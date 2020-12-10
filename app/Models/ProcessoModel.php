<?php

namespace App\Models;

use App\Models\NajModel;
use Illuminate\Support\Facades\DB;
use App\Models\PessoaRelacionamentoUsuarioModel;

/**
 * Modelo de processos.
 *
 * @since 2020-04-07
 */
class ProcessoModel extends NajModel {

   protected function loadTable() {
      $codigoCliente = implode(',', $this->getRelacionamentoClientes());

      if($codigoCliente == "") {
         $codigoCliente = "-1";
      }

      $this->setTable('prc');
      $this->addColumn('CODIGO', true)->setHidden();
      $this->addColumn('DATA_CADASTRO');
      $this->addColumn('CODIGO_CLIENTE');
      $this->addColumn('QUALIFICA_CLIENTE');
      $this->addColumn('CODIGO_ADVERSARIO');
      $this->addColumn('QUALIFICA_ADVERSARIO');
      $this->addColumn('CODIGO_ADV_CLIENTE');
      $this->addColumn('ID_ORGAO');
      $this->addColumn('NUMERO_PROCESSO_NEW');
      $this->addColumn('GRAU_JURISDICAO');
      $this->addColumn('DATA_DISTRIBUICAO');
      $this->addColumn('VALOR_CAUSA');
      $this->addColumn('CODIGO_CLASSE');
      $this->addColumn('CODIGO_CARTORIO');
      $this->addColumn('ID_AREA_JURIDICA');
      $this->addColumn('CODIGO_COMARCA');
      $this->addColumn('PEDIDOS_PROCESSO');
      $this->addColumn('CODIGO_DIVISAO');
      $this->addColumn('CODIGO_SITUACAO');
      $this->setOrder('SITUACAO, PRC.DATA_CADASTRO');
      $this->addAllColumns();
      $this->addRawFilter("PRC.CODIGO_CLIENTE IN ({$codigoCliente})");
      $this->setRawBaseSelect("
               SELECT [COLUMNS]
               FROM PRC
            LEFT JOIN PESSOA P1
                  ON P1.CODIGO = PRC.CODIGO_CLIENTE
            LEFT JOIN PESSOA P2
                  ON P2.CODIGO = PRC.CODIGO_ADVERSARIO
            LEFT JOIN PESSOA P3
                  ON P3.CODIGO = PRC.CODIGO_RESPONSAVEL
            LEFT JOIN PESSOA P4
                  ON P4.CODIGO = PRC.CODIGO_ADV_CLIENTE
            LEFT JOIN PRC_COMARCA CO
                  ON CO.CODIGO = PRC.CODIGO_COMARCA
            LEFT JOIN PRC_CARTORIO CA
                  ON CA.CODIGO = PRC.CODIGO_CARTORIO
            LEFT JOIN PRC_CLASSE CL
                  ON CL.CODIGO = PRC.CODIGO_CLASSE
      ");
   }

   public function getRelacionamentoClientes() {
      $codigo_usuario = request()->get('f');

      if($codigo_usuario) {
         $codigo_usuario = json_decode(base64_decode($codigo_usuario));
      } else {
         return [];
      }

      $PessoaRelUsuarioModel = new PessoaRelacionamentoUsuarioModel();
      $relacionamentos       = $PessoaRelUsuarioModel->getRelacionamentosUsuarioModuloProcessos($codigo_usuario[0]->val);
      $aCodigo = [];

      foreach($relacionamentos as $relacionamento) {
         $aCodigo[] = $relacionamento->pessoa_codigo;
      }

      $rota = request()->route()->getName();
      //Se for paginate remove o filtro para não add duas vezes
      if($rota == 'processos.paginate') {
         request()->request->remove('f');
      }

      return $aCodigo;
   }

   public function addAllColumns() {
      $this->addRawColumn("PRC.CODIGO AS CODIGO_PROCESSO")
         ->addRawColumn("IF ((
               SELECT ATIVO
               FROM PRC_SITUACAO
               WHERE CODIGO = PRC.CODIGO_SITUACAO
            ) = 'S', 'EM ANDAMENTO', 'ENCERRADO') AS SITUACAO")
         ->addRawColumn("P1.NOME AS NOME_CLIENTE")
         ->addRawColumn("P1.CODIGO AS CODIGO_CLIENTE")
         ->addRawColumn("PRC.QUALIFICA_CLIENTE")
         ->addRawColumn("(
            SELECT COUNT(0)
               FROM PRC_GRUPO_CLIENTE PGC
               WHERE PGC.CODIGO_PROCESSO = PRC.CODIGO
         ) AS QTDE_CLIENTES")
         ->addRawColumn("P2.NOME AS NOME_ADVERSARIO")
         ->addRawColumn("P2.CODIGO AS CODIGO_ADVERSARIO")
         ->addRawColumn("PRC.QUALIFICA_ADVERSARIO")
         ->addRawColumn("(
            SELECT COUNT(0)
               FROM PRC_GRUPO_ADVERSARIO PGA
               WHERE PGA.CODIGO_PROCESSO = PRC.CODIGO
         ) AS QTDE_ADVERSARIOS")
         ->addRawColumn("(
            SELECT COUNT(0) 
              FROM PRC_ANEXOS
             WHERE PRC_ANEXOS.CODIGO_PROCESSO = PRC.CODIGO
               AND PRC_ANEXOS.SERVICOS_CLIENTE = 'S'
         ) AS QTDE_ANEXOS_PROCESSO")
         ->addRawColumn("(
            SELECT COUNT(0) 
              FROM ATIVIDADE
             WHERE ATIVIDADE.CODIGO_PROCESSO = PRC.CODIGO
               AND ENVIAR = 'S'
         ) AS QTDE_ATIVIDADE_PROCESSO")
         ->addRawColumn("P3.NOME AS NOME_RESPONSAVEL")
         ->addRawColumn("P3.CODIGO AS CODIGO_RESPONSAVEL")
         ->addRawColumn("P4.NOME AS NOME_ADVOGADO")
         ->addRawColumn("P4.CODIGO AS CODIGO_ADVOGADO")
         ->addRawColumn("(
            SELECT DESCRICAO_ANDAMENTO
               FROM PRC_MOVIMENTO
               WHERE CODIGO_PROCESSO = PRC.CODIGO
            ORDER BY DATA DESC, ID DESC
               LIMIT 1
         ) AS ULTIMO_ANDAMENTO_DESCRICAO")
         ->addRawColumn("(
            SELECT DATE_FORMAT(DATA, '%d/%m/%Y')
               FROM PRC_MOVIMENTO
               WHERE CODIGO_PROCESSO = PRC.CODIGO
            ORDER BY DATA DESC, ID DESC
               LIMIT 1
         ) AS ULTIMO_ANDAMENTO_DATA")
         ->addRawColumn("(
            SELECT HISTORICO
               FROM ATIVIDADE
               WHERE CODIGO_PROCESSO = PRC.CODIGO
            ORDER BY DATA DESC, CODIGO DESC
               LIMIT 1
         ) AS ULTIMA_ATIVIDADE_DESCRICAO")
         ->addRawColumn("(
            SELECT DATE_FORMAT(DATA, '%d/%m/%Y')
               FROM ATIVIDADE
               WHERE CODIGO_PROCESSO = PRC.CODIGO
            ORDER BY DATA DESC, CODIGO DESC
               LIMIT 1
         ) AS ULTIMA_ATIVIDADE_DATA")
         ->addRawColumn("P2.NOME AS NOME_ADVERSARIO")
         ->addRawColumn("PRC.NUMERO_PROCESSO_NEW")
         ->addRawColumn("PRC.NUMERO_PROCESSO")
         ->addRawColumn("CL.CLASSE")
         ->addRawColumn("CA.CARTORIO")
         ->addRawColumn("CO.COMARCA")
         ->addRawColumn("CO.UF AS COMARCA_UF")
         ->addRawColumn("DATE_FORMAT(PRC.DATA_CADASTRO,'%d/%m/%Y') AS DATA_CADASTRO")
         ->addRawColumn("DATE_FORMAT(PRC.DATA_DISTRIBUICAO,'%d/%m/%Y') AS DATA_DISTRIBUICAO")
         ->addRawColumn("PRC.VALOR_CAUSA");
   }

   public function getPartes($key) {
      $key = json_decode(base64_decode($key));

      $sql = "
      (
         SELECT P1.NOME,
                PGC.QUALIFICACAO,
                P1.CODIGO
           FROM PRC_GRUPO_CLIENTE PGC
           JOIN PESSOA P1
             ON P1.CODIGO = PGC.CODIGO_CLIENTE
          WHERE PGC.CODIGO_PROCESSO = ?
       ORDER BY P1.NOME
      )
      UNION
      (
         SELECT P1.NOME,
                PGA.QUALIFICACAO,
                P1.CODIGO
           FROM PRC_GRUPO_ADVERSARIO PGA
           JOIN PESSOA P1
             ON P1.CODIGO = PGA.CODIGO_ADVERSARIO
          WHERE PGA.CODIGO_PROCESSO = ?
       ORDER BY P1.NOME
      )";

      $result = DB::select($sql, [$key->codigo, $key->codigo]);

      return $result;
   }

   public function getParteCliente($key) {
      $key = json_decode(base64_decode($key));

      $sql = "
         SELECT P1.NOME,
                PGC.QUALIFICACAO,
                P1.CODIGO
           FROM PRC_GRUPO_CLIENTE PGC
           JOIN PESSOA P1
             ON P1.CODIGO = PGC.CODIGO_CLIENTE
          WHERE PGC.CODIGO_PROCESSO = ?
       ORDER BY P1.NOME
      ";

      $result = DB::select($sql, [$key->codigo, $key->codigo]);

      return $result;
   }

   public function getParteAdversaria($key) {
      $key = json_decode(base64_decode($key));

      $sql = "
         SELECT P1.NOME,
                PGA.QUALIFICACAO,
                P1.CODIGO
           FROM PRC_GRUPO_ADVERSARIO PGA
           JOIN PESSOA P1
             ON P1.CODIGO = PGA.CODIGO_ADVERSARIO
          WHERE PGA.CODIGO_PROCESSO = ?
       ORDER BY P1.NOME";

      $result = DB::select($sql, [$key->codigo, $key->codigo]);

      return $result;
   }

   public function anexos($key) {
      $sql = "
         SELECT prc_anexos.*
           FROM prc
           JOIN prc_anexos
             ON prc_anexos.codigo_processo = prc.codigo
          WHERE TRUE
            AND prc.codigo = {$key}
      ";

      $result = DB::select($sql);

      return $result;
   }
   
   /**
     * Obtêm os registros de "prc_qualificacao"
     * 
     * @return JSON
     */
    public function getPrcQualificacao(){
        $sql = "
            SELECT * FROM prc_qualificacao
        ";

        $result = DB::select($sql);

        return $result;
    }
   
    /**
     * Obtêm os registros de "prc_orgao"
     * 
     * @return JSON
     */
    public function getPrcOrgao(){
        $sql = "
            SELECT ID, ORGAO FROM prc_orgao;
        ";

        $result = DB::select($sql);

        return $result;
    }
    
    /**
     * Obtêm os registros de "prc_situacao"
     * 
     * @return JSON
     */
    public function getPrcSituacao(){
        $sql = "
            SELECT CODIGO, SITUACAO FROM prc_situacao;
        ";

        $result = DB::select($sql);

        return $result;
    }

    public function getQtdeAtivoBaixado() {
        $codigoCliente = implode(',', $this->getRelacionamentoClientes());

         if($codigoCliente == "") {
            $codigoCliente = "-1";
         }

        $response = DB::select("
            SELECT COUNT(0) AS QTDE,
                   IF((SELECT ATIVO FROM PRC_SITUACAO WHERE CODIGO = PC.CODIGO_SITUACAO) = 'S','EM ANDAMENTO','ENCERRADO') AS SITUACAO
              FROM PRC PC
             WHERE PC.CODIGO_CLIENTE IN({$codigoCliente})
                OR PC.CODIGO IN (
                                 SELECT CODIGO_PROCESSO
                                   FROM PRC_GRUPO_CLIENTE
                                  WHERE CODIGO_CLIENTE IN({$codigoCliente})
                             )
          GROUP BY SITUACAO
        ");

        return $response;
    }
}