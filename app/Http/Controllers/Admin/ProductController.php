<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Inventory;
use App\Models\Product;
use App\Services\ActivityLogger;
use App\Services\NotificationService;
use App\Services\StockMovementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category', 'inventory')->latest()->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('status', 'active')->get();

        return view('admin.products.create', compact('categories'));
    }

    public function show($id)
    {
        $product = Product::with('category', 'inventory', 'reviews')->findOrFail($id);

        return view('admin.products.show', compact('product'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'product_name' => 'required|string|max:200',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'unit' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'brand' => 'nullable|string|max:100',
            'expiry_date' => 'nullable|date',
            'status' => 'required|in:active,inactive',
            'qty_in_stock' => 'required|integer|min:0',
            'reorder_level' => 'required|integer|min:0',
        ]);

        $data = $request->except(['image', 'qty_in_stock', 'reorder_level']);

        if ($request->hasFile('image')) {
            try {
                $data['image'] = $request->file('image')->store('images/products', 'public');
            } catch (\Exception $e) {
                // File storage may fail on read-only environments (e.g. Vercel)
            }
        }

        $product = DB::transaction(function () use ($request, $data) {
            $product = Product::create($data);

            Inventory::create([
                'product_id' => $product->id,
                'qty_in_stock' => $request->qty_in_stock,
                'reorder_level' => $request->reorder_level,
                'last_updated' => now(),
            ]);

            StockMovementService::stockIn($product->id, $request->qty_in_stock, null, null, 'Initial stock');

            return $product;
        });

        try {
            ActivityLogger::log('created', $product, "Created product: {$product->product_name}");
        } catch (\Exception $e) {
            // Activity logging should not block the create
        }

        if ($request->qty_in_stock <= $request->reorder_level) {
            try {
                NotificationService::lowStockAlert($product->product_name, $request->qty_in_stock);
            } catch (\Exception $e) {
                // Notification failure should not block the create
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit($id)
    {
        $product = Product::with('inventory')->findOrFail($id);
        $categories = Category::where('status', 'active')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'product_name' => 'required|string|max:200',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'unit' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'brand' => 'nullable|string|max:100',
            'expiry_date' => 'nullable|date',
            'status' => 'required|in:active,inactive',
            'qty_in_stock' => 'required|integer|min:0',
            'reorder_level' => 'required|integer|min:0',
        ]);

        $product = Product::findOrFail($id);
        $data = $request->except(['image', 'qty_in_stock', 'reorder_level']);

        if ($request->hasFile('image')) {
            try {
                if ($product->image && str_starts_with($product->image, 'data:')) {
                    // base64 images don't need file deletion
                } elseif ($product->image && File::exists(storage_path('app/public/images/products/'.$product->image))) {
                    File::delete(storage_path('app/public/images/products/'.$product->image));
                }
                $data['image'] = $request->file('image')->store('images/products', 'public');
            } catch (\Exception $e) {
                // On Vercel, Storage operations fail; keep old image path
            }
        }

        $oldData = $product->toArray();

        DB::transaction(function () use ($product, $data, $request) {
            $product->update($data);

            if ($product->inventory) {
                $oldStock = $product->inventory->qty_in_stock;
                $product->inventory->update([
                    'qty_in_stock' => $request->qty_in_stock,
                    'reorder_level' => $request->reorder_level,
                    'last_updated' => now(),
                ]);

                if ($request->qty_in_stock != $oldStock) {
                    $diff = $request->qty_in_stock - $oldStock;
                    StockMovementService::adjustment($product->id, $diff, "Stock adjusted from {$oldStock} to {$request->qty_in_stock}");
                }
            }
        });

        try {
            ActivityLogger::log('updated', $product, "Updated product: {$product->product_name}", $oldData, $product->toArray());
        } catch (\Exception $e) {
            // Activity logging should not block the update
        }

        if ($request->qty_in_stock <= $request->reorder_level) {
            try {
                NotificationService::lowStockAlert($product->product_name, $request->qty_in_stock);
            } catch (\Exception $e) {
                // Notification failure should not block the update
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if ($product->orderDetails()->count() > 0) {
            return back()->with('error', 'Cannot delete product with existing orders. Consider deactivating it instead.');
        }

        if ($product->image && ! str_starts_with($product->image, 'data:') && File::exists(storage_path('app/public/images/products/'.$product->image))) {
            File::delete(storage_path('app/public/images/products/'.$product->image));
        }
        ActivityLogger::logAction('deleted', 'Product', $product->id, "Deleted product: {$product->product_name}");
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
