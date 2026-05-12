<?php

namespace App\Http\Controllers;

use App\Models\HorarioBloqueado;
use App\Models\Profissional;
use App\Models\Barbearia;
use App\Services\LogAtividadeService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BloqueioController
{
    public function index()
    {
        $barbeariaId = auth()->user()->barbearia_id;
        $bloqueios = HorarioBloqueado::where('barbearia_id', $barbeariaId)->with('profissional')->latest()->get();
        $profissionais = Profissional::where('barbearia_id', $barbeariaId)->where('ativo', true)->get();
        return view('panel.bloquear-horarios', compact('bloqueios', 'profissionais'));
    }

    public function store(Request $request)
    {
        $barbeariaId = auth()->user()->barbearia_id;
        $request->validate([
            'profissional_id' => 'nullable|exists:profissionais,id',
            'data_inicio' => 'required|date',
            'hora_inicio' => 'required',
            'data_fim' => 'required|date',
            'hora_fim' => 'required',
            'motivo' => 'nullable|string|max:255',
        ]);

        if ($request->profissional_id) {
            Profissional::where('barbearia_id', $barbeariaId)->findOrFail($request->profissional_id);
        }
        
        $inicio = Carbon::parse($request->data_inicio . ' ' . $request->hora_inicio);
        $fim = Carbon::parse($request->data_fim . ' ' . $request->hora_fim);

        $bloqueio = HorarioBloqueado::create([
            'barbearia_id' => $barbeariaId,
            'profissional_id' => $request->profissional_id,
            'data_inicio' => $inicio,
            'data_fim' => $fim,
            'motivo' => $request->motivo,
        ]);

        $alvo = $bloqueio->profissional ? $bloqueio->profissional->nome : 'Todos os profissionais';
        LogAtividadeService::log('horario_bloqueado', "Horário bloqueado para {$alvo}: {$inicio->format('d/m H:i')} até {$fim->format('d/m H:i')}.", 'HorarioBloqueado', $bloqueio->id);

        return redirect()->route('panel.bloquear-horarios')->with('success', 'Horário bloqueado com sucesso!');
    }

    public function destroy(HorarioBloqueado $bloqueio)
    {
        $alvo = $bloqueio->profissional ? $bloqueio->profissional->nome : 'Todos os profissionais';
        $inicio = $bloqueio->data_inicio->format('d/m H:i');
        
        $bloqueio->delete();

        LogAtividadeService::log('horario_desbloqueado', "Bloqueio removido para {$alvo} ({$inicio}).", 'HorarioBloqueado');

        return redirect()->route('panel.bloquear-horarios')->with('success', 'Bloqueio removido com sucesso!');
    }
}
