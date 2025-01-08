<?php

namespace App\Filament\Resources\ClientResource\Pages;

use Filament\Pages\Actions;
use App\Filament\Resources\ClientResource;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageClients extends ManageRecords
{
    protected static string $resource = ClientResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
    protected function saving(): void
    {
        parent::saving();

        // Appeler la méthode beforeSave ici
        ClientResource::beforeSave($this->record, $this->data);
    }
}
