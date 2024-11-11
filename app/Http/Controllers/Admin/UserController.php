<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use App\Mail\SetupPasswordNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use App\Models\Setting;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'phone_number' => 'required|string|unique:users,phone_number',
                'location' => 'required|string|max:255',
                'age' => 'required|integer|min:18|max:120',
                'notifications_email' => 'boolean',
                'notifications_sms' => 'boolean',
            ]);

            // Generate setup token
            $token = Str::random(60);
            $tokenExpiry = Carbon::now()->addHours(24);

            $user = User::create([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'phone_number' => $validated['phone_number'],
                'location' => $validated['location'],
                'age' => $validated['age'],
                'registration_type' => 'staff',
                'notifications_email' => $request->notifications_email ?? false,
                'notifications_sms' => $request->notifications_sms ?? false,
                'password_set_token' => $token,
                'password_set_token_expires_at' => $tokenExpiry,
            ]);

            // Generate setup URL
            $setupUrl = URL::temporarySignedRoute(
                'password.setup',
                $tokenExpiry,
                ['token' => $token]
            );

            // Send notifications if requested
            if ($request->notifications_email) {
                Mail::to($user->email)->send(new SetupPasswordNotification($setupUrl));
            }

            if ($request->notifications_sms) {
                // Check if Twilio credentials are set
                if (config('services.twilio.sid') && config('services.twilio.token') && config('services.twilio.from')) {
                    try {
                        $smsTemplate = Setting::get('welcome_sms_template', 'Welcome! Click here to set up your password: {link}');
                        $smsContent = str_replace(
                            ['{name}', '{link}'],
                            [$user->first_name, $setupUrl],
                            $smsTemplate
                        );

                        Http::withBasicAuth(
                            config('services.twilio.sid'),
                            config('services.twilio.token')
                        )->post('https://api.twilio.com/2010-04-01/Accounts/'.config('services.twilio.sid').'/Messages.json', [
                            'From' => config('services.twilio.from'),
                            'To' => $user->phone_number,
                            'Body' => $smsContent,
                        ]);
                    } catch (\Exception $e) {
                        \Log::error('SMS sending failed: ' . $e->getMessage());
                        // Continue execution even if SMS fails
                    }
                } else {
                    \Log::warning('Twilio credentials not configured');
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'User created successfully. Setup instructions have been sent.',
                'user' => $user
            ]);

        } catch (\Exception $e) {
            \Log::error('User creation error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error creating user: ' . $e->getMessage()
            ], 422);
        }
    }
}

