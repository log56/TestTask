<?php
namespace App\Factories;

use App\DTO\Product\ProductDTO;
use App\DTO\UserProductDTO;
use App\Http\Requests\Order\StoreOrderRequest;

class UserProductDTOFactory
{
    public function makeDTO(StoreOrderRequest $request)
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
