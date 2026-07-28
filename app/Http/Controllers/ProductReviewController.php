<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductReviewController extends Controller
{
    public function store(Request $request, $productId)
    {
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'review_text' => 'nullable|string|max:1000',
        ]);

        $customer = Auth::user()->customer;
        if (! $customer) {
            return back()->with('error', 'Only customers can leave reviews.');
        }

        $product = Product::find($productId);
        if (! $product) {
            return back()->with('error', 'Product not found.');
        }

        $hasPurchased = Order::where('customer_id', $customer->id)
            ->where('order_status', 'delivered')
            ->whereHas('details', function ($q) use ($productId) {
                $q->where('product_id', $productId);
            })->exists();

        if (! $hasPurchased) {
            return back()->with('error', 'You can only review products you have purchased and received.');
        }

        try {
            $alreadyReviewed = ProductReview::where('product_id', $productId)
                ->where('customer_id', $customer->id)
                ->exists();

            if ($alreadyReviewed) {
                return back()->with('error', 'You have already reviewed this product.');
            }

            ProductReview::create([
                'product_id' => $productId,
                'customer_id' => $customer->id,
                'rating' => $request->rating,
                'review_text' => $request->review_text,
                'is_approved' => false,
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to submit review. Please try again.');
        }

        return back()->with('success', 'Thank you for your review! It will be visible after admin approval.');
    }
}
