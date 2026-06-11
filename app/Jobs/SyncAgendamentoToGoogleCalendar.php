<?php

namespace App\Jobs;

use App\Models\Agendamento;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncAgendamentoToGoogleCalendar implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 30;

    public function __construct(
        public int $agendamentoId,
        public string $action,
    ) {}

    public function handle(): void
    {
        $webhookUrl = config('services.make.agendamento_webhook');

        if (!$webhookUrl) {
            return;
        }

        $agendamento = Agendamento::with(['profissional', 'servico'])->find($this->agendamentoId);

        if (!$agendamento) {
            return;
        }

        $response = Http::timeout(15)->post($webhookUrl, [
            'agendamento_id'    => $agendamento->id,
            'google_event_id'   => $agendamento->google_event_id,
            'cliente_nome'      => $agendamento->nome_cliente,
            'cliente_telefone'  => $agendamento->cliente_telefone,
            'servico_nome'      => $agendamento->servico?->nome,
            'profissional_nome' => $agendamento->profissional?->nome,
            'data_inicio'       => $agendamento->data_inicio?->toIso8601String(),
            'data_fim'          => $agendamento->data_fim?->toIso8601String(),
            'observacoes'       => $agendamento->observacoes ?? $agendamento->descricao,
            'status'            => $agendamento->status,
            'action'            => $this->action,
        ]);

        if ($response->failed()) {
            Log::warning('Falha ao sincronizar agendamento com o Google Calendar via Make.com', [
                'agendamento_id' => $agendamento->id,
                'status'         => $response->status(),
                'body'           => $response->body(),
            ]);
            $response->throw();
        }
    }
}
