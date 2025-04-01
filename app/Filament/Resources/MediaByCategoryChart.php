<?php

namespace App\Filament\Widgets;

use Filament\Widgets\PieChartWidget;
use App\Models\Category;

class MediaByCategoryChart extends PieChartWidget
{
    protected static ?string $heading = 'Médias par catégorie';

    // protected function getData(): array
    // {
    //     $categories = Category::withCount('medias')->get();

    //     $labels = [];
    //     $values = [];

    //     foreach ($categories as $category) {
    //         $labels[] = $category->getTranslation('category_name', app()->getLocale()) ?? 'Inconnu';
    //         $values[] = $category->media_count;
    //     }

    //     return [
    //         'labels' => $labels,
    //         'datasets' => [
    //             [
    //                 'data' => $values,
    //             ],
    //         ],
    //     ];
    // }
}
