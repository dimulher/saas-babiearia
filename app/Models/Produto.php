<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produto extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'barbearia_id', 'nome', 'descricao', 'codigo',
        'preco_custo', 'preco_venda', 'estoque_atual', 'estoque_minimo', 'unidade', 'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'preco_custo' => 'decimal:2',
        'preco_venda' => 'decimal:2',
    ];

    public function barbearia() { return $this->belongsTo(Barbearia::class); }

    public function getEstoqueBaixoAttribute(): bool
    {
        return $this->estoque_atual <= $this->estoque_minimo;
    }
}
