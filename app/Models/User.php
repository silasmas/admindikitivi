<?php

namespace App\Models;

use Filament\Panel;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Filament\Models\Contracts\FilamentUser;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;

/**
 * @author Xanders
 * @see https://www.linkedin.com/in/xanders-samoth-b2770737/
 */
class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasPanelShield;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'birth_date' => 'date',
    ];
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
    public function getUserName(): string
    {
        return $this->firstname; // ou $this->email, selon votre logique
    }
    public function getStatus_name($lang = 'fr')
    {
        return $this->status?->getStatus_name($lang);
    }

    /**
     * MANY-TO-MANY
     * Several roles for several users
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * MANY-TO-MANY
     * Several media_approbations for several users
     */
    public function media_approbations()
    {
        return $this->belongsToMany(Media::class);
    }

    /**
     * ONE-TO-MANY
     * One country for several users
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * ONE-TO-MANY
     * One status for several users
     */
    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    /**
     * MANY-TO-ONE
     * Several medias for a user
     */
    public function medias()
    {
        return $this->hasMany(Media::class);
    }

    /**
     * MANY-TO-ONE
     * Several carts for a user
     */
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * MANY-TO-ONE
     * Several donations for a user
     */
    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    /**
     * MANY-TO-ONE
     * Several payments for a user
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * MANY-TO-ONE
     * Several notifications for a user
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * MANY-TO-ONE
     * Several sessions for a user
     */
    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

    /**
     * Étend canAccessPanel : super_admin, panel_user, ou tout rôle autre que Membre.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        if ($this->hasRole(config('filament-shield.super_admin.name'))) {
            return true;
        }
        if ($this->hasRole(config('filament-shield.panel_user.name'))) {
            return true;
        }
        return $this->roles()->where(function ($q) {
            $q->where('name', '!=', 'membre')
                ->where(function ($q2) {
                    $q2->whereNull('role_name')->orWhere('role_name', '!=', 'Membre');
                });
        })->exists();
    }
}
