<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @author Xanders
 * @see https://www.linkedin.com/in/xanders-samoth-b2770737/
 */
class Type extends Model
{
    use HasFactory,HasTranslations;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * Translatable properties.
     */
    protected $translatable = ['type_name'];

    /**
     * Retourne le nom du type traduit (chaîne) pour Filament et vues.
     */
    public function getDisplayNameAttribute(): string
    {
        $name = $this->getTranslation('type_name', app()->getLocale()) ?? $this->getTranslation('type_name', 'fr');
        if (is_array($name)) {
            return (string) ($name[app()->getLocale()] ?? $name['fr'] ?? $name['en'] ?? '');
        }
        return (string) ($name ?? '');
    }

    /**
     * ONE-TO-MANY
     * One group for several types
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * MANY-TO-ONE
     * Several medias for a type
     */
    public function medias()
    {
        return $this->hasMany(Media::class);
    }

    /**
     * MANY-TO-ONE
     * Several books for a type
     */
    public function books()
    {
        return $this->hasMany(Book::class);
    }

    /**
     * MANY-TO-ONE
     * Several carts for a type
     */
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * MANY-TO-ONE
     * Several payments for a type
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
