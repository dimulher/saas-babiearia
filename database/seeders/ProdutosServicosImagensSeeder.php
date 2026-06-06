<?php

namespace Database\Seeders;

use App\Models\Barbearia;
use App\Models\Produto;
use App\Models\Servico;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProdutosServicosImagensSeeder extends Seeder
{
    private array $palette = [
        ['#16a34a', '#052e16'],
        ['#0ea5e9', '#082f49'],
        ['#f59e0b', '#451a03'],
        ['#ec4899', '#500724'],
        ['#8b5cf6', '#2e1065'],
        ['#ef4444', '#450a0a'],
        ['#14b8a6', '#042f2e'],
        ['#eab308', '#422006'],
    ];

    public function run(): void
    {
        $barbearia = Barbearia::where('slug', 'gabriel')->first() ?? Barbearia::first();
        if (!$barbearia) return;

        $produtos = [
            ['nome' => 'Pomada Modeladora Matte', 'emoji' => '🧴', 'preco_venda' => 39.90, 'preco_custo' => 18.00, 'estoque' => 24, 'unidade' => 'un'],
            ['nome' => 'Óleo para Barba Premium', 'emoji' => '🛢️', 'preco_venda' => 54.90, 'preco_custo' => 26.00, 'estoque' => 15, 'unidade' => 'un'],
            ['nome' => 'Shampoo Anticaspa 300ml', 'emoji' => '🧴', 'preco_venda' => 32.50, 'preco_custo' => 15.00, 'estoque' => 30, 'unidade' => 'un'],
            ['nome' => 'Balm Pós-Barba', 'emoji' => '🧖', 'preco_venda' => 45.00, 'preco_custo' => 21.00, 'estoque' => 12, 'unidade' => 'un'],
            ['nome' => 'Cera Modeladora Forte', 'emoji' => '✨', 'preco_venda' => 28.90, 'preco_custo' => 13.50, 'estoque' => 20, 'unidade' => 'un'],
            ['nome' => 'Kit Navalha & Pente', 'emoji' => '🪒', 'preco_venda' => 89.90, 'preco_custo' => 42.00, 'estoque' => 8, 'unidade' => 'kit'],
        ];

        $servicos = [
            ['nome' => 'Corte Degradê Navalhado', 'emoji' => '💈', 'preco' => 45.00, 'duracao' => 40],
            ['nome' => 'Barba Completa', 'emoji' => '🧔', 'preco' => 30.00, 'duracao' => 25],
            ['nome' => 'Combo Corte + Barba', 'emoji' => '✂️', 'preco' => 65.00, 'duracao' => 60],
            ['nome' => 'Sobrancelha na Navalha', 'emoji' => '👁️', 'preco' => 15.00, 'duracao' => 15],
            ['nome' => 'Hidratação Capilar', 'emoji' => '💧', 'preco' => 50.00, 'duracao' => 35],
            ['nome' => 'Pigmentação de Barba', 'emoji' => '🎨', 'preco' => 70.00, 'duracao' => 45],
        ];

        foreach ($produtos as $i => $p) {
            $path = $this->makeImage('produtos', $p['nome'], $p['emoji'], $i);
            Produto::updateOrCreate(
                ['barbearia_id' => $barbearia->id, 'nome' => $p['nome']],
                [
                    'descricao' => 'Produto selecionado especialmente para o cuidado e estilo dos nossos clientes.',
                    'codigo' => 'PRD-' . str_pad((string) ($i + 1), 3, '0', STR_PAD_LEFT),
                    'preco_custo' => $p['preco_custo'],
                    'preco_venda' => $p['preco_venda'],
                    'estoque_atual' => $p['estoque'],
                    'estoque_minimo' => 5,
                    'unidade' => $p['unidade'],
                    'imagem' => $path,
                    'ativo' => true,
                ]
            );
        }

        foreach ($servicos as $i => $s) {
            $path = $this->makeImage('servicos', $s['nome'], $s['emoji'], $i + 10);
            Servico::updateOrCreate(
                ['barbearia_id' => $barbearia->id, 'nome' => $s['nome']],
                [
                    'descricao' => 'Procedimento realizado por profissionais especializados com produtos de alta qualidade.',
                    'preco' => $s['preco'],
                    'duracao_minutos' => $s['duracao'],
                    'cor' => $this->palette[$i % count($this->palette)][0],
                    'imagem' => $path,
                    'disponivel_online' => true,
                    'ativo' => true,
                ]
            );
        }
    }

    private function makeImage(string $folder, string $nome, string $emoji, int $seed): string
    {
        [$corA, $corB] = $this->palette[$seed % count($this->palette)];
        $gradientId = 'g' . $seed;

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="640" height="480" viewBox="0 0 640 480">
    <defs>
        <linearGradient id="{$gradientId}" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" stop-color="{$corA}" />
            <stop offset="100%" stop-color="{$corB}" />
        </linearGradient>
    </defs>
    <rect width="640" height="480" fill="url(#{$gradientId})" />
    <text x="320" y="260" font-size="150" text-anchor="middle" dominant-baseline="middle">{$emoji}</text>
    <text x="320" y="430" font-size="26" font-family="Arial, sans-serif" font-weight="bold" fill="rgba(255,255,255,0.88)" text-anchor="middle">{$nome}</text>
</svg>
SVG;

        $filename = $folder . '/' . Str::slug($nome) . '-' . $seed . '.svg';
        Storage::disk('public')->put($filename, $svg);

        return $filename;
    }
}
