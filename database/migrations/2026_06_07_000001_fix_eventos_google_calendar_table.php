<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop and recreate without FK constraint — a FK para barbearias bloqueava
        // o INSERT quando barbearia_id não existia na tabela de origem, causando 500.
        // O unique composto (barbearia_id, google_event_id) é mais correto que unique simples.
        Schema::dropIfExists('eventos_google_calendar');

        Schema::create('eventos_google_calendar', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('barbearia_id');
            $table->string('google_event_id');
            $table->string('titulo')->nullable();
            $table->text('descricao')->nullable();
            $table->dateTime('inicio');
            $table->dateTime('fim');
            $table->boolean('dia_inteiro')->default(false);
            $table->string('status')->nullable();
            $table->timestamps();

            $table->unique(['barbearia_id', 'google_event_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('eventos_google_calendar');
    }
};
