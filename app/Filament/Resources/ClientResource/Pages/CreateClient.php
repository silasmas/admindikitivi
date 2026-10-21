<?php

namespace App\Filament\Resources\ClientResource\Pages;

use App\Filament\Resources\ClientResource;
use Filament\Resources\Pages\CreateRecord;

/**
 * Création d’un client (membre).
 */
class CreateClient extends CreateRecord
{
    protected static string $resource = ClientResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['password'])) {
            unset($data['password']);
        }

        return $data;
    }
}
