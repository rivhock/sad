<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use App\Filament\Pages\MeuPerfil;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use App\Filament\Compaq\Widgets\AvaliacoesStatsWidget;
use App\Filament\Compaq\Widgets\AvaliacoesPorStatusChart;
use App\Filament\Compaq\Widgets\AvaliacoesPorSecretariaChart;
use App\Filament\Compaq\Widgets\UltimasAvaliacoesWidget;
use App\Filament\Compaq\Widgets\CursosPendentesWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class CompaqPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('compaq')
            ->path('compaq')
            ->login()
            ->brandName(new \Illuminate\Support\HtmlString(
                '<img src="' . asset('brasao.png') . '" style="height:2rem;display:inline-block;vertical-align:middle;margin-right:10px;">' .
                '<span style="vertical-align:middle;">SAD — COMPAQ</span>'
            ))
            ->favicon(asset('brasao.png'))
            ->favicon(asset('brasao.png'))
            ->colors(['primary' => Color::Emerald])
            ->navigationGroups([
                'Avaliações',
                'Consultas',
            ])
            ->discoverResources(in: app_path('Filament/Compaq/Resources'), for: 'App\\Filament\\Compaq\\Resources')
            ->discoverPages(in: app_path('Filament/Compaq/Pages'), for: 'App\\Filament\\Compaq\\Pages')
            ->pages([
                Pages\Dashboard::class,
                MeuPerfil::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Compaq/Widgets'), for: 'App\\Filament\\Compaq\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                AvaliacoesStatsWidget::class,
                AvaliacoesPorStatusChart::class,
                AvaliacoesPorSecretariaChart::class,
                UltimasAvaliacoesWidget::class,
                CursosPendentesWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
