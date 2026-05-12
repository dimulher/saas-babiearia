<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificacao extends Model
{
    use HasFactory;

    protected $table = 'notificacoes';

    protected $fillable = [
        'barbearia_id',
        'tipo',
        'icone',
        'cor',
        'titulo',
        'mensagem',
        'lida'
    ];

    protected $casts = [
        'lida' => 'boolean',
    ];

    public function barbearia()
    {
        return $this->belongsTo(Barbearia::class);
    }
}
