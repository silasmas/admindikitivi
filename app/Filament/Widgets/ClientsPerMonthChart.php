<?php
namespace App\Filament\Widgets;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\BarChartWidget;

class ClientsPerMonthChart extends BarChartWidget
{
    protected static ?string $heading = 'Inscriptions clients par mois';

    protected function getData(): array
    {
        $rows = DB::table('users')
            ->join('role_user', 'users.id', '=', 'role_user.user_id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('roles.name', 'Membre')
            ->selectRaw('MONTH(users.created_at) as month, COUNT(*) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $labels = [];
        $values = [];

        foreach ($rows as $row) {
            $labels[] = \Carbon\Carbon::create()->month($row->month)->translatedFormat('F');
            $values[] = $row->total;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Clients',
                    'data' => $values,
                ],
            ],
        ];
    }
}
