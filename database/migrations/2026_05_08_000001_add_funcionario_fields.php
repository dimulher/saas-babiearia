<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profissionais', function (Blueprint $table) {
            $table->string('codigo_acesso', 8)->nullable()->unique()->after('ativo');
        });

        Schema::table('agendamentos', function (Blueprint $table) {
            $table->text('descricao')->nullable()->after('observacoes');
            $table->text('produtos_solicitados')->nullable()->after('descricao');
        });
    }

    public function down(): void
    {
        Schema::table('profissionais', function (Blueprint $table) {
            $table->dropColumn('codigo_acesso');
        });
        Schema::table('agendamentos', function (Blueprint $table) {
            $table->dropColumn(['descricao', 'produtos_solicitados']);
        });
    }
};
