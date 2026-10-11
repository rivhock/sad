<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('lotes_avaliador', function (Blueprint $table) {
            $table->id();
            $table->string('token', 64)->unique();
            $table->string('nome_avaliador')->nullable();
            $table->string('cpf_avaliador', 11)->nullable();
            $table->timestamp('identificado_em')->nullable();
            $table->timestamp('expira_em')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });

        Schema::create('avaliacao_lote', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lote_id')->constrained('lotes_avaliador')->cascadeOnDelete();
            $table->foreignId('avaliacao_id')->constrained('avaliacoes')->cascadeOnDelete();
            $table->timestamp('preenchida_em')->nullable();
            $table->timestamp('assinada_em')->nullable();
            $table->timestamps();
            $table->unique(['lote_id', 'avaliacao_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avaliacao_lote');
        Schema::dropIfExists('lotes_avaliador');
    }
};
