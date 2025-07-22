<?php

namespace App\Services\Order;

use App\Models\Order;

class ShowOrderService
{
    /**
     * Retrieves all orders for a specific user with associated products.
     *
     * - Includes product name, price, and pivot data (quantity, unit_price)
     * - Orders results by creation date in descending order (latest first)
     *
     * @param int $id User ID
     * @return \Illuminate\Support\Collection<Order>
     */
    public function show(int $id)
    {
        $orders = Order::with(['products' => function ($query) {
            // Select only needed fields from products and include pivot data
            $query->select('products.id', 'name', 'price')
                  ->withPivot('quantity', 'unit_price');
        }])
        ->where('user_id', $id)
        ->orderBy('created_at', 'desc')
        ->get();

        return $orders;
    }
}