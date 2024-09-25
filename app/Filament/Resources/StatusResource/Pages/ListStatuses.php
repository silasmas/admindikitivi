<?php

namespace App\Filament\Resources\StatusResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\StatusResource;
use Filament\Resources\Pages\ListRecords\Concerns\Translatable;

class ListStatuses extends ListRecords
{
    use Translatable;
    protected static string $resource = StatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
