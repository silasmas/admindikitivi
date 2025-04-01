<?php
namespace App\Filament\Widgets;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget;

class UserRoleStats extends StatsOverviewWidget
{
    protected static ?int $sort = -1;
    protected function getStats(): array
    {
        $clients = countUsersByRole('Membre');
        $agents = countUsersByRole('Administrateur');

        return [
            Stat::make('Clients', $clients)->color('success')->icon('heroicon-o-user-group'),
            Stat::make('Agents', $agents)->color('warning')->icon('heroicon-o-shield-check'),
        ];
    }

}
