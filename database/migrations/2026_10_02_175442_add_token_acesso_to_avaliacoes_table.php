<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('avaliacoes', function (Blueprint $table) {
            $table->string('token_acesso', 64)->nullable()->unique()->after('id');
            $table->timestamp('token_expira_em')->nullable()->after('token_acesso');
        });
    }

    public function down(): void
    {
        Schema::table('avaliacoes', function (Blueprint $table) {
            $table->dropColumn(['token_acesso', 'token_expira_em']);
        });
    }
};
