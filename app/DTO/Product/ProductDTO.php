<?php

namespace App\DTO\Product;

class ProductDTO
{
    public int $id;
    public int $quantity;

    public function __construct(int $id, int $quantity)
    {
        $this->id = $id;
        $this->quantity = $quantity;
    }
}
