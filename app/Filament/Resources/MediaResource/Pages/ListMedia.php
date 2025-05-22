<?php

namespace App\Filament\Resources\MediaResource\Pages;

use Filament\Actions;
use App\Filament\Widgets\MediasStats;
use App\Filament\Resources\MediaResource;
use Filament\Resources\Pages\ListRecords;

class ListMedia extends ListRecords
{
    protected static string $resource = MediaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    public function getHeaderWidgets(): array
    {
        return [
            MediasStats::class,

            // InvitationsByCeremonieStats::class, // ✅ Nouveau widget par cérémonie
        ];
    }
}
