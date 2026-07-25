<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $customer = $user->customer;
        $addresses = $user->addresses()->orderByDesc('is_default')->get();

        $settings = [
            'theme' => session('settings.theme', 'light'),
            'layout' => session('settings.layout', 'grid'),
            'currency' => session('settings.currency', 'USD'),
            'date_format' => session('settings.date_format', 'MM/DD/YYYY'),
            'notifications' => session('settings.notifications', [
                'order_confirmations' => true,
                'delivery_status' => true,
                'order_cancellation' => true,
                'deals_discounts' => false,
                'new_products' => false,
                'weekly_newsletter' => false,
                'email' => true,
                'sms' => false,
                'push' => true,
            ]),
            'default_payment' => session('settings.default_payment', 'COD'),
            'save_payment_info' => session('settings.save_payment_info', true),
            'two_factor' => session('settings.two_factor', false),
            'show_order_activity' => session('settings.show_order_activity', true),
            'share_analytics' => session('settings.share_analytics', false),
            'font_size' => session('settings.font_size', 'default'),
            'high_contrast' => session('settings.high_contrast', false),
            'reduce_animations' => session('settings.reduce_animations', false),
            'enhanced_focus' => session('settings.enhanced_focus', true),
        ];

        return view('customer.settings.index', compact('user', 'customer', 'addresses', 'settings'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.auth()->id(),
            'phone' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:100',
            'bio' => 'nullable|string|max:500',
        ]);

        $user = auth()->user();
        $user->fill($request->only(['name', 'email', 'phone']));
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        $user->save();

        if ($customer = $user->customer) {
            $customer->update($request->only(['address', 'city']));
        }

        return redirect()->route('settings.index')->with('success', __('messages.settings_saved'));
    }

    public function updatePreferences(Request $request)
    {
        $request->validate([
            'theme' => 'required|in:light,dark,system',
            'layout' => 'required|in:grid,list',
            'currency' => 'required|in:USD,KHR',
        ]);

        session([
            'settings.theme' => $request->theme,
            'settings.layout' => $request->layout,
            'settings.currency' => $request->currency,
        ]);

        return redirect()->route('settings.index')->with('success', __('messages.settings_saved'));
    }

    public function updateLanguage(Request $request)
    {
        $request->validate([
            'language' => 'required|in:en,km',
            'date_format' => 'required|in:MM/DD/YYYY,DD/MM/YYYY,YYYY-MM-DD',
        ]);

        session([
            'locale' => $request->language,
            'settings.date_format' => $request->date_format,
        ]);

        app()->setLocale($request->language);

        return redirect()->route('settings.index')->with('success', __('messages.settings_saved'));
    }

    public function updateNotifications(Request $request)
    {
        $notifications = [
            'order_confirmations' => $request->boolean('order_confirmations'),
            'delivery_status' => $request->boolean('delivery_status'),
            'order_cancellation' => $request->boolean('order_cancellation'),
            'deals_discounts' => $request->boolean('deals_discounts'),
            'new_products' => $request->boolean('new_products'),
            'weekly_newsletter' => $request->boolean('weekly_newsletter'),
            'email' => $request->boolean('email'),
            'sms' => $request->boolean('sms'),
            'push' => $request->boolean('push'),
        ];

        session(['settings.notifications' => $notifications]);

        return redirect()->route('settings.index')->with('success', __('messages.settings_saved'));
    }

    public function updatePayment(Request $request)
    {
        $request->validate([
            'default_payment' => 'required|in:COD,ABA,Wing,Bakong',
        ]);

        session([
            'settings.default_payment' => $request->default_payment,
            'settings.save_payment_info' => $request->boolean('save_payment_info'),
        ]);

        return redirect()->route('settings.index')->with('success', __('messages.settings_saved'));
    }

    public function updatePrivacy(Request $request)
    {
        $request->validate([
            'two_factor' => 'nullable',
            'show_order_activity' => 'nullable',
            'share_analytics' => 'nullable',
        ]);

        session([
            'settings.two_factor' => $request->boolean('two_factor'),
            'settings.show_order_activity' => $request->boolean('show_order_activity'),
            'settings.share_analytics' => $request->boolean('share_analytics'),
        ]);

        return redirect()->route('settings.index')->with('success', __('messages.settings_saved'));
    }

    public function updateAccessibility(Request $request)
    {
        $request->validate([
            'font_size' => 'required|in:default,large,xlarge',
            'high_contrast' => 'nullable',
            'reduce_animations' => 'nullable',
            'enhanced_focus' => 'nullable',
        ]);

        session([
            'settings.font_size' => $request->font_size,
            'settings.high_contrast' => $request->boolean('high_contrast'),
            'settings.reduce_animations' => $request->boolean('reduce_animations'),
            'settings.enhanced_focus' => $request->boolean('enhanced_focus'),
        ]);

        return redirect()->route('settings.index')->with('success', __('messages.settings_saved'));
    }
}
