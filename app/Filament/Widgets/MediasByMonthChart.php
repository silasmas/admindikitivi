<?php
// app/Filament/Widgets/MediasByMonthChart.php

namespace App\Filament\Widgets;

use Filament\Widgets\BarChartWidget;
use App\Models\Media;
use Carbon\Carbon;

class MediasByMonthChart extends BarChartWidget
{
    protected static ?string $heading = 'Médias publiés par mois';
    protected static ?int $sort = 1; // Pour l'afficher après les stats globales
    protected function getData(): array
    {
        $data = Media::selectRaw('MONTH(published_date) as month, COUNT(*) as total')
            ->whereNotNull('published_date')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $labels = [];
        $values = [];

        foreach ($data as $row) {
            // Utilise Carbon pour traduire le nom du mois
    $labels[] = Carbon::createFromDate(null, $row->month)->translatedFormat('F'); // "janvier", "février", etc.
    $values[] = $row->total;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Médias',
                    'data' => $values,
                ],
            ],
            'labels' => $labels,
        ];
    }
}
