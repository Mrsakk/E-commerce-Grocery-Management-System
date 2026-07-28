<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductReview::with('product', 'customer.user');

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        if ($request->filled('status')) {
            if ($request->status === 'approved') {
                $query->where('is_approved', true);
            } elseif ($request->status === 'hidden') {
                $query->where('is_approved', false);
            }
        }

        if ($request->filled('search')) {
            $search = str_replace(['%', '_'], ['\%', '\_'], $request->search);
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('product_name', 'ilike', "%{$search}%");
            })->orWhereHas('customer.user', function ($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%");
            });
        }

        $reviews = $query->latest()->paginate(15);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function show($id)
    {
        $review = ProductReview::with('product', 'customer.user')->findOrFail($id);

        return view('admin.reviews.show', compact('review'));
    }

    public function approve($id)
    {
        $review = ProductReview::findOrFail($id);
        $review->update(['is_approved' => true]);

        $customerName = $review->customer && $review->customer->user ? $review->customer->user->name : 'Unknown';
        $productName = $review->product ? $review->product->product_name : 'Unknown';

        ActivityLogger::logAction('approved', 'ProductReview', $review->id,
            "Approved review by {$customerName} for {$productName}");

        return back()->with('success', 'Review approved and is now visible.');
    }

    public function hide($id)
    {
        $review = ProductReview::findOrFail($id);
        $review->update(['is_approved' => false]);

        $customerName = $review->customer && $review->customer->user ? $review->customer->user->name : 'Unknown';
        $productName = $review->product ? $review->product->product_name : 'Unknown';

        ActivityLogger::logAction('hidden', 'ProductReview', $review->id,
            "Hidden review by {$customerName} for {$productName}");

        return back()->with('success', 'Review hidden from public view.');
    }

    public function destroy($id)
    {
        $review = ProductReview::findOrFail($id);

        $customerName = $review->customer && $review->customer->user ? $review->customer->user->name : 'Unknown';
        $productName = $review->product ? $review->product->product_name : 'Unknown';
        $reviewName = "Review by {$customerName} for {$productName}";

        $review->delete();

        ActivityLogger::logAction('deleted', 'ProductReview', $id, "Deleted {$reviewName}");

        return back()->with('success', 'Review deleted permanently.');
    }
}
