<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Synchronise role_user → model_has_roles et normalise roles.name (portable SQLite/MySQL).
     *
     * @return void
     */
    public function up(): void
    {
        if (! Schema::hasTable('role_user') || ! Schema::hasTable('model_has_roles')) {
            return;
        }

        $userModel = config('filament-shield.auth_provider_model.fqcn', 'App\Models\User');

        foreach (DB::table('role_user')->get() as $row) {
            $exists = DB::table('model_has_roles')
                ->where('role_id', $row->role_id)
                ->where('model_id', $row->user_id)
                ->where('model_type', $userModel)
                ->exists();

            if (! $exists) {
                DB::table('model_has_roles')->insert([
                    'role_id' => $row->role_id,
                    'model_type' => $userModel,
                    'model_id' => $row->user_id,
                ]);
            }
        }

        if (! Schema::hasTable('roles')) {
            return;
        }

        $roles = DB::table('roles')
            ->where(function ($q) {
                $q->whereNull('name')->orWhere('name', '');
            })
            ->get();

        foreach ($roles as $role) {
            $name = ! empty($role->role_name)
                ? strtolower(str_replace(' ', '_', $role->role_name))
                : 'role_' . $role->id;

            DB::table('roles')->where('id', $role->id)->update([
                'name' => $name,
                'guard_name' => 'web',
            ]);
        }
    }

    /**
     * @return void
     */
    public function down(): void
    {
        //
    }
};
