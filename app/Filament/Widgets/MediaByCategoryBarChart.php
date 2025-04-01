<?php
namespace App\Filament\Widgets;

use Filament\Widgets\BarChartWidget;
use Illuminate\Support\Facades\DB;
use App\Models\Category;

class MediaByCategoryBarChart extends BarChartWidget
{
    // Titre affiché en haut du widget
    protected static ?string $heading = 'Top 10 des catégories avec le plus de médias';

    // S'assure qu'il est bien placé visuellement (peut être ajusté selon ton layout)
    protected static ?int $sort = 2;

    // Très important → fait que le widget prend toute la largeur du dashboard
    protected static ?string $maxWidth = 'full';

    // Propriété Livewire pour le filtre sélectionné
    public ?int $selectedYear = null;

    /**
     * Crée les filtres disponibles dans l’interface (ex : années 2022, 2023, 2024)
     */
    protected function getFilters(): ?array
    {
        $years = DB::table('medias')
            ->selectRaw('YEAR(published_date) as year')
            ->whereNotNull('published_date')
            ->groupBy('year')
            ->orderByDesc('year')
            ->pluck('year')
            ->toArray();

        return collect($years)
            ->mapWithKeys(fn ($year) => [$year => $year])
            ->toArray();
    }

    /**
     * Génère les données du graphe : labels (catégories) + datasets (nombre de médias)
     */
    protected function getData(): array
    {
        // Base : jointure entre le pivot category_media et la table medias
        $query = DB::table('category_media')
            ->join('medias', 'category_media.media_id', '=', 'medias.id');

        // Appliquer le filtre par année si présent
        if ($this->selectedYear) {
            $query->whereYear('medias.published_date', $this->selectedYear);
        }

        // On regroupe par catégorie et on compte le nombre de médias
        $rawData = $query
            ->select('category_media.category_id', DB::raw('COUNT(category_media.media_id) as total'))
            ->groupBy('category_media.category_id')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Construction des labels et valeurs pour le graphe
        $labels = [];
        $values = [];

        foreach ($rawData as $row) {
            $category = Category::find($row->category_id); // On récupère l’objet Category
            $labels[] = $category?->getTranslation('category_name', app()->getLocale()) ?? 'Inconnu';
            $values[] = $row->total;
        }

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Nombre de médias',
                    'data' => $values,
                ],
            ],
        ];
    }
}
