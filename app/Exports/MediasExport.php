<?php

namespace App\Exports;

use App\Models\Media;
use Maatwebsite\Excel\Concerns\FromCollection;

class MediasExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Media::all();
    }
}
