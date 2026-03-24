<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conta extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'barbearia_id', 'descricao', 'valor', 'tipo', 'status',
        'vencimento', 'pago_em', 'recorrencia', 'parcela_atual',
        'total_parcelas', 'categoria', 'observacoes',
    ];

    protected $casts = [
        'valor' => 'decimal:2',
        'vencimento' => 'date',
        'pago_em' => 'date',
    ];

    public function barbearia() { return $this->belongsTo(Barbearia::class); }

    public function getVencidaAttribute(): bool
    {
        return $this->status === 'pendente' && $this->vencimento->isPast();
    }
}
