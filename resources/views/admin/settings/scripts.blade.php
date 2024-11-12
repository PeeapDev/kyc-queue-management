@extends('layouts.admin')

@section('header')
    Script Settings
@endsection

@section('content')
<div x-data="scriptSettings()" class="space-y-6">
    <!-- Google reCAPTCHA Settings -->
    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                Google reCAPTCHA Settings
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                Configure your reCAPTCHA keys for form protection
            </p>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-700">
            <form @submit.prevent="updateRecaptcha" class="p-6 space-y-6">
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Site Key
                        </label>
                        <input type="text" x-model="recaptcha.siteKey" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Secret Key
                        </label>
                        <input type="text" x-model="recaptcha.secretKey" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Save reCAPTCHA Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Google SSO Settings -->
    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                Google SSO Settings
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                Configure Google Single Sign-On credentials
            </p>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-700">
            <form @submit.prevent="updateGoogleSSO" class="p-6 space-y-6">
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Client ID
                        </label>
                        <input type="text" x-model="googleSSO.clientId" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Client Secret
                        </label>
                        <input type="text" x-model="googleSSO.clientSecret" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Save Google SSO Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Chatbot Settings -->
    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                Sierra Mind AI Chatbot
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                Configure your Sierra Mind AI chatbot. Get your chatbot script from
                <a href="https://ai.peeap.com" target="_blank" class="text-blue-500 hover:text-blue-700 underline">
                    https://ai.peeap.com
                </a>
            </p>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-700">
            <form @submit.prevent="updateChatbot" class="p-6 space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Sierra Mind AI Widget Code
                    </label>
                    <div class="mt-1 text-sm text-gray-500 dark:text-gray-400 mb-2">
                        Example format:
                        <code class="bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">
                            &lt;script src='https://ai.peeap.com/Modules/Chatbot/Resources/assets/js/chatbot-widget.min.js' data-iframe-src="https://ai.peeap.com/chatbot/embed/chatbot_code=YOUR_CODE" data-iframe-height="532" data-iframe-width="400"&gt;&lt;/script&gt;
                        </code>
                    </div>
                    <textarea
                        x-model="chatbot.script"
                        rows="4"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white font-mono"
                        placeholder="Paste your Sierra Mind AI widget code here"></textarea>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Save Chatbot Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function scriptSettings() {
    return {
        recaptcha: {
            siteKey: @json($settings->recaptcha_site_key ?? ''),
            secretKey: @json($settings->recaptcha_secret_key ?? '')
        },
        googleSSO: {
            clientId: @json($settings->google_client_id ?? ''),
            clientSecret: @json($settings->google_client_secret ?? '')
        },
        chatbot: {
            script: @json($settings->chatbot_script ?? '')
        },

        async updateRecaptcha() {
            try {
                const response = await fetch('{{ route("admin.settings.scripts.recaptcha") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(this.recaptcha)
                });

                if (response.ok) {
                    alert('reCAPTCHA settings updated successfully');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error updating reCAPTCHA settings');
            }
        },

        async updateGoogleSSO() {
            try {
                const response = await fetch('{{ route("admin.settings.scripts.google-sso") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(this.googleSSO)
                });

                if (response.ok) {
                    alert('Google SSO settings updated successfully');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error updating Google SSO settings');
            }
        },

        async updateChatbot() {
            try {
                const response = await fetch('{{ route("admin.settings.scripts.chatbot") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(this.chatbot)
                });

                if (response.ok) {
                    alert('Chatbot settings updated successfully');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error updating chatbot settings');
            }
        }
    }
}
</script>
@endpush
@endsection
