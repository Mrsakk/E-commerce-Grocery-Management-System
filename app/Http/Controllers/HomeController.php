<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\OrderDetail;
use App\Models\Banner;
use DB;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::where('status', 'active')->orderBy('sort_order')->get();
        $categories = Category::where('status', 'active')->get();
        $featuredProducts = Product::where('status', 'active')
            ->with('inventory', 'category')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->inRandomOrder()
            ->take(12)
            ->get();
        $latestProducts = Product::where('status', 'active')
            ->with('inventory', 'category')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->latest()
            ->take(12)
            ->get();
        $bestSellers = Product::where('status', 'active')
            ->with('inventory', 'category')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->whereIn('id', OrderDetail::select('product_id')
                ->selectRaw('SUM(quantity) as total_qty')
                ->groupBy('product_id')
                ->orderByDesc('total_qty')
                ->limit(12)
                ->pluck('product_id')
            )
            ->get();
        $promotions = Product::where('status', 'active')
            ->where('price', '>', 0)
            ->with('inventory', 'category')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->inRandomOrder()
            ->take(6)
            ->get();
        return view('home', compact('banners', 'categories', 'featuredProducts', 'latestProducts', 'bestSellers', 'promotions'));
    }
}
