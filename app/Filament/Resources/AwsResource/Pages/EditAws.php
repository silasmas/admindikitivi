<?php

namespace App\Filament\Resources\AwsResource\Pages;

use App\Filament\Resources\AwsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAws extends EditRecord
{
    protected static string $resource = AwsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
