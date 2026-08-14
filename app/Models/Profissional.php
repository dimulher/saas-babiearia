<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profissional extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'profissionais';

    protected $fillable = [
        'barbearia_id', 'nome', 'email', 'telefone', 'foto',
        'comissao_percentual', 'ativo', 'aceita_agendamento_online', 'codigo_acesso',
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'aceita_agendamento_online' => 'boolean',
        'comissao_percentual' => 'decimal:2',
    ];

    public function barbearia() { return $this->belongsTo(Barbearia::class); }
    public function agendamentos() { return $this->hasMany(Agendamento::class); }
    public function expedientes() { return $this->hasMany(Expediente::class); }
    public function comandas() { return $this->hasMany(Comanda::class); }

    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->nome);
        return strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
    }

    public function getLinkAgendamentoAttribute(): string
    {
        $slug = $this->barbearia?->slug ?? '';
        return url('/agendar/' . $slug . '?funcionario=' . $this->id);
    }

    public function agendamentosHoje()
    {
        return $this->agendamentos()->whereDate('data_inicio', today());
    }
}
