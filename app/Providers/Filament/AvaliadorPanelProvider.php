<?php

namespace App\Providers\Filament;

use App\Filament\Avaliador\Widgets\MinhasAvaliacoesStats;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use App\Filament\Pages\MeuPerfil;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AvaliadorPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('avaliador')
            ->path('avaliador')
            ->login()
            ->brandName(new \Illuminate\Support\HtmlString(
                '<img src="' . asset('brasao.png') . '" style="height:2rem;display:inline-block;vertical-align:middle;margin-right:10px;">' .
                '<span style="vertical-align:middle;">SAD — Avaliador</span>'
            ))
            ->favicon(asset('brasao.png'))
            ->colors(['primary' => Color::Amber])
            ->navigationGroups([
                'Minhas Avaliações',
            ])
            ->discoverResources(in: app_path('Filament/Avaliador/Resources'), for: 'App\\Filament\\Avaliador\\Resources')
            ->discoverPages(in: app_path('Filament/Avaliador/Pages'), for: 'App\\Filament\\Avaliador\\Pages')
            ->pages([
                Pages\Dashboard::class,
                MeuPerfil::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Avaliador/Widgets'), for: 'App\\Filament\\Avaliador\\Widgets')
            ->widgets([
                
                Widgets\AccountWidget::class,
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
