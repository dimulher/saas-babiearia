<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Servico extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'barbearia_id', 'nome', 'descricao', 'preco', 'duracao_minutos',
        'cor', 'ativo', 'disponivel_online',
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'disponivel_online' => 'boolean',
        'preco' => 'decimal:2',
    ];

    public function barbearia() { return $this->belongsTo(Barbearia::class); }
    public function agendamentos() { return $this->hasMany(Agendamento::class); }

    public function getDuracaoFormatadaAttribute(): string
    {
        $h = intdiv($this->duracao_minutos, 60);
        $m = $this->duracao_minutos % 60;
        return $h > 0 ? "{$h}h" . ($m > 0 ? "{$m}min" : '') : "{$m}min";
    }
}
