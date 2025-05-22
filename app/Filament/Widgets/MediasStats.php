<?php

namespace App\Filament\Widgets;

use App\Models\Media;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class MediasStats extends BaseWidget
{
    protected function getStats(): array
    {
        $total = Media::count();
        $enfant = Media::where('for_youth', true)->count();
        $adulte = Media::where('for_youth', false)->count();
        $Live = Media::where('is_live', true)->count();
        $fermer = Media::where('is_live', false)->count();

        $enAttente = $total - $enfant - $adulte - $Live-$fermer;

        return [

                Stat::make(' Total Media', $total)
                    ->description('Tous les Medias')
                    ->color('primary'),

                Stat::make(' Pour enfant', $enfant)
                    ->description("Médias pour enfants")
                    ->color('info'),

                Stat::make(' Pour adulte', $adulte)
                    ->description("Medias pour adultes")
                    ->color('success'),

                Stat::make('Live', $Live)
                    ->description("Les médias en direct")
                    ->color('danger'),

                Stat::make('Enregistré', $fermer)
                    ->description("Médias déjà enregistrés")
                    ->color('gray'),
            ];
        }
        protected ?string $heading = '📨 Media';

        protected ?string $description = 'Statistiques sur les Medias ';
        protected static ?int $sort = 4;
}
