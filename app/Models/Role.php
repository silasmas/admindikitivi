<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

/**
 * Rôle étendu pour compatibilité Spatie/Shield + champs métier (role_name, role_description).
 *
 * @author Xanders
 * @see https://www.linkedin.com/in/xanders-samoth-b2770737/
 */
class Role extends SpatieRole
{
    protected $fillable = ['name', 'guard_name', 'role_name', 'role_description'];

    /**
     * Alias pour l'affichage : utilise role_name si défini, sinon name.
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->role_name ?? $this->name ?? '';
    }
}
