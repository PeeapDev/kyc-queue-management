<?php

namespace App\Providers;

use App\Models\SystemSetting;
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
            $settings = SystemSetting::first() ?? new SystemSetting();
            $view->with('settings', $settings);
        });
    }
}
