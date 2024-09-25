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
    
    protected $casts = [
        'category_name' => 'array', // S'assure que category_name est traité comme un tableau
    ];

    /**
     * Retourne la catégorie dans la langue demandée.
     *
     * @param string $lang
     * @return string|null
     */
    public function getCategoryName($lang = 'fr')
    {
        return $this->category_name[$lang] ?? null; // Retourne le nom dans la langue demandée
    }

    /**
     * MANY-TO-ONE
     * Several medias for several categories.
     */
    public function medias()
    {
        return $this->belongsToMany(Media::class);
    }
}
