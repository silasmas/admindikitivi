<?php
namespace App\Filament\Widgets;

use Illuminate\Support\Facades\DB;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CityStatsOverview extends StatsOverviewWidget
{
    protected static ?string $heading = 'Top 3 villes (clients)';
    protected static ?int $sort = 0;
    protected function getStats(): array
    {
        $rows = DB::table('users')
            ->join('role_user', 'users.id', '=', 'role_user.user_id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('roles.role_name', 'Membre')
            ->whereNotNull('users.city')
            ->select('users.city', DB::raw('COUNT(*) as total'))
            ->groupBy('users.city')
            ->orderByDesc('total')
            ->limit(3)
            ->get();

        return $rows->map(function ($row) {
            return Stat::make($row->city ?? 'Inconnu', $row->total)
                ->icon('heroicon-o-map-pin')
                ->color('success');
        })->toArray();
    }
}
