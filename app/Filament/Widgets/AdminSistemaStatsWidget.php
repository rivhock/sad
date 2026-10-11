<?php
namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Role;

class AdminSistemaStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $usuariosAtivos = User::where('ativo', true)->count();
        $usuariosInativos = User::where('ativo', false)->count();

        $totalPapeis = Role::count();

        // Atividades nas últimas 24h
        $atividades24h = Activity::where('created_at', '>=', now()->subDay())->count();

        // Últimas atividades administrativas (create/update/delete)
        $ultimaAtividade = Activity::latest()->first();

        // Tamanho do banco (aproximado via contagem de registros)
        $totalRegistros = \DB::table('activity_log')->count();

        return [
            Stat::make('Usuários ativos', $usuariosAtivos)
                ->description($usuariosInativos . ' inativo(s)')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Papéis configurados', $totalPapeis)
                ->description('Perfis de acesso')
                ->descriptionIcon('heroicon-m-shield-check')
                ->color('info'),

            Stat::make('Ações nas últimas 24h', $atividades24h)
                ->description('Registradas no log')
                ->descriptionIcon('heroicon-m-clock')
                ->color($atividades24h > 0 ? 'warning' : 'gray'),

            Stat::make('Total de registros de auditoria', $totalRegistros)
                ->description($ultimaAtividade
                    ? 'Última: ' . $ultimaAtividade->created_at->diffForHumans()
                    : 'Nenhum registro ainda')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('success'),
        ];
    }
}
