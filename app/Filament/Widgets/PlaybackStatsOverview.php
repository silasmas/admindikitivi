<?php

namespace App\Filament\Widgets;

use App\Models\PlaybackEvent;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * Lecture et vitesse sur les 7 derniers jours (slides 9 et 10).
 */
class PlaybackStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected ?string $heading = 'Lecture (7 derniers jours)';

    protected function getStats(): array
    {
        $since = now()->subDays(7);
        $counts = PlaybackEvent::where('occurred_at', '>=', $since)
                                ->selectRaw('event, COUNT(*) AS total')
                                ->groupBy('event')
                                ->pluck('total', 'event');
        $plays = (int) ($counts['video_play'] ?? 0);
        $completion = $plays > 0 ? round(100 * (int) ($counts['video_complete'] ?? 0) / $plays) . ' %' : '—';
        $startup = PlaybackEvent::where([['occurred_at', '>=', $since], ['event', 'video_startup_ms']])->avg('value_ms');
        $errors = (int) ($counts['playback_error'] ?? 0) + (int) ($counts['api_error'] ?? 0);

        return [
            Stat::make('Lectures', number_format($plays, 0, ',', ' '))
                ->description('Vidéos démarrées')
                ->icon('heroicon-o-play')
                ->color('primary'),
            Stat::make('Vidéos terminées', $completion)
                ->description('Abandons : ' . (int) ($counts['video_abandon'] ?? 0))
                ->icon('heroicon-o-check-circle')
                ->color('success'),
            Stat::make('Démarrage vidéo moyen', $startup ? number_format($startup / 1000, 1, ',', ' ') . ' s' : '—')
                ->description('Buffering : ' . (int) ($counts['buffer_start'] ?? 0))
                ->icon('heroicon-o-bolt')
                ->color('warning'),
            Stat::make('Erreurs', $errors)
                ->description('Lecture + API')
                ->icon('heroicon-o-exclamation-triangle')
                ->color($errors > 0 ? 'danger' : 'gray'),
        ];
    }
}
