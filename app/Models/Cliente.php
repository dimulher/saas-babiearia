<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'barbearia_id', 'nome', 'email', 'telefone', 'whatsapp',
        'data_nascimento', 'tipo', 'observacoes', 'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'data_nascimento' => 'date',
    ];

    public function barbearia() { return $this->belongsTo(Barbearia::class); }
    public function agendamentos() { return $this->hasMany(Agendamento::class); }
    public function comandas() { return $this->hasMany(Comanda::class); }

    public function getUltimoAgendamentoAttribute()
    {
        return $this->agendamentos()->latest('data_inicio')->first();
    }

    public function getTotalGastoAttribute()
    {
        return $this->comandas()->where('status', 'fechada')->sum('total');
    }
}
