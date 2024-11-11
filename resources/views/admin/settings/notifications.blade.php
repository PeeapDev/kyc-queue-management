@extends('layouts.admin')

@section('header')
    Notification Settings
@endsection

@section('content')
<div class="space-y-6">
    <!-- SMTP Settings -->
    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                SMTP Email Configuration
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                Configure your SMTP settings for email notifications
            </p>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-700">
            <form action="{{ route('admin.settings.notifications.update') }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                    <div>
                        <label for="smtp_host" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            SMTP Host
                        </label>
                        <input type="text"
                               name="smtp_host"
                               id="smtp_host"
                               value="{{ old('smtp_host', config('mail.mailers.smtp.host')) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <div>
                        <label for="smtp_port" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            SMTP Port
                        </label>
                        <input type="text"
                               name="smtp_port"
                               id="smtp_port"
                               value="{{ old('smtp_port', config('mail.mailers.smtp.port')) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <div>
                        <label for="smtp_username" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            SMTP Username
                        </label>
                        <input type="text"
                               name="smtp_username"
                               id="smtp_username"
                               value="{{ old('smtp_username', config('mail.mailers.smtp.username')) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <div>
                        <label for="smtp_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            SMTP Password
                        </label>
                        <input type="password"
                               name="smtp_password"
                               id="smtp_password"
                               value="{{ old('smtp_password', config('mail.mailers.smtp.password')) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <div>
                        <label for="smtp_encryption" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Encryption
                        </label>
                        <select name="smtp_encryption"
                                id="smtp_encryption"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="tls" {{ config('mail.mailers.smtp.encryption') === 'tls' ? 'selected' : '' }}>TLS</option>
                            <option value="ssl" {{ config('mail.mailers.smtp.encryption') === 'ssl' ? 'selected' : '' }}>SSL</option>
                        </select>
                    </div>

                    <div>
                        <label for="mail_from_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            From Address
                        </label>
                        <input type="email"
                               name="mail_from_address"
                               id="mail_from_address"
                               value="{{ old('mail_from_address', config('mail.from.address')) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Save SMTP Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Twilio Settings -->
    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                Twilio SMS Configuration
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                Configure your Twilio settings for SMS notifications
            </p>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-700">
            <form action="{{ route('admin.settings.notifications.update') }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                    <div>
                        <label for="twilio_sid" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Twilio Account SID
                        </label>
                        <input type="text"
                               name="twilio_sid"
                               id="twilio_sid"
                               value="{{ old('twilio_sid', config('services.twilio.sid')) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <div>
                        <label for="twilio_token" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Twilio Auth Token
                        </label>
                        <input type="password"
                               name="twilio_token"
                               id="twilio_token"
                               value="{{ old('twilio_token', config('services.twilio.token')) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <div>
                        <label for="twilio_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Twilio From Number
                        </label>
                        <input type="text"
                               name="twilio_from"
                               id="twilio_from"
                               value="{{ old('twilio_from', config('services.twilio.from')) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                               placeholder="+1234567890">
                    </div>

                    <div>
                        <label for="twilio_test_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Test Number (Optional)
                        </label>
                        <input type="text"
                               name="twilio_test_number"
                               id="twilio_test_number"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                               placeholder="+1234567890">
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button"
                            onclick="testTwilioSettings()"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Test Settings
                    </button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Save Twilio Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Message Templates -->
    <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                Custom Message Templates
            </h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                Configure custom messages for different notifications
            </p>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-700">
            <form action="{{ route('admin.settings.notifications.update') }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- Welcome Message Templates -->
                <div class="space-y-4">
                    <h4 class="text-lg font-medium text-gray-900 dark:text-white">Welcome Messages</h4>

                    <div>
                        <label for="welcome_sms" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Welcome SMS Template
                        </label>
                        <textarea name="welcome_sms"
                                  id="welcome_sms"
                                  rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('welcome_sms', setting('welcome_sms', 'Welcome {name}! Thank you for registering with us. Your account has been created successfully.')) }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Available variables: {name}, {phone}, {email}</p>
                    </div>

                    <div>
                        <label for="welcome_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Welcome Email Template
                        </label>
                        <textarea name="welcome_email"
                                  id="welcome_email"
                                  rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('welcome_email', setting('welcome_email', 'Welcome {name}! Thank you for registering with us. Your account has been created successfully.')) }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Available variables: {name}, {email}</p>
                    </div>
                </div>

                <!-- Queue Notification Templates -->
                <div class="space-y-4">
                    <h4 class="text-lg font-medium text-gray-900 dark:text-white">Queue Notifications</h4>

                    <div>
                        <label for="queue_sms" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Queue SMS Template
                        </label>
                        <textarea name="queue_sms"
                                  id="queue_sms"
                                  rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('queue_sms', setting('queue_sms', 'Hello {name}, your queue number is {number}. Estimated wait time: {wait_time} minutes.')) }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Available variables: {name}, {number}, {wait_time}, {counter}</p>
                    </div>

                    <div>
                        <label for="queue_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Queue Email Template
                        </label>
                        <textarea name="queue_email"
                                  id="queue_email"
                                  rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">{{ old('queue_email', setting('queue_email', 'Hello {name}, your queue number is {number}. Estimated wait time: {wait_time} minutes.')) }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Available variables: {name}, {number}, {wait_time}, {counter}</p>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Save Message Templates
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
async function testTwilioSettings() {
    const testNumber = document.getElementById('twilio_test_number').value;
    if (!testNumber) {
        alert('Please enter a test number');
        return;
    }

    try {
        const response = await fetch('{{ route("admin.settings.notifications.test") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                test_number: testNumber
            })
        });

        const data = await response.json();
        alert(data.message);
    } catch (error) {
        console.error('Error:', error);
        alert('Error testing Twilio settings');
    }
}
</script>
@endpush
@endsection
