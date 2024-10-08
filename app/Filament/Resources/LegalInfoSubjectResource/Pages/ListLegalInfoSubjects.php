<?php

namespace App\Filament\Resources\LegalInfoSubjectResource\Pages;

use App\Filament\Resources\LegalInfoSubjectResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLegalInfoSubjects extends ListRecords
{
    protected static string $resource = LegalInfoSubjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
