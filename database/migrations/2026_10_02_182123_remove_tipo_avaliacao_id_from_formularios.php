<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('formularios', function (Blueprint $table) {
            $table->dropForeign(['tipo_avaliacao_id']);
            $table->dropUnique(['tipo_avaliacao_id', 'versao', 'titulo']);
            $table->dropColumn('tipo_avaliacao_id');
            $table->unique(['versao', 'titulo']);
        });
    }

    public function down(): void
    {
        Schema::table('formularios', function (Blueprint $table) {
            $table->dropUnique(['versao', 'titulo']);
            $table->foreignId('tipo_avaliacao_id')->nullable()->after('id')
                ->constrained('tipo_avaliacoes')->cascadeOnDelete();
            $table->unique(['tipo_avaliacao_id', 'versao', 'titulo']);
        });
    }
};
