<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use Filament\Widgets\PieChartWidget;

/**
 * Répartition des médias par catégorie (widget dashboard).
 */
class MediaByCategoryChart extends PieChartWidget
{
    protected static ?string $heading = 'Médias par catégorie';

    protected static ?int $sort = 5;

    /**
     * Données du camembert : libellés de catégories + effectifs.
     *
     * @return array{labels: string[], datasets: array<int, array{data: int[]}>}
     */
    protected function getData(): array
    {
        $categories = Category::query()->withCount('medias')->get();

        $labels = [];
        $values = [];

        foreach ($categories as $category) {
            $name = $category->category_name;
            if (is_array($name)) {
                $name = $name[app()->getLocale()] ?? reset($name) ?: 'Inconnu';
            }
            $labels[] = $name ?: 'Inconnu';
            $values[] = (int) $category->medias_count;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                ['data' => $values],
            ],
        ];
    }
}
