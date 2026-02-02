<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoryMedia extends Model
{
    use HasFactory;

    protected $table = 'category_media';
    protected $guarded = [];

    public function media()
    {
        return $this->belongsTo(Media::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
