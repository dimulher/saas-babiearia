<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barbearia_id')->constrained()->cascadeOnDelete();
            $table->string('nome');
            $table->text('descricao')->nullable();
            $table->string('codigo')->nullable();
            $table->decimal('preco_custo', 10, 2)->default(0);
            $table->decimal('preco_venda', 10, 2);
            $table->integer('estoque_atual')->default(0);
            $table->integer('estoque_minimo')->default(5); // alerta de reposição
            $table->string('unidade')->default('un');
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('comanda_itens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comanda_id')->constrained('comandas')->cascadeOnDelete();
            $table->foreignId('servico_id')->nullable()->constrained('servicos')->nullOnDelete();
            $table->foreignId('produto_id')->nullable()->constrained('produtos')->nullOnDelete();
            $table->string('descricao');
            $table->integer('quantidade')->default(1);
            $table->decimal('preco_unitario', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comanda_itens');
        Schema::dropIfExists('produtos');
    }
};
