<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use App\Services\ActivityLogger;
use App\Services\StockMovementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = PurchaseOrder::with('supplier', 'orderedBy');
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $purchaseOrders = $query->latest()->paginate(10);

        return view('admin.purchase_orders.index', compact('purchaseOrders'));
    }

    public function create()
    {
        $suppliers = Supplier::where('status', 'active')->get();
        $products = Product::where('status', 'active')->with('inventory', 'suppliers')->get();

        return view('admin.purchase_orders.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_cost' => 'required|numeric|min:0',
            'note' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $total = 0;
            $orderNumber = 'PO-'.date('Ymd').'-'.strtoupper(uniqid());

            $purchaseOrder = PurchaseOrder::create([
                'supplier_id' => $request->supplier_id,
                'order_number' => $orderNumber,
                'status' => 'pending',
                'ordered_by' => Auth::id(),
                'note' => $request->note,
            ]);

            foreach ($request->items as $item) {
                $subtotal = $item['quantity'] * $item['unit_cost'];
                $total += $subtotal;

                PurchaseOrderItem::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_cost' => $item['unit_cost'],
                    'subtotal' => $subtotal,
                ]);
            }

            $purchaseOrder->update(['total_amount' => $total]);

            DB::commit();
            ActivityLogger::log('created', $purchaseOrder, "Created purchase order: {$orderNumber}");

            return redirect()->route('admin.purchase-orders.show', $purchaseOrder->id)
                ->with('success', 'Purchase order created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);

            return back()->with('error', 'Failed to create purchase order. Please try again.');
        }
    }

    public function show($id)
    {
        $purchaseOrder = PurchaseOrder::with('supplier', 'items.product', 'orderedBy', 'receivedBy')->findOrFail($id);

        return view('admin.purchase_orders.show', compact('purchaseOrder'));
    }

    public function edit($id)
    {
        $purchaseOrder = PurchaseOrder::with('items')->findOrFail($id);

        return view('admin.purchase_orders.edit', compact('purchaseOrder'));
    }

    public function update(Request $request, $id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        $request->validate([
            'note' => 'nullable|string',
        ]);

        $purchaseOrder->update(['note' => $request->note]);

        return redirect()->route('admin.purchase-orders.show', $id)
            ->with('success', 'Purchase order updated.');
    }

    public function destroy($id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);

        if (! in_array($purchaseOrder->status, ['pending'])) {
            return back()->with('error', 'Only pending purchase orders can be deleted.');
        }

        $purchaseOrder->items()->delete();
        $purchaseOrder->delete();

        return redirect()->route('admin.purchase-orders.index')
            ->with('success', 'Purchase order deleted.');
    }

    public function receiveStock(Request $request, $id)
    {
        $purchaseOrder = PurchaseOrder::with('items')->findOrFail($id);

        $request->validate([
            'items' => 'required|array',
            'items.*.received_qty' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->items as $itemId => $data) {
                $poItem = PurchaseOrderItem::findOrFail($itemId);
                $receivedQty = (int) $data['received_qty'];

                if ($receivedQty > 0) {
                    $poItem->update(['received_qty' => $receivedQty]);

                    $inventory = Inventory::lockForUpdate()
                        ->firstOrCreate(
                            ['product_id' => $poItem->product_id],
                            ['qty_in_stock' => 0, 'reorder_level' => 10]
                        );

                    $inventory->qty_in_stock += $receivedQty;
                    $inventory->last_updated = now();
                    $inventory->save();

                    StockMovementService::stockIn(
                        $poItem->product_id,
                        $receivedQty,
                        'purchase',
                        $purchaseOrder->id,
                        "Purchase order {$purchaseOrder->order_number} received"
                    );
                }
            }

            $purchaseOrder->refresh();

            $allReceived = $purchaseOrder->items->every(fn ($item) => $item->received_qty >= $item->quantity);
            $anyReceived = $purchaseOrder->items->sum('received_qty') > 0;

            $status = $allReceived ? 'received' : ($anyReceived ? 'partial' : $purchaseOrder->status);
            $purchaseOrder->update([
                'status' => $status,
                'received_by' => Auth::id(),
                'received_at' => now(),
            ]);

            DB::commit();
            ActivityLogger::logAction('received_stock', 'PurchaseOrder', $purchaseOrder->id, "Received stock for PO: {$purchaseOrder->order_number}");

            return back()->with('success', 'Stock received successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);

            return back()->with('error', 'Failed to receive stock. Please try again.');
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($id);
        $request->validate(['status' => 'required|in:pending,ordered,cancelled']);

        $purchaseOrder->update(['status' => $request->status]);

        return back()->with('success', 'Purchase order status updated.');
    }
}
