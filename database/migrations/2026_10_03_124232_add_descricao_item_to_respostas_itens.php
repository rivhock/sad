<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('respostas_itens', function (Blueprint $table) {
            $table->text('descricao_item')->nullable()->after('item_id');
            $table->integer('ordem_item')->nullable()->after('descricao_item');
        });
    }

    public function down(): void
    {
        Schema::table('respostas_itens', function (Blueprint $table) {
            $table->dropColumn(['descricao_item', 'ordem_item']);
        });
    }
};
