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
use Illuminate\Support\Facades\Hash;

/**
 * Utilisateur de la plateforme (admin, agent, membre).
 *
 * @author Xanders
 * @see https://www.linkedin.com/in/xanders-samoth-b2770737/
 */
class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasPanelShield;

    /**
     * Attributs mass-assignables (exclus : api_token, remember_token).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'surname',
        'name',
        'gender',
        'birth_date',
        'city',
        'address_1',
        'address_2',
        'p_o_box',
        'email',
        'phone',
        'username',
        'password',
        'belongs_to',
        'parental_code',
        'email_verified_at',
        'phone_verified_at',
        'avatar_url',
        'id_card_type',
        'id_card_recto',
        'id_card_verso',
        'prefered_theme',
        'country_id',
        'status_id',
    ];

    /**
     * Attributs masqués à la sérialisation.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'api_token',
    ];

    /**
     * Casts des attributs.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'birth_date' => 'date',
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
    ];

    /**
     * Hash le mot de passe une seule fois (évite le double hash si déjà bcrypt).
     *
     * @param  string|null  $value  Mot de passe en clair ou déjà hashé
     * @return void
     */
    public function setPasswordAttribute($value): void
    {
        if ($value === null || $value === '') {
            return;
        }

        if (Hash::isHashed($value)) {
            $this->attributes['password'] = $value;

            return;
        }

        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * Retourne le prénom affiché dans le panel.
     *
     * @return string
     */
    public function getUserName(): string
    {
        return $this->firstname;
    }

    /**
     * Libellé du statut traduit.
     *
     * @param  string  $lang  Code langue
     * @return mixed
     */
    public function getStatus_name($lang = 'fr')
    {
        return $this->status?->getStatus_name($lang);
    }

    /**
     * MANY-TO-MANY — Several media_approbations for several users
     *
     * Note : roles() est fourni par Spatie HasRoles (pivot model_has_roles).
     */
    public function media_approbations()
    {
        return $this->belongsToMany(Media::class);
    }

    /**
     * ONE-TO-MANY — One country for several users
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * ONE-TO-MANY — One status for several users
     */
    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    /**
     * MANY-TO-ONE — Several medias for a user
     */
    public function medias()
    {
        return $this->hasMany(Media::class);
    }

    /**
     * MANY-TO-ONE — Several carts for a user
     */
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    /**
     * MANY-TO-ONE — Several donations for a user
     */
    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    /**
     * MANY-TO-ONE — Several payments for a user
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * MANY-TO-ONE — Several notifications for a user
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * MANY-TO-ONE — Several sessions for a user
     */
    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

    /**
     * Étend canAccessPanel : super_admin, panel_user, ou tout rôle autre que Membre.
     *
     * @param  Panel  $panel  Panel Filament demandé
     * @return bool
     */
    public function canAccessPanel(Panel $panel): bool
    {
        if ($this->hasRole(config('filament-shield.super_admin.name'))) {
            return true;
        }
        if ($this->hasRole(config('filament-shield.panel_user.name'))) {
            return true;
        }

        return $this->roles()
            ->where(function ($q) {
                $q->where('name', '!=', 'membre')
                    ->where(function ($q2) {
                        $q2->whereNull('role_name')->orWhere('role_name', '!=', 'Membre');
                    });
            })
            ->exists();
    }
}
