<?php

namespace App\Filament\Resources\StatusResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\StatusResource;
use Filament\Resources\Pages\EditRecord\Concerns\Translatable;

class EditStatus extends EditRecord
{
    use Translatable;
    protected static string $resource = StatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
