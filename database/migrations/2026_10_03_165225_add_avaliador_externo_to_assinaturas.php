<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('assinaturas', function (Blueprint $table) {
            $table->string('nome_avaliador_externo')->nullable()->after('user_id');
            $table->string('cpf_avaliador_externo', 11)->nullable()->after('nome_avaliador_externo');
        });

        // Torna user_id nullable (SQL puro, sem precisar dbal)
        DB::statement('ALTER TABLE assinaturas ALTER COLUMN user_id DROP NOT NULL');
    }

    public function down(): void
    {
        Schema::table('assinaturas', function (Blueprint $table) {
            $table->dropColumn(['nome_avaliador_externo', 'cpf_avaliador_externo']);
        });
    }
};
