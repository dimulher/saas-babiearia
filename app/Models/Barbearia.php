<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barbearia extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nome', 'slug', 'email', 'telefone', 'logo', 'capa',
        'descricao', 'endereco', 'cidade', 'estado', 'cep',
        'instagram', 'facebook', 'whatsapp', 'ativo', 'plano', 'plano_expira_em',
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'plano_expira_em' => 'datetime',
    ];

    public function users() { return $this->hasMany(User::class); }
    public function profissionais() { return $this->hasMany(Profissional::class); }
    public function clientes() { return $this->hasMany(Cliente::class); }
    public function servicos() { return $this->hasMany(Servico::class); }
    public function agendamentos() { return $this->hasMany(Agendamento::class); }
    public function comandas() { return $this->hasMany(Comanda::class); }
    public function produtos() { return $this->hasMany(Produto::class); }
    public function expedientes() { return $this->hasMany(Expediente::class); }
    public function contas() { return $this->hasMany(Conta::class); }
    public function logs() { return $this->hasMany(LogAtividade::class); }

    public function getLinkAgendamentoAttribute(): string
    {
        return config('app.url') . '/' . $this->slug;
    }

    public function agendamentosHoje()
    {
        return $this->agendamentos()->whereDate('data_inicio', today());
    }
}
