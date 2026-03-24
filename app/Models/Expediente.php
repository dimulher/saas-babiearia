<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expediente extends Model
{
    use HasFactory;

    protected $fillable = [
        'barbearia_id', 'profissional_id', 'dia_semana',
        'hora_inicio', 'hora_fim', 'intervalo_inicio', 'intervalo_fim', 'ativo',
    ];

    protected $casts = ['ativo' => 'boolean'];

    public function barbearia() { return $this->belongsTo(Barbearia::class); }
    public function profissional() { return $this->belongsTo(Profissional::class); }

    public function getDiaNomeAttribute(): string
    {
        return ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'][$this->dia_semana];
    }
}
