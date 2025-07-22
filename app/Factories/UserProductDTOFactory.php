<?php
namespace App\Factories;

use App\DTO\Product\ProductDTO;
use App\DTO\UserProductDTO;
use App\Http\Requests\Order\StoreOrderRequest;

class UserProductDTOFactory
{
    /**
     * Creates a UserProductDTO from the validated StoreOrderRequest data.
     *
     * - Maps each product from the request into ProductDTO objects
     * - Collects user_id and comment from the request
     *
     * @param StoreOrderRequest $request Validated request containing user order data
     * @return UserProductDTO Data transfer object representing the order details
     */
    public function makeDTO(StoreOrderRequest $request): UserProductDTO
    {
        $items = array_map(
            fn (array $item) => new ProductDTO(
                id: $item['id'],
                quantity: $item['quantity'],
            ),
            $request->validated('products')
        );

        return new UserProductDTO(
            user_id: $request->user_id,
            comment: $request->comment,
            items: $items,
        );
    }
}