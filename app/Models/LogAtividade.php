<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogAtividade extends Model
{
    use HasFactory;

    protected $table = 'logs_atividades';

    protected $fillable = [
        'barbearia_id', 'user_id', 'acao', 'descricao',
        'modelo_tipo', 'modelo_id', 'dados_antigos', 'dados_novos', 'ip',
    ];

    protected $casts = [
        'dados_antigos' => 'array',
        'dados_novos' => 'array',
    ];

    public function barbearia() { return $this->belongsTo(Barbearia::class); }
    public function user() { return $this->belongsTo(User::class); }
}
