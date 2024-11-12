@extends('layouts.admin')

@section('header')
    System Settings
@endsection

@section('content')
<div x-data="systemSettings()" class="space-y-6">
    <!-- API Keys Section -->
    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                API Configuration
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                Configure your API keys for various services
            </p>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-700">
            <form @submit.prevent="updateApiKeys" class="p-6 space-y-6">
                <!-- Google Maps API Key -->
                <div>
                    <label for="google_maps_api_key" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Google Maps API Key
                    </label>
                    <input type="text"
                           x-model="apiKeys.google_maps_api_key"
                           id="google_maps_api_key"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <!-- reCAPTCHA Keys -->
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                    <div>
                        <label for="recaptcha_site_key" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            reCAPTCHA Site Key
                        </label>
                        <input type="text"
                               x-model="apiKeys.recaptcha_site_key"
                               id="recaptcha_site_key"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <div>
                        <label for="recaptcha_secret_key" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            reCAPTCHA Secret Key
                        </label>
                        <input type="text"
                               x-model="apiKeys.recaptcha_secret_key"
                               id="recaptcha_secret_key"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Save API Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Location Settings -->
    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                Location Settings
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                Configure default country and available regions
            </p>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-700">
            <div class="p-6 space-y-6">
                <!-- Default Country Selection -->
                <div>
                    <label for="default_country" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Default Country
                    </label>
                    <select x-model="locationSettings.default_country"
                            @change="updateDefaultCountry"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Select a country</option>
                        <template x-for="country in countries" :key="country.code">
                            <option :value="country.code" x-text="country.name"></option>
                        </template>
                    </select>
                </div>

                <!-- Available Countries List -->
                <div class="mt-6">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">Available Countries</h4>
                    <div class="space-y-4">
                        <template x-for="country in countries" :key="country.code">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <input type="checkbox"
                                           :id="'country_' + country.code"
                                           :value="country.code"
                                           x-model="locationSettings.active_countries"
                                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <label :for="'country_' + country.code" class="ml-2 text-sm text-gray-700 dark:text-gray-300" x-text="country.name"></label>
                                </div>
                                <button @click="fetchRegions(country.code)"
                                        class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                    Update Regions
                                </button>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="flex justify-end mt-6">
                    <button @click="updateLocationSettings" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Save Location Settings
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom Messages -->
    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                Custom Messages
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                Configure custom messages for different sections
            </p>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-700">
            <form @submit.prevent="updateMessages" class="p-6 space-y-6">
                <!-- Welcome Message -->
                <div>
                    <label for="welcome_message" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Welcome Message
                    </label>
                    <textarea x-model="messages.welcome_message"
                              id="welcome_message"
                              rows="3"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                </div>

                <!-- Queue Message -->
                <div>
                    <label for="queue_message" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Queue Message
                    </label>
                    <textarea x-model="messages.queue_message"
                              id="queue_message"
                              rows="3"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                </div>

                <!-- KYC Instructions -->
                <div>
                    <label for="kyc_instructions" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        KYC Instructions
                    </label>
                    <textarea x-model="messages.kyc_instructions"
                              id="kyc_instructions"
                              rows="3"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Save Messages
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function systemSettings() {
    return {
        apiKeys: {
            google_maps_api_key: @json($settings->google_maps_api_key ?? ''),
            recaptcha_site_key: @json($settings->recaptcha_site_key ?? ''),
            recaptcha_secret_key: @json($settings->recaptcha_secret_key ?? '')
        },
        locationSettings: {
            default_country: @json($settings->default_country ?? ''),
            active_countries: @json($settings->active_countries ?? [])
        },
        messages: {
            welcome_message: @json($settings->welcome_message ?? ''),
            queue_message: @json($settings->queue_message ?? ''),
            kyc_instructions: @json($settings->kyc_instructions ?? '')
        },
        countries: @json($countries),

        async updateApiKeys() {
            try {
                const response = await fetch('{{ route("admin.settings.system.api-keys") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify(this.apiKeys)
                });

                if (response.ok) {
                    alert('API settings updated successfully');
                } else {
                    alert('Failed to update API settings');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error updating API settings');
            }
        },

        async updateLocationSettings() {
            try {
                const response = await fetch('{{ route("admin.settings.system.countries") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify(this.locationSettings)
                });

                if (response.ok) {
                    alert('Location settings updated successfully');
                } else {
                    alert('Failed to update location settings');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error updating location settings');
            }
        },

        async updateMessages() {
            try {
                const response = await fetch('{{ route("admin.settings.system.messages") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify(this.messages)
                });

                if (response.ok) {
                    alert('Messages updated successfully');
                } else {
                    alert('Failed to update messages');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error updating messages');
            }
        },

        async fetchRegions(countryCode) {
            try {
                const response = await fetch(`{{ url('admin/settings/system/fetch-regions') }}/${countryCode}`, {
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    alert('Regions updated successfully');
                } else {
                    alert('Failed to fetch regions');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error fetching regions');
            }
        }
    }
}
</script>
@endpush
@endsection
