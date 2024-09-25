<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use Spatie\Translatable\HasTranslations;
use Filament\Resources\Concerns\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @author Xanders
 * @see https://www.linkedin.com/in/xanders-samoth-b2770737/
 */
class Group extends Model
{
    use HasFactory,Translatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * Translatable properties.
     */
    protected $translatable = ['group_name'];
    protected $casts = ['group_name' => 'array'];

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
