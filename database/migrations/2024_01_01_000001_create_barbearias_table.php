<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barbearias', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('slug')->unique(); // link público: /gabriel-y1yte
            $table->string('email')->unique();
            $table->string('telefone')->nullable();
            $table->string('logo')->nullable();
            $table->string('capa')->nullable();
            $table->text('descricao')->nullable();
            $table->string('endereco')->nullable();
            $table->string('cidade')->nullable();
            $table->string('estado', 2)->nullable();
            $table->string('cep', 9)->nullable();
            $table->string('instagram')->nullable();
            $table->string('facebook')->nullable();
            $table->string('whatsapp')->nullable();
            $table->boolean('ativo')->default(true);
            $table->string('plano')->default('gratuito'); // gratuito, basico, profissional, premium
            $table->timestamp('plano_expira_em')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barbearias');
    }
};
