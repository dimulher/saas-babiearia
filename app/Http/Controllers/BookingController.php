<?php

namespace App\Http\Controllers;

use App\Models\Barbearia;
use App\Models\Servico;
use App\Models\Profissional;
use App\Models\Expediente;
use App\Models\HorarioBloqueado;
use App\Models\Agendamento;
use App\Models\Cliente;
use App\Models\LogAtividade;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index($slug)
    {
        $barbearia = Barbearia::where('slug', $slug)->where('ativo', true)->firstOrFail();

        $servicos = Servico::where('barbearia_id', $barbearia->id)
            ->where('ativo', true)
            ->where('disponivel_online', true)
            ->get(['id', 'nome', 'descricao', 'preco', 'duracao_minutos']);

        $profissionais = Profissional::where('barbearia_id', $barbearia->id)
            ->where('ativo', true)
            ->where('aceita_agendamento_online', true)
            ->get(['id', 'nome', 'foto']);

        return view('booking.index', compact('barbearia', 'servicos', 'profissionais'));
    }

    public function getHorarios(Request $request, $slug)
    {
        $request->validate([
            'data' => 'required|date|after_or_equal:today',
            'servico_id' => 'required|exists:servicos,id',
            'profissional_id' => 'nullable'
        ]);

        $barbearia = Barbearia::where('slug', $slug)->where('ativo', true)->firstOrFail();
        $servico = Servico::where('barbearia_id', $barbearia->id)->findOrFail($request->servico_id);
        
        $data = Carbon::parse($request->data)->startOfDay();
        $diaSemana = $data->dayOfWeek;
        
        $profissionaisIds = [];
        if ($request->profissional_id && $request->profissional_id !== 'qualquer') {
            $profissionaisIds = [$request->profissional_id];
        } else {
            $profissionaisIds = Profissional::where('barbearia_id', $barbearia->id)
                ->where('ativo', true)
                ->where('aceita_agendamento_online', true)
                ->pluck('id')->toArray();
        }

        $horariosDisponiveis = [];

        foreach ($profissionaisIds as $profId) {
            $expedientes = Expediente::where('barbearia_id', $barbearia->id)
                ->where('profissional_id', $profId)
                ->where('dia_semana', $diaSemana)
                ->where('ativo', true)
                ->get();

            $agendamentos = Agendamento::where('profissional_id', $profId)
                ->where('barbearia_id', $barbearia->id)
                ->whereDate('data_inicio', $data)
                ->whereIn('status', ['pendente', 'confirmado'])
                ->get();

            $bloqueios = HorarioBloqueado::where('profissional_id', $profId)
                ->where('barbearia_id', $barbearia->id)
                ->whereDate('data_inicio', '<=', $data)
                ->whereDate('data_fim', '>=', $data)
                ->get();

            $duracao = $servico->duracao_minutos;

            foreach ($expedientes as $exp) {
                $inicioExp = Carbon::parse($data->format('Y-m-d') . ' ' . $exp->hora_inicio);
                $fimExp = Carbon::parse($data->format('Y-m-d') . ' ' . $exp->hora_fim);

                $intervaloInicio = $exp->intervalo_inicio ? Carbon::parse($data->format('Y-m-d') . ' ' . $exp->intervalo_inicio) : null;
                $intervaloFim = $exp->intervalo_fim ? Carbon::parse($data->format('Y-m-d') . ' ' . $exp->intervalo_fim) : null;

                $currentSlot = $inicioExp->copy();
                
                while ($currentSlot->copy()->addMinutes($duracao)->lte($fimExp)) {
                    $slotFim = $currentSlot->copy()->addMinutes($duracao);
                    
                    if ($currentSlot->isPast() && $data->isToday()) {
                        $currentSlot->addMinutes(30);
                        continue;
                    }

                    if ($intervaloInicio && $intervaloFim) {
                        if ($currentSlot->lt($intervaloFim) && $slotFim->gt($intervaloInicio)) {
                            $currentSlot->addMinutes(30);
                            continue;
                        }
                    }

                    $hasBloqueio = false;
                    foreach ($bloqueios as $bq) {
                        if ($bq->dia_todo) {
                            $hasBloqueio = true;
                            break;
                        }
                        
                        $bqStart = Carbon::parse($data->format('Y-m-d') . ' ' . $bq->hora_inicio);
                        $bqEnd = Carbon::parse($data->format('Y-m-d') . ' ' . $bq->hora_fim);
                        
                        if ($currentSlot->lt($bqEnd) && $slotFim->gt($bqStart)) {
                            $hasBloqueio = true;
                            break;
                        }
                    }
                    if ($hasBloqueio) {
                        $currentSlot->addMinutes(30);
                        continue;
                    }

                    $hasAgendamento = false;
                    foreach ($agendamentos as $agd) {
                        if ($currentSlot->lt($agd->data_fim) && $slotFim->gt($agd->data_inicio)) {
                            $hasAgendamento = true;
                            break;
                        }
                    }
                    if ($hasAgendamento) {
                        $currentSlot->addMinutes(30);
                        continue;
                    }

                    $horariosDisponiveis[$currentSlot->format('H:i')] = true;
                    $currentSlot->addMinutes(30);
                }
            }
        }

        $horariosList = array_keys($horariosDisponiveis);
        sort($horariosList);

        return response()->json(['horarios' => $horariosList]);
    }

    public function store(Request $request, $slug)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'telefone' => 'required|string|max:20',
            'servico_id' => 'required|exists:servicos,id',
            'profissional_id' => 'nullable',
            'data' => 'required|date|after_or_equal:today',
            'hora' => 'required|date_format:H:i',
        ]);

        $barbearia = Barbearia::where('slug', $slug)->where('ativo', true)->firstOrFail();
        $servico = Servico::where('barbearia_id', $barbearia->id)->findOrFail($request->servico_id);

        $dataInicio = Carbon::parse($request->data . ' ' . $request->hora);
        $dataFim = $dataInicio->copy()->addMinutes($servico->duracao_minutos);

        if ($dataInicio->isPast()) {
            return response()->json(['message' => 'Você não pode agendar em um horário no passado.'], 422);
        }

        $profissionaisIds = [];
        if ($request->profissional_id && $request->profissional_id !== 'qualquer') {
            $profissionaisIds = [$request->profissional_id];
        } else {
            $profissionaisIds = Profissional::where('barbearia_id', $barbearia->id)
                ->where('ativo', true)
                ->where('aceita_agendamento_online', true)
                ->pluck('id')->toArray();
        }

        $selectedProfissionalId = null;

        foreach ($profissionaisIds as $profId) {
            $diaSemana = $dataInicio->dayOfWeek;
            
            $exp = Expediente::where('barbearia_id', $barbearia->id)
                ->where('profissional_id', $profId)
                ->where('dia_semana', $diaSemana)
                ->where('ativo', true)
                ->where('hora_inicio', '<=', $dataInicio->format('H:i'))
                ->where('hora_fim', '>=', $dataFim->format('H:i'))
                ->first();

            if (!$exp) continue;

            if ($exp->intervalo_inicio && $exp->intervalo_fim) {
                $inicioInt = Carbon::parse($request->data . ' ' . $exp->intervalo_inicio);
                $fimInt = Carbon::parse($request->data . ' ' . $exp->intervalo_fim);
                if ($dataInicio->lt($fimInt) && $dataFim->gt($inicioInt)) continue;
            }

            $overlapBloqueio = HorarioBloqueado::where('profissional_id', $profId)
                ->where('barbearia_id', $barbearia->id)
                ->whereDate('data_inicio', '<=', $request->data)
                ->whereDate('data_fim', '>=', $request->data)
                ->where(function($q) use ($dataInicio, $dataFim) {
                    $q->where('dia_todo', true)
                      ->orWhere(function($q2) use ($dataInicio, $dataFim) {
                          $q2->where('hora_inicio', '<', $dataFim->format('H:i'))
                             ->where('hora_fim', '>', $dataInicio->format('H:i'));
                      });
                })->exists();

            if ($overlapBloqueio) continue;

            $overlapAgendamento = Agendamento::where('profissional_id', $profId)
                ->where('barbearia_id', $barbearia->id)
                ->whereIn('status', ['pendente', 'confirmado'])
                ->where('data_inicio', '<', $dataFim)
                ->where('data_fim', '>', $dataInicio)
                ->exists();

            if ($overlapAgendamento) continue;

            $selectedProfissionalId = $profId;
            break;
        }

        if (!$selectedProfissionalId) {
            return response()->json(['message' => 'Este horário não está mais disponível.'], 422);
        }

        DB::beginTransaction();
        try {
            $cliente = Cliente::firstOrCreate(
                ['telefone' => $request->telefone, 'barbearia_id' => $barbearia->id],
                ['nome' => $request->nome, 'ativo' => true]
            );

            $agendamento = Agendamento::create([
                'barbearia_id' => $barbearia->id,
                'profissional_id' => $selectedProfissionalId,
                'cliente_id' => $cliente->id,
                'servico_id' => $servico->id,
                'cliente_nome' => $cliente->nome,
                'cliente_telefone' => $cliente->telefone,
                'data_inicio' => $dataInicio,
                'data_fim' => $dataFim,
                'preco' => $servico->preco,
                'status' => 'pendente',
                'agendado_online' => true,
            ]);

            LogAtividade::create([
                'barbearia_id' => $barbearia->id,
                'user_id' => null,
                'acao' => 'criou',
                'descricao' => 'Agendamento criado online.',
                'modelo_tipo' => 'Agendamento',
                'modelo_id' => $agendamento->id,
                'ip' => request()->ip(),
            ]);

            DB::commit();

            return response()->json(['success' => true, 'agendamento_id' => $agendamento->id]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Erro ao realizar agendamento: ' . $e->getMessage()], 500);
        }
    }
}
