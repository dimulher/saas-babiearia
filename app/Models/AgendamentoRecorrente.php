<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgendamentoRecorrente extends Model
{
    use HasFactory;

    protected $table = 'agendamentos_recorrentes';

    protected $fillable = [
        'barbearia_id', 'profissional_id', 'cliente_id', 'servico_id', 'dia_semana', 'hora', 'ativo'
    ];

    public function barbearia() { return $this->belongsTo(Barbearia::class); }
    public function profissional() { return $this->belongsTo(Profissional::class); }
    public function cliente() { return $this->belongsTo(Cliente::class); }
    public function servico() { return $this->belongsTo(Servico::class); }

    public function getDiaSemanaNomeAttribute()
    {
        $dias = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];
        return $dias[$this->dia_semana] ?? 'N/A';
    }
}
