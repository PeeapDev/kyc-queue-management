<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\SetupPasswordNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();
        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validate the request
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

            // Create user first
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

            $notificationErrors = [];

            // Try to send email notification
            if ($request->notifications_email) {
                try {
                    Mail::to($user->email)->send(new SetupPasswordNotification($setupUrl));
                } catch (\Exception $e) {
                    Log::error('Email sending failed: ' . $e->getMessage());
                    $notificationErrors[] = 'Email notification could not be sent.';
                }
            }

            // Try to send SMS notification
            if ($request->notifications_sms) {
                try {
                    if (config('services.twilio.sid') && config('services.twilio.token') && config('services.twilio.from')) {
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
                    }
                } catch (\Exception $e) {
                    Log::error('SMS sending failed: ' . $e->getMessage());
                    $notificationErrors[] = 'SMS notification could not be sent.';
                }
            }

            DB::commit();

            // Format the user data for the response
            $userData = [
                'id' => $user->id,
                'name' => $user->first_name . ' ' . $user->last_name,
                'email' => $user->email,
                'registration_type' => $user->registration_type,
                'created_at' => $user->created_at,
                'phone_number' => $user->phone_number,
                'location' => $user->location,
                'age' => $user->age,
            ];

            // Return success response with any notification errors
            return response()->json([
                'success' => true,
                'message' => 'User created successfully.' .
                            (count($notificationErrors) > 0 ? ' Note: ' . implode(' ', $notificationErrors) : ''),
                'user' => $userData
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User creation error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error creating user: ' . $e->getMessage()
            ], 422);
        }
    }
}

