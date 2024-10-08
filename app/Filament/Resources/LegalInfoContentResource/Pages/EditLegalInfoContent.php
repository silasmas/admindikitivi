<?php

namespace App\Filament\Resources\LegalInfoContentResource\Pages;

use App\Filament\Resources\LegalInfoContentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLegalInfoContent extends EditRecord
{
    protected static string $resource = LegalInfoContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
