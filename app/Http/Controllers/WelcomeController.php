<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;

class WelcomeController extends Controller
{
    public function index()
    {
        $settings = SystemSetting::first();
        return view('welcome', compact('settings'));
    }
}
