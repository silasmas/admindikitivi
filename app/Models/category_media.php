<?php

namespace App\Models;

use App\Models\Media;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Filament\Resources\Concerns\Translatable;
use Spatie\Translatable\HasTranslations;

class category_media extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];
    public function medias()
    {
        return $this->belongsTo(Media::class);
    }
    public function categorie()
    {
        return $this->belongsTo( Category::class);
    }
    // public function getTranslatableAttributes()
    // {
    //     return parent::getTranslatableAttributes(); // Appelle la méthode parent si nécessaire
    // }
    public function getCustomTranslatableAttributes()
    {
        // Votre logique ici
    }
}
