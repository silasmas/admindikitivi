<?php

namespace App\Filament\Resources\LegalInfoSubjectResource\Pages;

use App\Filament\Resources\LegalInfoSubjectResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLegalInfoSubject extends EditRecord
{
    protected static string $resource = LegalInfoSubjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
