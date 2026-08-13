<?php

namespace App\Http\Controllers;

use App\Models\Profissional;
use App\Models\Agendamento;
use App\Models\Comanda;
use App\Models\ComandaItem;
use App\Models\HorarioBloqueado;
use App\Models\Expediente;
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

        $base = Agendamento::where('profissional_id', $profissionalId);
        $agendamentosHoje     = (clone $base)->whereDate('data_inicio', today())->count();
        $agendamentosSemana   = (clone $base)->whereBetween('data_inicio', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $agendamentosMes      = (clone $base)->whereMonth('data_inicio', now()->month)->whereYear('data_inicio', now()->year)->count();
        $agendamentosPendentes = (clone $base)->whereIn('status', ['pendente', 'confirmado'])->count();

        return view('funcionario.dashboard', compact(
            'profissional', 'agendamentos', 'filtro',
            'agendamentosHoje', 'agendamentosSemana', 'agendamentosMes', 'agendamentosPendentes'
        ));
    }

    public function logout()
    {
        if ($id = session('funcionario_id')) {
            Profissional::where('id', $id)->update(['is_online' => false]);
        }
        
        session()->forget(['funcionario_id', 'funcionario_nome']);
        return redirect()->route('funcionario.login')->with('success', 'Sessão encerrada com sucesso.');
    }

    public function agendamentos(Request $request)
    {
        $profissionalId = session('funcionario_id');
        if (!$profissionalId) return redirect()->route('funcionario.login');

        $profissional = Profissional::findOrFail($profissionalId);
        $filtro  = $request->get('filtro', 'hoje');
        $status  = $request->get('status', '');

        $query = Agendamento::with(['servico'])
            ->where('profissional_id', $profissionalId);

        match ($filtro) {
            'semana' => $query->whereBetween('data_inicio', [now()->startOfWeek(), now()->endOfWeek()]),
            'mes'    => $query->whereMonth('data_inicio', now()->month)->whereYear('data_inicio', now()->year),
            'todos'  => $query,
            default  => $query->whereDate('data_inicio', today()),
        };

        if ($status) $query->where('status', $status);

        $agendamentos = $query->orderBy('data_inicio', 'asc')->get();

        return view('funcionario.agendamentos', compact('profissional', 'agendamentos', 'filtro', 'status'));
    }

    public function bloqueios(Request $request)
    {
        $profissionalId = session('funcionario_id');
        if (!$profissionalId) return redirect()->route('funcionario.login');

        $profissional = Profissional::findOrFail($profissionalId);
        $bloqueios = HorarioBloqueado::where('profissional_id', $profissionalId)
            ->orderBy('data_inicio', 'asc')
            ->get();

        return view('funcionario.bloquear-horarios', compact('profissional', 'bloqueios'));
    }

    public function storeBloqueio(Request $request)
    {
        $profissionalId = session('funcionario_id');
        if (!$profissionalId) return redirect()->route('funcionario.login');

        $profissional = Profissional::findOrFail($profissionalId);

        $request->validate([
            'data_inicio' => 'required|date',
            'hora_inicio' => 'required',
            'data_fim'    => 'required|date',
            'hora_fim'    => 'required',
            'motivo'      => 'nullable|string|max:255',
        ]);

        HorarioBloqueado::create([
            'barbearia_id'    => $profissional->barbearia_id,
            'profissional_id' => $profissionalId,
            'data_inicio'     => Carbon::parse($request->data_inicio . ' ' . $request->hora_inicio),
            'data_fim'        => Carbon::parse($request->data_fim . ' ' . $request->hora_fim),
            'motivo'          => $request->motivo,
        ]);

        return redirect()->route('funcionario.bloqueios')->with('success', 'Horário bloqueado com sucesso!');
    }

    public function destroyBloqueio(HorarioBloqueado $bloqueio)
    {
        $profissionalId = session('funcionario_id');
        if (!$profissionalId || $bloqueio->profissional_id != $profissionalId) abort(403);

        $bloqueio->delete();

        return redirect()->route('funcionario.bloqueios')->with('success', 'Bloqueio removido com sucesso!');
    }

    public function horarios(Request $request)
    {
        $profissionalId = session('funcionario_id');
        if (!$profissionalId) return redirect()->route('funcionario.login');

        $profissional = Profissional::with('expedientes')->findOrFail($profissionalId);

        return view('funcionario.horarios', compact('profissional'));
    }

    public function storeHorarios(Request $request)
    {
        $profissionalId = session('funcionario_id');
        if (!$profissionalId) return redirect()->route('funcionario.login');

        $profissional = Profissional::findOrFail($profissionalId);

        $request->validate([
            'expedientes'              => 'required|array|size:7',
            'expedientes.*.dia_semana' => 'required|integer|min:0|max:6',
        ]);

        foreach ($request->expedientes as $item) {
            Expediente::updateOrCreate(
                [
                    'barbearia_id'    => $profissional->barbearia_id,
                    'profissional_id' => $profissionalId,
                    'dia_semana'      => $item['dia_semana'],
                ],
                [
                    'hora_inicio'       => $item['hora_inicio'] ?? '08:00',
                    'hora_fim'          => $item['hora_fim'] ?? '18:00',
                    'intervalo_inicio'  => $item['intervalo_inicio'] ?? null,
                    'intervalo_fim'     => $item['intervalo_fim'] ?? null,
                    'ativo'             => isset($item['ativo']) && $item['ativo'],
                ]
            );
        }

        return redirect()->route('funcionario.horarios')->with('success', 'Horários atualizados com sucesso!');
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

    public function configuracoes()
    {
        $profissionalId = session('funcionario_id');
        if (!$profissionalId) {
            return redirect()->route('funcionario.login');
        }
        $profissional = Profissional::findOrFail($profissionalId);
        return view('funcionario.configuracoes', compact('profissional'));
    }

    public function updateConfiguracoes(Request $request)
    {
        $profissionalId = session('funcionario_id');
        if (!$profissionalId) {
            return redirect()->route('funcionario.login');
        }

        $request->validate([
            'telefone'    => 'nullable|string|max:20',
            'foto_base64' => 'nullable|string|max:500000',
        ]);

        $profissional = Profissional::findOrFail($profissionalId);

        $updates = [];

        if ($request->has('telefone')) {
            $updates['telefone'] = $request->telefone;
        }

        if ($request->filled('foto_base64')) {
            $foto = $request->foto_base64;
            if (str_starts_with($foto, 'data:image/')) {
                $updates['foto'] = $foto;
            }
        }

        if (!empty($updates)) {
            $profissional->update($updates);
        }

        return back()->with('success', 'Informações atualizadas com sucesso!');
    }
}
