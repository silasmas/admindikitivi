<?php

namespace App\Filament\Columns;

use Filament\Tables\Columns\Column;

class VideoColumn extends Column
{
    protected string $view = 'filament.columns.video-column';

    public function videoUrl(string $url): static
    {
        return $this->setAttribute('videoUrl', $url);
    }
}
