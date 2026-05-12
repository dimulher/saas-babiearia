<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('comandas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barbearia_id')->constrained()->cascadeOnDelete();
            $table->foreignId('profissional_id')->nullable()->constrained('profissionais')->nullOnDelete();
            $table->foreignId('cliente_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('agendamento_id')->nullable()->constrained()->nullOnDelete();
            $table->string('cliente_nome')->nullable();
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('desconto', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->enum('forma_pagamento', ['dinheiro', 'cartao_credito', 'cartao_debito', 'pix', 'outro'])->nullable();
            $table->enum('status', ['aberta', 'fechada', 'cancelada'])->default('aberta');
            $table->text('observacoes')->nullable();
            $table->timestamp('fechada_em')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('comanda_itens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comanda_id')->constrained()->cascadeOnDelete();
            $table->foreignId('servico_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('produto_id')->nullable()->constrained()->nullOnDelete();
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
        Schema::dropIfExists('comandas');
    }
};
