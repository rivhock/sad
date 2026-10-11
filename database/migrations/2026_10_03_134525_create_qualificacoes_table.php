<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('qualificacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('servidor_id')->constrained('servidores')->cascadeOnDelete();
            $table->string('titulo');
            $table->string('instituicao')->nullable();
            $table->string('tipo')->default('curso');
            $table->integer('carga_horaria');
            $table->date('data_inicio');
            $table->date('data_fim');
            $table->string('arquivo_pdf');
            $table->string('status')->default('pendente');
            $table->foreignId('avaliado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('data_avaliacao')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['servidor_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qualificacoes');
    }
};
