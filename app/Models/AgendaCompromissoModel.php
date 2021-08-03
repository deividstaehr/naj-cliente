<?php

namespace App\Models;

use App\Models\NajModel;

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

        $this->setRawBaseSelect("
                SELECT [COLUMNS]
                  FROM agenda
                  JOIN agenda_tipo_compromisso atc
                    ON atc.codigo = agenda.codigo_tipo
                  JOIN prc
                    ON prc.codigo = agenda.codigo_processo
        ");
    }

    public function addAllColumns() {
        $this->addRawColumn("atc.descricao as descricaoTipo");
            //  ->addRawColumn("usuarios.nome AS nomeUsuario")
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

}