<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $fillable = [
        'google_maps_api_key',
        'recaptcha_site_key',
        'recaptcha_secret_key',
        'company_name',
        'company_logo',
        'company_favicon',
        'default_country',
        'welcome_message',
        'queue_message',
        'kyc_instructions',
        'chatbot_script',
        'google_client_id',
        'google_client_secret',
        'site_title',
        'logo',
        'favicon',
        'hero_title',
        'hero_subtitle',
        'primary_color',
        'secondary_color',
        'hero_image',
        'features',
        'cta_text',
        'cta_button_text',
        'footer_text',
        'custom_css'
    ];

    protected $casts = [
        'features' => 'array'
    ];
}
