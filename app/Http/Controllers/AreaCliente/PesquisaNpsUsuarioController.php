<?php

namespace App\Http\Controllers\AreaCliente;

use Auth;
use App\Http\Controllers\NajController;
use App\Models\PesquisaNpsUsuarioModel;
use Illuminate\Support\Facades\DB;

/**
 * Controller da pesquisa NPS usuários.
 *
 * @package    Controllers
 * @subpackage AreaCliente
 * @author     Roberto Oswaldo Klann
 * @since      27/07/2021
 */
class PesquisaNpsUsuarioController extends NajController {

    public function onLoad() {
        $this->setModel(new PesquisaNpsUsuarioModel);
    }

    public function searchsNotReadByUser() {
        return response()->json(
            ['data' => $this->getModel()->searchsNotReadByUser(Auth::user()->id)]
        );
    }

    public function updateLido() {
        $response = request()->get('keys');

        foreach($response as $model) {
            DB::update('UPDATE pesquisa_respostas set lido = ? where id = ?', ['S', $model['id']]);
        }
    }

    public function saveAnswer() {
        $save = $this->getModel()->saveAnswer(request()->all());

        return response()->json(['data' => $save]);
    }

    public function saveNotAnswer() {
        $save = $this->getModel()->saveNotAnswer(request()->all());

        return response()->json(['data' => $save]);
    }

    public function refreshNps() {
        $save = $this->getModel()->refreshNps(request()->all());

        return response()->json(['data' => $save]);
    }
    
}