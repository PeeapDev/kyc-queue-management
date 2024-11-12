@extends('layouts.admin')

@section('header')
    Dynamic Settings
@endsection

@section('content')
<div x-data="dynamicSettings()" class="space-y-6">
    <!-- Country Modal -->
    <div x-show="showCountryModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form @submit.prevent="submitCountry">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Add Country</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Country Name</label>
                                <input type="text" x-model="newCountry.name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Country Code (ISO)</label>
                                <input type="text" x-model="newCountry.code" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required maxlength="3">
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" x-model="newCountry.use_google_regions" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <label class="ml-2 block text-sm text-gray-900 dark:text-white">Use Google Maps API</label>
                            </div>
                            <div x-show="newCountry.use_google_regions">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Google Maps API Key</label>
                                <input type="text" x-model="newCountry.google_api_key" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">Save</button>
                        <button type="button" @click="showCountryModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Region Modal -->
    <div x-show="showRegionModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form @submit.prevent="submitRegion">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Add Region</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Select Country</label>
                                <select x-model="newRegion.country_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                                    <option value="">Select a country</option>
                                    <template x-for="country in countries" :key="country.id">
                                        <option :value="country.id" x-text="country.name"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Region Name</label>
                                <input type="text" x-model="newRegion.name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                            </div>
                            <template x-if="selectedCountry && selectedCountry.use_google_regions">
                                <div>
                                    <button type="button" @click="fetchGooglePlaces" class="mt-2 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                        Fetch from Google Maps
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">Save</button>
                        <button type="button" @click="showRegionModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Location Modal -->
    <div x-show="showLocationModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form @submit.prevent="submitLocation">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Add Location</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Select Region</label>
                                <select x-model="newLocation.region_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                                    <option value="">Select a region</option>
                                    <template x-for="region in regions" :key="region.id">
                                        <option :value="region.id" x-text="region.name"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Location Name</label>
                                <input type="text" x-model="newLocation.name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">Save</button>
                        <button type="button" @click="showLocationModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="space-y-6">
        <!-- Countries Section -->
        <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Countries</h3>
                <button @click="showCountryModal = true" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Add Country
                </button>
            </div>
            <div class="border-t border-gray-200 dark:border-gray-700">
                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                    <template x-for="country in countries" :key="country.id">
                        <li class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white" x-text="country.name"></p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400" x-text="country.code"></p>
                                </div>
                                <div class="flex space-x-2">
                                    <button @click="editCountry(country)" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400">Edit</button>
                                    <button @click="deleteCountry(country.id)" class="text-red-600 hover:text-red-900 dark:text-red-400">Delete</button>
                                </div>
                            </div>
                        </li>
                    </template>
                </ul>
            </div>
        </div>

        <!-- Regions Section -->
        <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
            <!-- Similar structure as Countries section -->
        </div>

        <!-- Locations Section -->
        <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
            <!-- Similar structure as Countries section -->
        </div>
    </div>
</div>

@push('scripts')
<script>
function dynamicSettings() {
    return {
        showCountryModal: false,
        showRegionModal: false,
        showLocationModal: false,
        countries: @json($countries),
        regions: @json($regions ?? []),
        locations: @json($locations ?? []),
        selectedCountry: null,
        selectedRegion: null,
        newCountry: {
            name: '',
            code: '',
            use_google_regions: false,
            google_api_key: ''
        },
        newRegion: {
            country_id: '',
            name: '',
            google_place_id: '',
            latitude: '',
            longitude: ''
        },
        newLocation: {
            region_id: '',
            name: '',
            google_place_id: '',
            latitude: '',
            longitude: ''
        },

        // Country Methods
        async submitCountry() {
            try {
                const response = await fetch('{{ route("admin.settings.countries.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(this.newCountry)
                });

                const data = await response.json();

                if (data.success) {
                    this.countries.push(data.country);
                    this.showCountryModal = false;
                    this.resetCountryForm();
                    // Show success message
                    alert('Country added successfully');
                } else {
                    alert(data.message || 'Error adding country');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error adding country');
            }
        },

        editCountry(country) {
            this.newCountry = { ...country };
            this.showCountryModal = true;
        },

        async deleteCountry(countryId) {
            if (!confirm('Are you sure you want to delete this country?')) return;

            try {
                const response = await fetch(`/admin/settings/countries/${countryId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    this.countries = this.countries.filter(c => c.id !== countryId);
                    alert('Country deleted successfully');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error deleting country');
            }
        },

        resetCountryForm() {
            this.newCountry = {
                name: '',
                code: '',
                use_google_regions: false,
                google_api_key: ''
            };
        },

        // Region Methods
        async submitRegion() {
            try {
                const response = await fetch('{{ route("admin.settings.regions.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(this.newRegion)
                });

                const data = await response.json();

                if (data.success) {
                    this.regions.push(data.region);
                    this.showRegionModal = false;
                    this.resetRegionForm();
                    alert('Region added successfully');
                } else {
                    alert(data.message || 'Error adding region');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error adding region');
            }
        },

        editRegion(region) {
            this.newRegion = { ...region };
            this.showRegionModal = true;
        },

        async deleteRegion(regionId) {
            if (!confirm('Are you sure you want to delete this region?')) return;

            try {
                const response = await fetch(`/admin/settings/regions/${regionId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    this.regions = this.regions.filter(r => r.id !== regionId);
                    alert('Region deleted successfully');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error deleting region');
            }
        },

        resetRegionForm() {
            this.newRegion = {
                country_id: '',
                name: '',
                google_place_id: '',
                latitude: '',
                longitude: ''
            };
        },

        // Location Methods
        async submitLocation() {
            try {
                const response = await fetch('{{ route("admin.settings.locations.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(this.newLocation)
                });

                const data = await response.json();

                if (data.success) {
                    this.locations.push(data.location);
                    this.showLocationModal = false;
                    this.resetLocationForm();
                    alert('Location added successfully');
                } else {
                    alert(data.message || 'Error adding location');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error adding location');
            }
        },

        editLocation(location) {
            this.newLocation = { ...location };
            this.showLocationModal = true;
        },

        async deleteLocation(locationId) {
            if (!confirm('Are you sure you want to delete this location?')) return;

            try {
                const response = await fetch(`/admin/settings/locations/${locationId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    this.locations = this.locations.filter(l => l.id !== locationId);
                    alert('Location deleted successfully');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error deleting location');
            }
        },

        resetLocationForm() {
            this.newLocation = {
                region_id: '',
                name: '',
                google_place_id: '',
                latitude: '',
                longitude: ''
            };
        },

        // Google Maps Integration
        async fetchGooglePlaces() {
            if (!this.selectedCountry?.google_api_key) {
                alert('No Google API key configured for this country');
                return;
            }

            try {
                const response = await fetch(`/admin/settings/google-places/search`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        country_code: this.selectedCountry.code,
                        api_key: this.selectedCountry.google_api_key
                    })
                });

                const data = await response.json();

                if (data.success) {
                    // Handle the Google Places data
                    this.handleGooglePlacesData(data.places);
                } else {
                    alert(data.message || 'Error fetching places');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error fetching places from Google Maps');
            }
        },

        handleGooglePlacesData(places) {
            // Implementation for handling Google Places data
            console.log('Places data:', places);
        }
    }
}
</script>
@endpush
@endsection
