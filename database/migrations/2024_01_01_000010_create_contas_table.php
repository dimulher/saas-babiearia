<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barbearia_id')->constrained()->cascadeOnDelete();
            $table->string('descricao');
            $table->decimal('valor', 10, 2);
            $table->enum('tipo', ['receita', 'despesa']);
            $table->enum('status', ['pendente', 'pago', 'cancelado'])->default('pendente');
            $table->date('vencimento');
            $table->date('pago_em')->nullable();
            $table->enum('recorrencia', ['nenhuma', 'semanal', 'mensal', 'anual'])->default('nenhuma');
            $table->integer('parcela_atual')->default(1);
            $table->integer('total_parcelas')->default(1);
            $table->string('categoria')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contas');
    }
};
