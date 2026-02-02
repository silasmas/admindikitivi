<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            SuperAdminSeeder::class,
            // DikitisiviDataSeeder::class, // Décommentez après avoir placé dikitivi_data.sql dans database/seeders/dumps/
        ]);
    }
}
