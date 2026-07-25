<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Exception $e) {
            return redirect()->route('login')->with('error', 'Google authentication failed. Please try again.');
        }

        $user = User::where('google_id', $googleUser->getId())->first();

        if ($user) {
            if ($user->status !== 'active') {
                return redirect()->route('login')->with('error', 'Your account has been deactivated. Please contact support.');
            }
            Auth::login($user);

            return redirect()->intended(route('home'));
        }

        if (Auth::check()) {
            $existingUser = Auth::user();
            if (! $existingUser->google_id) {
                $existingUser->update(['google_id' => $googleUser->getId()]);
            }

            return redirect()->intended(route('home'));
        }

        $existingUser = User::where('email', $googleUser->getEmail())->first();

        if ($existingUser) {
            return redirect()->route('login')->with('error', 'An account with this email already exists. Please log in with your email and password, then link your Google account from your profile settings.');
        }

        $user = User::create([
            'name' => $googleUser->getName() ?? 'Google User',
            'email' => $googleUser->getEmail(),
            'google_id' => $googleUser->getId(),
            'role' => 'customer',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        Customer::create([
            'user_id' => $user->id,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('home');
    }
}
