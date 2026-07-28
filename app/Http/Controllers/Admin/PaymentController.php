<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\PaymentVerification;
use App\Services\ActivityLogger;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with('order.customer.user');
        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }
        if ($request->filled('method')) {
            $query->where('payment_method', $request->method);
        }
        $payments = $query->latest()->paginate(10);

        return view('admin.payments.index', compact('payments'));
    }

    public function show($id)
    {
        $payment = Payment::with('order.customer.user', 'order.details.product', 'verifications.verifiedBy')->findOrFail($id);

        return view('admin.payments.show', compact('payment'));
    }

    public function confirm($id)
    {
        $payment = Payment::with('order')->findOrFail($id);

        if ($payment->payment_status === 'paid') {
            return back()->with('error', 'Payment is already confirmed.');
        }

        try {
            $payment->update([
                'payment_status' => 'paid',
                'payment_date' => now(),
            ]);
            $payment->order->update(['payment_status' => 'paid']);

            PaymentVerification::create([
                'payment_id' => $payment->id,
                'verified_by' => Auth::id(),
                'status' => 'approved',
                'verified_at' => now(),
            ]);

            ActivityLogger::logAction('confirmed', 'Payment', $payment->id, "Confirmed payment #{$payment->id} for order #{$payment->order_id}");

            if ($payment->order->customer && $payment->order->customer->user) {
                NotificationService::notifyCustomer(
                    $payment->order->customer->user_id,
                    'Payment Received',
                    "Your payment of \${$payment->amount} for order #{$payment->order_id} has been confirmed.",
                    'payment_received',
                    $payment->order_id
                );
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to confirm payment.');
        }

        return back()->with('success', 'Payment confirmed successfully.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate(['rejection_reason' => 'required|string']);

        $payment = Payment::with('order')->findOrFail($id);

        if ($payment->payment_status === 'failed') {
            return back()->with('error', 'Payment is already rejected.');
        }

        try {
            $payment->update(['payment_status' => 'failed']);

            PaymentVerification::create([
                'payment_id' => $payment->id,
                'verified_by' => Auth::id(),
                'status' => 'rejected',
                'rejection_reason' => $request->rejection_reason,
                'verified_at' => now(),
            ]);

            ActivityLogger::logAction('rejected', 'Payment', $payment->id, "Rejected payment #{$payment->id}");

            if ($payment->order->customer && $payment->order->customer->user) {
                NotificationService::notifyCustomer(
                    $payment->order->customer->user_id,
                    'Payment Rejected',
                    "Your payment for order #{$payment->order_id} was rejected. Reason: {$request->rejection_reason}",
                    'payment_rejected',
                    $payment->order_id
                );
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to reject payment.');
        }

        return back()->with('success', 'Payment rejected.');
    }

    public function markCodPaid($id)
    {
        $payment = Payment::with('order')->findOrFail($id);

        if (strtoupper($payment->payment_method) !== 'COD') {
            return back()->with('error', 'This action is only available for COD payments.');
        }

        if ($payment->payment_status === 'paid') {
            return back()->with('error', 'Payment is already marked as paid.');
        }

        $payment->update([
            'payment_status' => 'paid',
            'payment_date' => now(),
        ]);
        $payment->order->update(['payment_status' => 'paid']);

        PaymentVerification::create([
            'payment_id' => $payment->id,
            'verified_by' => Auth::id(),
            'status' => 'approved',
            'verified_at' => now(),
        ]);

        ActivityLogger::logAction('confirmed', 'Payment', $payment->id, "Marked COD payment #{$payment->id} as paid for order #{$payment->order_id}");

        if ($payment->order->customer && $payment->order->customer->user) {
            NotificationService::notifyCustomer(
                $payment->order->customer->user_id,
                'Payment Received',
                "Your COD payment of \${$payment->amount} for order #{$payment->order_id} has been recorded as paid.",
                'payment_received',
                $payment->order_id
            );
        }

        return back()->with('success', 'COD payment marked as paid.');
    }

    public function uploadSlip(Request $request, $id)
    {
        $request->validate([
            'slip_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'transaction_ref' => 'nullable|string|max:100',
        ]);

        $payment = Payment::findOrFail($id);
        $data = [];

        if ($request->hasFile('slip_image')) {
            if ($payment->slip_image && File::exists(storage_path('app/public/'.$payment->slip_image))) {
                File::delete(storage_path('app/public/'.$payment->slip_image));
            }
            $data['slip_image'] = $request->file('slip_image')->store('uploads/slips', 'public');
        }
        if ($request->filled('transaction_ref')) {
            $data['transaction_ref'] = $request->transaction_ref;
        }

        $payment->update($data);

        PaymentVerification::create([
            'payment_id' => $payment->id,
            'slip_image' => $data['slip_image'] ?? null,
            'transaction_ref' => $data['transaction_ref'] ?? null,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Payment slip uploaded. Awaiting verification.');
    }
}
