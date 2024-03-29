<?php

namespace App\Models;

use App\Models\NajModel;
use Illuminate\Support\Facades\DB;

/**
 * Modelo da pesquisa NPS usuários.
 *
 * @package    Models
 * @author     Roberto Oswaldo Klann
 * @since      27/07/2021
 */
class PesquisaNpsUsuarioModel extends NajModel {

    protected function loadTable() {
        $this->setTable('pesquisa_respostas');

        $this->addColumn('id', true);
        $this->addColumn('id_pesquisa')->addJoin('pesquisa_nps_csat');
        $this->addColumn('id_usuario')->addJoin('usuarios');
        $this->addColumn('data_hora_inclusao');
        $this->addColumn('data_hora_exibicao');
        $this->addColumn('data_hora_visualizacao');
        $this->addColumn('count');
        $this->addColumn('data_hora_resposta');
        $this->addColumn('nota');
        $this->addColumn('motivo');
        $this->addColumn('status');
        $this->addColumn('device');
        $this->addColumn('lido');

		$this->setOrder('status2, nota', 'asc', false);

        $this->addAllColumns();

        $this->setRawBaseSelect("
                SELECT [COLUMNS]
                  FROM pesquisa_respostas
                  JOIN pesquisa_nps_csat
                    ON pesquisa_nps_csat.id = pesquisa_respostas.id_pesquisa
                  JOIN usuarios
                    ON usuarios.id = pesquisa_respostas.id_usuario
        ");
    }

    public function addAllColumns() {
        $this->addRawColumn("usuarios.id as usuarioId")
             ->addRawColumn("usuarios.nome AS nomeUsuario")
             ->addRawColumn("
			    (
				  CASE WHEN pesquisa_respostas.status = 'R' THEN '1'
			           WHEN pesquisa_respostas.status = 'N' THEN '2'
			           ELSE '3'
				   END
				) AS status2")
             ->addRawColumn("pesquisa_nps_csat.range_max AS rangeMax")
             ->addRawColumn("pesquisa_nps_csat.descricao AS pesquisaTitulo");
    }

    public function searchsNotReadByUser($userId) {
		$data = date('Y-m-d');

		$search = DB::select("
            SELECT *,
			       pesquisa_respostas.id as id_resposta_nps
              FROM pesquisa_respostas
			  JOIN pesquisa_nps_csat
				ON pesquisa_nps_csat.id = pesquisa_respostas.id_pesquisa
             WHERE TRUE
               AND id_usuario = {$userId}
			   AND status = 'P'
			   AND (
				     data_hora_exibicao BETWEEN '{$data} 00:00:00' AND '{$data} 23:59:59'
					 OR
					 data_hora_inicio BETWEEN '{$data} 00:00:00' AND '{$data} 23:59:59'
				   )
        ");

        return $search;
    }

	public function userHasRelationshipWithSearch($search, $user) {
		return DB::select("
			SELECT *
			  FROM pesquisa_respostas
			 WHERE TRUE
			   AND id_usuario = {$user}
			   AND id_pesquisa = {$search}
		");
	}

	public function pendentesNotRead($pesquisa) {
		return DB::select("
			SELECT count(0) as quantidade
			  FROM pesquisa_respostas
		     WHERE TRUE
			   AND lido = 'N'
			   AND status <> 'P'
			   AND id_pesquisa = {$pesquisa->pesquisa}
		");
	}

	public function saveAnswer($data) {
		$updateValues = [
			'S',
			$data['note'],
			$data['motive'],
			$data['data_hora_resposta'],
			$data['data_hora_visualizacao'],
			$data['amount_open'],
			'R',
			'WEB',
			$data['id_answer_nps']
		];

		$save = DB::update("
			UPDATE pesquisa_respostas set lido = ?, nota = ?, motivo = ?, data_hora_resposta = ?, data_hora_visualizacao = ?,
				   count = ?, status = ?, device = ?
			 WHERE id = ?
			", $updateValues
		);

		return $save;
	}

	public function saveNotAnswer($data) {
		$now = date('Y-m-d H:i');

		$save = DB::update("
			UPDATE pesquisa_respostas set lido = 'S',
			       data_hora_exibicao = DATE_ADD('{$now}', INTERVAL 7 DAY),
				   count = {$data['amount_open']}, status = 'N', device = 'WEB'
			 WHERE id = {$data['id_answer_nps']}
		");

		return $save;
	}

	public function refreshNps($data) {
		$now = date('Y-m-d H:i');

		$save = DB::update("
            UPDATE pesquisa_respostas
               SET data_hora_visualizacao = '{$now}'
             WHERE id = {$data['id_answer_nps']}
       ");

	    return $save;
	}

}