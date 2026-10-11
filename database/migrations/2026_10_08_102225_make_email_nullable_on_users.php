<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 1. Remove a constraint unique (não o índice)
        DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS users_email_unique');

        // 2. Torna a coluna nullable
        DB::statement('ALTER TABLE users ALTER COLUMN email DROP NOT NULL');

        // 3. Recria unique condicional (permite vários NULL, mas email real é único)
        DB::statement('CREATE UNIQUE INDEX users_email_unique ON users (email) WHERE email IS NOT NULL');
    }

    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS users_email_unique');
        DB::statement('ALTER TABLE users ALTER COLUMN email SET NOT NULL');
        DB::statement('ALTER TABLE users ADD CONSTRAINT users_email_unique UNIQUE (email)');
    }
};
