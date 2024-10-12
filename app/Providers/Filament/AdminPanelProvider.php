<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Navigation\Item;
use Filament\Support\Colors\Color;
use Filament\Navigation\NavigationGroup;
use Filament\Http\Middleware\Authenticate;
use Filament\SpatieLaravelTranslatablePlugin;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Joaopaulolndev\FilamentEditEnv\FilamentEditEnvPlugin;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Joaopaulolndev\FilamentGeneralSettings\FilamentGeneralSettingsPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName('Dashboard DIKITIVI')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->brandLogo(asset('assets/images/logo/logo-text.png'))
            ->brandLogoHeight(fn() => auth()->check() ? '3rem' : '5rem')
            ->favicon(asset('assets/images/logo/logo.png'))
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
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
            ])
            ->plugins(
                [FilamentEditEnvPlugin::make()
                    ->showButton(fn() => auth()->user()->id === 1)
                    ->setIcon('heroicon-o-cog'),]
            )
            ->plugin(SpatieLaravelTranslatablePlugin::make()->defaultLocales(['fr', 'en', 'ln']),)
            ->plugins([
                FilamentGeneralSettingsPlugin::make()
                    ->canAccess(fn() => auth()->user()->id === 1)
                    ->setSort(3)
                    ->setIcon('heroicon-o-cog')
                    ->setNavigationGroup('Settings')
                    ->setTitle('General Settings')
                    ->setNavigationLabel('General Settings'),
                // \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make()
                //     ->gridColumns([
                //         'default' => 1,
                //         'sm' => 2,
                //         'lg' => 3
                //     ])
                //     ->sectionColumnSpan(1)
                //     ->checkboxListColumns([
                //         'default' => 1,
                //         'sm' => 2,
                //         'lg' => 4,
                //     ])
                //     ->resourceCheckboxListColumns([
                //         'default' => 1,
                //         'sm' => 2,
                //     ]),
            ]);
    }
    // public static function getNavigationItems(): array
    // {
    //     return [
    //         Item::make('Élément Principal')
    //             ->label('Libellé Principal')
    //             ->subItems([
    //                 Item::make('Sous-élément 1')->label('Libellé Sous-élément 1'),
    //                 Item::make('Sous-élément 2')->label('Libellé Sous-élément 2'),
    //             ]),
    //         // Ajoutez d'autres éléments ici
    //     ];
    // }
    // public static function getNavigationGroups(): array
    // {
    //     return [
    //         NavigationGroup::make('Mon Groupe')
    //             ->label('Nouveau Libellé')
    //             ->icon('heroicon-o-home'),
    //     ];
    // }
}
