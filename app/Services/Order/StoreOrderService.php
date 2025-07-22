<?php

namespace App\Services\Order;

use App\DTO\UserProductDTO;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class StoreOrderService
{
    /**
     * Creates a new order for the given user and attaches ordered products.
     *
     * - Saves the order with initial status and comment
     * - Calculates total price based on product quantity and unit price
     * - Attaches products to the order via pivot table with quantity and unit price
     * - Wraps all operations in a DB transaction
     *
     * @param UserProductDTO $orderData Data containing user_id, comment and ordered items
     * @return Order The created order
     *
     * @throws \Exception If a product from the input does not exist
     */
    public function store(UserProductDTO $orderData): Order
    {
        return DB::transaction(function () use ($orderData) {

            // Create initial order with default status and zero total
            $order = new Order();
            $order->user_id = $orderData->user_id;
            $order->status = 'pending';
            $order->total_price = 0;
            $order->comment = $orderData->comment;
            $order->save();

            $totalPrice = 0;
            $productsToAdd = [];

            // Loop through each item to calculate total and prepare pivot data
            foreach ($orderData->items as $item) {

                // Ensure product exists
                $product = Product::find($item->id);
                if (! $product) {
                    throw new \Exception("Product not found: " . $item->id);
                }

                // Calculate subtotal for the item
                $itemPrice = $product->price * $item->quantity;
                $totalPrice += $itemPrice;

                // Prepare data for the pivot table
                $productsToAdd[$product->id] = [
                    'quantity' => $item->quantity,
                    'unit_price' => $product->price,
                ];
            }

            // Update total price and re-save the order
            $order->total_price = $totalPrice;
            $order->save();

            // Attach products to order with quantity and price info
            $order->products()->attach($productsToAdd);

            return $order;
        });
    }
}