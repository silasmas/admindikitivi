<?php

namespace App\Filament\Resources\LegalInfoTitleResource\Pages;

use App\Filament\Resources\LegalInfoTitleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLegalInfoTitles extends ListRecords
{
    protected static string $resource = LegalInfoTitleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
