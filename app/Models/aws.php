<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Aws extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function setVideoAttribute($value)
    {
        if (is_file($value)) {
            $this->attributes['video'] = $value->store('videos', 's3');
        }
    }

    public function getVideoUrlAttribute()
    {
        return Storage::disk('s3')->url($this->attributes['video'] ?? '');
    }
}
