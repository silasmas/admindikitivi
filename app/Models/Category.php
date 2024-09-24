<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

/**
 * @author Xanders
 * @see https://www.linkedin.com/in/xanders-samoth-b2770737/
 */
class Category extends Model
{
    use HasFactory, HasTranslations;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * Translatable properties.
     */
    protected $translatable = ['category_name'];
    protected $casts = ['category_name' => 'array'];
    public function getCategory_name($lang = 'en')
    {
        return $this->category_name[$lang] ?? null; // Retourne la description dans la langue demandée
    }
    /**
     * MANY-TO-ONE
     * Several medias for several categories
     */
    public function medias()
    {
        return $this->belongsToMany(Media::class);
    }
}
