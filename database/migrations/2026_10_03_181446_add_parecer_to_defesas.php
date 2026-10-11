<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('defesas', function (Blueprint $table) {
            $table->string('parecer_decisao')->nullable()->after('anexo');
            $table->text('parecer_texto')->nullable()->after('parecer_decisao');
            $table->decimal('parecer_nota_anterior', 6, 2)->nullable()->after('parecer_texto');
            $table->decimal('parecer_nota_nova', 6, 2)->nullable()->after('parecer_nota_anterior');
            $table->timestamp('parecer_em')->nullable()->after('parecer_nota_nova');
            $table->foreignId('parecer_por')->nullable()->after('parecer_em')
                ->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('defesas', function (Blueprint $table) {
            $table->dropForeign(['parecer_por']);
            $table->dropColumn([
                'parecer_decisao','parecer_texto','parecer_nota_anterior',
                'parecer_nota_nova','parecer_em','parecer_por',
            ]);
        });
    }
};
