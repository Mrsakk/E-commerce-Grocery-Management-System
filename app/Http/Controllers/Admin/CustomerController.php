<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::with('user', 'orders');

        if ($request->filled('search')) {
            $search = str_replace(['%', '_'], ['\%', '\_'], $request->search);
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $customers = $query->latest()->paginate(10);

        return view('admin.customers.index', compact('customers'));
    }

    public function show($id)
    {
        $customer = Customer::with('user', 'orders.details.product')->findOrFail($id);

        return view('admin.customers.show', compact('customer'));
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot change your own account status.');
        }

        $newStatus = $user->status === 'active' ? 'inactive' : 'active';
        $oldStatus = $user->status;

        $user->update(['status' => $newStatus]);

        ActivityLogger::logAction('updated', 'User', $user->id,
            "Customer {$user->name} status changed from {$oldStatus} to {$newStatus}");

        return back()->with('success', "Customer account {$newStatus}.");
    }
}
