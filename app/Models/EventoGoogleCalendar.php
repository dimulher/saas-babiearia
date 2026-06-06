<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventoGoogleCalendar extends Model
{
    use HasFactory;

    protected $table = 'eventos_google_calendar';

    protected $fillable = [
        'barbearia_id', 'google_event_id', 'titulo', 'descricao',
        'inicio', 'fim', 'dia_inteiro', 'status',
    ];

    protected $casts = [
        'inicio' => 'datetime',
        'fim' => 'datetime',
        'dia_inteiro' => 'boolean',
    ];

    public function barbearia()
    {
        return $this->belongsTo(Barbearia::class);
    }
}
