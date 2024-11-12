<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Only create admin if it doesn't exist
        if (!Admin::where('email', 'admin@admin.com')->exists()) {
            $this->call([
                AdminSeeder::class,
            ]);
        }

        // Always run the system settings seeder
        $this->call([
            SystemSettingsSeeder::class,
        ]);

        // Run the settings seeder
        $this->call([
            SettingsSeeder::class,
        ]);
    }
}
