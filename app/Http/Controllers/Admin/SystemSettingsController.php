<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use App\Models\AvailableCountry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class SystemSettingsController extends Controller
{
    public function index()
    {
        $settings = SystemSetting::first();
        $countries = AvailableCountry::where('is_active', true)->get();

        return view('admin.settings.system', compact('settings', 'countries'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'google_maps_api_key' => 'required|string',
            'recaptcha_site_key' => 'required|string',
            'recaptcha_secret_key' => 'required|string',
            'company_name' => 'required|string',
            'company_logo' => 'nullable|image|max:1024',
            'company_favicon' => 'nullable|image|max:1024',
            'default_country' => 'required|string',
            'welcome_message' => 'nullable|string',
            'queue_message' => 'nullable|string',
            'kyc_instructions' => 'nullable|string',
        ]);

        // Handle file uploads
        if ($request->hasFile('company_logo')) {
            $logoPath = $request->file('company_logo')->store('public/logos');
            $validated['company_logo'] = Storage::url($logoPath);
        }

        if ($request->hasFile('company_favicon')) {
            $faviconPath = $request->file('company_favicon')->store('public/favicons');
            $validated['company_favicon'] = Storage::url($faviconPath);
        }

        $settings = SystemSetting::updateOrCreate(
            ['id' => 1],
            $validated
        );

        return back()->with('success', 'Settings updated successfully.');
    }

    public function fetchCountries()
    {
        try {
            $response = Http::get('https://restcountries.com/v3.1/all', [
                'fields' => 'name,cca2,region'
            ]);

            if ($response->successful()) {
                $countries = $response->json();

                foreach ($countries as $country) {
                    AvailableCountry::updateOrCreate(
                        ['code' => $country['cca2']],
                        [
                            'name' => $country['name']['common'],
                            'regions' => [],
                            'is_active' => true
                        ]
                    );
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Countries updated successfully'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch countries'
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching countries: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateRegions(Request $request, AvailableCountry $country)
    {
        $validated = $request->validate([
            'regions' => 'required|array',
            'regions.*' => 'string'
        ]);

        $country->update([
            'regions' => $validated['regions']
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Regions updated successfully'
        ]);
    }

    public function toggleCountry(AvailableCountry $country)
    {
        $country->update([
            'is_active' => !$country->is_active
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Country status updated successfully'
        ]);
    }

    public function updateChatbot(Request $request)
    {
        $validated = $request->validate([
            'script' => 'required|string',
        ]);

        // Remove any HTML escaping to store the raw script
        $script = html_entity_decode($validated['script'], ENT_QUOTES);

        SystemSetting::updateOrCreate(
            ['id' => 1],
            ['chatbot_script' => $script]
        );

        return response()->json(['success' => true]);
    }
}
