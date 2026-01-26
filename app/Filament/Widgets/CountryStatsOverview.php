<?php
namespace App\Filament\Widgets;

use Illuminate\Support\Facades\DB;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Country;

class CountryStatsOverview extends StatsOverviewWidget
{
    protected ?string $heading = 'Top 3 pays (clients)';

    protected function getStats(): array
    {
        $rows = DB::table('users')
            ->join('role_user', 'users.id', '=', 'role_user.user_id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('roles.role_name', 'Membre')
            ->whereNotNull('users.country_id')
            ->select('users.country_id', DB::raw('COUNT(*) as total'))
            ->groupBy('users.country_id')
            ->orderByDesc('total')
            ->limit(3)
            ->get();

        return $rows->map(function ($row) {
            $country = Country::find($row->country_id);
            return Stat::make($country?->country_name ?? 'Inconnu', $row->total)
                ->icon('heroicon-o-globe-alt')
                ->color('primary');
        })->toArray();
    }
}
