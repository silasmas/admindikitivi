<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class GenderStatsOverview extends StatsOverviewWidget
{
    protected static ?string $heading = 'Répartition des clients par sexe';



protected ?string $description = 'Statistiques des événements';
    protected static ?int $sort = 0;
    protected function getStats(): array
    {
        $query = DB::table('users')
            ->join('role_user', 'users.id', '=', 'role_user.user_id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('roles.role_name', 'Membre');

        $hommes = (clone $query)->where('gender', 'h')->count();
        $femmes = (clone $query)->where('gender', 'f')->count();
        $autres = (clone $query)->whereNull('gender')->orWhereNotIn('gender', ['homme', 'femme'])->count();

        return [
            Stat::make('Hommes', $hommes)
                ->icon('heroicon-o-user')
                ->description('Client par sexe masculin')
                ->color('blue'),

            Stat::make('Femmes', $femmes)
                ->icon('heroicon-o-user')
                ->description('Client par sexe féminin')
                ->color('pink'),

            Stat::make('Non spécifié', $autres)
                ->icon('heroicon-o-question-mark-circle')
                ->description('Client par sexe non spécifié')
                ->color('gray'),
        ];
    }
}
