<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HorarioBloqueado extends Model
{
    use HasFactory;

    protected $table = 'horarios_bloqueados';

    protected $fillable = [
        'barbearia_id', 'profissional_id', 'data_inicio', 'data_fim', 'motivo',
    ];

    protected $casts = [
        'data_inicio' => 'datetime',
        'data_fim' => 'datetime',
    ];

    public function barbearia() { return $this->belongsTo(Barbearia::class); }
    public function profissional() { return $this->belongsTo(Profissional::class); }
}
