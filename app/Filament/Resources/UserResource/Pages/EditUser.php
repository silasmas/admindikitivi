<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

/**
 * Édition d’un agent Filament.
 */
class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    /**
     * Actions d’en-tête (suppression).
     *
     * @return array<int, Actions\Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    /**
     * Prépare les données avant mise à jour.
     * Mot de passe vide = inchangé ; sinon mutator User hashe.
     *
     * @param  array<string, mixed>  $data  Données du formulaire
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (empty($data['password'])) {
            unset($data['password']);
        }

        return $data;
    }
}
