<?php

namespace App\Http\Controllers;

use App\Models\AgendamentoRecorrente;
use App\Models\Profissional;
use App\Models\Cliente;
use App\Models\Servico;
use App\Models\Barbearia;
use App\Services\LogAtividadeService;
use Illuminate\Http\Request;

class AgendamentoRecorrenteController
{
    public function index()
    {
        $barbeariaId = auth()->user()->barbearia_id;
        $recorrentes = AgendamentoRecorrente::where('barbearia_id', $barbeariaId)->with(['profissional', 'cliente', 'servico'])->latest()->get();
        $profissionais = Profissional::where('barbearia_id', $barbeariaId)->where('ativo', true)->get();
        $clientes = Cliente::where('barbearia_id', $barbeariaId)->get();
        $servicos = Servico::where('barbearia_id', $barbeariaId)->where('ativo', true)->get();
        
        return view('panel.agendamentos-recorrentes', compact('recorrentes', 'profissionais', 'clientes', 'servicos'));
    }

    public function store(Request $request)
    {
        $barbeariaId = auth()->user()->barbearia_id;
        $request->validate([
            'profissional_id' => 'required|exists:profissionais,id',
            'cliente_id' => 'required|exists:clientes,id',
            'servico_id' => 'required|exists:servicos,id',
            'dia_semana' => 'required|integer|min:0|max:6',
            'hora' => 'required',
        ]);

        // Security check
        Profissional::where('barbearia_id', $barbeariaId)->findOrFail($request->profissional_id);
        Cliente::where('barbearia_id', $barbeariaId)->findOrFail($request->cliente_id);
        Servico::where('barbearia_id', $barbeariaId)->findOrFail($request->servico_id);

        $recorrente = AgendamentoRecorrente::create([
            'barbearia_id' => $barbeariaId,
            'profissional_id' => $request->profissional_id,
            'cliente_id' => $request->cliente_id,
            'servico_id' => $request->servico_id,
            'dia_semana' => $request->dia_semana,
            'hora' => $request->hora,
            'ativo' => true,
        ]);

        LogAtividadeService::log('agendamento_recorrente_criado', "Agendamento recorrente criado para {$recorrente->cliente->nome}.", 'AgendamentoRecorrente', $recorrente->id);

        return redirect()->route('panel.agendamentos-recorrentes')->with('success', 'Agendamento recorrente cadastrado com sucesso!');
    }

    public function destroy(AgendamentoRecorrente $recorrente)
    {
        $clienteNome = $recorrente->cliente->nome;
        $recorrente->delete();

        LogAtividadeService::log('agendamento_recorrente_excluido', "Agendamento recorrente de {$clienteNome} removido.", 'AgendamentoRecorrente');

        return redirect()->route('panel.agendamentos-recorrentes')->with('success', 'Agendamento recorrente excluído com sucesso!');
    }

    public function toggle(AgendamentoRecorrente $recorrente)
    {
        $recorrente->update(['ativo' => !$recorrente->ativo]);
        $status = $recorrente->ativo ? 'ativado' : 'desativado';
        
        LogAtividadeService::log('agendamento_recorrente_toggle', "Agendamento recorrente de {$recorrente->cliente->nome} {$status}.", 'AgendamentoRecorrente', $recorrente->id);

        return back()->with('success', "Agendamento recorrente $status com sucesso!");
    }
}
