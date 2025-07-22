<?php

namespace App\Services\Order;
use App\Models\Order;
class ShowOrderService
{
    public function show(int $id)
    {
        $orders = Order::with(['products' => function ($query) {
            $query->select('products.id', 'name', 'price')
            ->withPivot('quantity', 'unit_price');
        }])
            ->where('user_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();
        return $orders;
    }
}
