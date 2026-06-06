<?php

namespace App\Http\Controllers;

use App\Models\EventoGoogleCalendar;
use Illuminate\Http\Request;

class GoogleCalendarSyncController
{
    /**
     * Recebe um evento do Google Calendar enviado pelo cenário de leitura do Make.com
     * (um POST por evento, padrão natural do módulo "Search Events") e o grava
     * localmente para exibição na visão unificada do painel.
     */
    public function store(Request $request)
    {
        $token = config('services.make.calendar_sync_token');

        if (!$token || $request->header('X-Calendar-Sync-Token') !== $token) {
            return response()->json(['error' => 'Não autorizado'], 401);
        }

        $request->validate([
            'barbearia_id'    => 'required|integer|exists:barbearias,id',
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
}
