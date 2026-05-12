<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barbearia;
use App\Models\Profissional;
use App\Models\Servico;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $barbearia = Barbearia::create([
            'nome' => 'Barbearia do Gabriel',
            'slug' => 'gabriel',
            'email' => 'admin@barbeariadogabriel.com',
            'telefone' => '11999999999',
            'endereco' => 'Rua Teste, 123'
        ]);

        Profissional::create([
            'barbearia_id' => $barbearia->id,
            'nome' => 'Gabriel',
            'telefone' => '11999999999',
            'ativo' => true
        ]);

        Servico::create([
            'barbearia_id' => $barbearia->id,
            'nome' => 'Corte de Cabelo',
            'descricao' => 'Corte masculino',
            'preco' => 35.00,
            'duracao_minutos' => 30,
            'ativo' => true
        ]);
        
        Servico::create([
            'barbearia_id' => $barbearia->id,
            'nome' => 'Barba',
            'descricao' => 'Barba simples',
            'preco' => 25.00,
            'duracao_minutos' => 20,
            'ativo' => true
        ]);
    }
}
