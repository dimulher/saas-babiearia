<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agendamentos', function (Blueprint $table) {
            $table->string('google_event_id')->nullable()->after('lembrete_enviado');
        });
    }

    public function down(): void
    {
        Schema::table('agendamentos', function (Blueprint $table) {
            $table->dropColumn('google_event_id');
        });
    }
};
