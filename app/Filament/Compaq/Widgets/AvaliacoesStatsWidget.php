<?php
namespace App\Filament\Compaq\Widgets;

use App\Filament\Compaq\Resources\AvaliacaoResource;
use App\Models\Avaliacao;
use App\Models\CicloAvaliacao;
use App\Models\Qualificacao;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AvaliacoesStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $cicloAtual = CicloAvaliacao::where('ativo', true)->orderByDesc('data_inicio')->first();

        $baseQuery = Avaliacao::query();
        if ($cicloAtual) {
            $baseQuery->where('ciclo_id', $cicloAtual->id);
        }

        $pendentes   = (clone $baseQuery)->whereIn('status', ['pendente', 'preenchida'])->count();
        $notificadas = (clone $baseQuery)->where('status', 'notificada')->count();
        $emDefesa    = (clone $baseQuery)->where('status', 'em_defesa')->count();
        $concluidas  = (clone $baseQuery)->where('status', 'concluida')->count();
        $total       = (clone $baseQuery)->count();

        // Aguardando homologação da COMPAQ:
        // tem chefia assinada MAS ainda não tem compaq assinada
        $aguardandoCompaq = Avaliacao::whereHas('assinaturas', function ($q) {
                $q->where('tipo_assinante', 'chefia');
            })
            ->whereDoesntHave('assinaturas', function ($q) {
                $q->where('tipo_assinante', 'compaq');
            })
            ->count();

        $cursosPendentes = Qualificacao::where('status', 'pendente')->count();

        $percentual = $total > 0 ? round(($concluidas / $total) * 100) : 0;

        return [
            Stat::make('Avaliações ativas', $total)
                ->description($cicloAtual?->nome ?? 'Nenhum ciclo ativo')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('primary'),

            Stat::make('Pendentes', $pendentes)
                ->description('Aguardando preenchimento')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendentes > 0 ? 'warning' : 'gray'),

            Stat::make('Aguardando COMPAQ', $aguardandoCompaq)
                ->description($aguardandoCompaq > 0 ? 'Clique para homologar' : 'Nada para homologar')
                ->descriptionIcon($aguardandoCompaq > 0 ? 'heroicon-m-shield-check' : 'heroicon-m-check-badge')
                ->color($aguardandoCompaq > 0 ? 'info' : 'gray')
                ->url($aguardandoCompaq > 0
                    ? AvaliacaoResource::getUrl('index', ['tableFilters' => ['aguardando_compaq' => ['isActive' => true]]])
                    : null),

            Stat::make('Em defesa', $emDefesa)
                ->description($notificadas . ' notificada(s) sem resposta')
                ->descriptionIcon('heroicon-m-scale')
                ->color($emDefesa > 0 ? 'danger' : 'gray'),

            Stat::make('Concluídas', $concluidas . ' (' . $percentual . '%)')
                ->description('Finalizadas no ciclo')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Cursos em análise', $cursosPendentes)
                ->description('Certificados aguardando')
                ->descriptionIcon('heroicon-m-academic-cap')
                ->color($cursosPendentes > 0 ? 'warning' : 'gray')
                ->url($cursosPendentes > 0
                    ? \App\Filament\Compaq\Resources\QualificacaoResource::getUrl('index', ['tableFilters' => ['status' => ['value' => 'pendente']]])
                    : null),
        ];
    }
}
