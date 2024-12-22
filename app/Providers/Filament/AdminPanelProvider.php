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
use Schmeits\FilamentUmami\FilamentUmamiPlugin;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Schmeits\FilamentUmami\Enums\UmamiStatsWidgets;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Joaopaulolndev\FilamentEditEnv\FilamentEditEnvPlugin;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Schmeits\FilamentUmami\Widgets\UmamiWidgetStatsGrouped;
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
                // UmamiWidgetStatsGrouped::class,
                // // this is the grouped stats widget
                // \Schmeits\FilamentUmami\Widgets\UmamiWidgetStatsGrouped::class,

                // these are the separate stats widgets
                // \Schmeits\FilamentUmami\Widgets\UmamiWidgetStatsLiveVisitors::class,
                // \Schmeits\FilamentUmami\Widgets\UmamiWidgetStatsPageViews::class,
                // \Schmeits\FilamentUmami\Widgets\UmamiWidgetStatsVisitors::class,
                // \Schmeits\FilamentUmami\Widgets\UmamiWidgetStatsVisits::class,
                // \Schmeits\FilamentUmami\Widgets\UmamiWidgetStatsBounces::class,
                // \Schmeits\FilamentUmami\Widgets\UmamiWidgetStatsTotalTime::class,

                // // and some table widgets
                // \Schmeits\FilamentUmami\Widgets\UmamiWidgetTableUrls::class,
                // \Schmeits\FilamentUmami\Widgets\UmamiWidgetTableTitle::class,
                // \Schmeits\FilamentUmami\Widgets\UmamiWidgetTableReferrers::class,
                // \Schmeits\FilamentUmami\Widgets\UmamiWidgetTableCountry::class,
                // \Schmeits\FilamentUmami\Widgets\UmamiWidgetTableRegion::class,
                // \Schmeits\FilamentUmami\Widgets\UmamiWidgetTableCity::class,
                // \Schmeits\FilamentUmami\Widgets\UmamiWidgetTableDevice::class,
                // \Schmeits\FilamentUmami\Widgets\UmamiWidgetTableOs::class,
                // \Schmeits\FilamentUmami\Widgets\UmamiWidgetTableBrowser::class,
                // \Schmeits\FilamentUmami\Widgets\UmamiWidgetTableLanguage::class,
                // \Schmeits\FilamentUmami\Widgets\UmamiWidgetTableScreen::class,
                // \Schmeits\FilamentUmami\Widgets\UmamiWidgetTableEvents::class,
                // \Schmeits\FilamentUmami\Widgets\UmamiWidgetTableQuery::class,

                // // grouped table widgets
                // \Schmeits\FilamentUmami\Widgets\UmamiWidgetTableGroupedPages::class,
                // \Schmeits\FilamentUmami\Widgets\UmamiWidgetTableGroupedGeo::class,
                // \Schmeits\FilamentUmami\Widgets\UmamiWidgetTableGroupedClientInfo::class,

                // // chart widgets
                // \Schmeits\FilamentUmami\Widgets\UmamiWidgetGraphPageViews::class,
                // \Schmeits\FilamentUmami\Widgets\UmamiWidgetGraphSessions::class,
                // \Schmeits\FilamentUmami\Widgets\UmamiWidgetGraphEvents::class,
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
                    ->setNavigationGroup('Paramètres')
                    ->setTitle('Paramètres généraux')
                    ->setNavigationLabel('Paramètres généraux'),
                // FilamentUmamiPlugin::make()
                //     ->pollingInterval("60s") //Auto polling interval
                //     ->widgetsForGroupedStats([
                //         UmamiStatsWidgets::WIDGET_LIVE,
                //         UmamiStatsWidgets::WIDGET_PAGEVIEWS,
                //         UmamiStatsWidgets::WIDGET_VISITORS,
                //         UmamiStatsWidgets::WIDGET_TOTAL_TIME,
                //         UmamiStatsWidgets::WIDGET_BOUNCES,
                //         UmamiStatsWidgets::WIDGET_VISITS,
                //     ]),
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
