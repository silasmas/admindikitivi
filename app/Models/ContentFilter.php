<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

/**
 * Filtre "Jeunesse" de l'application (table créée par la migration de l'API).
 * Un média correspond au filtre s'il a l'un des types OU l'une des catégories listés.
 */
class ContentFilter extends Model
{
    use HasFactory, HasTranslations;

    protected $guarded = [];

    protected $translatable = ['filter_name'];

    protected $casts = [
        'type_ids' => 'array',
        'category_ids' => 'array',
        'is_active' => 'boolean',
    ];

    public function getDisplayNameAttribute(): string
    {
        return (string) ($this->getTranslation('filter_name', app()->getLocale()) ?: $this->getTranslation('filter_name', 'fr'));
    }
}
