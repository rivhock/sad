<?php
namespace App\Filament\Compaq\Widgets;

use App\Models\Avaliacao;
use Filament\Widgets\ChartWidget;

class AvaliacoesPorStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Avaliações por status';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $status = [
            'pendente'   => 'Pendente',
            'preenchida' => 'Preenchida',
            'notificada' => 'Notificada',
            'em_defesa'  => 'Em defesa',
            'concluida'  => 'Concluída',
        ];

        $dados = [];
        $labels = [];

        foreach ($status as $chave => $label) {
            $dados[] = Avaliacao::where('status', $chave)->count();
            $labels[] = $label;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Avaliações',
                    'data' => $dados,
                    'backgroundColor' => [
                        '#f59e0b', // pendente
                        '#3b82f6', // preenchida
                        '#8b5cf6', // notificada
                        '#ef4444', // em defesa
                        '#10b981', // concluída
                    ],
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
