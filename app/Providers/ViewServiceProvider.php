<?php

namespace App\Providers;

use App\Models\Settings;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Share settings with all views
        View::composer('*', function ($view) {
            $settings = Settings::first() ?? new Settings();
            $view->with('settings', $settings);
        });
    }
}
