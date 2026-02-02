<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $superAdminName = config('filament-shield.super_admin.name', 'super_admin');

        // Créer ou récupérer le rôle super_admin (Spatie)
        $superAdminRole = Role::firstOrCreate(
            ['name' => $superAdminName, 'guard_name' => 'web'],
            ['role_name' => 'Super Administrateur', 'role_description' => 'Accès complet à l\'administration']
        );

        // Créer ou mettre à jour l'utilisateur super admin
        $user = User::updateOrCreate(
            ['email' => 'contact@silasmas.com'],
            [
                'firstname' => 'Super',
                'lastname' => 'Admin',
                'name' => 'Super Admin',
                'password' => 'silasmas',
                'email_verified_at' => now(),
            ]
        );

        // S'assurer que le rôle est assigné (évite les doublons)
        if (! $user->hasRole($superAdminName)) {
            $user->assignRole($superAdminName);
        }

        // Synchroniser avec la table role_user (relation custom du projet)
        $user->roles()->syncWithoutDetaching([$superAdminRole->id]);
    }
}
