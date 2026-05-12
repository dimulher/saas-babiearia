<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assinatura extends Model
{
    use HasFactory;

    protected $fillable = [
        'barbearia_id',
        'cliente_id',
        'plano_id',
        'status',
        'dia_vencimento',
        'data_inicio',
    ];

    protected $casts = [
        'data_inicio' => 'date',
    ];

    public function barbearia()
    {
        return $this->belongsTo(Barbearia::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function plano()
    {
        return $this->belongsTo(Plano::class);
    }
}
