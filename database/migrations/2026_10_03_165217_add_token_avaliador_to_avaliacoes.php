<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('avaliacoes', function (Blueprint $table) {
            $table->string('token_avaliador', 64)->nullable()->unique()->after('token_acesso');
            $table->timestamp('token_avaliador_expira_em')->nullable()->after('token_avaliador');
        });
    }

    public function down(): void
    {
        Schema::table('avaliacoes', function (Blueprint $table) {
            $table->dropColumn(['token_avaliador', 'token_avaliador_expira_em']);
        });
    }
};
