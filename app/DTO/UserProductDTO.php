<?php

namespace App\DTO;

class UserProductDTO
{
    public int $user_id;
    public string $comment;
    public array $items;

    public function __construct(int $user_id, array $items, string $comment)
    {
        $this->user_id = $user_id;
        $this->comment = $comment;
        $this->items = $items;
    }

}
