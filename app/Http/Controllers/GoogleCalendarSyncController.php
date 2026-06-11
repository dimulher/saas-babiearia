<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\EventoGoogleCalendar;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GoogleCalendarSyncController
{
    public function store(Request $request)
    {
        $token = config('services.make.calendar_sync_token');

        if (!$token || $request->header('X-Calendar-Sync-Token') !== $token) {
            return response()->json(['error' => 'Não autorizado'], 401);
        }

        // Normaliza datas antes de validar — aceita ISO 8601, Unix timestamp ou date-only
        foreach (['inicio', 'fim'] as $campo) {
            $valor = $request->input($campo);
            if ($valor !== null && $valor !== '') {
                try {
                    $dt = is_numeric($valor)
                        ? Carbon::createFromTimestamp((int) $valor)
                        : Carbon::parse($valor);
                    $request->merge([$campo => $dt->format('Y-m-d H:i:s')]);
                } catch (\Throwable $e) {
                    $request->merge([$campo => null]);
                }
            }
        }

        $request->validate([
            'barbearia_id'    => 'required|integer',
            'google_event_id' => 'required|string',
            'titulo'          => 'nullable|string',
            'descricao'       => 'nullable|string',
            'inicio'          => 'nullable|date',
            'fim'             => 'nullable|date',
            'dia_inteiro'     => 'nullable|boolean',
            'status'          => 'nullable|string',
        ]);

        $barbeariaId = $request->integer('barbearia_id');

        if ($request->input('status') === 'cancelled') {
            $removido = EventoGoogleCalendar::where('barbearia_id', $barbeariaId)
                ->where('google_event_id', $request->input('google_event_id'))
                ->delete();

            return response()->json(['success' => true, 'removido' => (bool) $removido]);
        }

        EventoGoogleCalendar::updateOrCreate(
            [
                'barbearia_id'    => $barbeariaId,
                'google_event_id' => $request->input('google_event_id'),
            ],
            [
                'titulo'      => $request->input('titulo'),
                'descricao'   => $request->input('descricao'),
                'inicio'      => $request->input('inicio') ?? $request->input('fim') ?? now(),
                'fim'         => $request->input('fim') ?? $request->input('inicio') ?? now(),
                'dia_inteiro' => $request->boolean('dia_inteiro'),
                'status'      => $request->input('status'),
            ]
        );

        return response()->json(['success' => true]);
    }

    // Callback chamado pelo Make após criar evento no Google Calendar.
    // Armazena o google_event_id no agendamento para permitir update/delete futuro.
    public function storeEventId(Request $request)
    {
        $token = config('services.make.calendar_sync_token');

        if (!$token || $request->header('X-Calendar-Sync-Token') !== $token) {
            return response()->json(['error' => 'Não autorizado'], 401);
        }

        $request->validate([
            'agendamento_id' => 'required|integer',
            'google_event_id' => 'required|string',
        ]);

        Agendamento::where('id', $request->integer('agendamento_id'))
            ->update(['google_event_id' => $request->input('google_event_id')]);

        return response()->json(['success' => true]);
    }
}
