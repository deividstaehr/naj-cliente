<?php

namespace App\Models;

use App\Models\NajModel;
use App\Models\PessoaRelacionamentoUsuarioModel;
use Illuminate\Support\Facades\DB;

/**
 * Modelo da agenda do cliente.
 *
 * @package    Models
 * @author     Roberto Oswaldo Klann
 * @since      02/08/2021
 */
class AgendaCompromissoModel extends NajModel {

    protected function loadTable() {
        $this->setTable('agenda');

        $this->addColumn('id', true);
        $this->addColumn('codigo_divisao');
        $this->addColumn('codigo_tipo');
        $this->addColumn('codigo_usuario');
        $this->addColumn('codigo_processo');
        $this->addColumn('id_intimacao');
        $this->addColumn('data_hora_inclusao');
        $this->addColumn('data_hora_compromisso');
        $this->addColumn('data_publicacao');
        $this->addColumn('local');
        $this->addColumn('assunto');
        $this->addColumn('alteracao');
        $this->addColumn('situacao');
        $this->addColumn('privado');

		$this->setOrder('data_hora_inclusao', 'desc');

        $this->addAllColumns();

        // $codigoCliente = implode(',', $this->getRelacionamentoClientes());

        // if ($codigoCliente == "")
        //     $codigoCliente = "-1";

        // $this->addRawFilter("agenda.codigo_usuario IN ({$codigoCliente})");

        $this->setRawBaseSelect("
                SELECT [COLUMNS]
                  FROM agenda
             LEFT JOIN agenda_tipo_compromisso atc
                    ON atc.codigo = agenda.codigo_tipo
             LEFT JOIN prc
                    ON prc.codigo = agenda.codigo_processo
        ");
    }

    public function addAllColumns() {
        $this->addRawColumn("atc.descricao as descricaoTipo")
             ->addRawColumn("DATE_FORMAT(data_hora_inclusao,'%d/%m/%Y') AS data_hora_inclusao")
             ->addRawColumn("DATE_FORMAT(data_hora_compromisso,'%d/%m/%Y') AS data_hora_compromisso");
            //  ->addRawColumn("
			//     (
			// 	  CASE WHEN pesquisa_respostas.status = 'R' THEN '1'
			//            WHEN pesquisa_respostas.status = 'N' THEN '2'
			//            ELSE '3'
			// 	   END
			// 	) AS status2")
            //  ->addRawColumn("pesquisa_nps_csat.range_max AS rangeMax")
            //  ->addRawColumn("pesquisa_nps_csat.descricao AS pesquisaTitulo");
    }

    public function getRelacionamentoClientes($user) {
        $aCodigo = [];

        $PessoaRelUsuarioModel = new PessoaRelacionamentoUsuarioModel();
        $relacionamentos       = $PessoaRelUsuarioModel->getRelacionamentosUsuarioModuloAgenda($user);

        foreach($relacionamentos as $relacionamento)
            $aCodigo[] = $relacionamento->pessoa_codigo;

        return $aCodigo;
    }

    public function allEvents() {
        $filters = json_decode(base64_decode(request()->get('filters')));

        $Sysconfig = new SysConfigModel();
        $hasConfig = $Sysconfig->searchSysConfig('AGENDA_NAJ_CLIENTE', 'TIPO_COMPROMISSO_EXIBIR');
        $conditionCompromisso = '';

        if ($hasConfig)
            $conditionCompromisso = ' AND A.CODIGO_TIPO IN(' . $hasConfig . ') ';

        $codigoCliente = implode(',', $this->getRelacionamentoClientes($filters->user_id));

        if ($codigoCliente == "")
            $codigoCliente = "-1";

        $events = DB::select("
            SELECT A.ID AS ID_COMPROMISSO,
                   DATE_FORMAT(A.DATA_HORA_COMPROMISSO,'%d/%m/%Y') AS DATA,
                   DATE_FORMAT(A.DATA_HORA_COMPROMISSO,'%H:%i:%S') AS HORA,
                   A.ASSUNTO, 
                   A.LOCAL,
                   PC.NUMERO_PROCESSO,
                   PC.NUMERO_PROCESSO_NEW,
                   CL.CLASSE,
                   CA.CARTORIO,
                   CO.COMARCA,
                   PC.VALOR_CAUSA,
                   PC.DATA_CADASTRO,
                   PC.DATA_DISTRIBUICAO,
                   P1.NOME AS NOME_CLIENTE,
                   P2.NOME AS PARTE_CONTRARIA,
                   P3.NOME AS RESPONSAVEL
              FROM AGENDA A
         LEFT JOIN PRC PC ON PC.CODIGO = A.CODIGO_PROCESSO
         LEFT JOIN PESSOA P1 ON P1.CODIGO = PC.CODIGO_CLIENTE
         LEFT JOIN PESSOA P2 ON P2.CODIGO = PC.CODIGO_ADVERSARIO
         LEFT JOIN PESSOA P3 ON P3.CODIGO = A.CODIGO_PESSOA
         LEFT JOIN PRC_COMARCA CO ON CO.CODIGO = PC.CODIGO_COMARCA
         LEFT JOIN PRC_CARTORIO CA ON CA.CODIGO = PC.CODIGO_CARTORIO
         LEFT JOIN PRC_CLASSE CL ON CL.CODIGO = PC.CODIGO_CLASSE
             WHERE (
                    (
                        A.ID IN(
                            SELECT ID_COMPROMISSO
                              FROM AGENDA_MEMBRO
                             WHERE CODIGO_PESSOA IN({$codigoCliente})
                        )
                    )
                    OR 
                    (
                        A.CODIGO_PROCESSO IN(
                            SELECT CODIGO
                              FROM PRC
                             WHERE CODIGO_CLIENTE IN({$codigoCliente})
                                OR CODIGO IN(
                                    SELECT CODIGO_PROCESSO
                                      FROM PRC_GRUPO_CLIENTE
                                     WHERE CODIGO_CLIENTE IN({$codigoCliente})
                                )
                        )

                    )
                )
        {$conditionCompromisso}
          ORDER BY A.DATA_HORA_COMPROMISSO DESC
        ");

        return $events;
    }

}