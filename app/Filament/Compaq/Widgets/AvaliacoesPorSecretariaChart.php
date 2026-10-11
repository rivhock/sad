<?php
namespace App\Filament\Compaq\Widgets;

use App\Models\Avaliacao;
use App\Models\Secretaria;
use Filament\Widgets\ChartWidget;

class AvaliacoesPorSecretariaChart extends ChartWidget
{
    protected static ?string $heading = 'Avaliações por secretaria';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $secretarias = Secretaria::where('ativo', true)->orderBy('nome')->get();

        $labels = [];
        $concluidas = [];
        $pendentes = [];

        foreach ($secretarias as $s) {
            $labels[] = $s->sigla ?? $s->nome;

            $concluidas[] = Avaliacao::whereHas('servidor', function ($q) use ($s) {
                $q->where('secretaria_id', $s->id);
            })->where('status', 'concluida')->count();

            $pendentes[] = Avaliacao::whereHas('servidor', function ($q) use ($s) {
                $q->where('secretaria_id', $s->id);
            })->whereIn('status', ['pendente', 'preenchida', 'notificada', 'em_defesa'])->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Concluídas',
                    'data' => $concluidas,
                    'backgroundColor' => '#10b981',
                ],
                [
                    'label' => 'Em andamento',
                    'data' => $pendentes,
                    'backgroundColor' => '#f59e0b',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
