<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agendamento extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'barbearia_id', 'profissional_id', 'cliente_id', 'servico_id',
        'cliente_nome', 'cliente_telefone', 'data_inicio', 'data_fim',
        'preco', 'status', 'observacoes', 'descricao', 'produtos_solicitados',
        'agendado_online', 'lembrete_enviado',
    ];

    protected $casts = [
        'data_inicio' => 'datetime',
        'data_fim' => 'datetime',
        'agendado_online' => 'boolean',
        'lembrete_enviado' => 'boolean',
        'preco' => 'decimal:2',
    ];

    public function barbearia() { return $this->belongsTo(Barbearia::class); }
    public function profissional() { return $this->belongsTo(Profissional::class); }
    public function cliente() { return $this->belongsTo(Cliente::class); }
    public function servico() { return $this->belongsTo(Servico::class); }

    public function getNomeClienteAttribute(): string
    {
        return $this->cliente?->nome ?? $this->cliente_nome ?? 'Sem nome';
    }

    public function getStatusCorAttribute(): string
    {
        return match($this->status) {
            'pendente'   => 'yellow',
            'confirmado' => 'blue',
            'concluido'  => 'green',
            'cancelado'  => 'red',
            'faltou'     => 'gray',
            default      => 'gray',
        };
    }
}
