<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getFormSchema(): array
    {
        return [
            \Filament\Forms\Components\TextInput::make('password')
                ->password()
                ->required()
                ->minLength(8) // Règle pour un mot de passe d'au moins 8 caractères
                ->label('Mot de Passe')
                ->confirmed() // Exige un champ de confirmation
                ->maxLength(255), // Limite de longueur
        ];
    }
}
