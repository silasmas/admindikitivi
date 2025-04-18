<?php

namespace App\Filament\Resources\MediaResource\Pages;

use App\Filament\Resources\MediaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMedia extends CreateRecord
{
    protected function rules(): array
{
    return array_merge(parent::rules(), [
        'media_url' => ['required', 'string', 'max:2048', 'url'],
    ]);
}

    protected static string $resource = MediaResource::class;
}
