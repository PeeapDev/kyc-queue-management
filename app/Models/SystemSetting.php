<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $fillable = [
        'site_title',
        'logo',
        'favicon',
        'hero_title',
        'hero_subtitle',
        'hero_image',
        'chatbot_script'
    ];
}
