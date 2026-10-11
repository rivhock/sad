<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::table('qualificacoes')
            ->whereIn('tipo', ['especializacao', 'pos', 'outro'])
            ->update(['tipo' => 'curso']);
    }

    public function down(): void {}
};
