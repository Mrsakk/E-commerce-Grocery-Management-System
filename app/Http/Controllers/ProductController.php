<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $categories = Category::where('status', 'active')->get();
        $query = Product::where('status', 'active')
            ->select('products.*')
            ->with('inventory', 'category')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews');

        if ($catId = request('category')) {
            $query->where('category_id', $catId);
        }

        if ($minPrice = request('min_price')) {
            $query->where('price', '>=', (float) $minPrice);
        }
        if ($maxPrice = request('max_price')) {
            $query->where('price', '<=', (float) $maxPrice);
        }

        switch (request('sort')) {
            case 'price_low':
                $query->orderBy('price');
                break;
            case 'price_high':
                $query->orderByDesc('price');
                break;
            case 'popular':
                $query->withCount(['orderDetails as orders_quantity' => function ($q) {
                    $q->select(DB::raw('COALESCE(SUM(quantity), 0)'));
                }])
                    ->orderBy('orders_quantity', 'desc');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12)->appends(request()->query());

        return view('customer.products.index', compact('products', 'categories'));
    }

    public function show($id)
    {
        $product = Product::with('inventory', 'category')->findOrFail($id);
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->with('inventory', 'category')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->take(4)
            ->get();

        return view('customer.products.show', compact('product', 'relatedProducts'));
    }

    public function category($id)
    {
        $category = Category::findOrFail($id);
        $categories = Category::where('status', 'active')->get();
        $products = Product::where('category_id', $id)
            ->where('status', 'active')
            ->with('inventory', 'category')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->paginate(12);

        return view('customer.products.index', compact('products', 'categories', 'category'));
    }

    public function search()
    {
        $query = request('q');
        $categories = Category::where('status', 'active')->get();

        $escapedQuery = str_replace(['%', '_'], ['\%', '\_'], $query);

        $products = Product::where(function ($q) use ($escapedQuery) {
            $q->where('product_name', 'like', "%{$escapedQuery}%")
                ->orWhere('description', 'like', "%{$escapedQuery}%")
                ->orWhere('brand', 'like', "%{$escapedQuery}%");
        })
            ->where('status', 'active')
            ->with('inventory', 'category')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->paginate(12);
        $products->appends(['q' => $query]);

        return view('customer.products.index', compact('products', 'categories', 'query'));
    }

    public function promotions()
    {
        $categories = Category::where('status', 'active')->get();
        $products = Product::where('status', 'active')
            ->with('inventory', 'category')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->latest()
            ->paginate(12);
        $coupons = Coupon::where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->get();

        return view('customer.products.promotions', compact('products', 'categories', 'coupons'));
    }

    public function suggestions()
    {
        $query = request('q');
        if (! $query) {
            return response()->json([]);
        }

        $escapedQuery = str_replace(['%', '_'], ['\%', '\_'], $query);

        $products = Product::where('status', 'active')
            ->where(function ($q) use ($escapedQuery) {
                $q->where('product_name', 'like', "%{$escapedQuery}%")
                    ->orWhere('brand', 'like', "%{$escapedQuery}%");
            })
            ->take(5)
            ->get();

        return response()->json($products->map(function ($product) {
            return [
                'id' => $product->id,
                'product_name' => $product->product_name,
                'price' => number_format($product->price, 2),
                'unit' => $product->unit,
                'image_url' => $product->image_url,
                'url' => route('products.show', $product->id),
            ];
        }));
    }
}
