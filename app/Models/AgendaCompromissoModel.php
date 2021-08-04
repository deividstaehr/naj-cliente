<?php

namespace App\Models;

use App\Models\NajModel;
use App\Models\PessoaRelacionamentoUsuarioModel;

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

        $codigoCliente = implode(',', $this->getRelacionamentoClientes());

        if ($codigoCliente == "")
            $codigoCliente = "-1";

        $this->addRawFilter("agenda.codigo_usuario IN ({$codigoCliente})");

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

    public function getRelacionamentoClientes() {
        $aCodigo = [];

        if (request()->get('codigo_usuario')) {
            $PessoaRelUsuarioModel = new PessoaRelacionamentoUsuarioModel();
            $relacionamentos       = $PessoaRelUsuarioModel->getRelacionamentosUsuarioModuloAgenda(request()->get('codigo_usuario'));
    
            foreach($relacionamentos as $relacionamento)
                $aCodigo[] = $relacionamento->pessoa_codigo;
        }

        return $aCodigo;
    }

}