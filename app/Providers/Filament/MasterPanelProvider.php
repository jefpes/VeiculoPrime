<?php

namespace App\Providers\Filament;

use App\Http\Middleware\EnsureCentralDomain;
use Filament\Http\Middleware\{Authenticate, AuthenticateSession, DisableBladeIconComponents, DispatchServingFilamentEvent};
use Filament\Support\Colors\Color;
use Filament\{Pages, Panel, PanelProvider};
use Illuminate\Cookie\Middleware\{AddQueuedCookiesToResponse, EncryptCookies};
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class MasterPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('master')
            ->path('master')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Master/Resources'), for: 'App\\Filament\\Master\\Resources')
            ->discoverWidgets(in: app_path('Filament/Master/Widgets'), for: 'App\\Filament\\Master\\Widgets')
            ->discoverPages(in: app_path('Filament/Master/Pages'), for: 'App\\Filament\\Master\\Pages')
            ->discoverClusters(in: app_path('Filament/Master/Clusters'), for: 'App\\Filament\\Master\\Clusters')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->login()
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
                EnsureCentralDomain::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
