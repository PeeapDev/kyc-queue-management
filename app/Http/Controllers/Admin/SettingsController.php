<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
        $validated = $request->validate([
            'smtp_host' => 'required|string',
            'smtp_port' => 'required|string',
            'smtp_username' => 'required|string',
            'smtp_password' => 'required|string',
            'smtp_encryption' => 'required|in:tls,ssl',
            'mail_from_address' => 'required|email',
            'twilio_sid' => 'required|string',
            'twilio_token' => 'required|string',
            'twilio_from' => 'required|string',
            'welcome_sms_template' => 'nullable|string',
            'welcome_email_template' => 'nullable|string',
        ]);

        // Update SMTP settings
        $this->updateEnvironmentFile([
            'MAIL_HOST' => $validated['smtp_host'],
            'MAIL_PORT' => $validated['smtp_port'],
            'MAIL_USERNAME' => $validated['smtp_username'],
            'MAIL_PASSWORD' => $validated['smtp_password'],
            'MAIL_ENCRYPTION' => $validated['smtp_encryption'],
            'MAIL_FROM_ADDRESS' => $validated['mail_from_address'],
            'MAIL_MAILER' => 'smtp',
        ]);

        // Update Twilio settings
        $this->updateEnvironmentFile([
            'TWILIO_SID' => $validated['twilio_sid'],
            'TWILIO_AUTH_TOKEN' => $validated['twilio_token'],
            'TWILIO_FROM' => $validated['twilio_from'],
        ]);

        // Update message templates
        Setting::set('welcome_sms_template', $request->welcome_sms_template);
        Setting::set('welcome_email_template', $request->welcome_email_template);

        return back()->with('success', 'Settings updated successfully.');
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

    private function updateEnvironmentFile($data)
    {
        $path = base_path('.env');
        $env = file_get_contents($path);

        foreach ($data as $key => $value) {
            $env = preg_replace(
                "/^{$key}=.*/m",
                "{$key}={$value}",
                $env
            );
        }

        file_put_contents($path, $env);
    }
}
