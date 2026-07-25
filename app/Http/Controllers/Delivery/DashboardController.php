<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Services\OrderStatusService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $staffId = Auth::id();

        $assignedDeliveries = Delivery::where('delivery_staff_id', $staffId)
            ->where('delivery_status', 'assigned')
            ->count();

        $onTheWay = Delivery::where('delivery_staff_id', $staffId)
            ->where('delivery_status', 'on_the_way')
            ->count();

        $delivered = Delivery::where('delivery_staff_id', $staffId)
            ->where('delivery_status', 'delivered')
            ->count();

        $deliveries = Delivery::where('delivery_staff_id', $staffId)
            ->with('order.customer.user')
            ->latest()
            ->paginate(10);

        return view('delivery.dashboard', compact('assignedDeliveries', 'onTheWay', 'delivered', 'deliveries'));
    }

    public function show($id)
    {
        $delivery = Delivery::with('order.customer.user', 'order.details.product')
            ->findOrFail($id);

        if ((int) $delivery->delivery_staff_id !== (int) Auth::id()) {
            abort(403);
        }

        return view('delivery.show', compact('delivery'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'delivery_status' => 'required|in:assigned,on_the_way,delivered,failed',
            'received_by' => 'nullable|string|max:100',
            'failed_delivery_reason' => 'nullable|string|max:500',
        ]);

        $delivery = Delivery::with('order')->findOrFail($id);

        if ((int) $delivery->delivery_staff_id !== (int) Auth::id()) {
            abort(403);
        }

        $currentStatus = $delivery->delivery_status ?? 'assigned';
        $newStatus = $request->delivery_status;

        if ($currentStatus === $newStatus) {
            return back()->with('success', 'No changes to update.');
        }

        if (in_array($currentStatus, ['delivered', 'failed'])) {
            return back()->with('error', 'Cannot update status of a completed or failed delivery.');
        }

        if ($currentStatus === 'assigned' && ! in_array($newStatus, ['on_the_way', 'failed'])) {
            return back()->with('error', 'Assigned delivery can only transition to On The Way or Failed.');
        }

        if ($currentStatus === 'on_the_way' && ! in_array($newStatus, ['delivered', 'failed'])) {
            return back()->with('error', 'On The Way delivery can only transition to Delivered or Failed.');
        }

        DB::beginTransaction();
        try {
            $updateData = [
                'delivery_status' => $newStatus,
                'delivery_date' => in_array($newStatus, ['delivered', 'failed']) ? now() : $delivery->delivery_date,
            ];

            if ($newStatus === 'delivered') {
                $updateData['received_by'] = $request->received_by;
            }

            if ($newStatus === 'failed') {
                $updateData['failed_delivery_reason'] = $request->failed_delivery_reason;
            }

            $delivery->update($updateData);

            if ($newStatus === 'delivered') {
                OrderStatusService::change($delivery->order, 'delivered');
            }

            if ($newStatus === 'failed') {
                OrderStatusService::change($delivery->order, 'cancelled', $request->failed_delivery_reason ?? 'Delivery failed');
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);

            return back()->with('error', 'Failed to update delivery status.');
        }

        return back()->with('success', 'Delivery status updated successfully.');
    }
}
