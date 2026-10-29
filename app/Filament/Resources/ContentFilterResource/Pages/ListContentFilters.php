<?php

namespace App\Filament\Resources\ContentFilterResource\Pages;

use App\Filament\Resources\ContentFilterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListContentFilters extends ListRecords
{
    protected static string $resource = ContentFilterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
