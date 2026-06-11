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
        $barbeariaId = auth()->user()->barbearia_id;
        $date = $request->get('date', today()->format('Y-m-d'));
        $profissionalId = $request->get('profissional_id');
        $status = $request->get('status');

        $query = Agendamento::where('barbearia_id', $barbeariaId)
            ->with(['profissional', 'servico'])
            ->whereDate('data_inicio', $date);

        if ($profissionalId) {
            $query->where('profissional_id', $profissionalId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $agendamentos = $query->orderBy('data_inicio', 'asc')->get();
        $profissionais = Profissional::where('barbearia_id', $barbeariaId)->where('ativo', true)->get();

        $eventosCalendar = EventoGoogleCalendar::where('barbearia_id', $barbeariaId)
            ->whereDate('inicio', $date)
            ->where('status', '!=', 'cancelled')
            ->orderBy('inicio')
            ->get();

        return view('panel.agendamentos', compact('agendamentos', 'profissionais', 'date', 'profissionalId', 'status', 'eventosCalendar'));
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
            $cliente = Cliente::where('barbearia_id', $barbeariaId)->get()->first(function ($c) use ($cleanTel) {
                return preg_replace('/[^0-9]/', '', (string)$c->telefone) === $cleanTel;
            });
            
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
        $telefone = $request->get('telefone');
        $barbeariaId = $request->get('barbearia_id');
        
        if (!$telefone || !$barbeariaId) {
            return response()->json(['isVip' => false]);
        }

        $cleanTel = preg_replace('/[^0-9]/', '', $telefone);

        // Busca o cliente filtrando pela string limpa e barbearia
        $cliente = Cliente::where('barbearia_id', $barbeariaId)->get()->first(function ($c) use ($cleanTel) {
            return preg_replace('/[^0-9]/', '', (string)$c->telefone) === $cleanTel;
        });

        if (!$cliente) {
            return response()->json(['isVip' => false]);
        }

        // Verifica se tem assinatura ativa
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
