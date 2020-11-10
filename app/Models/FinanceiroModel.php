<?php

namespace App\Models;

use App\Models\NajModel;
use Illuminate\Support\Facades\DB;
use App\Models\PessoaRelacionamentoUsuarioModel;

/**
 * Modelo do financeiro.
 *
 * @since 2020-08-10
 */
class FinanceiroModel extends NajModel {

    protected function loadTable() {
        $codigoCliente = implode(',', $this->getRelacionamentoClientes());

        if($codigoCliente == "") {
            $codigoCliente = "-9999";
        }

        $this->setTable('conta');

        $this->addColumn('CODIGO', true)->setHidden();

        $this->setOrder('CP.DATA_VENCIMENTO DESC, CP.CODIGO_CONTA, CP.PARCELA');

        $this->addAllColumns();

        $this->addRawFilter("CP.SITUACAO IN('A','P')");
        $this->addRawFilter("CONTA.CODIGO_PESSOA IN ({$codigoCliente})");
        $this->addRawFilter("((CONTA.TIPO = 'R' AND CONTA.PAGADOR = '2') OR CONTA.TIPO = 'P')");

        $this->setRawBaseSelect("
                SELECT [COLUMNS]
                  FROM CONTA
            INNER JOIN CONTA_PARCELA CP
                    ON CP.CODIGO_CONTA = CONTA.CODIGO
             LEFT JOIN PRC PC
                    ON PC.CODIGO = CONTA.CODIGO_PROCESSO
             LEFT JOIN PESSOA P1
                    ON P1.CODIGO = CONTA.CODIGO_PESSOA
             LEFT JOIN PESSOA P2
                    ON P2.CODIGO = CONTA.CODIGO_ADVERSARIO
             LEFT JOIN PESSOA P3
                    ON P3.CODIGO = PC.CODIGO_ADVERSARIO
             LEFT JOIN PRC_CLASSE CL 
                    ON CL.CODIGO = PC.CODIGO_CLASSE
             LEFT JOIN PRC_CARTORIO CA 
                    ON CA.CODIGO = PC.CODIGO_CARTORIO
             LEFT JOIN PRC_COMARCA CO 
                    ON CO.CODIGO = PC.CODIGO_COMARCA
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
        $relacionamentos       = $PessoaRelUsuarioModel->getRelacionamentosUsuario($codigo_usuario[0]->val);
        $aCodigo = [];

        foreach($relacionamentos as $relacionamento) {
           $aCodigo[] = $relacionamento->pessoa_codigo;
        }

        $rota = request()->route()->getName();

        //Se for paginate remove o filtro para não add duas vezes
        if($rota == 'financeiro/receber/paginate') {
           request()->request->remove('filterUser');
        }

        return $aCodigo;
    }

    public function addAllColumns() {
        $this->addRawColumn("CONTA.CODIGO AS CODIGO_CONTA")
            ->addRawColumn("CONTA.TIPO AS TIPO_CONTA")
            ->addRawColumn("CP.ID AS ID_PARCELA")
            ->addRawColumn("CP.SITUACAO")
            ->addRawColumn("CP.PARCELA AS PARCELA_ATUAL")
            ->addRawColumn("(
                SELECT COUNT(0)
                  FROM CONTA_PARCELA
                 WHERE CODIGO_CONTA = CONTA.CODIGO
            ) AS PARCELA_TOTAL")
            ->addRawColumn("DATE_FORMAT(CP.DATA_VENCIMENTO, '%d/%m/%Y') AS DATA_VENCIMENTO")
            ->addRawColumn("DATE_FORMAT(CP.DATA_PAGAMENTO, '%d/%m/%Y') AS DATA_PAGAMENTO")
            ->addRawColumn("IF (
                CP.VALOR_PARCIAL > 0,
                CP.VALOR_PARCELA - CP.VALOR_PARCIAL,
                CP.VALOR_PARCELA
            ) AS VALOR_PARCELA")
            ->addRawColumn("IF (
                (
                    SELECT SUM(VALOR_PAGAMENTO)
                      FROM CONTA_PARCELA_PARCIAL
                     WHERE ID_PARCELA = CP.ID
                ) > 0, 'SIM', 'NÃO'
            ) AS PAGAMENTOS_PARCIAIS")
            ->addRawColumn("IF (
                CP.DATA_PAGAMENTO IS NOT NULL,
                CP.VALOR_PAGAMENTO, (
                    SELECT SUM(VALOR_PAGAMENTO)
                      FROM CONTA_PARCELA_PARCIAL
                     WHERE ID_PARCELA = CP.ID
                )
            ) AS VALOR_PAGAMENTO")
            ->addRawColumn("P1.NOME AS NOME_CLIENTE")
            ->addRawColumn("IF (
                CONTA.CODIGO_ADVERSARIO IS NOT NULL,
                P2.NOME,
                P3.NOME
            ) AS NOME_ADVERSARIO")
            ->addRawColumn("CONTA.DESCRICAO")
            ->addRawColumn("PC.NUMERO_PROCESSO_NEW")
            ->addRawColumn("PC.NUMERO_PROCESSO")
            ->addRawColumn("CL.CLASSE")
            ->addRawColumn("CO.COMARCA")
            ->addRawColumn("CO.UF")
            ->addRawColumn("CA.CARTORIO");
    }

    public function getTotalPagarTotalReceber() {
        $codigoCliente = implode(',', $this->getRelacionamentoClientes());

        $total_pagar = DB::select("
            SELECT SUM( 
                    IF(CP.VALOR_PARCIAL>0, CP.VALOR_PARCELA-CP.VALOR_PARCIAL, CP.VALOR_PARCELA)
                   ) AS TOTAL_EM_ABERTO,
                   SUM(
                    IF(CP.DATA_PAGAMENTO IS NOT NULL, CP.VALOR_PAGAMENTO,(SELECT SUM(VALOR_PAGAMENTO) 
                        FROM CONTA_PARCELA_PARCIAL WHERE ID_PARCELA=CP.ID)
                    )
                   ) AS TOTAL_PAGO
              FROM CONTA C
        INNER JOIN CONTA_PARCELA CP ON CP.CODIGO_CONTA = C.CODIGO
         LEFT JOIN PRC PC ON PC.CODIGO = C.CODIGO_PROCESSO
         LEFT JOIN PESSOA P1 ON P1.CODIGO = C.CODIGO_PESSOA
         LEFT JOIN PESSOA P2 ON P2.CODIGO = C.CODIGO_ADVERSARIO
         LEFT JOIN PESSOA P3 ON P3.CODIGO = PC.CODIGO_ADVERSARIO
             WHERE CP.SITUACAO IN('A','P')
               AND C.CODIGO_PESSOA IN ({$codigoCliente})
               #PARA CONTAS DA GUIA A PAGAR (QUE O CLIENTE TEM PARA PAGAR PARA O ESCRITÓRIO)
               AND (
                 C.TIPO='R' AND (C.PAGADOR='1' OR C.PAGADOR IS NULL)
               )
          ORDER BY CP.DATA_VENCIMENTO,
                   CP.CODIGO_CONTA,
                   CP.PARCELA ASC
        ");

        $total_receber = DB::select("
            SELECT SUM( 
                       IF(CP.VALOR_PARCIAL>0, CP.VALOR_PARCELA-CP.VALOR_PARCIAL, CP.VALOR_PARCELA)
                   ) AS TOTAL_EM_ABERTO,
                   SUM(
                       IF(CP.DATA_PAGAMENTO IS NOT NULL, CP.VALOR_PAGAMENTO,
                           (
                               SELECT SUM(VALOR_PAGAMENTO) 
                                 FROM CONTA_PARCELA_PARCIAL WHERE ID_PARCELA = CP.ID
                           )
                       )
                   ) AS TOTAL_PAGO
            
              FROM CONTA C
        INNER JOIN CONTA_PARCELA CP ON CP.CODIGO_CONTA = C.CODIGO
         LEFT JOIN PRC PC ON PC.CODIGO = C.CODIGO_PROCESSO
         LEFT JOIN PESSOA P1 ON P1.CODIGO = C.CODIGO_PESSOA
         LEFT JOIN PESSOA P2 ON P2.CODIGO = C.CODIGO_ADVERSARIO
         LEFT JOIN PESSOA P3 ON P3.CODIGO = PC.CODIGO_ADVERSARIO
             WHERE CP.SITUACAO IN('A','P')
               AND C.CODIGO_PESSOA IN ({$codigoCliente})
            #PARA CONTAS DA GUIA A RECEBER (QUE O CLIENTE TEM PARA RECEBER)
              AND (
                   (C.TIPO='R' AND C.PAGADOR='2')
                   OR C.TIPO='P'
              )
         ORDER BY CP.DATA_VENCIMENTO,
                  CP.CODIGO_CONTA,
                  CP.PARCELA ASC
        ");

        return ['pagar' => $total_pagar, 'receber' => $total_receber];
    }

    public function getTotalRecebidoReceberAtrasado($parametro) {
        $codigoCliente = implode(',', $this->getRelacionamentoClientes());

        $total = DB::select("
            SELECT IF(CP.VALOR_PARCIAL>0, 
                    (select sum(CP.VALOR_PARCELA-CP.VALOR_PARCIAL) 
                        from conta_parcela
                        where id=cp.id
                        and data_vencimento < now()
                    )
                    ,(select sum(CP.VALOR_PARCELA) 
                        from conta_parcela
                        where id=cp.id
                        and data_vencimento < now()
                    )
                   ) AS TOTAL_ATRASADO,
                   SUM( 
                    IF(CP.VALOR_PARCIAL>0, CP.VALOR_PARCELA-CP.VALOR_PARCIAL, CP.VALOR_PARCELA)
                   ) AS TOTAL_EM_ABERTO,
                   SUM(
                    IF(CP.DATA_PAGAMENTO IS NOT NULL, CP.VALOR_PAGAMENTO,(SELECT SUM(VALOR_PAGAMENTO) 
                        FROM CONTA_PARCELA_PARCIAL WHERE ID_PARCELA=CP.ID)
                    )
                   ) AS TOTAL_PAGO,
                   DATE_FORMAT(CP.DATA_VENCIMENTO, '%d/%m/%Y') AS DATA_VENCIMENTO,
                   DATE_FORMAT(CP.DATA_PAGAMENTO, '%d/%m/%Y') AS DATA_PAGAMENTO
              FROM CONTA C
        INNER JOIN CONTA_PARCELA CP ON CP.CODIGO_CONTA = C.CODIGO
         LEFT JOIN PRC PC 
                ON PC.CODIGO = C.CODIGO_PROCESSO
         LEFT JOIN PESSOA P1
                ON P1.CODIGO = C.CODIGO_PESSOA
         LEFT JOIN PESSOA P2
                ON P2.CODIGO = C.CODIGO_ADVERSARIO
         LEFT JOIN PESSOA P3 
                ON P3.CODIGO = PC.CODIGO_ADVERSARIO
             WHERE CP.SITUACAO IN('A','P')
               AND C.CODIGO_PESSOA IN ({$codigoCliente})
               AND (
                 C.TIPO = 'R' AND (C.PAGADOR='1' OR C.PAGADOR IS NULL)
               )
               AND (
                 CP.DATA_VENCIMENTO BETWEEN '{$parametro->data_inicial}' AND '{$parametro->data_final}'
                 OR
                 CP.DATA_PAGAMENTO BETWEEN '{$parametro->data_inicial}' AND '{$parametro->data_final}'
               )
          ORDER BY CP.DATA_VENCIMENTO,
                   CP.CODIGO_CONTA,
                   CP.PARCELA ASC
        ");

        return $total;
    }

}