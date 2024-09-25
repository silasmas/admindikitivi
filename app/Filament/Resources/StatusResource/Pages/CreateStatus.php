<?php

namespace App\Filament\Resources\StatusResource\Pages;

use Filament\Actions;
use App\Filament\Resources\StatusResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\Translatable;

class CreateStatus extends CreateRecord
{
    use Translatable;
    protected static string $resource = StatusResource::class;
}
