<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function customerProfile()
    {
        $user = auth()->user();
        $customer = $user->customer;

        return view('customer.profile.index', compact('user', 'customer'));
    }

    public function customerUpdate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.auth()->id(),
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
        ]);

        try {
            $user = auth()->user();
            $user->fill($request->only(['name', 'email', 'phone']));
            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }
            $user->save();

            if ($customer = $user->customer) {
                $customer->update($request->only(['address', 'city']));
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update profile.');
        }

        return redirect()->route('profile.index')->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            auth()->user()->update([
                'password' => Hash::make($request->password),
            ]);

            Auth::guard('web')->onceUsingId(Auth::id());
            $request->session()->regenerate();
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to change password.');
        }

        return redirect()->route('profile.index')->with('success', 'Password changed successfully!');
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $user = auth()->user();

        try {
            if ($request->hasFile('avatar')) {
                if ($user->avatar && ! str_starts_with($user->avatar, 'data:')) {
                    try {
                        Storage::disk('public')->delete($user->avatar);
                    } catch (\Exception $e) {
                        \Log::warning('Failed to delete old avatar: '.$e->getMessage());
                    }
                }

                $file = $request->file('avatar');
                $dataUrl = 'data:'.$file->getMimeType().';base64,'.base64_encode($file->get());
                $user->update(['avatar' => $dataUrl]);

                return redirect()->route('profile.index')->with('success', 'Profile picture updated successfully!');
            }
        } catch (\Exception $e) {
            return redirect()->route('profile.index')->with('error', 'Failed to upload profile picture.');
        }

        return redirect()->route('profile.index')->with('error', 'Failed to upload profile picture.');
    }

    public function deleteAvatar(Request $request)
    {
        $user = auth()->user();

        if ($user->avatar) {
            try {
                if (! str_starts_with($user->avatar, 'data:')) {
                    try {
                        Storage::disk('public')->delete($user->avatar);
                    } catch (\Exception $e) {
                        \Log::warning('Failed to delete avatar file: '.$e->getMessage());
                    }
                }
                $user->update(['avatar' => null]);
            } catch (\Exception $e) {
                return redirect()->route('profile.index')->with('error', 'Failed to delete profile picture.');
            }

            return redirect()->route('profile.index')->with('success', 'Profile picture deleted successfully!');
        }

        return redirect()->route('profile.index')->with('error', 'No profile picture to delete.');
    }
}
