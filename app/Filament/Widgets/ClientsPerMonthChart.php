<?php
namespace App\Filament\Widgets;

use Filament\Widgets\BarChartWidget;

class ClientsPerMonthChart extends BarChartWidget
{
    /** Chargement différé : limite les connexions MySQL simultanées sur l’accueil. */
    protected static bool $isLazy = true;

    protected static ?string $heading = 'Inscriptions clients par mois';
    protected static ?int $sort = 1;
    protected function getData(): array
    {
        $rows = usersByRoleQuery('Membre')
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
