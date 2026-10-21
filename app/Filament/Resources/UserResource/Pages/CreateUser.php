<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

/**
 * Création d’un agent : utilise le formulaire UserResource (pas un schéma réduit).
 */
class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    /**
     * Prépare les données avant création.
     * Le mutator User hashe le mot de passe : ne pas re-hasher ici.
     *
     * @param  array<string, mixed>  $data  Données du formulaire
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
