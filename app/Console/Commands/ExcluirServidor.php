<?php
namespace App\Console\Commands;

use App\Models\Servidor;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExcluirServidor extends Command
{
    protected $signature = 'sad:excluir-servidor {matricula : Matricula do servidor} {--forcar : Ignora avisos sobre historico}';
    protected $description = 'Exclui um servidor definitivamente (use apenas para casos especiais)';

    public function handle(): int
    {
        $matricula = $this->argument('matricula');
        $forcar = $this->option('forcar');

        $user = User::where('matricula', $matricula)->first();
        if (! $user) {
            $this->error("Servidor com matricula {$matricula} nao encontrado");
            return 1;
        }

        $servidor = $user->servidor;
        $nome = $user->nome;

        if ($servidor) {
            $avaliacoes = \App\Models\Avaliacao::where('servidor_id', $servidor->id)->count();
            $qualificacoes = \App\Models\Qualificacao::where('servidor_id', $servidor->id)->count();
            $assinaturas = \App\Models\Assinatura::whereHas('avaliacao', function ($q) use ($servidor) {
                $q->where('servidor_id', $servidor->id);
            })->count();

            $this->warn("Servidor: {$nome} (mat. {$matricula})");
            $this->info("Vinculos:");
            $this->line("  Avaliacoes:   {$avaliacoes}");
            $this->line("  Cursos:       {$qualificacoes}");
            $this->line("  Assinaturas:  {$assinaturas}");

            if (($avaliacoes + $qualificacoes + $assinaturas) > 0 && ! $forcar) {
                $this->error("Ha vinculos com historico. Use --forcar para excluir mesmo assim (CUIDADO!).");
                return 1;
            }

            if ($forcar && ($avaliacoes + $qualificacoes) > 0) {
                if (! $this->confirm('Isso vai APAGAR todo o historico. Tem certeza?', false)) {
                    return 0;
                }
            }
        }

        DB::transaction(function () use ($servidor, $user) {
            if ($servidor) {
                $servidor->forceDelete();
            }
            $user->forceDelete();
        });

        $this->info("Servidor {$nome} excluido definitivamente");
        return 0;
    }
}
