<?php
namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ClientTotalOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Statistiques générales des clients';
    protected ?string $maxWidth = 'full';

    protected static ?int $sort = -1;
    protected function getStats(): array
    {
        $base = usersByRoleQuery('Membre');

        return [
            Stat::make('Total Clients', (clone $base)->count())
                ->icon('heroicon-o-users')
                ->color('primary'),

            Stat::make('Email vérifié', (clone $base)->whereNotNull('email_verified_at')->count())
                ->icon('heroicon-o-envelope')
                ->color('success'),

            Stat::make('Téléphone vérifié', (clone $base)->whereNotNull('phone_verified_at')->count())
                ->icon('heroicon-o-phone')
                ->color('success'),
        ];
    }
}
