<?php

namespace App\Http\Controllers;

use App\Models\Profissional;
use App\Models\Agendamento;
use App\Models\Comanda;
use App\Models\ComandaItem;
use App\Jobs\SyncAgendamentoToGoogleCalendar;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FuncionarioController
{
    public function showLogin()
    {
        // Se já estiver logado como funcionário, vai pro dashboard
        if (session('funcionario_id')) {
            return redirect()->route('funcionario.dashboard');
        }
        return view('funcionario.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|size:6',
        ], [
            'codigo.required' => 'Informe o código de acesso.',
            'codigo.size'     => 'O código deve ter 6 caracteres.',
        ]);

        $profissional = Profissional::where('codigo_acesso', strtoupper(trim($request->codigo)))
            ->where('ativo', true)
            ->first();

        if (!$profissional) {
            return back()->withErrors(['codigo' => 'Código inválido ou funcionário inativo.'])->withInput();
        }

        $profissional->update([
            'is_online' => true,
            'ultimo_login_at' => now(),
        ]);

        session([
            'funcionario_id'   => $profissional->id,
            'funcionario_nome' => $profissional->nome,
        ]);

        return redirect()->route('funcionario.dashboard');
    }

    public function dashboard(Request $request)
    {
        $profissionalId = session('funcionario_id');
        if (!$profissionalId) {
            return redirect()->route('funcionario.login');
        }

        $profissional = Profissional::findOrFail($profissionalId);

        // Garante que o status esteja atualizado caso já estivesse logado antes da nova funcionalidade
        if (!$profissional->is_online) {
            $profissional->update(['is_online' => true, 'ultimo_login_at' => now()]);
        }

        $filtro = $request->get('filtro', 'hoje');

        $query = Agendamento::with(['servico'])
            ->where('profissional_id', $profissionalId);

        match ($filtro) {
            'semana' => $query->whereBetween('data_inicio', [now()->startOfWeek(), now()->endOfWeek()]),
            'todos'  => $query,
            default  => $query->whereDate('data_inicio', today()),
        };

        $agendamentos = $query->orderBy('data_inicio', 'asc')->get();

        return view('funcionario.dashboard', compact('profissional', 'agendamentos', 'filtro'));
    }

    public function logout()
    {
        if ($id = session('funcionario_id')) {
            Profissional::where('id', $id)->update(['is_online' => false]);
        }
        
        session()->forget(['funcionario_id', 'funcionario_nome']);
        return redirect()->route('funcionario.login')->with('success', 'Sessão encerrada com sucesso.');
    }

    public function finalizar(Request $request, Agendamento $agendamento)
    {
        $profissionalId = session('funcionario_id');
        if (!$profissionalId || $agendamento->profissional_id != $profissionalId) {
            abort(403);
        }

        // 1. Atualizar agendamento
        $agendamento->update(['status' => 'concluido']);

        // 2. Criar comanda fechada
        $comanda = Comanda::create([
            'barbearia_id' => $agendamento->barbearia_id,
            'profissional_id' => $agendamento->profissional_id,
            'cliente_id' => $agendamento->cliente_id,
            'agendamento_id' => $agendamento->id,
            'cliente_nome' => $agendamento->cliente_nome ?? ($agendamento->cliente->nome ?? 'Cliente Avulso'),
            'subtotal' => 0,
            'desconto' => 0,
            'total' => 0,
            'forma_pagamento' => 'outro',
            'status' => 'fechada',
            'fechada_em' => now(),
            'observacoes' => 'Comanda gerada via finalização no painel do funcionário.',
        ]);

        // 3. Adicionar o item (serviço) principal
        if ($agendamento->servico_id) {
            ComandaItem::create([
                'comanda_id' => $comanda->id,
                'servico_id' => $agendamento->servico_id,
                'descricao' => $agendamento->servico->nome,
                'quantidade' => 1,
                'preco_unitario' => $agendamento->preco,
                'subtotal' => $agendamento->preco,
            ]);
        }

        $comanda->calcularTotal();

        SyncAgendamentoToGoogleCalendar::dispatch($agendamento->id, 'updated');

        return redirect()->back()->with('success', 'Atendimento finalizado com sucesso!');
    }
}
