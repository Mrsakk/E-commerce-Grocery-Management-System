<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Supplier;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::withCount('products')->latest()->paginate(10);

        return view('admin.suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('admin.suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $supplier = Supplier::create($validated);
        ActivityLogger::log('created', $supplier, "Created supplier: {$supplier->supplier_name}");

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier created successfully.');
    }

    public function show($id)
    {
        $supplier = Supplier::with('products', 'purchaseOrders')->findOrFail($id);

        return view('admin.suppliers.show', compact('supplier'));
    }

    public function edit($id)
    {
        $supplier = Supplier::findOrFail($id);
        $products = Product::where('status', 'active')->get();

        return view('admin.suppliers.edit', compact('supplier', 'products'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'supplier_name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);

        $supplier = Supplier::findOrFail($id);
        $supplier->update($validated);

        if ($request->filled('products')) {
            $syncData = [];
            foreach ($request->products as $productId) {
                $syncData[$productId] = [
                    'supply_price' => $request->input("supply_price.{$productId}"),
                    'lead_time_days' => $request->input("lead_time_days.{$productId}"),
                ];
            }
            $supplier->products()->sync($syncData);
        }

        ActivityLogger::log('updated', $supplier, "Updated supplier: {$supplier->supplier_name}");

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier updated successfully.');
    }

    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);

        if ($supplier->purchaseOrders()->count() > 0) {
            return back()->with('error', 'Cannot delete supplier with existing purchase orders.');
        }

        ActivityLogger::logAction('deleted', 'Supplier', $supplier->id, "Deleted supplier: {$supplier->supplier_name}");
        $supplier->delete();

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier deleted successfully.');
    }
}
