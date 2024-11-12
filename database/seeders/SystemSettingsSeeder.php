<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemSetting;

class SystemSettingsSeeder extends Seeder
{
    public function run()
    {
        SystemSetting::create([
            'company_name' => 'QCell KYC',
            'welcome_message' => 'Welcome to QCell KYC System',
            'queue_message' => 'Please wait while we process your request',
            'kyc_instructions' => 'Please have your identification documents ready',
        ]);
    }
}
