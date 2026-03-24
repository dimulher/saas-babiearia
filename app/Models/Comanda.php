<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comanda extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'barbearia_id', 'profissional_id', 'cliente_id', 'agendamento_id',
        'cliente_nome', 'subtotal', 'desconto', 'total',
        'forma_pagamento', 'status', 'observacoes', 'fechada_em',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'desconto' => 'decimal:2',
        'total' => 'decimal:2',
        'fechada_em' => 'datetime',
    ];

    public function barbearia() { return $this->belongsTo(Barbearia::class); }
    public function profissional() { return $this->belongsTo(Profissional::class); }
    public function cliente() { return $this->belongsTo(Cliente::class); }
    public function agendamento() { return $this->belongsTo(Agendamento::class); }
    public function itens() { return $this->hasMany(ComandaItem::class); }

    public function calcularTotal(): void
    {
        $this->subtotal = $this->itens->sum('subtotal');
        $this->total = $this->subtotal - $this->desconto;
        $this->save();
    }
}
