<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\StockMovement;
use App\Services\ActivityLogger;
use App\Services\NotificationService;
use App\Services\StockMovementService;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        $inventories = Inventory::with('product.category')
            ->orderBy('qty_in_stock', 'asc')
            ->paginate(10);

        return view('admin.inventory.index', compact('inventories'));
    }

    public function edit($id)
    {
        $inventory = Inventory::with('product')->findOrFail($id);

        return view('admin.inventory.edit', compact('inventory'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'qty_in_stock' => 'required|integer|min:0',
            'reorder_level' => 'required|integer|min:0',
        ]);

        $inventory = Inventory::findOrFail($id);
        $oldStock = $inventory->qty_in_stock;

        try {
            $inventory = Inventory::findOrFail($inventory->id);
            $inventory->update([
                'qty_in_stock' => $request->qty_in_stock,
                'reorder_level' => $request->reorder_level,
                'last_updated' => now(),
            ]);

            if ($request->qty_in_stock != $oldStock) {
                $diff = $request->qty_in_stock - $oldStock;
                $type = $diff > 0 ? 'stock_in' : 'stock_out';
                StockMovementService::record(
                    $inventory->product_id,
                    $type,
                    abs($diff),
                    'adjustment',
                    null,
                    "Manual adjustment: {$oldStock} -> {$request->qty_in_stock}"
                );
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update inventory.');
        }

        $inventory->refresh();

        ActivityLogger::logAction('updated', 'Inventory', $inventory->id,
            "Updated stock for {$inventory->product->product_name}: {$oldStock} -> {$request->qty_in_stock}");

        if ($request->qty_in_stock <= $request->reorder_level) {
            NotificationService::lowStockAlert($inventory->product->product_name, $request->qty_in_stock);
        }

        return redirect()->route('admin.inventory.index')
            ->with('success', 'Inventory updated successfully.');
    }

    public function lowStock()
    {
        $inventories = Inventory::whereColumn('qty_in_stock', '<=', 'reorder_level')
            ->with('product.category')
            ->paginate(10);

        return view('admin.inventory.low_stock', compact('inventories'));
    }

    public function damaged(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'note' => 'nullable|string',
        ]);

        $inventory = Inventory::findOrFail($id);
        $qty = (int) $request->quantity;

        try {
            $inventory = Inventory::findOrFail($id);

            if ($inventory->qty_in_stock < $qty) {
                throw new \Exception('Not enough stock to mark as damaged.');
            }

            $inventory->qty_in_stock -= $qty;
            $inventory->last_updated = now();
            $inventory->save();

            StockMovementService::damaged($inventory->product_id, $qty, $request->note);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage() ?: 'Failed to record damaged stock.');
        }

        $inventory->refresh();
        ActivityLogger::logAction('updated', 'Inventory', $inventory->id, "Marked {$qty} units as damaged for {$inventory->product->product_name}");

        return back()->with('success', 'Damaged stock recorded.');
    }

    public function stockIn($id)
    {
        $inventory = Inventory::with('product')->findOrFail($id);

        return view('admin.inventory.stock_in', compact('inventory'));
    }

    public function processStockIn(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'note' => 'nullable|string',
        ]);

        $qty = (int) $request->quantity;

        try {
            $inventory = Inventory::findOrFail($id);
            $inventory->qty_in_stock += $qty;
            $inventory->last_updated = now();
            $inventory->save();

            StockMovementService::stockIn($inventory->product_id, $qty, 'manual', null, $request->note ?: 'Manual stock in');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to process stock in.');
        }

        $inventory = Inventory::with('product')->findOrFail($id);
        ActivityLogger::logAction('updated', 'Inventory', $id, "Stock in {$qty} units for {$inventory->product->product_name}");

        return redirect()->route('admin.inventory.index')->with('success', "Added {$qty} units to stock.");
    }

    public function stockOut($id)
    {
        $inventory = Inventory::with('product')->findOrFail($id);

        return view('admin.inventory.stock_out', compact('inventory'));
    }

    public function processStockOut(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'note' => 'nullable|string',
        ]);

        $qty = (int) $request->quantity;

        try {
            $inventory = Inventory::findOrFail($id);

            if ($inventory->qty_in_stock < $qty) {
                throw new \Exception('Not enough stock for stock out.');
            }

            $inventory->qty_in_stock -= $qty;
            $inventory->last_updated = now();
            $inventory->save();

            StockMovementService::stockOut($inventory->product_id, $qty, 'manual', null);

            if ($request->note) {
                StockMovement::where('product_id', $inventory->product_id)
                    ->latest()->first()
                    ->update(['note' => $request->note]);
            }
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage() ?: 'Failed to process stock out.');
        }

        $inventory = Inventory::with('product')->findOrFail($id);
        ActivityLogger::logAction('updated', 'Inventory', $id, "Stock out {$qty} units for {$inventory->product->product_name}");

        if ($inventory->qty_in_stock <= $inventory->reorder_level) {
            NotificationService::lowStockAlert($inventory->product->product_name, $inventory->qty_in_stock);
        }

        return redirect()->route('admin.inventory.index')->with('success', "Removed {$qty} units from stock.");
    }
}
