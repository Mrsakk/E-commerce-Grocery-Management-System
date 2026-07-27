<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class ContactController extends Controller
{
    public function index()
    {
        return view('customer.contact.index');
    }

    public function send(Request $request)
    {
        if (RateLimiter::tooManyAttempts('contact:'.($request->ip()), 5)) {
            return back()->with('error', 'You are sending messages too quickly. Please wait a moment and try again.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        RateLimiter::hit('contact:'.($request->ip()), 300);

        $message = ContactMessage::create($request->only(['name', 'email', 'subject', 'message']));

        try {
            NotificationService::notifyAdmins(
                'New Contact Message: '.e($message->subject),
                e($message->name).' ('.e($message->email).') sent: '.e($message->message),
                'contact_message',
                $message->id
            );
        } catch (\Exception $e) {
            \Log::warning('Failed to notify admins about contact message: '.$e->getMessage());
        }

        return back()->with('success', 'Thank you for your message! We will get back to you soon.');
    }
}
