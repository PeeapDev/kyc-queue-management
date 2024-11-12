<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ThemeSettingsController extends Controller
{
    public function index()
    {
        $settings = SystemSetting::first() ?? new SystemSetting();
        return view('admin.settings.theme', compact('settings'));
    }

    public function updateBranding(Request $request)
    {
        try {
            $validated = $request->validate([
                'site_title' => 'required|string|max:255',
                'logo' => 'nullable|image|max:2048',
                'favicon' => 'nullable|image|max:1024',
            ]);

            $settings = SystemSetting::firstOrNew(['id' => 1]);
            $settings->site_title = $validated['site_title'];

            // Handle logo upload
            if ($request->hasFile('logo')) {
                if ($settings->logo) {
                    Storage::disk('public')->delete($settings->logo);
                }
                $settings->logo = $request->file('logo')->store('logos', 'public');
            }

            // Handle favicon upload
            if ($request->hasFile('favicon')) {
                if ($settings->favicon) {
                    Storage::disk('public')->delete($settings->favicon);
                }
                $settings->favicon = $request->file('favicon')->store('favicons', 'public');
            }

            $settings->save();

            return response()->json([
                'success' => true,
                'message' => 'Branding settings updated successfully',
                'settings' => $settings,
                'logo_url' => $settings->logo ? Storage::disk('public')->url($settings->logo) : null
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating branding settings: ' . $e->getMessage()
            ], 500);
        }
    }

    public function uploadImage(Request $request)
    {
        try {
            $request->validate([
                'image' => 'required|image|max:2048'
            ]);

            $path = $request->file('image')->store('public/branding');
            $url = Storage::url($path);

            return response()->json([
                'success' => true,
                'url' => $url
            ]);
        } catch (\Exception $e) {
            Log::error('Error uploading image: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error uploading image: ' . $e->getMessage()
            ], 500);
        }
    }
}
