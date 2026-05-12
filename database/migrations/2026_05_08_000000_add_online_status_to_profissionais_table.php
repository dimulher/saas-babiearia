<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('profissionais', function (Blueprint $table) {
            $table->boolean('is_online')->default(false);
            $table->timestamp('ultimo_login_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('profissionais', function (Blueprint $table) {
            $table->dropColumn(['is_online', 'ultimo_login_at']);
        });
    }
};
