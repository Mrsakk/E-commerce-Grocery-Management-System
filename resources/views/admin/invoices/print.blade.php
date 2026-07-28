<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->id }} - FreshMart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; color: #1e293b; padding: 20px; }
        .invoice-header { border-bottom: 3px solid #10b981; padding-bottom: 20px; margin-bottom: 20px; }
        .invoice-title { font-size: 2rem; font-weight: 800; color: #10b981; }
        .info-label { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; font-weight: 700; }
        .info-value { font-weight: 600; font-size: 0.9rem; }
        table { width: 100%; border-collapse: collapse; }
        table th { background: #f1f5f9; padding: 10px 14px; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; font-weight: 700; border-bottom: 2px solid #e2e8f0; }
        table td { padding: 10px 14px; border-bottom: 1px solid #f1f5f9; font-size: 0.85rem; }
        .total-row td { font-weight: 700; font-size: 1rem; border-top: 2px solid #10b981; }
        .footer-note { margin-top: 30px; padding-top: 15px; border-top: 1px solid #e2e8f0; font-size: 0.8rem; color: #64748b; text-align: center; }
        @media print {
            body { padding: 0; }
            .no-print { display: none !important; }
            .invoice-header { border-bottom-color: #10b981; }
        }
    </style>
</head>
<body>
    <div class="no-print mb-3 text-end">
        <button onclick="window.print()" class="btn btn-success btn-sm">
            <i class="bi bi-printer me-1"></i> Print Invoice
        </button>
    </div>

    <div class="invoice-header d-flex justify-content-between align-items-start">
        <div>
            <div class="invoice-title">INVOICE</div>
            <div class="mt-2">
                <span class="info-label">Invoice #</span>
                <span class="info-value">INV-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div>
                <span class="info-label">Date</span>
                <span class="info-value">{{ $order->created_at->format('M d, Y') }}</span>
            </div>
        </div>
        <div class="text-end">
            <div class="fw-bold fs-5" style="color: #10b981;">FreshMart Grocery</div>
            <div style="font-size: 0.8rem; color: #64748b;">#42A, Street 271, Phnom Penh, Cambodia</div>
            <div style="font-size: 0.8rem; color: #64748b;">Phone: +855 12 345 678</div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="info-label mb-1">Bill To</div>
            <div class="fw-bold">{{ $order->customer?->user?->name ?? 'N/A' }}</div>
            <div style="font-size: 0.85rem;">{{ $order->customer?->user?->email ?? '' }}</div>
            <div style="font-size: 0.85rem;">{{ $order->customer?->user?->phone ?? '' }}</div>
            <div style="font-size: 0.85rem;">{{ $order->delivery_address }}</div>
        </div>
        <div class="col-md-6 text-md-end">
            <div class="info-label mb-1">Payment Info</div>
            <div class="info-value">Method: {{ strtoupper($order->payment_method) }}</div>
            <div class="info-value">Status: <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">{{ ucfirst($order->payment_status) }}</span></div>
            @if($order->payment && $order->payment->transaction_ref)
                <div class="info-value">Ref: {{ $order->payment->transaction_ref }}</div>
            @endif
        </div>
    </div>

    <table class="mb-4">
        <thead>
            <tr>
                <th>#</th>
                <th>Product</th>
                <th class="text-end">Unit Price</th>
                <th class="text-center">Qty</th>
                <th class="text-end">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->details as $detail)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td class="fw-semibold">{{ $detail->product?->product_name ?? 'Deleted Product' }}</td>
                <td class="text-end">${{ number_format($detail->unit_price, 2) }}</td>
                <td class="text-center">{{ $detail->quantity }}</td>
                <td class="text-end">${{ number_format($detail->subtotal, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="row">
        <div class="col-md-6">
            @if($order->coupon)
            <div class="mb-2">
                <span class="info-label">Coupon Applied:</span>
                <span class="info-value">{{ $order->coupon->code }}</span>
            </div>
            @endif
        </div>
        <div class="col-md-6">
            <table>
                <tr>
                    <td class="info-label pe-4">Subtotal</td>
                    <td class="text-end">${{ number_format($order->total_amount + ($order->discount_amount ?? 0), 2) }}</td>
                </tr>
                @if($order->discount_amount > 0)
                <tr>
                    <td class="info-label pe-4">Discount</td>
                    <td class="text-end text-danger">-${{ number_format($order->discount_amount, 2) }}</td>
                </tr>
                @endif
                <tr>
                    <td class="info-label pe-4">Delivery Fee</td>
                    <td class="text-end">${{ number_format(\App\Models\Setting::getValue('delivery_fee', 2.00), 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td class="pe-4">Grand Total</td>
                    <td class="text-end">${{ number_format($order->total_amount, 2) }}</td>
                </tr>
            </table>
        </div>
    </div>

    @if($order->delivery)
    <div class="mt-4 p-3 rounded-3" style="background: #f0fdf4;">
        <div class="row">
            <div class="col-md-4">
                <div class="info-label">Delivery Status</div>
                <div class="info-value">{{ ucfirst($order->delivery->delivery_status) }}</div>
            </div>
            <div class="col-md-4">
                <div class="info-label">Tracking Number</div>
                <div class="info-value">{{ $order->delivery->tracking_no ?? 'N/A' }}</div>
            </div>
            <div class="col-md-4">
                <div class="info-label">Delivery Staff</div>
                <div class="info-value">{{ $order->delivery?->staff?->name ?? 'Not assigned' }}</div>
            </div>
        </div>
    </div>
    @endif

    <div class="footer-note">
        Thank you for shopping with FreshMart Grocery! This is a computer-generated invoice.
    </div>
</body>
</html>
