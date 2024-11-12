<?php

namespace Database\Seeders;

use App\Models\Settings;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        // First check if settings already exist
        if (!Settings::exists()) {
            Settings::create([
                'site_title' => config('app.name'),
                'hero_title' => 'Welcome to QCell KYC',
                'hero_subtitle' => 'Fast, Secure, and Efficient Customer Verification'
            ]);
        }
    }
}
