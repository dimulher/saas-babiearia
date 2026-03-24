<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'barbearia_id', 'nome', 'email', 'telefone', 'password', 'role',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function barbearia() { return $this->belongsTo(Barbearia::class); }

    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isGerente(): bool { return $this->role === 'gerente'; }
    public function isBarbeiro(): bool { return $this->role === 'barbeiro'; }

    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->nome);
        return strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
    }
}
