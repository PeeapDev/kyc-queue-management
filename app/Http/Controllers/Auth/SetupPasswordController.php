<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class SetupPasswordController extends Controller
{
    public function showSetupForm(Request $request, $token)
    {
        $user = User::where('password_set_token', $token)
            ->where('password_set_token_expires_at', '>', Carbon::now())
            ->firstOrFail();

        return view('auth.setup-password', [
            'token' => $token,
            'email' => $user->email
        ]);
    }

    public function setup(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::where('password_set_token', $request->token)
            ->where('email', $request->email)
            ->where('password_set_token_expires_at', '>', Carbon::now())
            ->firstOrFail();

        $user->update([
            'password' => Hash::make($request->password),
            'password_set_token' => null,
            'password_set_token_expires_at' => null,
            'email_verified_at' => Carbon::now(),
        ]);

        auth()->login($user);

        return redirect()->route('dashboard')->with('success', 'Password set successfully!');
    }
}
