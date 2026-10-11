<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tipo_avaliacoes', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->string('nome');
            $table->string('anexo')->nullable();
            $table->string('categoria')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });

        Schema::create('formularios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipo_avaliacao_id')->constrained('tipo_avaliacoes')->cascadeOnDelete();
            $table->string('titulo');
            $table->integer('versao')->default(1);
            $table->jsonb('schema_json')->nullable();
            $table->string('calculo')->default('soma');
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->unique(['tipo_avaliacao_id','versao','titulo']);
        });

        Schema::create('itens_formulario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formulario_id')->constrained('formularios')->cascadeOnDelete();
            $table->integer('ordem');
            $table->text('descricao');
            $table->decimal('peso', 6, 2)->default(1);
            $table->decimal('nota_min', 4, 2)->default(0);
            $table->decimal('nota_max', 4, 2)->default(10);
            $table->timestamps();
            $table->index(['formulario_id','ordem']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('itens_formulario');
        Schema::dropIfExists('formularios');
        Schema::dropIfExists('tipo_avaliacoes');
    }
};
