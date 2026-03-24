<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agendamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barbearia_id')->constrained()->cascadeOnDelete();
            $table->foreignId('profissional_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cliente_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('servico_id')->constrained()->cascadeOnDelete();
            $table->string('cliente_nome')->nullable(); // para agendamentos sem cadastro
            $table->string('cliente_telefone')->nullable();
            $table->dateTime('data_inicio');
            $table->dateTime('data_fim');
            $table->decimal('preco', 10, 2);
            $table->enum('status', ['pendente', 'confirmado', 'concluido', 'cancelado', 'faltou'])->default('pendente');
            $table->text('observacoes')->nullable();
            $table->boolean('agendado_online')->default(false);
            $table->boolean('lembrete_enviado')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agendamentos');
    }
};
