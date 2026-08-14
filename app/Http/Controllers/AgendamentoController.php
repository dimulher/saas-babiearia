<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agendamento;
use App\Models\Profissional;
use App\Models\Servico;
use App\Models\Produto;
use App\Models\Cliente;
use App\Models\Assinatura;
use Carbon\Carbon;
use App\Models\Notificacao;
use App\Jobs\SyncAgendamentoToGoogleCalendar;
use App\Models\EventoGoogleCalendar;

class AgendamentoController
{
    public function index(Request $request)
    {
        $barbeariaId   = auth()->user()->barbearia_id;
        $profissionais = Profissional::where('barbearia_id', $barbeariaId)->where('ativo', true)->orderBy('nome')->get();
        $servicos      = Servico::where('barbearia_id', $barbeariaId)->where('ativo', true)->orderBy('nome')->get();
        $produtos      = Produto::where('barbearia_id', $barbeariaId)->where('ativo', true)->orderBy('nome')->get();
        return view('panel.agendamentos', compact('profissionais', 'servicos', 'produtos'));
    }

    public function storePanel(Request $request)
    {
        $barbeariaId = auth()->user()->barbearia_id;

        $request->validate([
            'nome_cliente'    => 'required|string|max:255',
            'telefone'        => 'nullable|string|max:20',
            'profissional_id' => 'required|integer',
            'servico_id'      => 'required|integer',
            'data'            => 'required|date',
            'hora'            => 'required|string',
            'status'          => 'required|in:pendente,confirmado',
            'descricao'       => 'nullable|string|max:1000',
        ]);

        $profissional = Profissional::where('barbearia_id', $barbeariaId)->findOrFail($request->profissional_id);
        $servico      = Servico::where('barbearia_id', $barbeariaId)->findOrFail($request->servico_id);

        $dataInicio = Carbon::parse($request->data . ' ' . $request->hora);
        $dataFim    = (clone $dataInicio)->addMinutes($servico->duracao_minutos ?? 30);

        $agendamento = Agendamento::create([
            'barbearia_id'     => $barbeariaId,
            'profissional_id'  => $profissional->id,
            'servico_id'       => $servico->id,
            'cliente_nome'     => $request->nome_cliente,
            'cliente_telefone' => $request->telefone,
            'data_inicio'      => $dataInicio,
            'data_fim'         => $dataFim,
            'preco'            => $servico->preco ?? 0,
            'status'           => $request->status,
            'agendado_online'  => false,
            'descricao'        => $request->descricao,
        ]);

        SyncAgendamentoToGoogleCalendar::dispatch($agendamento->id, 'created');

        return response()->json(['success' => true]);
    }

    public function eventosJson(Request $request)
    {
        $barbeariaId = auth()->user()->barbearia_id;
        $start = $request->get('start') ? Carbon::parse($request->get('start')) : now()->startOfMonth();
        $end   = $request->get('end')   ? Carbon::parse($request->get('end'))   : now()->endOfMonth();
        $profissionalId = $request->get('profissional_id');

        $servicoId  = $request->get('servico_id');
        $produtoId  = $request->get('produto_id');
        $busca      = $request->get('busca');

        $query = Agendamento::where('barbearia_id', $barbeariaId)
            ->with(['profissional:id,nome', 'servico:id,nome'])
            ->where('data_inicio', '>=', $start)
            ->where('data_inicio', '<=', $end);

        if ($profissionalId) $query->where('profissional_id', $profissionalId);
        if ($servicoId)      $query->where('servico_id', $servicoId);
        if ($busca)          $query->where('cliente_nome', 'like', '%' . $busca . '%');
        if ($produtoId) {
            $prod = Produto::find($produtoId);
            if ($prod) $query->where('produtos_solicitados', 'like', '%' . $prod->nome . '%');
        }

        $cores = [
            'pendente'   => ['#d97706', '#92400e'],
            'confirmado' => ['#2563eb', '#1e3a8a'],
            'concluido'  => ['#059669', '#065f46'],
            'cancelado'  => ['#e11d48', '#881337'],
            'faltou'     => ['#6b7280', '#374151'],
        ];

        $agendamentos = $query->get()->map(function ($a) use ($cores) {
            [$bg, $border] = $cores[$a->status] ?? ['#6b7280', '#374151'];
            return [
                'id'              => 'ag_' . $a->id,
                'title'           => $a->cliente_nome ?? 'Cliente',
                'start'           => $a->data_inicio->toIso8601String(),
                'end'             => $a->data_fim ? $a->data_fim->toIso8601String() : null,
                'backgroundColor' => $bg,
                'borderColor'     => $border,
                'textColor'       => '#ffffff',
                'extendedProps'   => [
                    'tipo'            => 'agendamento',
                    'agendamento_id'  => $a->id,
                    'servico'         => $a->servico?->nome ?? '—',
                    'profissional'    => $a->profissional?->nome ?? '—',
                    'telefone'        => $a->cliente_telefone ?? '',
                    'preco'           => 'R$ ' . number_format($a->preco ?? 0, 2, ',', '.'),
                    'status'          => $a->status,
                    'descricao'       => $a->descricao ?? '',
                ],
            ];
        });

        $gcEventos = EventoGoogleCalendar::where('barbearia_id', $barbeariaId)
            ->where('inicio', '>=', $start)
            ->where('inicio', '<=', $end)
            ->where('status', '!=', 'cancelled')
            ->get()
            ->map(function ($e) {
                return [
                    'id'              => 'gc_' . $e->id,
                    'title'           => $e->titulo ?? 'Evento',
                    'start'           => $e->inicio->toIso8601String(),
                    'end'             => $e->fim?->toIso8601String(),
                    'allDay'          => (bool) $e->dia_inteiro,
                    'backgroundColor' => '#4285f4',
                    'borderColor'     => '#1a56db',
                    'textColor'       => '#ffffff',
                    'extendedProps'   => [
                        'tipo'      => 'google_calendar',
                        'descricao' => $e->descricao ?? '',
                    ],
                ];
            });

        return response()->json($agendamentos->merge($gcEventos)->values());
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomeCliente'       => 'required|string|max:255',
            'telefone'          => 'required|string|max:255',
            'barbearia_id'      => 'required|exists:barbearias,id',
            'profissional_id'   => 'nullable|integer',
            'servico_id'        => 'required|integer',
            'data'              => 'required|date',
            'hora'              => 'required|string',
            'descricao'         => 'nullable|string|max:1000',
            'produtos_ids'      => 'nullable|array',
            'produtos_ids.*'    => 'integer|exists:produtos,id',
            'is_vip'            => 'nullable|boolean',
        ]);

        $barbeariaId = $request->barbearia_id;

        $profissional = Profissional::where('barbearia_id', $barbeariaId)->find($request->profissional_id);
        if (!$profissional) {
            $profissional = Profissional::where('barbearia_id', $barbeariaId)
                ->where('ativo', true)
                ->where('aceita_agendamento_online', true)
                ->first();
        }

        if (!$profissional) {
            return response()->json(['error' => 'Nenhum profissional disponível'], 422);
        }

        $servico = Servico::where('barbearia_id', $barbeariaId)->find($request->servico_id);
        
        $dataInicio = Carbon::parse($request->data . ' ' . $request->hora);
        $duracao    = $servico ? $servico->duracao_minutos : 30;
        $dataFim    = (clone $dataInicio)->addMinutes($duracao);

        $produtosSolicitados = null;
        if ($request->produtos_ids) {
            $produtos = Produto::where('barbearia_id', $barbeariaId)->whereIn('id', $request->produtos_ids)->get();
            $produtosSolicitados = $produtos->map(fn($p) => "{$p->nome} (R$ {$p->preco_venda})")->implode(', ');
        }

        // Aplica o benefício VIP se for válido
        $precoFinal = $servico ? $servico->preco : 0;
        $descricaoFinal = $request->descricao;

        if ($request->is_vip) {
            // Verificar no backend se o telefone bate com uma assinatura ativa
            $cleanTel = preg_replace('/[^0-9]/', '', $request->telefone);
            $cliente = Cliente::where('barbearia_id', $barbeariaId)
                ->whereRaw("REGEXP_REPLACE(CAST(telefone AS TEXT), '[^0-9]', '', 'g') = ?", [$cleanTel])
                ->first();
            
            if ($cliente) {
                $assinatura = Assinatura::where('barbearia_id', $barbeariaId)
                    ->where('cliente_id', $cliente->id)
                    ->where('status', 'ativo')
                    ->with('plano')
                    ->first();
                
                if ($assinatura && $assinatura->plano) {
                    $precoFinal = 0; // Serviço gratuito pelo clube VIP
                    $descricaoFinal = "[CLIENTE VIP - " . strtoupper($assinatura->plano->nome) . "] Benefício Aplicado.\n" . $descricaoFinal;
                }
            }
        }

        $agendamento = Agendamento::create([
            'barbearia_id'        => $barbeariaId,
            'profissional_id'     => $profissional->id,
            'servico_id'          => $servico->id,
            'cliente_nome'        => $request->nomeCliente,
            'cliente_telefone'    => $request->telefone,
            'data_inicio'         => $dataInicio,
            'data_fim'            => $dataFim,
            'preco'               => $precoFinal,
            'status'              => 'pendente',
            'agendado_online'     => true,
            'descricao'           => $descricaoFinal,
            'produtos_solicitados' => $produtosSolicitados,
        ]);

        // Criar Notificação para o Painel
        Notificacao::create([
            'barbearia_id' => $barbeariaId,
            'tipo' => 'agendamento',
            'icone' => 'fa-calendar-plus',
            'cor' => 'violet',
            'titulo' => 'Novo Agendamento Online',
            'mensagem' => $request->nomeCliente . " agendou " . ($servico ? $servico->nome : 'Serviço') . " com " . $profissional->nome . " para " . $dataInicio->format('d/m \à\s H:i'),
            'lida' => false
        ]);

        SyncAgendamentoToGoogleCalendar::dispatch($agendamento->id, 'created');

        return response()->json(['success' => true]);
    }

    public function updateStatus(Request $request, Agendamento $agendamento)
    {
        $barbeariaId = auth()->user()->barbearia_id;

        if ($agendamento->barbearia_id !== $barbeariaId) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:confirmado,cancelado,faltou,pendente',
        ]);

        $agendamento->update(['status' => $request->status]);

        $action = $request->status === 'cancelado' ? 'cancelled' : 'updated';
        SyncAgendamentoToGoogleCalendar::dispatch($agendamento->id, $action);

        return response()->json(['success' => true, 'status' => $request->status]);
    }

    public function checkVip(Request $request)
    {
        $barbeariaId = $request->get('barbearia_id');
        $cpf         = $request->get('cpf');
        $telefone    = $request->get('telefone');

        if (!$barbeariaId || (!$cpf && !$telefone)) {
            return response()->json(['isVip' => false]);
        }

        $cliente = null;

        // Busca prioritária por CPF
        if ($cpf) {
            $cleanCpf = preg_replace('/[^0-9]/', '', $cpf);
            $cliente = Cliente::where('barbearia_id', $barbeariaId)
                ->whereRaw("REGEXP_REPLACE(CAST(cpf AS TEXT), '[^0-9]', '', 'g') = ?", [$cleanCpf])
                ->first();
        }

        // Fallback por telefone se CPF não encontrou
        if (!$cliente && $telefone) {
            $cleanTel = preg_replace('/[^0-9]/', '', $telefone);
            $cliente = Cliente::where('barbearia_id', $barbeariaId)
                ->whereRaw("REGEXP_REPLACE(CAST(telefone AS TEXT), '[^0-9]', '', 'g') = ?", [$cleanTel])
                ->first();
        }

        if (!$cliente) {
            return response()->json(['isVip' => false]);
        }

        $assinatura = Assinatura::where('barbearia_id', $barbeariaId)
            ->where('cliente_id', $cliente->id)
            ->where('status', 'ativo')
            ->with('plano')
            ->first();

        if ($assinatura && $assinatura->plano) {
            return response()->json([
                'isVip' => true,
                'plano' => $assinatura->plano->nome
            ]);
        }

        return response()->json(['isVip' => false]);
    }
}
