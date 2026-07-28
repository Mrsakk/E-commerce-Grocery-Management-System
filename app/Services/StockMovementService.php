<?php

namespace App\Services;

use App\Models\StockMovement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StockMovementService
{
    public static function record($productId, $type, $quantity, $referenceType = null, $referenceId = null, $note = null)
    {
        try {
            return StockMovement::create([
                'product_id' => $productId,
                'user_id' => Auth::id(),
                'type' => $type,
                'quantity' => $quantity,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'note' => $note,
            ]);
        } catch (\Exception $e) {
            Log::warning('StockMovement record failed: '.$e->getMessage());

            return null;
        }
    }

    public static function stockOut($productId, $quantity, $referenceType = null, $referenceId = null)
    {
        return self::record($productId, 'stock_out', -$quantity, $referenceType, $referenceId, 'Sale');
    }

    public static function stockIn($productId, $quantity, $referenceType = null, $referenceId = null, $note = 'Restock')
    {
        return self::record($productId, 'stock_in', $quantity, $referenceType, $referenceId, $note);
    }

    public static function adjustment($productId, $quantity, $note = null)
    {
        return self::record($productId, 'adjustment', $quantity, 'adjustment', null, $note);
    }

    public static function damaged($productId, $quantity, $note = null)
    {
        return self::record($productId, 'damaged', -$quantity, null, null, $note);
    }

    public static function returned($productId, $quantity, $referenceType = null, $referenceId = null, $note = null)
    {
        return self::record($productId, 'returned', $quantity, $referenceType, $referenceId, $note);
    }
}
