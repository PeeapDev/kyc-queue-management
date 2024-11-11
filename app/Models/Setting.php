<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get($key, $default = null)
    {
        try {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        } catch (\Exception $e) {
            \Log::error('Error getting setting: ' . $e->getMessage());
            return $default;
        }
    }

    public static function set($key, $value)
    {
        try {
            $setting = static::firstOrNew(['key' => $key]);
            $setting->value = $value;
            $setting->save();
            return $setting;
        } catch (\Exception $e) {
            \Log::error('Error setting setting: ' . $e->getMessage());
            return null;
        }
    }
}
