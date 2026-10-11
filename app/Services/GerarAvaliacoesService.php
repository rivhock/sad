<?php
namespace App\Services;

use App\Models\Avaliacao;
use App\Models\CicloAvaliacao;
use App\Models\Formulario;
use App\Models\Servidor;

class GerarAvaliacoesService
{
    /**
     * Simula a geração sem criar nada.
     */
    public static function simular(CicloAvaliacao $ciclo, array $filtros): array
    {
        $servidores = self::buscarServidores($filtros);

        $criadas = 0;
        $ignoradas = 0;
        $semChefia = 0;
        $semFormulario = 0;

        foreach ($servidores as $servidor) {
            if (self::jaTemAvaliacao($ciclo, $servidor)) {
                $ignoradas++;
                continue;
            }

            if (! self::acharFormulario($servidor)) {
                $semFormulario++;
                continue;
            }

            $chefia = $servidor->chefias()->where('ativo', true)->latest('inicio')->first();

            if (($filtros['so_chefia'] ?? true) && ! $chefia) {
                $semChefia++;
                continue;
            }

            $criadas++;
        }

        return [
            'total_servidores' => $servidores->count(),
            'criadas'          => $criadas,
            'ignoradas'        => $ignoradas,
            'sem_chefia'       => $semChefia,
            'sem_formulario'   => $semFormulario,
        ];
    }

    /**
     * Executa a geração de fato.
     */
    public static function gerar(CicloAvaliacao $ciclo, array $filtros, string $tipo = 'desempenho'): array
    {
        $servidores = self::buscarServidores($filtros);

        $criadas = 0;
        $ignoradas = 0;
        $semChefia = 0;
        $semFormulario = 0;
        $comLink = 0;
        $detalhes = [];

        foreach ($servidores as $servidor) {
            if (self::jaTemAvaliacao($ciclo, $servidor)) {
                $ignoradas++;
                continue;
            }

            $formulario = self::acharFormulario($servidor);
            if (! $formulario) {
                $semFormulario++;
                $detalhes[] = "{$servidor->user?->nome}: sem formulário para {$servidor->categoria?->value}";
                continue;
            }

            $chefia = $servidor->chefias()->where('ativo', true)->latest('inicio')->first();
            $avaliadorId = $chefia?->chefe?->user_id;

            if (($filtros['so_chefia'] ?? true) && ! $avaliadorId) {
                $semChefia++;
                $detalhes[] = "{$servidor->user?->nome}: sem chefia vinculada";
                continue;
            }

            $avaliacao = Avaliacao::create([
                'ciclo_id'       => $ciclo->id,
                'formulario_id'  => $formulario->id,
                'servidor_id'    => $servidor->id,
                'avaliador_id'   => $avaliadorId,
                'tipo'           => $tipo,
                'status'         => 'pendente',
                'periodo_inicio' => $ciclo->data_inicio,
                'periodo_fim'    => $ciclo->data_fim,
            ]);

            // Se não tem chefia vinculada, gera token de avaliador externo
            if (! $avaliadorId) {
                $avaliacao->gerarTokenAvaliador(30);
                $comLink++;
            }

            $criadas++;
        }

        return [
            'total_servidores' => $servidores->count(),
            'criadas'          => $criadas,
            'ignoradas'        => $ignoradas,
            'sem_chefia'       => $semChefia,
            'sem_formulario'   => $semFormulario,
            'com_link'         => $comLink,
            'detalhes'         => $detalhes,
        ];
    }

    protected static function buscarServidores(array $filtros)
    {
        $query = Servidor::query()->where('ativo', true)->with('user');

        if (! empty($filtros['secretarias'])) {
            $query->whereIn('secretaria_id', $filtros['secretarias']);
        }

        if (! empty($filtros['categorias'])) {
            $query->whereIn('categoria', $filtros['categorias']);
        }

        if (! empty($filtros['admissao_de'])) {
            $query->whereDate('data_admissao', '>=', $filtros['admissao_de']);
        }

        if (! empty($filtros['admissao_ate'])) {
            $query->whereDate('data_admissao', '<=', $filtros['admissao_ate']);
        }

        if (! empty($filtros['estagio_de'])) {
            $query->whereDate('data_fim_estagio', '>=', $filtros['estagio_de']);
        }

        if (! empty($filtros['estagio_ate'])) {
            $query->whereDate('data_fim_estagio', '<=', $filtros['estagio_ate']);
        }

        return $query->orderBy('id')->get();
    }

    protected static function jaTemAvaliacao(CicloAvaliacao $ciclo, Servidor $servidor): bool
    {
        return Avaliacao::where('ciclo_id', $ciclo->id)
            ->where('servidor_id', $servidor->id)
            ->exists();
    }

    protected static function acharFormulario(Servidor $servidor): ?Formulario
    {
        $label = match ($servidor->categoria?->value) {
            'saude'        => 'Saúde',
            'magisterio'   => 'Magistério',
            'guarda'       => 'Guarda',
            'quadro_geral' => 'Quadro Geral',
            default        => null,
        };

        if (! $label) {
            return null;
        }

        return Formulario::where('ativo', true)
            ->where('titulo', 'like', '%' . $label . '%')
            ->first();
    }
}
