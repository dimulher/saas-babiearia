<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plano extends Model
{
    use HasFactory;

    protected $fillable = [
        'barbearia_id',
        'nome',
        'valor_mensal',
        'recursos',
        'ativo',
    ];

    protected $casts = [
        'valor_mensal' => 'decimal:2',
        'recursos' => 'array',
        'ativo' => 'boolean',
    ];

    public function barbearia()
    {
        return $this->belongsTo(Barbearia::class);
    }

    public function assinaturas()
    {
        return $this->hasMany(Assinatura::class);
    }
}
