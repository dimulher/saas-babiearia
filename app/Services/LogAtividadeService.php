<?php

namespace App\Services;

use App\Models\LogAtividade;
use App\Models\Barbearia;
use Illuminate\Support\Facades\Auth;

class LogAtividadeService
{
    /**
     * Records an activity log.
     *
     * @param string $acao The action key (e.g., 'cliente_criado')
     * @param string $descricao Human-readable description
     * @param string|null $modeloTipo The model class name
     * @param int|null $modeloId The model primary key
     * @param array|null $dadosAntigos Previous state
     * @param array|null $dadosNovos New state
     */
    public static function log($acao, $descricao, $modeloTipo = null, $modeloId = null, $dadosAntigos = null, $dadosNovos = null)
    {
        $barbeariaId = Auth::check() ? Auth::user()->barbearia_id : Barbearia::first()->id;

        LogAtividade::create([
            'barbearia_id' => $barbeariaId,
            'user_id' => Auth::id(),
            'acao' => $acao,
            'descricao' => $descricao,
            'modelo_tipo' => $modeloTipo,
            'modelo_id' => $modeloId,
            'dados_antigos' => $dadosAntigos,
            'dados_novos' => $dadosNovos,
            'ip' => request()->ip(),
        ]);
    }
}
