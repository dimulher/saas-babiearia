<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expedientes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barbearia_id')->constrained()->cascadeOnDelete();
            $table->foreignId('profissional_id')->nullable()->constrained()->nullOnDelete();
            $table->tinyInteger('dia_semana'); // 0=Dom, 1=Seg ... 6=Sáb
            $table->time('hora_inicio');
            $table->time('hora_fim');
            $table->time('intervalo_inicio')->nullable();
            $table->time('intervalo_fim')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('horarios_bloqueados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barbearia_id')->constrained()->cascadeOnDelete();
            $table->foreignId('profissional_id')->nullable()->constrained()->nullOnDelete();
            $table->dateTime('data_inicio');
            $table->dateTime('data_fim');
            $table->string('motivo')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('horarios_bloqueados');
        Schema::dropIfExists('expedientes');
    }
};
