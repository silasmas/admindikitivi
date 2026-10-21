<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Crée le super administrateur Filament à partir des variables d’environnement.
 */
class SuperAdminSeeder extends Seeder
{
    /**
     * Exécute le seeder.
     *
     * Variables attendues : SUPER_ADMIN_EMAIL, SUPER_ADMIN_PASSWORD.
     * En non-production, un mot de passe aléatoire est généré s’il est absent.
     *
     * @return void
     */
    public function run(): void
    {
        $superAdminName = config('filament-shield.super_admin.name', 'super_admin');
        $email = env('SUPER_ADMIN_EMAIL', 'admin@localhost');
        $password = env('SUPER_ADMIN_PASSWORD');

        if (blank($password)) {
            if (app()->isProduction()) {
                throw new RuntimeException(
                    'SUPER_ADMIN_PASSWORD doit être défini dans .env avant de lancer SuperAdminSeeder en production.'
                );
            }

            $password = Str::password(16);
            $this->command?->warn("SUPER_ADMIN_PASSWORD non défini. Mot de passe généré (à conserver) : {$password}");
        }

        $superAdminRole = Role::firstOrCreate(
            ['name' => $superAdminName, 'guard_name' => 'web'],
            ['role_name' => 'Super Administrateur', 'role_description' => 'Accès complet à l\'administration']
        );

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'firstname' => 'Super',
                'lastname' => 'Admin',
                'name' => 'Super Admin',
                'password' => $password,
                'email_verified_at' => now(),
            ]
        );

        if (! $user->hasRole($superAdminName)) {
            $user->assignRole($superAdminRole);
        }

        $this->command?->info("Super admin prêt : {$email}");
    }
}
