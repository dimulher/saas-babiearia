<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logs_atividades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barbearia_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('acao'); // 'agendamento_criado', 'comanda_fechada', etc.
            $table->string('descricao');
            $table->string('modelo_tipo')->nullable(); // Agendamento, Comanda, etc.
            $table->unsignedBigInteger('modelo_id')->nullable();
            $table->json('dados_antigos')->nullable();
            $table->json('dados_novos')->nullable();
            $table->string('ip')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logs_atividades');
    }
};
