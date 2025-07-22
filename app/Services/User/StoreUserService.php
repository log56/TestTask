<?php

namespace App\Services\User;

use Illuminate\Support\Facades\Hash;

use App\DTO\User\UserDTO;
use App\Models\User;

class StoreUserService
{
    public function store(UserDTO $dto): User
    {
        $user = new User();
        $user->username = $dto->username;
        $user->phone = $dto->phone;
        $user->adress = $dto->adress;
        $user->password = Hash::make($dto->password);
        $user->save();

        return $user;
    }
}
