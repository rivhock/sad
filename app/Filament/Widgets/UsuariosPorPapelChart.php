<?php
namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\ChartWidget;
use Spatie\Permission\Models\Role;

class UsuariosPorPapelChart extends ChartWidget
{
    protected static ?string $heading = 'Usuários por papel';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $papeis = Role::orderBy('name')->get();

        $labels = [];
        $dados = [];

        foreach ($papeis as $papel) {
            $labels[] = $papel->name;
            $dados[] = User::whereHas('roles', fn ($q) => $q->where('name', $papel->name))
                ->where('ativo', true)
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Usuários ativos',
                    'data' => $dados,
                    'backgroundColor' => [
                        '#3b82f6', // Administrador
                        '#10b981', // RH
                        '#f59e0b', // Avaliador
                        '#8b5cf6', // Avaliado
                        '#ef4444', // COMPAQ
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
