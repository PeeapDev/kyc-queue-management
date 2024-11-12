<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Country;
use App\Models\Region;
use App\Models\Location;
use App\Models\SystemSetting;

class SettingsController extends Controller
{
    public function account()
    {
        return view('admin.settings.account');
    }

    public function roles()
    {
        return view('admin.settings.roles');
    }

    public function notifications()
    {
        $settings = [
            'smtp_host' => config('mail.mailers.smtp.host'),
            'smtp_port' => config('mail.mailers.smtp.port'),
            'smtp_username' => config('mail.mailers.smtp.username'),
            'smtp_encryption' => config('mail.mailers.smtp.encryption'),
            'mail_from_address' => config('mail.from.address'),
            'twilio_sid' => config('services.twilio.sid'),
            'twilio_from' => config('services.twilio.from'),
            'welcome_sms_template' => Setting::get('welcome_sms_template', 'Welcome {name}! Your account has been created successfully.'),
            'welcome_email_template' => Setting::get('welcome_email_template', 'Welcome {name}! Your account has been created successfully.'),
            'queue_sms_template' => Setting::get('queue_sms_template', 'Hello {name}, your queue number is {number}. Estimated wait time: {wait_time} minutes.'),
            'queue_email_template' => Setting::get('queue_email_template', 'Hello {name}, your queue number is {number}. Estimated wait time: {wait_time} minutes.'),
        ];

        return view('admin.settings.notifications', compact('settings'));
    }

    public function updateNotifications(Request $request)
    {
        try {
            $validated = $request->validate([
                'smtp_host' => 'required|string',
                'smtp_port' => 'required|string',
                'smtp_username' => 'required|string',
                'smtp_password' => 'required|string',
                'smtp_encryption' => 'required|in:tls,ssl',
                'mail_from_address' => 'required|email',
            ]);

            // Update SMTP settings in .env file
            $envUpdates = [
                'MAIL_MAILER' => 'smtp',
                'MAIL_HOST' => $validated['smtp_host'],
                'MAIL_PORT' => $validated['smtp_port'],
                'MAIL_USERNAME' => $validated['smtp_username'],
                'MAIL_PASSWORD' => $validated['smtp_password'],
                'MAIL_ENCRYPTION' => $validated['smtp_encryption'],
                'MAIL_FROM_ADDRESS' => $validated['mail_from_address'],
            ];

            foreach ($envUpdates as $key => $value) {
                $this->updateEnvironmentVariable($key, $value);
            }

            // Clear config cache after updating .env
            \Artisan::call('config:clear');

            return back()->with('success', 'SMTP settings updated successfully.');

        } catch (\Exception $e) {
            \Log::error('SMTP settings update error: ' . $e->getMessage());
            return back()->with('error', 'Error updating SMTP settings: ' . $e->getMessage())->withInput();
        }
    }

    private function updateEnvironmentVariable($key, $value)
    {
        $path = base_path('.env');

        if (file_exists($path)) {
            $oldValue = env($key);

            if (file_get_contents($path) !== false) {
                file_put_contents($path, str_replace(
                    "$key=" . $oldValue,
                    "$key=" . $value,
                    file_get_contents($path)
                ));
            }
        }
    }

    public function testNotifications(Request $request)
    {
        $request->validate([
            'test_number' => 'required|string',
        ]);

        try {
            $message = "This is a test message from your application.";

            $response = Http::withBasicAuth(
                config('services.twilio.sid'),
                config('services.twilio.token')
            )->post('https://api.twilio.com/2010-04-01/Accounts/'.config('services.twilio.sid').'/Messages.json', [
                'From' => config('services.twilio.from'),
                'To' => $request->test_number,
                'Body' => $message,
            ]);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Test message sent successfully!'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Error sending test message: ' . $response->body()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error sending test message: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateSmtp(Request $request)
    {
        try {
            $validated = $request->validate([
                'smtp_host' => 'required|string',
                'smtp_port' => 'required|string',
                'smtp_username' => 'required|string',
                'smtp_password' => 'required|string',
                'smtp_encryption' => 'required|in:tls,ssl',
                'mail_from_address' => 'required|email',
            ]);

            // Update SMTP settings in .env file
            $envUpdates = [
                'MAIL_MAILER' => 'smtp',
                'MAIL_HOST' => $validated['smtp_host'],
                'MAIL_PORT' => $validated['smtp_port'],
                'MAIL_USERNAME' => $validated['smtp_username'],
                'MAIL_PASSWORD' => $validated['smtp_password'],
                'MAIL_ENCRYPTION' => $validated['smtp_encryption'],
                'MAIL_FROM_ADDRESS' => $validated['mail_from_address'],
                'MAIL_FROM_NAME' => config('app.name'),
            ];

            foreach ($envUpdates as $key => $value) {
                $this->updateEnvironmentVariable($key, $value);
            }

            // Clear config cache after updating .env
            \Artisan::call('config:clear');

            return back()->with('success', 'SMTP settings updated successfully.');

        } catch (\Exception $e) {
            \Log::error('SMTP settings update error: ' . $e->getMessage());
            return back()->with('error', 'Error updating SMTP settings: ' . $e->getMessage())->withInput();
        }
    }

    public function dynamic()
    {
        $countries = Country::with(['regions.locations'])->get();
        $regions = Region::with('country')->get();
        $locations = Location::with('region')->get();

        return view('admin.settings.dynamic', compact('countries', 'regions', 'locations'));
    }

    public function scripts()
    {
        $settings = SystemSetting::first();
        return view('admin.settings.scripts', compact('settings'));
    }

    public function updateRecaptcha(Request $request)
    {
        $validated = $request->validate([
            'siteKey' => 'required|string',
            'secretKey' => 'required|string',
        ]);

        SystemSetting::updateOrCreate(
            ['id' => 1],
            [
                'recaptcha_site_key' => $validated['siteKey'],
                'recaptcha_secret_key' => $validated['secretKey']
            ]
        );

        return response()->json(['success' => true]);
    }

    public function updateGoogleSSO(Request $request)
    {
        $validated = $request->validate([
            'clientId' => 'required|string',
            'clientSecret' => 'required|string',
        ]);

        SystemSetting::updateOrCreate(
            ['id' => 1],
            [
                'google_client_id' => $validated['clientId'],
                'google_client_secret' => $validated['clientSecret']
            ]
        );

        return response()->json(['success' => true]);
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
