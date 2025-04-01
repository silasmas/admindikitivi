<?php
namespace App\Filament\Widgets;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\PieChartWidget;

class GenderDistributionChart extends PieChartWidget
{
    protected static ?string $heading = 'Répartition des clients par sexe';

    protected function getData(): array
    {
        $rows = DB::table('users')
            ->join('role_user', 'users.id', '=', 'role_user.user_id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('roles.role_name', 'Membre')
            ->select('gender', DB::raw('COUNT(*) as total'))
            ->groupBy('gender')
            ->get();

        $labels = [];
        $values = [];

        foreach ($rows as $row) {
            $labels[] = ucfirst($row->gender ?? 'Non spécifié');
            $values[] = $row->total;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                ['data' => $values],
            ],
        ];
    }
}
