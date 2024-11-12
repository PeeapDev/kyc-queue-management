<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Region;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DynamicSettingsController extends Controller
{
    public function index()
    {
        $countries = Country::with('regions.locations')->get();
        return view('admin.settings.dynamic', compact('countries'));
    }

    public function storeCountry(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|size:3|unique:countries',
            'use_google_regions' => 'boolean',
            'google_api_key' => 'required_if:use_google_regions,true'
        ]);

        $country = Country::create($validated);

        if ($country->use_google_regions && $country->google_api_key) {
            // Fetch regions from Google Maps API
            $this->fetchGoogleRegions($country);
        }

        return response()->json([
            'success' => true,
            'message' => 'Country added successfully',
            'country' => $country
        ]);
    }

    public function storeRegion(Request $request)
    {
        $validated = $request->validate([
            'country_id' => 'required|exists:countries,id',
            'name' => 'required|string|max:255',
            'google_place_id' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric'
        ]);

        $region = Region::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Region added successfully',
            'region' => $region
        ]);
    }

    public function storeLocation(Request $request)
    {
        $validated = $request->validate([
            'region_id' => 'required|exists:regions,id',
            'name' => 'required|string|max:255',
            'google_place_id' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric'
        ]);

        $location = Location::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Location added successfully',
            'location' => $location
        ]);
    }

    private function fetchGoogleRegions(Country $country)
    {
        try {
            $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json', [
                'address' => $country->name,
                'key' => $country->google_api_key,
                'components' => 'country:' . $country->code
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if ($data['status'] === 'OK') {
                    // Process and store regions
                    // Implementation depends on the specific requirements
                }
            }
        } catch (\Exception $e) {
            \Log::error('Error fetching Google regions: ' . $e->getMessage());
        }
    }

    public function getRegions(Request $request)
    {
        $validated = $request->validate([
            'country_id' => 'required|exists:countries,id'
        ]);

        $regions = Region::where('country_id', $validated['country_id'])
            ->where('is_active', true)
            ->get();

        return response()->json($regions);
    }

    public function getLocations(Request $request)
    {
        $validated = $request->validate([
            'region_id' => 'required|exists:regions,id'
        ]);

        $locations = Location::where('region_id', $validated['region_id'])
            ->where('is_active', true)
            ->get();

        return response()->json($locations);
    }
}
