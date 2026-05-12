<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assinaturas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barbearia_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cliente_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plano_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('ativo'); // ativo, pausado, inadimplente, cancelado
            $table->integer('dia_vencimento')->default(1);
            $table->date('data_inicio');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assinaturas');
    }
};
