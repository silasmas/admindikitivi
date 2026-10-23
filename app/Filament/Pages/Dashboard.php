<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ClientTotalOverview;
use App\Filament\Widgets\ClientsPerMonthChart;
use App\Filament\Widgets\CountryStatsOverview;
use App\Filament\Widgets\MediaByCategoryPieChart;
use App\Filament\Widgets\MediasByMonthChart;
use App\Filament\Widgets\MediasStats;
use App\Filament\Widgets\MediaTypePieChart;
use App\Filament\Widgets\UserRoleStats;

/**
 * Tableau de bord Filament : widgets explicites et limités
 * pour éviter une tempête de connexions MySQL (SQLSTATE 2002) sur l’hébergement.
 */
class Dashboard extends \Filament\Pages\Dashboard
{
    /**
     * Widgets affichés sur la page d’accueil admin.
     *
     * @return array<class-string>
     */
    public function getWidgets(): array
    {
        return [
            ClientTotalOverview::class,
            MediasStats::class,
            UserRoleStats::class,
            CountryStatsOverview::class,
            ClientsPerMonthChart::class,
            MediasByMonthChart::class,
            MediaByCategoryPieChart::class,
            MediaTypePieChart::class,
        ];
    }

    /**
     * Colonnes de grille du dashboard.
     *
     * @return int|string|array
     */
    public function getColumns(): int|string|array
    {
        return 2;
    }
}
