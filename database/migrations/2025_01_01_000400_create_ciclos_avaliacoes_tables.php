<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ciclos_avaliacao', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->date('data_inicio');
            $table->date('data_fim');
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('grupos_avaliacao', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->foreignId('ciclo_id')->nullable()->constrained('ciclos_avaliacao')->nullOnDelete();
            $table->text('descricao')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('grupo_servidor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grupo_id')->constrained('grupos_avaliacao')->cascadeOnDelete();
            $table->foreignId('servidor_id')->constrained('servidores')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['grupo_id','servidor_id']);
        });

        Schema::create('avaliacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ciclo_id')->constrained('ciclos_avaliacao')->cascadeOnDelete();
            $table->foreignId('formulario_id')->constrained('formularios');
            $table->foreignId('servidor_id')->constrained('servidores');
            $table->foreignId('avaliador_id')->constrained('users');
            $table->string('tipo')->default('desempenho');
            $table->string('status')->default('pendente');
            $table->date('periodo_inicio')->nullable();
            $table->date('periodo_fim')->nullable();
            $table->timestamp('data_avaliacao')->nullable();
            $table->decimal('nota_total', 6, 2)->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamp('data_notificacao')->nullable();
            $table->timestamp('prazo_defesa')->nullable();
            $table->timestamps();
            $table->index(['ciclo_id','status']);
            $table->index(['servidor_id','status']);
        });

        Schema::create('respostas_itens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('avaliacao_id')->constrained('avaliacoes')->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('itens_formulario')->cascadeOnDelete();
            $table->decimal('nota', 5, 2);
            $table->text('comentario')->nullable();
            $table->timestamps();
            $table->unique(['avaliacao_id','item_id']);
        });

        Schema::create('defesas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('avaliacao_id')->constrained('avaliacoes')->cascadeOnDelete();
            $table->text('texto')->nullable();
            $table->timestamp('data_protocolo')->nullable();
            $table->string('anexo')->nullable();
            $table->foreignId('protocolado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('assinaturas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('avaliacao_id')->constrained('avaliacoes')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users');
            $table->string('tipo_assinante');
            $table->string('hash', 64);
            $table->ipAddress('ip')->nullable();
            $table->timestamp('assinado_em');
            $table->timestamps();
        });

        Schema::create('logs_auditoria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('acao');
            $table->string('entidade')->nullable();
            $table->unsignedBigInteger('entidade_id')->nullable();
            $table->jsonb('payload')->nullable();
            $table->ipAddress('ip')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('data_hora')->useCurrent();
            $table->index(['entidade','entidade_id']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logs_auditoria');
        Schema::dropIfExists('assinaturas');
        Schema::dropIfExists('defesas');
        Schema::dropIfExists('respostas_itens');
        Schema::dropIfExists('avaliacoes');
        Schema::dropIfExists('grupo_servidor');
        Schema::dropIfExists('grupos_avaliacao');
        Schema::dropIfExists('ciclos_avaliacao');
    }
};
