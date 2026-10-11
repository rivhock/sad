<?php
namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Spatie\Activitylog\Models\Activity;

class AtividadesPorDiaChart extends ChartWidget
{
    protected static ?string $heading = 'Atividades do sistema (últimos 7 dias)';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $labels = [];
        $criados = [];
        $alterados = [];
        $excluidos = [];

        for ($i = 6; $i >= 0; $i--) {
            $dia = now()->subDays($i);
            $labels[] = $dia->format('d/m');

            $criados[] = Activity::whereDate('created_at', $dia->toDateString())->where('event', 'created')->count();
            $alterados[] = Activity::whereDate('created_at', $dia->toDateString())->where('event', 'updated')->count();
            $excluidos[] = Activity::whereDate('created_at', $dia->toDateString())->where('event', 'deleted')->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Criados',
                    'data' => $criados,
                    'backgroundColor' => '#10b981',
                ],
                [
                    'label' => 'Alterados',
                    'data' => $alterados,
                    'backgroundColor' => '#f59e0b',
                ],
                [
                    'label' => 'Excluídos',
                    'data' => $excluidos,
                    'backgroundColor' => '#ef4444',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
