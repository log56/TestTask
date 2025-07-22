<?php
namespace App\Services\Order;

use App\DTO\UserProductDTO;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class StoreOrderService
{
    public function store(UserProductDTO $orderData): Order
    {

        return DB::transaction(function () use ($orderData) {

            $order = new Order();
            $order->user_id = $orderData->user_id;
            $order->status = 'pending';
            $order->total_price = 0;
            $order->comment = $orderData->comment;
            $order->save();

            $totalPrice = 0;
            $productsToAdd = [];


            foreach ($orderData->items as $item) {

                $product = Product::find($item->id);

                if (!$product) {
                    throw new \Exception("Product not found: " . $item->id);
                }

                $itemPrice = $product->price * $item->quantity;
                $totalPrice += $itemPrice;


                $productsToAdd[$product->id] = [
                    'quantity' => $item->quantity,
                    'unit_price' => $product->price,
                ];
            }


            $order->total_price = $totalPrice;
            $order->save();

            $order->products()->attach($productsToAdd);

            return $order;
        });
    }
}
