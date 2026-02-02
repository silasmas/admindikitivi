<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @author Xanders
 * @see https://www.linkedin.com/in/xanders-samoth-b2770737/
 */
class Group extends Model
{
    use HasFactory, HasTranslations;

    protected $guarded = [];

    protected $translatable = ['group_name'];

    public function getDisplayNameAttribute(): string
    {
        $name = $this->getTranslation('group_name', app()->getLocale()) ?? $this->getTranslation('group_name', 'fr');
        if (is_array($name)) {
            return (string) ($name[app()->getLocale()] ?? $name['fr'] ?? $name['en'] ?? '');
        }
        return (string) ($name ?? '');
    }

    /**
     * MANY-TO-ONE
     * Several statuses for a group
     */
    public function statuses()
    {
        return $this->hasMany(Status::class);
    }

    /**
     * MANY-TO-ONE
     * Several types for a group
     */
    public function types()
    {
        return $this->hasMany(Type::class);
    }
}
