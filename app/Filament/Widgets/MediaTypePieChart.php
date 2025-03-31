<?php

// app/Filament/Widgets/MediaTypePieChart.php

namespace App\Filament\Widgets;

use Filament\Widgets\PieChartWidget;
use App\Models\Media;

class MediaTypePieChart extends PieChartWidget
{
    protected static ?string $heading = 'Répartition des types de médias';
    protected static ?int $sort    = 1;
    protected function getData(): array
    {
        $types = Media::selectRaw('type_id, COUNT(*) as total')
            ->groupBy('type_id')
            ->with('type')
            ->get();

        $labels = [];
        $values = [];

        foreach ($types as $entry) {
            $labels[] = optional($entry->type)?->getTranslation('type_name', app()->getLocale()) ?? 'Inconnu';
            $values[] = $entry->total;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'data' => $values,
                ],
            ],
        ];
    }
}
