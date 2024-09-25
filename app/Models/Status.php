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
class Status extends Model
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
    protected $translatable = ['status_name'];

    protected $casts = ["status_name" => 'array'];
    public function getStatus_name($lang = 'fr')
    {
        return $this->status_name[$lang] ?? null; // Retourne la description dans la langue demandée
    }
    /**
     * ONE-TO-MANY
     * One group for several statuses
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * MANY-TO-ONE
     * Several users for a status
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * MANY-TO-ONE
     * Several carts for a status
     */
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * MANY-TO-ONE
     * Several payments for a status
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * MANY-TO-ONE
     * Several notifications for a status
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
