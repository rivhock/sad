<?php
namespace App\Services;

use App\Enums\CategoriaServidor;
use App\Models\Cargo;
use App\Models\Lotacao;
use App\Models\Secretaria;
use App\Models\Servidor;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class ImportacaoServidoresService
{
    /**
     * Processa um array de linhas do CSV.
     * Retorna: ['criados' => int, 'ignorados' => int, 'erros' => []]
     */
    public static function processar(array $linhas): array
    {
        $criados = 0;
        $ignorados = 0;
        $erros = [];

        Role::firstOrCreate(['name' => 'Avaliado', 'guard_name' => 'web']);

        foreach ($linhas as $i => $linha) {
            $numeroLinha = $i + 2; // +2 porque a linha 1 é cabeçalho

            try {
                $resultado = self::processarLinha($linha);
                if ($resultado === 'criado') {
                    $criados++;
                } elseif ($resultado === 'ignorado') {
                    $ignorados++;
                }
            } catch (\Throwable $e) {
                $erros[] = "Linha {$numeroLinha}: " . $e->getMessage();
            }
        }

        return [
            'criados'   => $criados,
            'ignorados' => $ignorados,
            'erros'     => $erros,
        ];
    }

    protected static function processarLinha(array $linha): string
    {
        $dados = [
            'nome'             => self::normalizarNome($linha['nome'] ?? ''),
            'email'            => trim($linha['email'] ?? ''),
            'cpf'              => preg_replace('/\D/', '', $linha['cpf'] ?? ''),
            'matricula'        => trim($linha['matricula'] ?? ''),
            'cargo'            => self::normalizarNome($linha['cargo'] ?? ''),
            'secretaria'       => trim($linha['secretaria'] ?? ''),
            'lotacao'          => trim($linha['lotacao'] ?? ''),
            'categoria'        => strtolower(trim($linha['categoria'] ?? '')),
            'classe'           => strtoupper(trim($linha['classe'] ?? '')),
            'data_admissao'    => trim($linha['data_admissao'] ?? ''),
            'data_fim_estagio' => trim($linha['data_fim_estagio'] ?? ''),
        ];

        // ---------- VALIDAÇÕES ----------
        if (empty($dados['nome'])) {
            throw new \Exception("Nome vazio");
        }

        // Email: só valida se preenchido (não é mais obrigatório)
        if (! empty($dados['email']) && ! filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
            throw new \Exception("Email inválido: {$dados['email']}");
        }

        // ---------- DUPLICIDADES ----------
        // Email duplicado (só se preenchido)
        if (! empty($dados['email']) && User::where('email', $dados['email'])->exists()) {
            return 'ignorado';
        }

        // Matrícula duplicada
        if (! empty($dados['matricula']) && User::where('matricula', $dados['matricula'])->exists()) {
            return 'ignorado';
        }

        // CPF duplicado
        if (! empty($dados['cpf']) && User::where('cpf', $dados['cpf'])->exists()) {
            return 'ignorado';
        }

        // ---------- CATEGORIA ----------
        $categoria = null;
        if (! empty($dados['categoria'])) {
            $cat = strtolower($dados['categoria']);
            $cat = match (true) {
                str_contains($cat, 'saude')        => CategoriaServidor::SAUDE,
                str_contains($cat, 'magist')       => CategoriaServidor::MAGISTERIO,
                str_contains($cat, 'guarda')       => CategoriaServidor::GUARDA,
                str_contains($cat, 'quadro') || str_contains($cat, 'geral') => CategoriaServidor::QUADRO_GERAL,
                default                            => null,
            };
            $categoria = $cat?->value;
        }
        $categoria ??= CategoriaServidor::QUADRO_GERAL->value;

        // ---------- CARGO ----------
        $cargo = null;
        if (! empty($dados['cargo'])) {
            $cargo = Cargo::firstOrCreate(
                ['nome' => $dados['cargo']],
                ['categoria' => $categoria, 'ativo' => true]
            );
        }

        // ---------- SECRETARIA ----------
        $secretaria = null;
        if (! empty($dados['secretaria'])) {
            $secretaria = Secretaria::where('sigla', $dados['secretaria'])
                ->orWhere('nome', 'like', '%' . $dados['secretaria'] . '%')
                ->first();
        }

        // ---------- LOTAÇÃO ----------
        $lotacao = null;
        if (! empty($dados['lotacao'])) {
            $lotacao = Lotacao::firstOrCreate(
                ['nome' => $dados['lotacao']],
                ['secretaria_id' => $secretaria?->id, 'ativo' => true]
            );
        }

        // ---------- DATAS ----------
        $dataAdmissao = self::parseData($dados['data_admissao']);
        $dataFimEstagio = self::parseData($dados['data_fim_estagio']);

        // ---------- CRIA ----------
        DB::transaction(function () use ($dados, $categoria, $cargo, $secretaria, $lotacao, $dataAdmissao, $dataFimEstagio) {
            $user = User::create([
                'nome'      => $dados['nome'],
                'email'     => $dados['email'] ?: null,   // null se vazio
                'cpf'       => $dados['cpf'] ?: null,
                'matricula' => $dados['matricula'] ?: null,
                'password'  => Hash::make('mudar123'),
                'ativo'     => true,
            ]);
            $user->assignRole('Avaliado');

            Servidor::create([
                'user_id'          => $user->id,
                'cargo_id'         => $cargo?->id,
                'secretaria_id'    => $secretaria?->id,
                'lotacao_id'       => $lotacao?->id,
                'categoria'        => $categoria,
                'classe'           => $dados['classe'] ?: null,
                'data_admissao'    => $dataAdmissao,
                'data_fim_estagio' => $dataFimEstagio,
                'ativo'            => true,
            ]);
        });

        return 'criado';
    }

    /**
     * Padroniza nome para Title Case respeitando preposições.
     * Ex: "joão DA silva" -> "João da Silva"
     */
    protected static function normalizarNome(?string $texto): ?string
    {
        if (empty($texto)) {
            return $texto;
        }

        // Preposições que ficam em minúsculo
        $minusculas = ['da','de','do','das','dos','e','em','na','no','nas','nos','a','o','ao','à','às','aos','para','por','com'];

        // Normaliza para minúsculo primeiro
        $texto = mb_strtolower(trim($texto), 'UTF-8');

        // Divide em palavras
        $palavras = explode(' ', $texto);
        $resultado = [];

        foreach ($palavras as $i => $palavra) {
            if (empty($palavra)) continue;

            // Primeira palavra sempre em maiúsculo
            // Preposições no meio ficam minúsculas
            if ($i === 0 || ! in_array($palavra, $minusculas)) {
                $resultado[] = mb_strtoupper(mb_substr($palavra, 0, 1, 'UTF-8'), 'UTF-8')
                             . mb_substr($palavra, 1, null, 'UTF-8');
            } else {
                $resultado[] = $palavra;
            }
        }

        return implode(' ', $resultado);
    }

    protected static function parseData(?string $data): ?string
    {
        if (empty($data)) {
            return null;
        }

        if (preg_match('#^(\d{2})/(\d{2})/(\d{4})$#', $data, $m)) {
            return "{$m[3]}-{$m[2]}-{$m[1]}";
        }

        if (preg_match('#^(\d{4})-(\d{2})-(\d{2})$#', $data)) {
            return $data;
        }

        return null;
    }
}
