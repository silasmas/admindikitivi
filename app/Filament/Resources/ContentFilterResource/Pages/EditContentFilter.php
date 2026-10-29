<?php

namespace App\Filament\Resources\ContentFilterResource\Pages;

use App\Filament\Resources\ContentFilterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditContentFilter extends EditRecord
{
    protected static string $resource = ContentFilterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
