<?php

namespace App\Filament\Resources\UserResource\Pages;

use Filament\Pages\Actions;
use App\Filament\Resources\UserResource;
use Filament\Pages\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageUsers extends ManageRecords
{
    protected static string $resource = UserResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
    protected function saving(): void
    {
        parent::saving();

        // Appeler la méthode beforeSave ici
        UserResource::beforeSave($this->record, $this->data);
    }
}
