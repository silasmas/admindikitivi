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

    /**
     * Retourne le nom traduit (chaîne) pour Filament et vues.
     */
    public function getDisplayNameAttribute(): string
    {
        $name = $this->getTranslation('category_name', app()->getLocale()) ?? $this->getTranslation('category_name', 'fr');
        if (is_array($name)) {
            return (string) ($name[app()->getLocale()] ?? $name['fr'] ?? $name['en'] ?? '');
        }
        return (string) ($name ?? '');
    }

    /**
     * Retourne la catégorie dans la langue demandée.
     */
    public function getCategoryName($lang = 'fr'): ?string
    {
        $name = $this->getTranslation('category_name', $lang);
        return is_string($name) ? $name : null;
    }

    /**
     * MANY-TO-ONE
     * Several medias for several categories.
     */
    public function medias()
    {
        return $this->belongsToMany(Media::class, 'category_media');
    }
}
