<?php

namespace App\Filament\Avaliador\Widgets;

use App\Models\Avaliacao;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MinhasAvaliacoesStats extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $userId = auth()->id() ?? 0;

        $pendentes = Avaliacao::where('avaliador_id', $userId)
            ->whereIn('status', ['pendente', 'preenchida'])
            ->count();

        $notificadas = Avaliacao::where('avaliador_id', $userId)
            ->whereIn('status', ['notificada', 'em_defesa'])
            ->count();

        $concluidas = Avaliacao::where('avaliador_id', $userId)
            ->where('status', 'concluida')
            ->count();

        return [
            Stat::make('Pendentes para mim', $pendentes)
                ->description('Aguardando preenchimento')
                ->color($pendentes > 0 ? 'warning' : 'success'),

            Stat::make('Notificadas', $notificadas)
                ->description('Aguardando defesa/assinatura')
                ->color('info'),

            Stat::make('Concluídas', $concluidas)
                ->description('Finalizadas')
                ->color('success'),
        ];
    }
}
