<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\View;

class ShareSystemSettings
{
    public function handle(Request $request, Closure $next)
    {
        $settings = SystemSetting::first();
        View::share('settings', $settings);

        return $next($request);
    }
}
