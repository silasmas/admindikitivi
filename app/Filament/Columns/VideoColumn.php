<?php

namespace App\Filament\Columns;

use Filament\Tables\Columns\Column;

class VideoColumn extends Column
{
    protected string $view = 'filament.columns.video-column';

    protected mixed $videoUrl = null;

    public function videoUrl(string|callable $url): static
    {
        $this->videoUrl = $url;
        return $this;
    }

    public function getVideoUrl($record): ?string
    {
        if (is_callable($this->videoUrl)) {
            return ($this->videoUrl)($record);
        }

        return $this->videoUrl;
    }
}
