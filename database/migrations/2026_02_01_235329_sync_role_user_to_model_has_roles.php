<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('role_user')) {
            return;
        }

        $userModel = config('filament-shield.auth_provider_model.fqcn', 'App\Models\User');

        $roleUserRows = DB::table('role_user')->get();

        foreach ($roleUserRows as $row) {
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

        DB::table('roles')->whereNull('name')->orWhere('name', '')->update([
            'name' => DB::raw('COALESCE(LOWER(REPLACE(role_name, " ", "_")), CONCAT("role_", id))'),
            'guard_name' => 'web',
        ]);
    }

    public function down(): void
    {
        //
    }
};
