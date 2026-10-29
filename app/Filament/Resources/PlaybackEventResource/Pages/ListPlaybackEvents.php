<?php

namespace App\Filament\Resources\PlaybackEventResource\Pages;

use App\Filament\Resources\PlaybackEventResource;
use App\Filament\Widgets\PlaybackStatsOverview;
use Filament\Resources\Pages\ListRecords;

class ListPlaybackEvents extends ListRecords
{
    protected static string $resource = PlaybackEventResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            PlaybackStatsOverview::class,
        ];
    }
}
