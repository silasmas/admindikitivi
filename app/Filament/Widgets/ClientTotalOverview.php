<?php
namespace App\Filament\Widgets;

use Illuminate\Support\Facades\DB;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ClientTotalOverview extends StatsOverviewWidget
{
    protected static ?string $heading = 'Statistiques générales des clients';
    protected static ?string $maxWidth = 'full';

    protected static ?int $sort = -1;
    protected function getStats(): array
    {
        $base = DB::table('users')
            ->join('role_user', 'users.id', '=', 'role_user.user_id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('roles.role_name', 'Membre');

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
