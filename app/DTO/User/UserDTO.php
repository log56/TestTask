<?php

namespace App\DTO\User;

class UserDTO
{
    public $username;
    public $phone;
    public $adress;
    public $password;

    public function __construct(
        string $username,
        string $phone,
        string $adress,
        string $password
    )
    {
        $this->username = $username;
        $this->phone = $phone;
        $this->adress = $adress;
        $this->password = $password;
    }
}
