<?php

namespace App\Filament\Resources\LegalInfoTitleResource\Pages;

use App\Filament\Resources\LegalInfoTitleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLegalInfoTitle extends EditRecord
{
    protected static string $resource = LegalInfoTitleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
