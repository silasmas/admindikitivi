<?php
namespace App\Filament\Widgets;

use App\Models\Media;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class MediaStats extends BaseWidget
{
    protected ?string $heading = 'Cérémonies';

    protected ?string $description = 'Liste des cérémonies à venir';
    protected static ?int $sort    = 0; // Pour l'afficher après les stats globales
    protected function getCards(): array
    {
        $total = Media::count();
        $avg   = Media::avg('price');
        return [
            Stat::make('Total Médias', $total)
                ->description('Nombre total de médias')
                ->icon('heroicon-o-film')
                ->color('primary'),
            Stat::make('Prix moyen', number_format($avg, 2) . ' FCFA')
                ->description('Prix moyen de tous les médias')
                ->icon('heroicon-o-currency-dollar')
                ->color('success'),
            Stat::make('Médias en live', Media::where('is_live', true)->count())
                ->description('Contenus actuellement en direct')
                ->icon('heroicon-o-bolt')
                ->color('warning'),
            Stat::make('Médias publics', Media::where('is_public', true)->count())
                ->color('primary')
                ->icon('heroicon-o-eye'),

            Stat::make('Médias privés', Media::where('is_public', false)->count())
                ->color('danger')
                ->icon('heroicon-o-eye-slash'),
        ];

    }
}
