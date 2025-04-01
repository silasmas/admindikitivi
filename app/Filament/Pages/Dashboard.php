<?php

namespace App\Filament\Pages;


use App\Filament\Widgets\GenderStatsWidget;
use Schmeits\FilamentUmami\Concerns\HasFilter;

class Dashboard extends \Filament\Pages\Dashboard
{
    use HasFilter;
    public function getWidgets(): array
{
    return [

        // ... autres widgets
    ];
}

}
